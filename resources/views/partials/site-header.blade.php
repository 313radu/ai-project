<header class="site-header">
    <div class="site-header__inner">
        <a href="{{ route('home') }}" class="brand" aria-label="AI Deals home">
            <span class="brand-mark" aria-hidden="true">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3 20 7.5v9L12 21l-8-4.5v-9L12 3Z" />
                    <path d="m8.5 10 3.5 2 3.5-2M12 12v4.5" />
                </svg>
            </span>
            <span class="brand-copy"><strong>AI</strong><span>Deals</span></span>
        </a>

        <nav class="site-nav" aria-label="Primary navigation">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Discover</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}">All products</a>
        </nav>

        <div class="header-actions">
            <span class="header-meta">Live catalog</span>
            <button class="theme-toggle" type="button" data-theme-toggle aria-label="Switch to dark mode">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" /></svg>
            </button>
        </div>
    </div>
</header>
