const SITE = {
  logo: 'https://www.smartwerk.art/wp-content/uploads/2025/12/smartwerk_logo.png',
  hero: 'https://www.smartwerk.art/wp-content/uploads/2026/09/167805C5-905F-4A85-A00A-7E9436D84053.png'
};

const PRODUCTS = [
  {
    id: 'pitch-markers',
    name: 'Sportliche Spielfeldmarkierungen – Fußball, Tor & Spielfeld',
    price: '5,99 € – 14,99 €',
    image: 'assets/product-1.jpg',
    category: 'sport',
    badge: 'SmartWerk Design'
  },
  {
    id: 'tennis-camera',
    name: 'Tennis Netz Kamera Halterung – sicher filmen beim Training',
    price: '19,90 € – 89,90 €',
    image: 'assets/product-2.jpg',
    category: 'sport',
    badge: 'Praxisprodukt'
  },
  {
    id: 'bottle-tags',
    name: 'Personalisierter Flaschenanhänger mit Wunschtext',
    price: '9,99 € – 259,00 €',
    image: 'assets/product-3.jpg',
    category: 'personalisiert',
    badge: 'Personalisierbar'
  },
  {
    id: 'fence-hooks',
    name: 'Performance Zaunhaken – Tennis, Padel & Outdoor',
    price: '12,90 € – 79,99 €',
    image: 'assets/product-4.jpg',
    category: 'sport',
    badge: 'Bestseller'
  },
  {
    id: 'mini-signs',
    name: 'Personalisiertes Mini-Kennzeichen im deutschen Design',
    price: '9,90 € – 23,90 €',
    image: 'assets/product-5.jpg',
    category: 'personalisiert',
    badge: 'Geschenkidee'
  },
  {
    id: 'wiesn-frame',
    name: 'Personalisierter Wiesn Fotorahmen – Polaroid Foto-Prop',
    price: '5,90 € – 23,90 €',
    image: 'assets/product-6.jpg',
    category: 'geschenk',
    badge: 'Wiesn'
  },
  {
    id: 'place-signs',
    name: 'Personalisiertes Mini-Ortsschild mit Wunschname',
    price: '6,99 € – 14,99 €',
    image: 'assets/product-7.jpg',
    category: 'personalisiert',
    badge: 'Personalisierbar'
  },
  {
    id: 'custom-print',
    name: 'Individueller 3D-Druck nach Datei oder Idee',
    price: 'Preis nach Konfiguration',
    image: 'assets/product-8.svg',
    category: 'individuell',
    badge: 'Made in Feldkirchen'
  }
];

function headerMarkup() {
  return `
    <header class="site-header" data-site-header>
      <div class="site-shell header-row">
        <a class="brand" href="index.html" aria-label="SmartWerk Startseite">
          <img src="${SITE.logo}" alt="SmartWerk.art" onerror="this.style.display='none';this.nextElementSibling.style.display='inline'">
          <span class="brand-fallback" aria-hidden="true">SmartWerk.art</span>
        </a>
        <nav class="desktop-nav" aria-label="Hauptnavigation">
          <a href="3d-druck.html" data-nav="3d-druck">3D-Druck</a>
          <a href="individuell.html" data-nav="individuell">Individuell</a>
          <a href="shop.html" data-nav="shop" class="nav-shop">Shop</a>
          <a href="projekte.html" data-nav="projekte">Projekte</a>
          <a href="stl-dateien.html" data-nav="stl-dateien">Wissen &amp; STL</a>
        </nav>
        <div class="header-actions">
          <a class="button button-primary header-price" href="3d-druck.html#konfigurator">Preis berechnen</a>
          <a class="cart-button" href="warenkorb.html" aria-label="Warenkorb">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 4.5h2l1.7 9.1a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 1.9-1.4l1.2-5.3H7.1M9.5 19a1.25 1.25 0 1 0 0 .01M17.3 19a1.25 1.25 0 1 0 0 .01"/></svg>
            <span class="cart-count" data-cart-count hidden>0</span>
          </a>
          <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="Menü öffnen">
            <span></span><span></span><span></span>
          </button>
        </div>
      </div>
      <nav class="mobile-nav" id="mobile-menu" aria-label="Mobile Navigation" hidden>
        <div class="site-shell mobile-nav-inner">
          <a href="index.html">Startseite</a>
          <a href="3d-druck.html">3D-Druck</a>
          <a href="individuell.html">Individuell</a>
          <a href="shop.html">Shop</a>
          <a href="projekte.html">Projekte</a>
          <a href="stl-dateien.html">Wissen &amp; STL</a>
          <a href="kontakt.html">Kontakt</a>
          <a class="mobile-price" href="3d-druck.html#konfigurator">Preis berechnen</a>
        </div>
      </nav>
    </header>`;
}

function footerMarkup() {
  return `
    <footer class="site-footer">
      <div class="site-shell footer-grid">
        <a class="footer-brand" href="index.html">
          <img src="${SITE.logo}" alt="SmartWerk.art" onerror="this.style.display='none';this.nextElementSibling.style.display='inline'">
          <span class="brand-fallback" aria-hidden="true">SmartWerk.art</span>
        </a>
        <p>Online 3D-Druck &amp; individuelle Fertigung aus Feldkirchen bei München.</p>
        <nav class="footer-links" aria-label="Rechtliches">
          <a href="kontakt.html">Kontakt</a>
          <a href="impressum.html">Impressum</a>
          <a href="datenschutz.html">Datenschutz</a>
          <a href="agb.html">AGB</a>
        </nav>
      </div>
    </footer>`;
}

function injectChrome() {
  const header = document.querySelector('[data-header-slot]');
  const footer = document.querySelector('[data-footer-slot]');
  if (header) header.outerHTML = headerMarkup();
  if (footer) footer.outerHTML = footerMarkup();

  const current = document.body.dataset.page;
  const active = document.querySelector(`[data-nav="${current}"]`);
  if (active) active.classList.add('is-active');
}

function setupMobileMenu() {
  const toggle = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.mobile-nav');
  if (!toggle || !menu) return;

  toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!open));
    menu.hidden = open;
    document.body.classList.toggle('menu-open', !open);
  });
}

function getCart() {
  try { return JSON.parse(localStorage.getItem('smartwerkCart') || '[]'); }
  catch { return []; }
}

function setCart(cart) {
  localStorage.setItem('smartwerkCart', JSON.stringify(cart));
  updateCartCount();
}

function updateCartCount() {
  const total = getCart().reduce((sum, item) => sum + (item.qty || 0), 0);
  document.querySelectorAll('[data-cart-count]').forEach(el => {
    el.textContent = total;
    el.hidden = total < 1;
  });
}

function addToCart(id) {
  const cart = getCart();
  const existing = cart.find(item => item.id === id);
  if (existing) existing.qty += 1;
  else cart.push({ id, qty: 1 });
  setCart(cart);
  showToast('Zum Warenkorb hinzugefügt');
}

function showToast(message) {
  let toast = document.querySelector('.toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.className = 'toast';
    toast.setAttribute('role', 'status');
    document.body.appendChild(toast);
  }
  toast.textContent = message;
  toast.classList.add('is-visible');
  clearTimeout(showToast.timer);
  showToast.timer = setTimeout(() => toast.classList.remove('is-visible'), 1800);
}

function productCard(product) {
  return `
    <article class="product-card" data-category="${product.category}">
      <div class="product-image-wrap">
        <img class="product-image" src="${product.image}" alt="${product.name}">
        <span class="product-badge">${product.badge}</span>
      </div>
      <div class="product-body">
        <h3>${product.name}</h3>
        <p class="product-price">${product.price}</p>
        <p class="product-meta">Kein Mehrwertsteuerausweis, da Kleinunternehmer nach §19 UStG.</p>
        <p class="product-shipping">zzgl. Versandkosten</p>
        <button class="button button-primary product-button" data-add-cart="${product.id}">Ausführung wählen</button>
      </div>
    </article>`;
}

function renderProducts() {
  const shopGrid = document.querySelector('[data-product-grid]');
  if (shopGrid) shopGrid.innerHTML = PRODUCTS.map(productCard).join('');

  const preview = document.querySelector('[data-product-preview]');
  if (preview) preview.innerHTML = PRODUCTS.slice(0, 4).map(productCard).join('');

  document.querySelectorAll('[data-add-cart]').forEach(button => {
    button.addEventListener('click', () => addToCart(button.dataset.addCart));
  });
}

function setupFilters() {
  const buttons = document.querySelectorAll('[data-filter]');
  if (!buttons.length) return;
  buttons.forEach(button => button.addEventListener('click', () => {
    buttons.forEach(b => b.classList.remove('is-active'));
    button.classList.add('is-active');
    const filter = button.dataset.filter;
    document.querySelectorAll('.product-card').forEach(card => {
      card.hidden = filter !== 'all' && card.dataset.category !== filter;
    });
  }));
}

function setupConfigurator() {
  const config = document.querySelector('[data-configurator]');
  if (!config) return;
  const material = config.querySelector('[name="material"]');
  const quality = config.querySelector('[name="quality"]');
  const scale = config.querySelector('[name="scale"]');
  const price = config.querySelector('[data-price]');
  const fileInput = config.querySelector('[type="file"]');
  const fileName = config.querySelector('[data-file-name]');
  const drop = config.querySelector('.upload-zone');

  const recalc = () => {
    const m = { PLA: 1, PETG: 1.12, TPU: 1.36 }[material?.value] || 1;
    const q = { Standard: 1, Fein: 1.28, Extra: 1.55 }[quality?.value] || 1;
    const s = Number(scale?.value || 100) / 100;
    const value = Math.max(9.9, 13.4 * m * q * Math.pow(s, 1.35));
    if (price) price.textContent = `${value.toFixed(2).replace('.', ',')} €`;
  };

  [material, quality, scale].forEach(el => el?.addEventListener('input', recalc));
  config.querySelectorAll('[data-color]').forEach(swatch => swatch.addEventListener('click', () => {
    config.querySelectorAll('[data-color]').forEach(s => s.classList.remove('is-selected'));
    swatch.classList.add('is-selected');
    config.style.setProperty('--viewer-accent', swatch.dataset.color);
  }));

  if (drop && fileInput) drop.addEventListener('click', () => fileInput.click());
  fileInput?.addEventListener('change', () => {
    if (fileInput.files[0]) {
      fileName.textContent = fileInput.files[0].name;
      drop.classList.add('has-file');
    }
  });
  recalc();
}

function renderCart() {
  const root = document.querySelector('[data-cart-page]');
  if (!root) return;
  const cart = getCart();
  const items = cart.map(item => ({ ...item, product: PRODUCTS.find(p => p.id === item.id) })).filter(x => x.product);

  if (!items.length) {
    root.innerHTML = `
      <div class="empty-state">
        <div class="empty-icon">🛒</div>
        <h2>Dein Warenkorb ist leer.</h2>
        <p>Im SmartWerk Shop findest du ausgewählte 3D-gedruckte Produkte und personalisierbare Designs.</p>
        <a class="button button-primary" href="shop.html">Zum Shop</a>
      </div>`;
    return;
  }

  root.innerHTML = `
    <div class="cart-layout">
      <div class="cart-items">
        ${items.map(({product, qty}) => `
          <article class="cart-item" data-cart-item="${product.id}">
            <img src="${product.image}" alt="${product.name}">
            <div class="cart-item-copy">
              <h3>${product.name}</h3>
              <p>${product.price}</p>
              <div class="quantity-row">
                <button data-qty="minus" aria-label="Menge verringern">−</button>
                <span>${qty}</span>
                <button data-qty="plus" aria-label="Menge erhöhen">+</button>
                <button class="remove-item" data-remove>Entfernen</button>
              </div>
            </div>
          </article>`).join('')}
      </div>
      <aside class="cart-summary">
        <span class="eyebrow">Zusammenfassung</span>
        <h2>Deine Auswahl</h2>
        <div class="summary-row"><span>Artikel</span><strong>${items.reduce((a,b)=>a+b.qty,0)}</strong></div>
        <div class="summary-row"><span>Versand</span><strong>im Checkout</strong></div>
        <p class="summary-note">Für diese statische Vorschau wird noch kein echter Checkout ausgeführt.</p>
        <button class="button button-primary button-wide" type="button" onclick="showToast('Checkout ist in dieser Demo deaktiviert')">Weiter zum Checkout</button>
      </aside>
    </div>`;

  root.querySelectorAll('[data-cart-item]').forEach(itemEl => {
    const id = itemEl.dataset.cartItem;
    itemEl.querySelector('[data-qty="minus"]').addEventListener('click', () => changeQty(id, -1));
    itemEl.querySelector('[data-qty="plus"]').addEventListener('click', () => changeQty(id, 1));
    itemEl.querySelector('[data-remove]').addEventListener('click', () => removeItem(id));
  });
}

function changeQty(id, delta) {
  const cart = getCart();
  const item = cart.find(x => x.id === id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) cart.splice(cart.indexOf(item), 1);
  setCart(cart);
  renderCart();
}

function removeItem(id) {
  setCart(getCart().filter(x => x.id !== id));
  renderCart();
}

function setupContactForm() {
  const form = document.querySelector('[data-contact-form]');
  if (!form) return;
  form.addEventListener('submit', event => {
    event.preventDefault();
    const status = form.querySelector('[data-form-status]');
    status.textContent = 'Danke! In dieser statischen Vorschau wird noch keine Nachricht versendet.';
    status.hidden = false;
  });
}

function setupHeroImage() {
  document.documentElement.style.setProperty('--hero-image', `url("${SITE.hero}")`);
}

document.addEventListener('DOMContentLoaded', () => {
  injectChrome();
  setupMobileMenu();
  updateCartCount();
  renderProducts();
  setupFilters();
  setupConfigurator();
  renderCart();
  setupContactForm();
  setupHeroImage();
});
