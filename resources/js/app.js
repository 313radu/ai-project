const moonIcon = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" /></svg>';
const sunIcon = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" /></svg>';

const getPreferredTheme = () => {
    const savedTheme = window.localStorage.getItem('ai-deals-theme');

    if (savedTheme === 'dark' || savedTheme === 'light') {
        return savedTheme;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const setTheme = (theme) => {
    document.documentElement.dataset.theme = theme;
    window.localStorage.setItem('ai-deals-theme', theme);

    document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
        const nextTheme = theme === 'dark' ? 'light' : 'dark';
        toggle.innerHTML = theme === 'dark' ? sunIcon : moonIcon;
        toggle.setAttribute('aria-label', `Switch to ${nextTheme} mode`);
    });
};

setTheme(getPreferredTheme());

document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
    });
});

const cookieConsent = document.querySelector('[data-cookie-consent]');

if (cookieConsent) {
    const consentCookieName = 'ai_deals_cookie_consent';
    const consentVersion = 1;
    const banner = cookieConsent.querySelector('[data-cookie-banner]');
    const settings = cookieConsent.querySelector('[data-cookie-settings]');
    const analyticsToggle = cookieConsent.querySelector('[data-cookie-category="analytics"]');
    const marketingToggle = cookieConsent.querySelector('[data-cookie-category="marketing"]');

    const getCookie = (name) => {
        const cookie = document.cookie
            .split('; ')
            .find((item) => item.startsWith(`${name}=`));

        if (!cookie) {
            return null;
        }

        try {
            return JSON.parse(decodeURIComponent(cookie.slice(name.length + 1)));
        } catch {
            return null;
        }
    };

    const setCookie = (name, value, maxAge) => {
        const secure = window.location.protocol === 'https:' ? '; Secure' : '';
        document.cookie = `${name}=${encodeURIComponent(JSON.stringify(value))}; Max-Age=${maxAge}; Path=/; SameSite=Lax${secure}`;
    };

    const getStoredConsent = () => {
        const stored = getCookie(consentCookieName);

        return stored?.version === consentVersion ? stored : null;
    };

    const closeConsentUi = () => {
        cookieConsent.hidden = true;
        cookieConsent.classList.remove('is-settings-open');
        banner.hidden = false;
        settings.hidden = true;
    };

    const showBanner = () => {
        cookieConsent.hidden = false;
        cookieConsent.classList.remove('is-settings-open');
        banner.hidden = false;
        settings.hidden = true;
    };

    const openSettings = () => {
        const stored = getStoredConsent();
        cookieConsent.hidden = false;
        cookieConsent.classList.add('is-settings-open');
        banner.hidden = true;
        settings.hidden = false;
        analyticsToggle.checked = stored?.analytics === true;
        marketingToggle.checked = stored?.marketing === true;
    };

    const saveConsent = (analytics, marketing) => {
        const consent = {
            version: consentVersion,
            necessary: true,
            analytics,
            marketing,
            updatedAt: new Date().toISOString(),
        };

        setCookie(consentCookieName, consent, 60 * 60 * 24 * 180);
        window.dispatchEvent(new CustomEvent('ai-deals:consent-change', { detail: consent }));
        closeConsentUi();
    };

    cookieConsent.querySelectorAll('[data-cookie-action]').forEach((action) => {
        action.addEventListener('click', () => {
            const choice = action.dataset.cookieAction;

            if (choice === 'accept') {
                saveConsent(true, true);
            }

            if (choice === 'reject') {
                saveConsent(false, false);
            }

            if (choice === 'settings') {
                openSettings();
            }

            if (choice === 'save') {
                saveConsent(analyticsToggle.checked, marketingToggle.checked);
            }
        });
    });

    document.querySelectorAll('[data-cookie-open]').forEach((trigger) => {
        trigger.addEventListener('click', openSettings);
    });

    cookieConsent.querySelectorAll('[data-cookie-close]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            if (getStoredConsent()) {
                closeConsentUi();
                return;
            }

            showBanner();
        });
    });

    if (!getStoredConsent()) {
        showBanner();
    }
}

const aiForm = document.querySelector('[data-ai-form]');

if (aiForm) {
    const searchInput = aiForm.querySelector('#search-input');
    const askButton = aiForm.querySelector('#ask-btn');
    const responsePanel = aiForm.querySelector('[data-ai-response]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    let history = [];

    const createAvatar = () => {
        const avatar = document.createElement('span');
        avatar.className = 'ai-response__avatar';
        avatar.innerHTML = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 14 9l6 2-6 2-2 6-2-6-6-2 6-2 2-6Z" /></svg>';
        return avatar;
    };

    const createPlaceholder = () => {
        const placeholder = document.createElement('span');
        placeholder.className = 'ai-product__placeholder';
        placeholder.textContent = '✦';
        placeholder.setAttribute('aria-hidden', 'true');
        return placeholder;
    };

    const showResponseShell = (status, isLoading = false) => {
        responsePanel.className = `ai-response is-visible${isLoading ? ' is-loading' : ''}`;
        responsePanel.replaceChildren();

        const header = document.createElement('div');
        header.className = 'ai-response__header';
        header.appendChild(createAvatar());

        const title = document.createElement('span');
        title.className = 'ai-response__title';
        title.textContent = 'AI shopping assistant';
        header.appendChild(title);

        const statusLabel = document.createElement('span');
        statusLabel.className = 'ai-response__status';
        statusLabel.textContent = status;
        header.appendChild(statusLabel);
        responsePanel.appendChild(header);

        return responsePanel;
    };

    const renderLoading = () => {
        showResponseShell('Thinking', true);
        const body = document.createElement('div');
        body.className = 'ai-response__body';
        const text = document.createElement('p');
        text.className = 'ai-response__text';
        text.textContent = 'I’m checking the catalog for the best match...';
        body.appendChild(text);
        responsePanel.appendChild(body);
    };

    const renderResponse = (reply, products) => {
        showResponseShell(products.length ? `${products.length} recommendation${products.length === 1 ? '' : 's'}` : 'Ready');

        const body = document.createElement('div');
        body.className = 'ai-response__body';

        const text = document.createElement('p');
        text.className = 'ai-response__text';
        text.textContent = reply;
        body.appendChild(text);

        if (products.length) {
            const recommendations = document.createElement('div');
            recommendations.className = 'ai-response__recommendations';

            products.forEach((product) => {
                const link = document.createElement('a');
                link.className = 'ai-product';
                link.href = product.url;

                if (product.image) {
                    const image = document.createElement('img');
                    image.className = 'ai-product__image';
                    image.src = product.image;
                    image.alt = '';
                    image.loading = 'lazy';
                    image.addEventListener('error', () => image.replaceWith(createPlaceholder()), { once: true });
                    link.appendChild(image);
                } else {
                    link.appendChild(createPlaceholder());
                }

                const info = document.createElement('span');
                info.className = 'ai-product__info';

                const name = document.createElement('span');
                name.className = 'ai-product__name';
                name.textContent = product.name;
                info.appendChild(name);

                const price = document.createElement('span');
                price.className = 'ai-product__price';
                price.textContent = `${product.price ?? 'Price unavailable'} ${product.currency ?? ''}`.trim();
                info.appendChild(price);

                link.appendChild(info);
                recommendations.appendChild(link);
            });

            body.appendChild(recommendations);
        }

        responsePanel.appendChild(body);
    };

    aiForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = searchInput.value.trim();

        if (!message || askButton.disabled) {
            return;
        }

        askButton.disabled = true;
        askButton.setAttribute('aria-busy', 'true');
        renderLoading();

        try {
            const response = await fetch('/ai-chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: JSON.stringify({ message, history }),
            });

            if (!response.ok) {
                throw new Error('AI request failed');
            }

            const data = await response.json();
            const reply = data.reply ?? 'Nu am primit un răspuns. Încearcă din nou.';
            const products = Array.isArray(data.products) ? data.products : [];

            renderResponse(reply, products);
            history.push({ role: 'user', text: message }, { role: 'model', text: reply });
            history = history.slice(-10);
        } catch {
            renderResponse('Momentan nu pot răspunde. Verifică conexiunea și încearcă din nou.', []);
        } finally {
            askButton.disabled = false;
            askButton.removeAttribute('aria-busy');
        }
    });
}
