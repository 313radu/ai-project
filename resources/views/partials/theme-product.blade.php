<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#f7f6f2; --surface:#fff; --surface-2:#f3f0ec; --border:rgba(0,0,0,.08);
  --text:#1a1916; --muted:#6b6a66; --faint:#b0aea9;
  --primary:#01696f; --primary-h:#0c4e54; --accent:#da7101;
  --radius:14px; --radius-sm:8px;
  --shadow:0 2px 8px rgba(0,0,0,.06),0 8px 24px rgba(0,0,0,.04);
  --font-body:'Inter',sans-serif; --font-disp:'Instrument Serif',serif;
}
@media (prefers-color-scheme: dark){
  :root{
    --bg:#141312; --surface:#1c1b19; --surface-2:#222120; --border:rgba(255,255,255,.07);
    --text:#d4d3d0; --muted:#8a8986; --faint:#4a4947;
    --primary:#4f98a3; --primary-h:#62adb9; --accent:#fdab43;
    --shadow:0 2px 8px rgba(0,0,0,.4);
  }
}
html{-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:100%}
body{font-family:var(--font-body);background:var(--bg);color:var(--text);line-height:1.6;overflow-x:hidden}
img{max-width:100%;height:auto}

header{background:var(--surface);color:var(--text);height:60px;padding:0 24px;
  border-bottom:1px solid var(--border);box-shadow:none;backdrop-filter:blur(12px)}
.logo{font-family:var(--font-disp);font-weight:400;font-size:1.25rem;color:var(--text)}

.container{max-width:1080px;padding:32px 24px 64px}
.back-link{color:var(--muted);font-weight:500;margin-bottom:20px;min-height:44px}
.back-link:hover{color:var(--primary)}

.product-wrap{background:var(--surface);border:1px solid var(--border);
  border-radius:var(--radius);box-shadow:var(--shadow);grid-template-columns:minmax(0,420px) minmax(0,1fr)}
.product-gallery{background:var(--surface-2);border-right:1px solid var(--border);padding:32px}
.product-gallery img{max-height:360px;border-radius:var(--radius-sm)}
.product-info{padding:32px 40px}

.info-category{color:var(--primary);font-weight:600;letter-spacing:.06em}
.info-name{font-family:var(--font-disp);font-weight:400;font-size:clamp(1.6rem,4vw,2.25rem);line-height:1.15;overflow-wrap:anywhere}
.info-brand{color:var(--muted)}
.info-brand span{color:var(--text)}

.price-block{background:color-mix(in oklab,var(--primary) 8%,var(--surface));
  border:1px solid color-mix(in oklab,var(--primary) 25%,transparent);border-radius:var(--radius-sm)}
.price-main{color:var(--primary);font-weight:700;font-variant-numeric:tabular-nums}
.price-currency{color:var(--muted)}
.price-old{color:var(--faint)}
.badge-discount{background:var(--accent);color:#1a1916}

.info-desc{color:var(--muted);overflow-wrap:anywhere}
.info-meta{color:var(--muted)}
.info-meta strong{color:var(--text)}

.btn-buy{background:var(--primary);color:#fff;border-radius:var(--radius-sm);font-weight:600;
  min-height:48px;display:inline-flex;align-items:center;justify-content:center}
.btn-buy:hover{background:var(--primary-h)}

/* ---------- Tablet ---------- */
@media (max-width:900px){
  .product-wrap{grid-template-columns:minmax(0,320px) minmax(0,1fr)}
  .product-info{padding:24px}
}

/* ---------- Mobile ---------- */
@media (max-width:720px){
  header{padding:0 16px;height:56px}
  .container{padding:16px 16px 48px}
  .product-wrap{grid-template-columns:minmax(0,1fr)}
  .product-gallery{border-right:0;border-bottom:1px solid var(--border);padding:20px;min-height:0}
  .product-gallery img{max-height:260px}
  .product-info{padding:20px 16px}
  .price-block{padding:14px;gap:8px}
  .price-main{font-size:1.75rem}
  .actions{flex-direction:column;gap:10px}
  .actions > *{width:100%;min-width:0;flex:none}
  .btn-buy{width:100%}
}
@media (max-width:380px){
  .info-name{font-size:1.4rem}
  .price-main{font-size:1.5rem}
}
</style>
