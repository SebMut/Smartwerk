SmartWerk WordPress Theme 1.2.4
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
2. smartwerk-theme-1.2.4.zip auswählen
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


SmartWerk 1.2.4 migration layer
-------------------------------
- Full current GitHub master CSS remains the base layer.
- Existing sw-* transfer pages receive a complete layout compatibility bridge.
- Gutenberg Contact/Legal pages receive a centered SmartWerk shell.
- WooCommerce Cart/Checkout Blocks remain native.
- WP3DPrinting internals remain untouched.

SmartWerk 1.2.4 proof-layout fix
-------------------------------
- prevents the "Echt bei SmartWerk" copy column from collapsing
- stacks copy and gallery earlier on medium desktop/tablet widths
- preserves the existing mobile gallery layout


SmartWerk 1.2.4 safe performance baseline
-----------------------------------------
- based on stable 1.2.1 theme code
- no forced hero preload
- no additional script strategy changes
- homepage image lazy-loading remains in page content
- homepage hero image uses the WordPress image CDN


SmartWerk 1.2.4 mobile polish
-----------------------------
- keeps the SmartWerk logo compact and visible in the sticky mobile header
- prevents the logo from overflowing into the homepage hero
- stacks all three homepage hero CTAs at equal full width on phones
- leaves desktop/tablet styling unchanged
