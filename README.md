# Kinetica Physio — WordPress Landing Page Demo

A **self-initiated demonstration project** by Dhansurrya S P: a custom WordPress block theme that builds a responsive landing page for a fictional physiotherapy clinic. Kinetica Physio is not a real business and this is not a client website. Prices, hours and services are sample content.

**Live demo:** https://dhansurryasp.github.io/wp-landing-demo/
(Runs real WordPress in your browser through [WordPress Playground](https://wordpress.org/playground/); first load takes 10–20 seconds.)

## What it shows

- Custom **block theme** (`theme.json` v3, templates and template parts) — no page builder, every section is editable in the Site Editor
- Landing page sections: hero with first-visit card, services grid, process, pricing, FAQ (accessible `<details>`), contact
- **Secure contact form** (`[kp_contact_form]` shortcode):
  - nonce check, honeypot spam trap, server-side validation and sanitisation, length limits
  - enquiries saved to a private "Enquiries" post type in the dashboard, plus an email to the site admin
  - accessible labels, status messages announced to screen readers
- Responsive from 360 px phones to wide desktops; sticky header; reduced-motion support
- Fonts: Fraunces (display) and Inter (body) from Google Fonts

## Structure

```
theme/kinetica-physio/
├── style.css             theme header
├── theme.json            palette, typography, layout
├── functions.php         assets, enquiry post type, contact form + handler
├── templates/            front-page.html, index.html
├── parts/                header.html, footer.html
└── assets/css/landing.css
docs/                     GitHub Pages: Playground blueprint, theme zip, redirect page
```

## Run it

- Online: open the live demo link above.
- Locally: copy `theme/kinetica-physio` into `wp-content/themes/` of any WordPress 6.5+ site and activate it.

Note: in the Playground demo, outgoing email is not delivered; submitted enquiries appear under **Enquiries** in the dashboard.

## Licence

GPL-2.0-or-later, like WordPress.
