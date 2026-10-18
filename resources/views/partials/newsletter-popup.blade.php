<div class="newsletter-modal" data-newsletter-popup hidden>
    <div class="newsletter-modal__backdrop" data-newsletter-close></div>

    <section class="newsletter-dialog" role="dialog" aria-modal="true" aria-labelledby="newsletter-title" aria-describedby="newsletter-description">
        <button type="button" class="newsletter-dialog__close" data-newsletter-close aria-label="Close newsletter sign-up">×</button>

        <div class="newsletter-dialog__icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5.5h16v13H4z" /><path d="m4 7 8 6 8-6" /><path d="M8 2.5v3M16 2.5v3" /></svg>
        </div>

        <span class="eyebrow">The deal memo</span>
        <h2 id="newsletter-title">Better deals, delivered.</h2>
        <p id="newsletter-description">Get a short weekly email with new finds, real discounts and smart shopping ideas. No noise, just the good stuff.</p>

        <form class="newsletter-form" data-newsletter-form>
            <label for="newsletter-email">Email address</label>
            <div class="newsletter-form__row">
                <input id="newsletter-email" name="email" type="email" placeholder="you@example.com" autocomplete="email" required>
                <button class="button-primary" type="submit">Sign me up</button>
            </div>
            <p class="newsletter-form__error" data-newsletter-error hidden>Enter a valid email address to continue.</p>
            <small>Frontend preview only — this form does not send or store your email yet.</small>
        </form>

        <div class="newsletter-success" data-newsletter-success hidden aria-live="polite">
            <span class="newsletter-success__check" aria-hidden="true">✓</span>
            <div>
                <strong>You’re on the list.</strong>
                <p>Thanks — the newsletter connection can be added whenever you’re ready.</p>
            </div>
        </div>
    </section>
</div>
