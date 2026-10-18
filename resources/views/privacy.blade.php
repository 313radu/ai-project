<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy policy — AI Deals</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-shell">
    @include('partials.site-header')

    <main class="site-main">
        <div class="site-container legal-layout">
            <header class="legal-heading">
                <span class="eyebrow">Privacy &amp; confidentiality</span>
                <h1>Privacy policy</h1>
                <p>How AI Deals handles information when you browse the catalog or use the AI shopping assistant.</p>
                <span class="legal-updated">Last updated: {{ now()->format('d F Y') }}</span>
            </header>

            <article class="legal-card">
                <div class="legal-callout">
                    <strong>In short:</strong> we use the minimum information needed to operate the site, protect it, answer your AI shopping request, and remember the choices you make. Optional cookies are disabled until you enable them.
                </div>

                <h2>1. Who is responsible for your data?</h2>
                <p>AI Deals is the operator of this website and the data controller for the processing described here. For privacy questions, contact the site operator at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.</p>
                <p class="legal-note">Before launching publicly, replace the default mail address in <code>MAIL_FROM_ADDRESS</code> with the operator's real privacy contact address.</p>

                <h2>2. What information may be processed?</h2>
                <ul class="legal-list">
                    <li><strong>Technical and security information:</strong> session, CSRF protection, browser, device and request information needed to serve pages securely.</li>
                    <li><strong>Cookie choices:</strong> the consent categories you select and your theme preference.</li>
                    <li><strong>AI shopping requests:</strong> the message you submit and the limited conversation history sent with it so the assistant can answer your request.</li>
                    <li><strong>Affiliate interactions:</strong> when you follow a product link, you leave AI Deals and the merchant or affiliate network may process information under its own policy.</li>
                </ul>

                <h2>3. AI assistant and confidentiality</h2>
                <p>The AI assistant is provided through the configured Google Gemini API integration. Your request and the limited chat history are sent to that provider to generate a response. The application does not intentionally save AI messages in its product database.</p>
                <p>Do not submit passwords, payment details, government identifiers, health information, or other sensitive personal information in the assistant. Review Google's applicable terms and configure your provider and retention settings before using the assistant with real customer data.</p>

                <h2>4. Why do we use information?</h2>
                <ul class="legal-list">
                    <li>To display products, search the catalog and respond to requests.</li>
                    <li>To maintain security, prevent abuse and keep the application reliable.</li>
                    <li>To remember your cookie, theme and interface preferences.</li>
                    <li>To enable the optional analytics or marketing categories only if you actively choose them. No optional tracking scripts are configured in this version.</li>
                </ul>

                <h2>5. Legal bases</h2>
                <p>Necessary processing is used to provide and secure the service. Optional analytics or marketing processing, if introduced later, will be based on your consent and will not be activated before you choose it. You can withdraw optional consent at any time through <strong>Manage cookies</strong>.</p>

                <h2>6. Sharing and international transfers</h2>
                <p>We may use hosting, infrastructure, security and AI service providers to operate this website. Product links may take you to merchants or affiliate networks that have separate privacy and cookie practices. Some providers may process information outside the European Economic Area; the operator should confirm the applicable safeguards and provider agreements before production use.</p>

                <h2>7. Retention and security</h2>
                <p>We keep information only for as long as needed for the purposes above, legal obligations, security and dispute handling. The site uses Laravel session and CSRF protections. No online service can guarantee absolute security, so please avoid submitting sensitive information to the assistant.</p>

                <h2>8. Your rights</h2>
                <p>Depending on your location and the applicable law, you may have rights to access, correct, delete, restrict or object to processing, receive a portable copy of certain data, and withdraw consent. You may also complain to your local data-protection authority. Contact the operator first so we can investigate your request.</p>

                <h2>9. Changes to this policy</h2>
                <p>We may update this policy when the site, providers or legal requirements change. The “Last updated” date at the top shows when the current version was published.</p>

                <div class="legal-actions">
                    <a class="button-secondary" href="{{ route('cookies') }}">Read cookie policy</a>
                    <a class="button-primary" href="{{ route('home') }}">Back to AI Deals</a>
                </div>
            </article>
        </div>
    </main>

    @include('partials.site-footer')
</body>
</html>
