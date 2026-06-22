# Al-Kautsar Mosque — WordPress Theme

An elegant, luxurious WordPress theme designed exclusively for **Masjid Al-Kautsar**.

## ✨ Features

- **Refined 60-30-10 palette** — espresso brown `#3B1E12` (30%), cream ivory `#F8F1E4` (60%), bright gold `#D4AF37` (10% accent)
- **Live prayer schedule** — auto-calculated daily from the Aladhan API using mosque coordinates (defaults to Jakarta / Kemenag RI method)
- **Donation shortcuts** — bank transfer with copy-to-clipboard, QRIS QR code, WhatsApp confirmation (no payment gateway)
- **Responsive & mobile-friendly** — slide-in mobile menu, fluid typography, breakpoint-optimised layouts
- **OWASP security hardening** — escaping & sanitisation on every output, CSRF nonces on AJAX, security headers, disabled XML-RPC & file editing
- **WordPress Codex compliance** — semantic markup, ARIA roles, `wp_localize_script`, custom logo, custom menus, widget areas, translation-ready
- **Performance** — Google Fonts with `preconnect`, deferred scripts, `IntersectionObserver` reveal animations, localStorage caching for prayer times
- **Accessibility** — `prefers-reduced-motion` support, skip-to-content link, visible focus states, screen-reader text
- **Customizer integration** — configure bank account, QRIS image, WhatsApp number, prayer coordinates, contact info, hero content — all without touching code

## 📦 Installation

### Option A — WordPress admin (recommended)
1. Zip the `alkautsar-mosque` folder → `alkautsar-mosque.zip`
2. WordPress admin → **Appearance → Themes → Add New → Upload Theme**
3. Upload the zip → **Install → Activate**

### Option B — FTP
1. Upload the `alkautsar-mosque` folder to `/wp-content/themes/`
2. **Appearance → Themes → Activate** "Al-Kautsar Mosque"

## ⚙️ Configuration

After activation, go to **Appearance → Customize → Mosque Settings**:

- **Hero Section** — Arabic verse, title, subtitle, background image
- **Prayer Times** — latitude/longitude, calculation method (default: Kemenag RI)
- **Donation** — bank name, account number, holder name, QRIS image, WhatsApp number
- **Contact** — address, phone, email, Instagram, YouTube

## 🧭 Menu Setup

1. **Appearance → Menus → Create Menu**
2. Add pages: Home, News, Profile, Program, Donation, Transparency, Prayer Schedule, Contact
3. **Menu Settings → Display location → Primary Menu**
4. (Optional) Create a second menu and assign to "Footer Menu"

If no menu is assigned, the theme will display a sensible fallback.

## 🏠 Setting the Homepage

1. **Settings → Reading → Your homepage displays** → "A static page"
2. Create a page titled "Home" and assign it as the homepage
3. The `front-page.php` template automatically renders the mosque homepage

## 🔒 Security Notes

- All outputs use `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`
- AJAX endpoints check `wp_verify_nonce` (CSRF protection)
- Server-side input validation on prayer-time coordinates
- Security headers: `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`
- `DISALLOW_FILE_EDIT` enabled by default
- XML-RPC disabled
- No server-side form processing for the contact form (uses WhatsApp redirect to avoid PII storage)

## 🌐 Third-party APIs

- **Aladhan Prayer Times API** — https://aladhan.com/prayer-times-api — free, no key required
- **Google Fonts** — Cormorant Garamond (display), Inter (body), Amiri (Arabic)

## 📁 File Structure

```
alkautsar-mosque/
├── style.css                 Theme header (visible to WP admin)
├── functions.php             Theme setup, scripts, AJAX, widgets
├── front-page.php            Homepage (hero, prayer, about, programs, donation, news, transparency, contact)
├── index.php                 Blog listing fallback
├── single.php                Single post
├── page.php                  Static page
├── search.php                Search results
├── searchform.php            Search form
├── archive.php               Category / tag / date archive
├── 404.php                   Not-found page
├── header.php                Site header with top bar + main nav
├── footer.php                Site footer with quick links + donation recap
├── sidebar.php               Sidebar widget area
├── comments.php              Comments template
├── README.md                 This file
├── screenshot.png            Theme preview image (shown in WP admin)
├── inc/
│   ├── customizer.php        All Customizer panels/sections/settings
│   ├── security.php          OWASP hardening
│   └── template-tags.php     Reusable template functions
└── assets/
    ├── css/
    │   ├── main.css          ~2100 lines, organised in 20 sections
    │   └── editor-style.css  Block editor styles
    └── js/
        ├── main.js           Menu toggle, sticky header, scroll-to-top, copy, reveal
        └── prayer-times.js   Aladhan API with localStorage cache + AJAX fallback
```

## 📝 License

GPL v2 or later.

## 💛 Credits

Designed for Masjid Al-Kautsar. Built following the WordPress Codex and OWASP Top 10 (2021).
