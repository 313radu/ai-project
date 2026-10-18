<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie policy — AI Deals</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-shell">
    @include('partials.site-header')

    <main class="site-main">
        <div class="site-container legal-layout">
            <header class="legal-heading">
                <span class="eyebrow">Privacy controls</span>
                <h1>Cookie policy</h1>
                <p>What is stored on your device, why it is used, and how you can change your choices.</p>
                <span class="legal-updated">Last updated: {{ now()->format('d F Y') }}</span>
            </header>

            <article class="legal-card">
                <div class="legal-callout">
                    <strong>Our approach:</strong> essential cookies are used for security and core functionality. Optional categories are off by default and can be changed at any time from the footer.
                </div>

                <h2>1. What are cookies?</h2>
                <p>Cookies are small text files stored by a website in your browser. Similar browser storage technologies, such as local storage, can also remember a preference on your device.</p>

                <h2>2. Cookies used by AI Deals</h2>
                <div class="legal-table-wrap">
                    <table class="legal-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Purpose</th>
                                <th>Category</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>{{ config('session.cookie') }}</code></td>
                                <td>Maintains a secure Laravel session when the application needs one.</td>
                                <td><span class="legal-pill legal-pill--required">Necessary</span></td>
                                <td>{{ config('session.lifetime') }} minutes of inactivity, unless configured otherwise.</td>
                            </tr>
                            <tr>
                                <td><code>XSRF-TOKEN</code></td>
                                <td>Helps protect state-changing requests against cross-site request forgery.</td>
                                <td><span class="legal-pill legal-pill--required">Necessary</span></td>
                                <td>Set by the application framework.</td>
                            </tr>
                            <tr>
                                <td><code>ai_deals_cookie_consent</code></td>
                                <td>Stores the cookie categories you selected so the banner does not reappear on every page.</td>
                                <td><span class="legal-pill legal-pill--required">Necessary</span></td>
                                <td>180 days, then you will be asked again.</td>
                            </tr>
                            <tr>
                                <td><code>ai-deals-theme</code></td>
                                <td>Local browser storage that remembers light or dark mode.</td>
                                <td><span class="legal-pill legal-pill--preference">Preference</span></td>
                                <td>Until you clear it or change the preference.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>3. Optional categories</h2>
                <p>The consent manager includes Analytics and Marketing categories so the site can be extended responsibly later. They are not active in this version, and no optional analytics or marketing script is loaded by this application.</p>
                <p>If optional tools are added, they should only be initialized after consent and should be documented here with their provider, purpose, cookie names, retention and transfer information.</p>

                <h2>4. How to change your choices</h2>
                <p>Select <strong>Manage cookies</strong> in the footer on any page. You can accept all, keep only necessary cookies, or save a granular choice. Withdrawing optional consent does not affect the lawfulness of processing that occurred before withdrawal.</p>

                <h2>5. Third-party websites</h2>
                <p>Product links lead to external merchants and affiliate networks. They may use their own cookies and tracking technologies after you leave AI Deals. Read their policies before continuing.</p>

                <div class="legal-actions">
                    <button class="button-secondary" type="button" data-cookie-open>Manage cookies</button>
                    <a class="button-primary" href="{{ route('privacy') }}">Read privacy policy</a>
                </div>
            </article>
        </div>
    </main>

    @include('partials.site-footer')
</body>
</html>
