(() => {
  'use strict';

  function setupMobileMenu() {
    const toggle = document.querySelector('.menu-toggle');
    const menu = document.getElementById('mobile-menu');
    if (!toggle || !menu) return;

    const close = () => {
      menu.hidden = true;
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Menü öffnen');
      document.body.classList.remove('menu-open');
    };

    toggle.addEventListener('click', () => {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      if (isOpen) {
        close();
        return;
      }

      menu.hidden = false;
      toggle.setAttribute('aria-expanded', 'true');
      toggle.setAttribute('aria-label', 'Menü schließen');
      document.body.classList.add('menu-open');
    });

    menu.querySelectorAll('a').forEach(link => link.addEventListener('click', close));
    window.addEventListener('resize', () => {
      if (window.innerWidth > 1080) close();
    });
  }

  function setupMobileKnowledge() {
    const page = document.querySelector('body[data-page="stl-dateien"]');
    if (!page) return;

    const media = window.matchMedia('(max-width: 640px)');
    const sections = [...page.querySelectorAll('main section.section[id], .smartwerk-content section.section[id]')]
      .filter(section => section.querySelector(':scope > .site-shell > .section-head'));

    sections.forEach((section, index) => {
      if (section.querySelector(':scope > .site-shell > .mobile-section-toggle')) return;

      const shell = section.querySelector(':scope > .site-shell');
      const head = shell?.querySelector(':scope > .section-head');
      if (!shell || !head) return;

      section.classList.add('mobile-section-collapsible');
      section.dataset.mobileDefault = index === 0 ? 'open' : 'closed';

      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'mobile-section-toggle';
      button.setAttribute('aria-expanded', 'true');
      button.innerHTML = '<span>Inhalt anzeigen</span><b aria-hidden="true">+</b>';
      head.insertAdjacentElement('afterend', button);

      button.addEventListener('click', () => {
        const collapsed = section.classList.toggle('is-collapsed');
        section.dataset.mobileOpened = collapsed ? '' : 'true';
        button.setAttribute('aria-expanded', String(!collapsed));
        button.querySelector('span').textContent = collapsed ? 'Inhalt anzeigen' : 'Inhalt schließen';
        button.querySelector('b').textContent = collapsed ? '+' : '−';
      });
    });

    const applyState = () => {
      sections.forEach(section => {
        const button = section.querySelector('.mobile-section-toggle');
        if (!button) return;

        if (!media.matches) {
          section.classList.remove('is-collapsed');
          button.setAttribute('aria-expanded', 'true');
          button.querySelector('span').textContent = 'Inhalt schließen';
          button.querySelector('b').textContent = '−';
          return;
        }

        const collapse = section.dataset.mobileDefault === 'closed' && section.dataset.mobileOpened !== 'true';
        section.classList.toggle('is-collapsed', collapse);
        button.setAttribute('aria-expanded', String(!collapse));
        button.querySelector('span').textContent = collapse ? 'Inhalt anzeigen' : 'Inhalt schließen';
        button.querySelector('b').textContent = collapse ? '+' : '−';
      });
    };

    page.querySelectorAll('.knowledge-nav a[href^="#"], .knowledge-topic-card[href^="#"]').forEach(link => {
      link.addEventListener('click', () => {
        const target = document.querySelector(link.getAttribute('href'));
        if (!target?.classList.contains('mobile-section-collapsible')) return;
        target.dataset.mobileOpened = 'true';
        target.classList.remove('is-collapsed');
      });
    });

    media.addEventListener?.('change', applyState);
    applyState();
  }

  function setCartCount(count) {
    const value = Math.max(0, Number.parseInt(count, 10) || 0);

    document.querySelectorAll('[data-cart-count]').forEach(node => {
      node.textContent = String(value);
      node.hidden = value === 0;
    });
  }

  async function refreshCartCount() {
    const url = window.smartwerkTheme?.storeCartUrl;
    if (!url) return;

    try {
      const response = await fetch(url, {
        method: 'GET',
        credentials: 'same-origin',
        headers: { Accept: 'application/json' },
        cache: 'no-store'
      });

      if (!response.ok) return;

      const cart = await response.json();
      if (typeof cart?.items_count !== 'undefined') {
        setCartCount(cart.items_count);
      }
    } catch (_) {
      // The PHP-rendered counter remains the fallback if the Store API is unavailable.
    }
  }

  function setupCartCountSync() {
    if (!document.querySelector('[data-cart-count]')) return;

    ['wc-blocks_added_to_cart', 'wc-blocks_removed_from_cart'].forEach(eventName => {
      document.body.addEventListener(eventName, refreshCartCount);
    });

    if (window.jQuery) {
      window.jQuery(document.body).on(
        'added_to_cart removed_from_cart updated_wc_div',
        () => window.setTimeout(refreshCartCount, 0)
      );
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    setupMobileMenu();
    setupMobileKnowledge();
    setupCartCountSync();
  });
})();