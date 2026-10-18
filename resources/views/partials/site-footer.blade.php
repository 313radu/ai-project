<footer class="site-footer">
    <div class="site-container site-footer__inner">
        <span>© {{ date('Y') }} AI Deals</span>
        <nav class="site-footer__links" aria-label="Legal links">
            <a href="{{ route('privacy') }}">Privacy policy</a>
            <a href="{{ route('cookies') }}">Cookie policy</a>
            <button type="button" data-cookie-open>Manage cookies</button>
        </nav>
    </div>
</footer>

@include('partials.cookie-consent')
