<div class="cookie-consent" data-cookie-consent hidden>
    <div class="cookie-consent__backdrop" data-cookie-close></div>

    <section class="cookie-banner" data-cookie-banner role="dialog" aria-labelledby="cookie-banner-title" aria-describedby="cookie-banner-description">
        <div class="cookie-banner__copy">
            <span class="cookie-banner__icon" aria-hidden="true">◌</span>
            <div>
                <h2 id="cookie-banner-title">Your privacy, your choice.</h2>
                <p id="cookie-banner-description">We use essential cookies to keep AI Deals secure and remember your preferences. Optional cookies stay off unless you choose them.</p>
                <a href="{{ route('cookies') }}">Read our cookie policy</a>
            </div>
        </div>
        <div class="cookie-banner__actions">
            <button type="button" class="button-secondary" data-cookie-action="reject">Only necessary</button>
            <button type="button" class="button-secondary" data-cookie-action="settings">Manage choices</button>
            <button type="button" class="button-primary" data-cookie-action="accept">Accept all</button>
        </div>
    </section>

    <section class="cookie-settings" data-cookie-settings hidden role="dialog" aria-modal="true" aria-labelledby="cookie-settings-title" aria-describedby="cookie-settings-description">
        <div class="cookie-settings__header">
            <div>
                <span class="eyebrow">Privacy controls</span>
                <h2 id="cookie-settings-title">Choose your cookie preferences</h2>
                <p id="cookie-settings-description">Necessary cookies are always active because the site cannot work securely without them.</p>
            </div>
            <button type="button" class="cookie-settings__close" data-cookie-close aria-label="Close cookie settings">×</button>
        </div>

        <div class="cookie-settings__options">
            <label class="cookie-option">
                <span>
                    <strong>Necessary</strong>
                    <small>Security, session and consent storage. Always active.</small>
                </span>
                <input type="checkbox" checked disabled>
            </label>
            <label class="cookie-option">
                <span>
                    <strong>Analytics</strong>
                    <small>Helps us understand which pages are useful. Not active in this version.</small>
                </span>
                <input type="checkbox" data-cookie-category="analytics">
            </label>
            <label class="cookie-option">
                <span>
                    <strong>Marketing</strong>
                    <small>Would support personalized advertising or affiliate measurement. Not active in this version.</small>
                </span>
                <input type="checkbox" data-cookie-category="marketing">
            </label>
        </div>

        <div class="cookie-settings__footer">
            <a href="{{ route('privacy') }}">Privacy policy</a>
            <button type="button" class="button-secondary" data-cookie-action="reject">Only necessary</button>
            <button type="button" class="button-primary" data-cookie-action="save">Save choices</button>
        </div>
    </section>
</div>
