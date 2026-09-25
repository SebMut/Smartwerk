# WP3DPrinting → SmartWerk Integration

## Ziel

Die statische SmartWerk-Seite bleibt die visuelle Referenz. In WordPress ersetzt WP3DPrinting ausschließlich die statische Konfigurator-Vorschau innerhalb des vorbereiteten SmartWerk-Wrappers.

## Feste Grenze

SmartWerk besitzt und gestaltet:

- Seiten-Header
- Seiten-Hero
- Überschrift und Einleitung des Konfigurators
- Funktions-Chips oberhalb des Konfigurators
- äußere Breite, Abstände und responsive Seitenstruktur
- Bereiche unterhalb des Konfigurators

WP3DPrinting / WooCommerce besitzt:

- Datei-Upload
- 3D-Modellansicht
- Skalierung / Rotation
- Material- und Druckoptionen
- Preisberechnung
- Add-to-cart / Warenkorb-Übergabe

## Einbaupunkt

In `3d-druck.html` ist folgender Wrapper vorbereitet:

```html
<div class="wp3dprint-integration-shell" data-wp3dprint-shell>
  <div class="wp3dprint-plugin-mount" data-wp3dprint-mount></div>
</div>
```

In WordPress gehört die reale WP3DPrinting-Ausgabe in `.wp3dprint-plugin-mount`.

Die statische Vorschau `.wp3dprint-static-preview` dient nur der HTML/GitHub-Pages-Version und wird in WordPress entfernt oder ausgeblendet.

## CSS-Regel

Vor der echten WordPress-Integration werden **keine vermuteten Plugin-Klassen** in SmartWerk-CSS überschrieben.

Erst wenn WP3DPrinting im Browser real gerendert ist:

1. HTML-Struktur im Browser prüfen.
2. Nur notwendige visuelle Abweichungen dokumentieren.
3. Möglichst über den äußeren Wrapper und CSS Custom Properties arbeiten.
4. Plugin-interne Selektoren nur gezielt und minimal überschreiben.
5. Keine globalen Regeln wie `form input`, `.button`, `select` oder WooCommerce-weite Hard-Fixes verwenden.
6. Desktop, Tablet und Smartphone nach jeder Anpassung vergleichen.

## WordPress-Abnahme

Vor Live-Schaltung prüfen:

- Upload funktioniert für die tatsächlich aktivierten Dateiformate.
- Modellansicht lädt ohne Layout-Sprung.
- Material und Druckoptionen funktionieren.
- Preis aktualisiert sich korrekt.
- Add-to-cart übernimmt die Konfiguration.
- Warenkorb zeigt Produkt und Konfiguration korrekt.
- Produkt 783 / bestehende Schutzregeln werden beim Umbau berücksichtigt, falls weiterhin relevant.
- Header und Footer entsprechen der statischen SmartWerk-Referenz.
- Kein Plugin-CSS verändert Shop, Warenkorb oder andere Seiten unbeabsichtigt.

## Grundsatz

**Erst reale Plugin-Ausgabe ansehen, dann CSS anpassen. Keine Trial-and-Error-Hard-Fixes gegen unbekannte Plugin-Strukturen.**
