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

    const refreshWhenIdle = () => refreshCartCount();
    if ('requestIdleCallback' in window) {
      window.requestIdleCallback(refreshWhenIdle, { timeout: 1500 });
    } else {
      window.setTimeout(refreshWhenIdle, 700);
    }
  }


  function setupShopFilters() {
    const controls = document.querySelector('[data-smartwerk-shop-filters]');
    const grid = document.querySelector('.sw-shop-products ul.products');
    if (!controls || !grid) return;

    const cards = [...grid.querySelectorAll(':scope > li.product')];
    if (!cards.length) return;

    const search = controls.querySelector('[data-shop-search]');
    const category = controls.querySelector('[data-shop-category]');
    const maxPrice = controls.querySelector('[data-shop-price]');
    const priceOutput = controls.querySelector('[data-shop-price-output]');
    const personalized = controls.querySelector('[data-shop-personalized]');
    const sort = controls.querySelector('[data-shop-sort]');
    const reset = controls.querySelector('[data-shop-reset]');
    const count = controls.querySelector('[data-shop-result-count]');
    const noResults = document.querySelector('[data-shop-no-results]');
    const originalOrder = new Map(cards.map((card, index) => [card, index]));

    const priceField = maxPrice?.closest('.smartwerk-shop-filter-field');
    let minPrice = priceField?.querySelector('[data-shop-price-min]');

    if (priceField && maxPrice && !minPrice) {
      priceField.classList.add('smartwerk-shop-price-field');
      minPrice = document.createElement('input');
      minPrice.type = 'range';
      minPrice.min = maxPrice.min || '5';
      minPrice.max = maxPrice.max || '250';
      minPrice.step = maxPrice.step || '5';
      minPrice.value = minPrice.min;
      minPrice.setAttribute('data-shop-price-min', '');
      minPrice.setAttribute('aria-label', 'Startpreis ab');
      maxPrice.setAttribute('aria-label', 'Startpreis bis');
      maxPrice.insertAdjacentElement('beforebegin', minPrice);

      const caption = document.createElement('div');
      caption.className = 'smartwerk-shop-price-range-caption';
      caption.innerHTML = '<span>ab</span><span>bis</span>';
      maxPrice.insertAdjacentElement('afterend', caption);
    }

    let chips = controls.querySelector('[data-shop-active-filters]');
    if (!chips) {
      chips = document.createElement('div');
      chips.className = 'smartwerk-shop-active-filters';
      chips.setAttribute('data-shop-active-filters', '');
      controls.append(chips);
    }

    const productTitle = card =>
      (card.querySelector('.woocommerce-loop-product__title')?.textContent || '')
        .trim()
        .toLocaleLowerCase('de');

    const productPrice = card => {
      const priceNode =
        card.querySelector('.price ins .woocommerce-Price-amount') ||
        card.querySelector('.price .woocommerce-Price-amount') ||
        card.querySelector('.price');

      const text = priceNode?.textContent || '';
      const match = text.match(/(\d[\d.\s]*,\d{2}|\d+(?:[.,]\d+)?)/);
      if (!match) return Number.POSITIVE_INFINITY;

      const normalized = match[1]
        .replace(/\s/g, '')
        .replace(/\./g, '')
        .replace(',', '.');

      const value = Number.parseFloat(normalized);
      return Number.isFinite(value) ? value : Number.POSITIVE_INFINITY;
    };

    const productMatchesCategory = (card, slug) =>
      !slug || card.classList.contains('product_cat-' + slug);

    const defaults = {
      min: Number.parseFloat(minPrice?.min || '5'),
      max: Number.parseFloat(maxPrice?.max || '250')
    };

    const syncUrl = state => {
      const url = new URL(window.location.href);
      ['q','cat','min','max','personalized','sort'].forEach(key => url.searchParams.delete(key));

      if (state.query) url.searchParams.set('q', state.query);
      if (state.categorySlug) url.searchParams.set('cat', state.categorySlug);
      if (state.min > defaults.min) url.searchParams.set('min', String(state.min));
      if (state.max < defaults.max) url.searchParams.set('max', String(state.max));
      if (state.onlyPersonalized) url.searchParams.set('personalized', '1');
      if (state.sortMode !== 'default') url.searchParams.set('sort', state.sortMode);

      history.replaceState(
        null,
        '',
        url.pathname + (url.searchParams.toString() ? '?' + url.searchParams.toString() : '') + url.hash
      );
    };

    const addChip = (label, clear) => {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'smartwerk-filter-chip';
      button.textContent = label;
      button.addEventListener('click', () => {
        clear();
        apply();
      });
      chips.append(button);
    };

    const renderChips = state => {
      chips.replaceChildren();

      if (state.query) addChip('Suche: ' + state.query, () => { if (search) search.value = ''; });

      if (state.categorySlug) {
        const label = category?.selectedOptions?.[0]?.textContent?.trim() || state.categorySlug;
        addChip(label, () => { if (category) category.value = ''; });
      }

      if (state.min > defaults.min) {
        addChip('ab ' + Math.round(state.min) + ' €', () => {
          if (minPrice) minPrice.value = String(defaults.min);
        });
      }

      if (state.max < defaults.max) {
        addChip('bis ' + Math.round(state.max) + ' €', () => {
          if (maxPrice) maxPrice.value = String(defaults.max);
        });
      }

      if (state.onlyPersonalized) {
        addChip('Personalisierbar', () => {
          if (personalized) personalized.checked = false;
        });
      }

      if (state.sortMode !== 'default') {
        const label = sort?.selectedOptions?.[0]?.textContent?.trim() || 'Sortierung';
        addChip(label, () => { if (sort) sort.value = 'default'; });
      }
    };

    const apply = (updateUrl = true) => {
      let min = Number.parseFloat(minPrice?.value || String(defaults.min));
      let max = Number.parseFloat(maxPrice?.value || String(defaults.max));

      if (min > max) {
        if (document.activeElement === minPrice && maxPrice) {
          max = min;
          maxPrice.value = String(max);
        } else if (minPrice) {
          min = max;
          minPrice.value = String(min);
        }
      }

      const state = {
        query: (search?.value || '').trim(),
        categorySlug: category?.value || '',
        min,
        max,
        onlyPersonalized: Boolean(personalized?.checked),
        sortMode: sort?.value || 'default'
      };

      if (priceOutput) {
        priceOutput.textContent = Math.round(state.min) + '–' + Math.round(state.max) + ' €';
      }

      let visible = 0;

      cards.forEach(card => {
        const priceValue = productPrice(card);
        const matchesSearch =
          !state.query || productTitle(card).includes(state.query.toLocaleLowerCase('de'));
        const matchesCategory = productMatchesCategory(card, state.categorySlug);
        const matchesPersonalized =
          !state.onlyPersonalized || card.classList.contains('product_cat-personalisierte-produkte');
        const matchesPrice = priceValue >= state.min && priceValue <= state.max;
        const show = matchesSearch && matchesCategory && matchesPersonalized && matchesPrice;

        card.classList.toggle('smartwerk-filter-hidden', !show);

        if (show) {
          card.hidden = false;
          card.style.removeProperty('display');
          visible += 1;
        } else {
          card.hidden = true;
          card.style.setProperty('display', 'none', 'important');
        }
      });

      const sorted = [...cards].sort((a, b) => {
        if (state.sortMode === 'price-asc') return productPrice(a) - productPrice(b);
        if (state.sortMode === 'price-desc') return productPrice(b) - productPrice(a);
        if (state.sortMode === 'name-asc') return productTitle(a).localeCompare(productTitle(b), 'de');
        return originalOrder.get(a) - originalOrder.get(b);
      });

      sorted.forEach(card => grid.append(card));

      if (count) count.textContent = String(visible);
      if (noResults) noResults.hidden = visible !== 0;

      renderChips(state);
      if (updateUrl) syncUrl(state);
    };

    const params = new URLSearchParams(window.location.search);
    if (search && params.has('q')) search.value = params.get('q') || '';
    if (category && params.has('cat')) category.value = params.get('cat') || '';
    if (minPrice && params.has('min')) minPrice.value = params.get('min') || minPrice.min;
    if (maxPrice && params.has('max')) maxPrice.value = params.get('max') || maxPrice.max;
    if (personalized) personalized.checked = params.get('personalized') === '1';
    if (sort && params.has('sort')) sort.value = params.get('sort') || 'default';

    [search, category, minPrice, maxPrice, personalized, sort].forEach(control => {
      const eventName =
        control?.matches('input[type="search"],input[type="range"]') ? 'input' : 'change';
      control?.addEventListener(eventName, () => apply());
    });

    reset?.addEventListener('click', () => {
      if (search) search.value = '';
      if (category) category.value = '';
      if (minPrice) minPrice.value = String(defaults.min);
      if (maxPrice) maxPrice.value = String(defaults.max);
      if (personalized) personalized.checked = false;
      if (sort) sort.value = 'default';
      apply();
    });

    apply(false);
  }


  function setupProductQuantityLabel() {
    if (!document.body.classList.contains('single-product')) return;
    if (document.body.classList.contains('smartwerk-pack-quantity-product')) return;

    const form = document.querySelector('.summary form.cart');
    if (!form) return;

    const quantity = form.querySelector('.quantity');
    const input = quantity?.querySelector('input.qty');
    if (!quantity || !input) return;

    if (!input.id) {
      input.id = 'smartwerk-product-quantity';
    }

    let label = form.querySelector('.smartwerk-quantity-label');
    if (!label) {
      label = document.createElement('label');
      label.className = 'smartwerk-quantity-label';
      label.htmlFor = input.id;
      label.textContent = 'Anzahl';
      quantity.insertAdjacentElement('beforebegin', label);
    } else {
      label.htmlFor = input.id;
    }
  }

  function setupProductPersonalizationFields() {
    if (!document.body.classList.contains('single-product')) return;

    const form = document.querySelector('.summary form.cart');
    if (!form) return;

    const fields = [
      ...form.querySelectorAll('input[type="text"], textarea')
    ].filter(field => {
      if (field.disabled || field.type === 'hidden') return false;
      if (field.name === 'quantity') return false;
      if (field.closest('.variations')) return false;
      return true;
    });

    fields.forEach((field, index) => {
      if (!field.id) {
        field.id = 'smartwerk-personalization-' + (index + 1);
      }

      field.classList.add('smartwerk-personalization-input');

      const wrappingLabel = field.closest('label');
      let label = form.querySelector('label[for="' + CSS.escape(field.id) + '"]');

      if (!label && !wrappingLabel) {
        label = document.createElement('label');
        label.htmlFor = field.id;
        label.textContent = 'Personalisierung / Wunschtext';
        field.insertAdjacentElement('beforebegin', label);
      }

      if (label) {
        label.classList.add('smartwerk-personalization-label');
        label.textContent = 'Personalisierung / Wunschtext';
      } else if (wrappingLabel) {
        wrappingLabel.classList.add('smartwerk-personalization-label');
      }

      if (!field.getAttribute('aria-label')) {
        field.setAttribute('aria-label', 'Personalisierung / Wunschtext');
      }

      if (!field.getAttribute('placeholder')) {
        field.setAttribute('placeholder', 'Wunschtext eingeben');
      }
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    setupMobileMenu();
    setupMobileKnowledge();
    setupCartCountSync();
    setupShopFilters();
    setupProductPersonalizationFields();
    setupProductQuantityLabel();
  });
})();