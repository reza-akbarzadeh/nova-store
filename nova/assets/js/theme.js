function initCategoryMenu() {
  const button = document.getElementById('novaCategoryToggle');
  const menu = document.getElementById('novaMegaMenu');
  if (!button || !menu) return;

  button.setAttribute('aria-controls', menu.id);
  button.setAttribute('aria-expanded', 'false');
  button.addEventListener('click', () => {
    const open = menu.classList.toggle('is-open');
    button.setAttribute('aria-expanded', String(open));
  });
}

function initProductSearch() {
  const input = document.getElementById('novaSearch');
  const results = document.getElementById('novaSearchResults');
  if (!input || !results || !window.novaData) return;

  let timer;
  let request;

  function showResults(products) {
    results.replaceChildren();
    if (!products.length) {
      const message = document.createElement('p');
      message.textContent = 'کالایی پیدا نشد.';
      results.appendChild(message);
    }

    products.forEach(product => {
      const link = document.createElement('a');
      const symbol = document.createElement('span');
      const details = document.createElement('div');
      const name = document.createElement('b');
      const price = document.createElement('small');

      link.href = product.url;
      symbol.textContent = product.symbol;
      name.textContent = product.name;
      price.textContent = product.price;
      details.append(name, price);
      link.append(symbol, details);
      results.appendChild(link);
    });
    results.hidden = false;
  }

  input.addEventListener('input', () => {
    clearTimeout(timer);
    request?.abort();
    results.hidden = true;
    const query = input.value.trim();
    if (query.length < 2) return;

    timer = setTimeout(async () => {
      request = new AbortController();
      const url = new URL(novaData.ajaxUrl);
      url.searchParams.set('action', 'nova_search');
      url.searchParams.set('nonce', novaData.nonce);
      url.searchParams.set('q', query);

      try {
        const response = await fetch(url, { signal: request.signal });
        if (!response.ok) throw new Error('Search request failed');
        const json = await response.json();
        if (input.value.trim() !== query || document.activeElement !== input) return;
        showResults(Array.isArray(json.data) ? json.data : []);
      } catch (error) {
        if (error.name !== 'AbortError') results.hidden = true;
      }
    }, 250);
  });

  document.addEventListener('click', event => {
    if (!event.target.closest('.nova-search-wrap')) results.hidden = true;
  });
}
function initThemeToggle() {
  const button = document.getElementById('novaThemeToggle');
  if (!button) return;
  const root = document.documentElement;
  let paintFrame;
  let finishFrame;
  const update = () => {
    const dark = document.documentElement.dataset.theme === 'dark';
    button.setAttribute('aria-checked', String(dark));
    button.setAttribute('aria-label', dark ? 'فعال کردن حالت روشن' : 'فعال کردن حالت تیره');
  };
  update();
  button.addEventListener('click', () => {
    cancelAnimationFrame(paintFrame);
    cancelAnimationFrame(finishFrame);
    const theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
    root.classList.add('nova-theme-switching');
    root.dataset.theme = theme;
    update();

    // Paint the new palette once instead of animating every card and text color.
    paintFrame = requestAnimationFrame(() => {
      finishFrame = requestAnimationFrame(() => {
        root.classList.remove('nova-theme-switching');
        try { localStorage.setItem('nova-theme', theme); } catch (_) {}
      });
    });
  });
}
function initMobileMenu() {
  const drawer = document.getElementById('novaMobileMenu');
  const opener = document.getElementById('novaMenuToggle');
  if (!drawer || !opener) return;
  let closeTimer;
  const closeMenu = (immediate = false) => {
    if (!drawer.open) return;
    clearTimeout(closeTimer);
    drawer.classList.remove('is-open');
    opener.setAttribute('aria-expanded', 'false');
    const finish = () => drawer.close();
    if (immediate || matchMedia('(prefers-reduced-motion: reduce)').matches) finish();
    else closeTimer = setTimeout(finish, 240);
  };
  opener.addEventListener('click', () => {
    clearTimeout(closeTimer);
    if (drawer.open) { closeMenu(); return; }
    drawer.showModal();
    drawer.querySelector('.nova-mobile-menu__panel').scrollTop = 0;
    document.documentElement.classList.add('nova-menu-is-open');
    opener.setAttribute('aria-expanded', 'true');
    requestAnimationFrame(() => requestAnimationFrame(() => {
      if (drawer.open) drawer.classList.add('is-open');
    }));
  });
  drawer.querySelector('.nova-mobile-menu__close').addEventListener('click', () => closeMenu());
  drawer.addEventListener('click', event => {
    if (event.target === drawer) closeMenu();
    if (event.target.closest('a')) closeMenu(true);
  });
  drawer.addEventListener('cancel', event => { event.preventDefault(); closeMenu(); });
  drawer.addEventListener('close', () => {
    clearTimeout(closeTimer);
    drawer.classList.remove('is-open');
    document.documentElement.classList.remove('nova-menu-is-open');
    opener.setAttribute('aria-expanded', 'false');
  });
  matchMedia('(min-width: 1001px)').addEventListener('change', event => {
    if (event.matches) closeMenu(true);
  });
  const current = new URL(location.href);
  drawer.querySelectorAll('a').forEach(link => {
    const url = new URL(link.href);
    if (url.pathname.replace(/\/$/, '') === current.pathname.replace(/\/$/, '') && url.search === current.search) {
      link.setAttribute('aria-current', 'page');
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initCategoryMenu();
  initProductSearch();
  initThemeToggle();
  initMobileMenu();
});
