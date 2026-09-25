SmartWerk WordPress Theme 1.2.17
================================

Eigenständiges SmartWerk-Theme auf Basis des bestätigten GitHub-Masters.
Kein Kiosko-Child-Theme.

Enthalten
---------
- vollständiger aktueller SmartWerk-Master-CSS-Stand
- zentraler SmartWerk Header und Footer
- korrekte WordPress-Menüstruktur
- responsive Desktop/Tablet/Mobile-Navigation
- WooCommerce Theme Support
- WooCommerce Classic + WooCommerce Blocks Grundintegration
- echter WooCommerce Warenkorb-Link und dynamischer Artikelzähler
- Gutenberg/WordPress Seiteninhalte bleiben bearbeitbar
- Übergangsschutz gegen alte eingebettete sw-header/sw-footer
- WP3DPrinting wird nur außen eingebettet; keine geratenen Plugin-internen CSS-Hacks
- Wissen-&-STL-Mobile-Accordions

WP3DPrinting
------------
Bestehender SmartWerk-Shortcode:
[3dprint product_id="783" mode="single" compatibility_mode="true"]

Wichtig
-------
Das Theme enthält die Darstellungs- und Integrationsschicht. Die alten
WordPress-Seiteninhalte müssen anschließend mit Easy MCP AI/WPWriter auf den
neuen GitHub-Master-Inhalt migriert werden. Das Theme löscht keine Inhalte.

Die alten page-level SmartWerk Header/Footer werden während der Übergangsphase
nur ausgeblendet, damit der neue globale Theme-Header/Footer nicht doppelt
erscheint.

Installation
------------
1. WordPress > Design > Themes > Theme hinzufügen > Theme hochladen
2. smartwerk-theme-1.2.17.zip auswählen
3. Installieren
4. Vor der Live-Aktivierung in Preview/Staging prüfen
5. Hauptnavigation dem Theme-Ort "Hauptnavigation" zuweisen
6. WooCommerce und WP3DPrinting aktiv lassen

Hinweis zu Schriftarten
-----------------------
Der bestätigte Master verwendet derzeit Manrope über Google Fonts. Diese
Referenz wurde für visuelle Gleichheit unverändert übernommen. Vor dem
endgültigen Livegang kann die Schrift datenschutzorientiert lokalisiert oder
durch einen System-Font-Stack ersetzt werden.


SmartWerk 1.2.17 migration layer
-------------------------------
- Full current GitHub master CSS remains the base layer.
- Existing sw-* transfer pages receive a complete layout compatibility bridge.
- Gutenberg Contact/Legal pages receive a centered SmartWerk shell.
- WooCommerce Cart/Checkout Blocks remain native.
- WP3DPrinting internals remain untouched.

SmartWerk 1.2.17 proof-layout fix
-------------------------------
- prevents the "Echt bei SmartWerk" copy column from collapsing
- stacks copy and gallery earlier on medium desktop/tablet widths
- preserves the existing mobile gallery layout


SmartWerk 1.2.17 safe performance baseline
-----------------------------------------
- based on stable 1.2.1 theme code
- no forced hero preload
- no additional script strategy changes
- homepage image lazy-loading remains in page content
- homepage hero image uses the WordPress image CDN


SmartWerk 1.2.17 mobile polish
-----------------------------
- keeps the SmartWerk logo compact and visible in the sticky mobile header
- prevents the logo from overflowing into the homepage hero
- stacks all three homepage hero CTAs at equal full width on phones
- leaves desktop/tablet styling unchanged


SmartWerk 1.2.17 performance
---------------------------
- based directly on SmartWerk 1.2.4
- removes Google Fonts CSS @import and loads Manrope asynchronously
- adds font preconnect hints
- removes WordPress emoji frontend overhead
- removes unused Gutenberg/WooCommerce frontend assets from the homepage only
- preserves WooCommerce assets on shop, product, cart and checkout pages
- refreshes the cached header cart count via WooCommerce Store API when idle
- uses content-visibility for below-the-fold homepage sections
- does not alter WP3DPrinting internals


SmartWerk 1.2.17 product detail pages
------------------------------------
- SmartWerk two-column product detail layout for desktop
- responsive one-column product layout on tablet/mobile
- readable long product titles and cleaner variation controls
- optimized gallery display size via WordPress medium_large
- small gallery thumbnails via WordPress thumbnail size
- primary product image: eager + fetchpriority high
- additional gallery images: native lazy loading + async decoding
- responsive sizes hint for the product gallery
- full original remains available for WooCommerce zoom/lightbox
- tabs and related products use content-visibility below the fold
- removes unused Gutenberg/Blocks CSS on classic product pages only


SmartWerk 1.2.17 product polish
------------------------------
- reduces desktop product gallery to about 430px
- increases long product description font size and line height
- adds a top-right "15% günstiger wie auf Etsy" badge on the nine confirmed Etsy-import products
- badge is intentionally not shown on services or unverified legacy products
- keeps 1.2.6 product-image lazy/eager strategy unchanged


SmartWerk 1.2.17 badge wording
-----------------------------
- changes the Etsy comparison badge to "15 % unter meinem Etsy-Preis"
- no layout, pricing or product logic changes


SmartWerk 1.2.17 shop filters
----------------------------
- Etsy comparison badge is forced into one single-line pill
- adds live product search
- adds WooCommerce category filter
- adds "only personalized products" filter
- adds maximum start-price slider
- adds client-side sorting by newest, price and name
- adds result count and reset control
- keeps all filtering tied to real WooCommerce product categories and prices


SmartWerk 1.2.17 filter visibility fix
--------------------------------------
- non-matching shop products are hidden with inline display:none!important
- matching products explicitly restore their display state
- result count and visible grid now stay in sync


SmartWerk 1.2.17 product title typography
-----------------------------------------
- smaller product H1 on desktop
- smaller product H1 on mobile
- no changes to prices, gallery, variants or shop filters


SmartWerk 1.2.17 product purchase UI
------------------------------------
- more compact primary product price
- variation rows use a clear label/control grid on desktop
- selected variation price is visually separated
- quantity and add-to-cart button share one purchase row
- mobile variations stack cleanly while preserving a prominent CTA
- no changes to WooCommerce pricing, stock or variation logic


SmartWerk 1.2.17 pack quantity + personalization
------------------------------------------------
- products with the variation attribute "Staffelpreis" or "Menge" are sold as one selected pack
- the redundant WooCommerce quantity selector is removed for those products
- cart quantity for such pack products stays at one
- product text/textarea personalization inputs receive a visible "Personalisierung / Wunschtext" label
- unlabeled personalization inputs receive an accessible label and placeholder


SmartWerk 1.2.17 quantity + content flow
----------------------------------------
- normal product quantity is placed below variation selectors such as Größe/Farbe
- quantity receives a visible semantic label "Anzahl"
- pack/Staffelpreis/Menge products still have no redundant quantity control
- full product description is rendered directly below the purchase controls
- duplicate WooCommerce description tab is removed
- "Zusätzliche Informationen" is renamed to "Produktdetails"
- product attribute table and remaining tabs receive cleaner SmartWerk styling


SmartWerk 1.2.17 trust + product performance
--------------------------------------------
- adds a compact factual shipping/payment block below purchase controls
- shipping facts link to the published Versandarten page
- payment facts link to the published Zahlungsarten page
- no generic delivery promise is added to product pages
- removes wc-cart-fragments and wp-embed on single product pages
- preserves variation, product gallery and add-to-cart scripts


SmartWerk 1.2.17 technical SEO
------------------------------
- preserves native WooCommerce Product structured data and adds SmartWerk brand only when missing
- guarantees one WooCommerce breadcrumb trail on WooCommerce templates
- adds clean breadcrumb styling and category archive typography
- adds fallback meta description, Open Graph and Twitter Card tags when no major SEO plugin is active
- adds canonical tags for product-category and shop archives; WordPress core keeps singular canonicals
- enables the WordPress core XML sitemap fallback and advertises /wp-sitemap.xml in robots.txt
- includes concise SEO title/description fallbacks for current SmartWerk products
- avoids duplicate SEO output when Yoast, Rank Math, AIOSEO, SEOPress or The SEO Framework is detected


SmartWerk 1.2.17 SEO hardening
------------------------------
- explicit homepage SEO title and meta-description fallback
- Organization JSON-LD for SmartWerk on the homepage
- noindex/follow/noarchive for cart, checkout, account, search and 404 pages
- keeps products, shop and product-category archives indexable
- removes obsolete WordPress shortlink head/header output
- does not add an SEO plugin or duplicate WooCommerce Product schema
