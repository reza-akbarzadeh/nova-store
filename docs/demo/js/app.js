import { products, categories, articles } from './data.js';

const main = document.getElementById('main');
const number = new Intl.NumberFormat('fa-IR');
const money = value => `${number.format(value)} <small>تومان</small>`;
const escapeHtml = value => String(value).replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));
const findProduct = id => products.find(product => product.id === Number(id));
const storageKey = 'nova-static-demo-cart-v1';
let toastTimer;
let cart = readCart();

function readCart() {
  try {
    const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
    if (!saved || typeof saved !== 'object' || Array.isArray(saved)) return {};
    return Object.fromEntries(Object.entries(saved)
      .filter(([id, quantity]) => String(Number(id)) === id && findProduct(id) && Number.isInteger(quantity) && quantity > 0 && quantity <= 10));
  } catch { return {}; }
}

function saveCart() {
  try { localStorage.setItem(storageKey, JSON.stringify(cart)); } catch { /* The demo still works without storage. */ }
  document.getElementById('demoCartCount').textContent = number.format(Object.values(cart).reduce((sum, quantity) => sum + quantity, 0));
}

function notify(message) {
  const toast = document.getElementById('demoToast');
  clearTimeout(toastTimer);
  toast.textContent = message;
  toast.hidden = false;
  toastTimer = setTimeout(() => { toast.hidden = true; }, 2800);
}

function prices(product) {
  return `<div class="nova-product-price"><span class="nova-discount-badge">${number.format(product.discount)}٪</span><strong><del>${money(product.regular)}</del><ins>${money(product.price)}</ins></strong></div>`;
}

function productCard(product, catalog = false) {
  if (catalog) return `<li class="product"><span class="onsale">${number.format(product.discount)}٪ تخفیف</span><a class="woocommerce-LoopProduct-link" href="#/product/${product.id}"><span class="nova-product-symbol nova-shop-symbol" aria-hidden="true">${product.symbol}</span><h2 class="woocommerce-loop-product__title">${product.name}</h2><span class="price"><del>${money(product.regular)}</del><ins>${money(product.price)}</ins></span></a><button class="button demo-add" data-add="${product.id}">افزودن به سبد خرید</button></li>`;
  return `<article class="nova-product-card"><a class="nova-product-card__link" href="#/product/${product.id}"><span class="nova-product-symbol" aria-hidden="true">${product.symbol}</span><h3>${product.name}</h3><div class="nova-product-meta"><span>موجود در انبار</span><span>${categories[product.category]}</span></div>${prices(product)}</a><button class="add_to_cart_button demo-add" data-add="${product.id}">افزودن به سبد</button></article>`;
}

function articleCard(article) {
  return `<article class="nova-story-card"><a class="nova-story-card__image demo-article-art" href="#/article/${article.id}" aria-label="${article.title}"><span aria-hidden="true">${article.symbol}</span></a><div class="nova-story-card__body"><a class="nova-story-category" href="#/magazine?topic=${encodeURIComponent(article.category)}">${article.category}</a><h3><a href="#/article/${article.id}">${article.title}</a></h3><p class="nova-story-excerpt">${article.summary}</p><div class="nova-story-meta"><span>مقالهٔ نمونه</span><span>${number.format(article.minutes)} دقیقه مطالعه</span></div><a class="nova-story-more" href="#/article/${article.id}">خواندن مقاله ←</a></div></article>`;
}

function sectionHead(title, link = '#/shop', label = 'مشاهده همه ←') {
  return `<div class="nova-section-head"><h2>${title}</h2><a href="${link}">${label}</a></div>`;
}

function emptyState(title, message, link = '#/shop', label = 'رفتن به فروشگاه') {
  return `<div class="nova-shell demo-empty"><span aria-hidden="true">◇</span><h1>${title}</h1><p>${message}</p><a class="demo-primary" href="${link}">${label} ←</a></div>`;
}

function homePage() {
  const stories = [['٪', 'تخفیف امروز', ''], ['⌚', 'دیجیتال تازه', 'digital'], ['☕', 'خانه زیبا', 'home'], ['♧', 'سبک زندگی', 'lifestyle'], ['✦', 'پرفروش‌ها', ''], ['🎁', 'ایدهٔ هدیه', '']];
  return `<section class="nova-stories nova-shell">${stories.map(([symbol, label, category]) => `<a href="#/shop?category=${category}"><span>${symbol}</span><b>${label}</b></a>`).join('')}</section>
  <section class="nova-hero nova-shell"><div class="nova-hero__copy"><span>جشنوارهٔ انتخاب‌های تازه</span><p>فرصت کوتاه، انتخاب‌های بزرگ</p><h1>از نو<br><em>زندگی کن!</em></h1><a href="#/shop?sort=discount">خرید با تخفیف ←</a></div><div class="nova-hero__art" aria-hidden="true"><i class="nova-phone"></i><i class="nova-headphone"></i><i class="nova-box"></i><strong>تا<br>۳۲٪<br><small>تخفیف</small></strong></div></section>
  <section class="nova-services nova-shell">${[['⚡', 'ارسال فوری'], ['🛍', 'خرید آسان'], ['٪', 'پیشنهادهای امروز'], ['✦', 'نوا پلاس'], ['🎁', 'ایدهٔ هدیه'], ['♻', 'انتخاب ماندگار']].map(([symbol, label]) => `<a href="#/shop"><span>${symbol}</span><b>${label}</b><small>تجربهٔ یک انتخاب بهتر</small></a>`).join('')}</section>
  <section class="nova-amazing nova-shell"><div class="nova-amazing__title"><p>پیشنهاد</p><h2>شگفت‌انگیز</h2><span aria-hidden="true">🎁</span><a href="#/shop?sort=discount">مشاهده همه ←</a></div><div class="nova-products-track" tabindex="0" role="region" aria-label="محصولات شگفت‌انگیز؛ با لمس یا کلیدهای جهت حرکت کنید">${products.slice(0, 8).map(product => productCard(product)).join('')}</div></section>
  <section class="nova-fresh nova-shell"><div><span aria-hidden="true">🛒</span><strong>نوا فِرِش</strong><b>انتخاب‌های روزمره</b></div><p aria-hidden="true">🍎　☕　🍪</p><a href="#/shop?category=home">برای خانهٔ شما ←</a></section>
  <section class="nova-ads nova-shell"><a href="#/shop?category=home" class="coral"><small>خانه نو</small><strong>رنگ تازه<br>برای خانه</strong><span>مشاهدهٔ محصولات ←</span><i aria-hidden="true">🏺</i></a><a href="#/shop?category=digital" class="mint"><small>تکنولوژی روز</small><strong>دنیایت را<br>هوشمند کن</strong><span>خرید دیجیتال ←</span><i aria-hidden="true">📱</i></a><a href="#/shop?category=lifestyle" class="purple"><small>استایل تو</small><strong>ساده ولی<br>متفاوت</strong><span>مشاهدهٔ کالکشن ←</span><i aria-hidden="true">🎒</i></a><a href="#/magazine" class="amber"><small>حال خوب</small><strong>کمی بیشتر<br>بدانیم</strong><span>خواندنی‌های نوا ←</span><i aria-hidden="true">☕</i></a></section>
  <section class="nova-section nova-shell"><h2>خرید بر اساس دسته‌بندی</h2><div class="nova-category-grid demo-categories">${Object.entries(categories).map(([key, label], index) => `<a href="#/shop?category=${key}"><span aria-hidden="true">${['⌚', '☕', '🎒'][index]}</span><b>${label}</b></a>`).join('')}</div></section>
  <section class="nova-brands nova-shell"><h2>نام‌های آشنا، انتخاب‌های تازه</h2><div>${['Samsung', 'Apple', 'SONY', 'Xiaomi', 'adidas', 'Philips', 'نوا'].map(name => `<span>${name}</span>`).join('')}</div></section>
  <section class="nova-popular nova-shell">${sectionHead('🔥 پرفروش‌ترین کالاها', '#/shop?sort=popular')}<div class="nova-products-grid">${products.slice(4, 12).map(product => productCard(product)).join('')}</div></section>
  <section class="nova-home-journal nova-shell">${sectionHead('خواندنی‌های مجله نوا', '#/magazine')}<div class="nova-magazine-grid">${articles.map(articleCard).join('')}</div></section>`;
}

function shopPage(params) {
  const query = (params.get('q') || '').trim();
  const category = params.get('category') || '';
  const sort = params.get('sort') || 'default';
  const list = products.filter(product => (!category || product.category === category) && product.name.includes(query));
  if (sort === 'price') list.sort((a, b) => a.price - b.price);
  if (sort === 'price-desc') list.sort((a, b) => b.price - a.price);
  if (sort === 'discount') list.sort((a, b) => b.discount - a.discount);
  if (sort === 'popular') list.reverse();
  return `<div class="nova-shell nova-woocommerce-wrap"><div class="demo-page-heading"><div><span class="nova-magazine-kicker">یک انتخاب تازه</span><h1>${escapeHtml(categories[category] || 'فروشگاه')}</h1></div><a href="#/cart">سبد خرید ←</a></div><nav class="demo-filters" aria-label="دسته‌بندی محصولات"><a href="#/shop" ${!category ? 'aria-current="page"' : ''}>همهٔ کالاها</a>${Object.entries(categories).map(([key, label]) => `<a href="#/shop?category=${key}" ${category === key ? 'aria-current="page"' : ''}>${label}</a>`).join('')}</nav>
  <div class="woocommerce"><p class="woocommerce-result-count">${query ? `نتیجهٔ جستجو برای «${escapeHtml(query)}» — ` : ''}${number.format(list.length)} محصول</p><form class="woocommerce-ordering"><label class="screen-reader-text" for="demoSort">مرتب‌سازی محصولات</label><select class="orderby" id="demoSort">${[['default', 'مرتب‌سازی پیش‌فرض'], ['popular', 'نمایش پیشنهادی'], ['price', 'ارزان‌ترین'], ['price-desc', 'گران‌ترین'], ['discount', 'بیشترین تخفیف']].map(([value, label]) => `<option value="${value}" ${sort === value ? 'selected' : ''}>${label}</option>`).join('')}</select></form><div class="demo-clear"></div>${list.length ? `<ul class="products">${list.map(product => productCard(product, true)).join('')}</ul>` : `<div class="demo-empty"><h2>محصولی پیدا نشد.</h2><p>عبارت یا دستهٔ دیگری را امتحان کنید.</p><a href="#/shop">همهٔ محصولات ←</a></div>`}</div></div>`;
}

function productPage(id) {
  const product = findProduct(id);
  if (!product) return emptyState('محصول پیدا نشد', 'از فروشگاه یک محصول دیگر انتخاب کنید.');
  return `<div class="nova-shell nova-content"><nav class="nova-article-breadcrumb"><a href="#/">خانه</a><span>/</span><a href="#/shop?category=${product.category}">${categories[product.category]}</a><span>/</span><span>${product.name}</span></nav><div class="demo-product-layout"><div class="demo-product-art" aria-hidden="true">${product.symbol}</div><div class="demo-product-summary"><span class="nova-magazine-kicker">${categories[product.category]}</span><h1>${product.name}</h1><p>${product.description}</p><div class="demo-stock">✓ موجود در انبار نمونه</div>${prices(product)}<form id="demoProductForm" data-product="${product.id}"><label for="productQuantity">تعداد</label><input id="productQuantity" name="quantity" type="number" min="1" max="10" value="1" required><button class="demo-primary" type="submit">افزودن به سبد خرید</button></form><p class="demo-muted">محصول و قیمت برای نمایش نمونه‌کار هستند. خرید واقعی انجام نمی‌شود.</p></div></div><section class="demo-product-description"><h2>دربارهٔ این محصول</h2><p>${product.description}. این صفحه نمونه‌ای از نمایش جزئیات محصول در قالب نوا است. در نسخهٔ وردپرس، اطلاعات و تصاویر از پنل مدیریت محصول قابل ویرایش‌اند.</p><ul><li>دسته‌بندی: ${categories[product.category]}</li><li>قیمت و موجودی: دادهٔ نمایشی</li><li>نمایش سازگار با موبایل و حالت روشن و تیره</li></ul></section><section class="nova-popular">${sectionHead('انتخاب‌های مرتبط', `#/shop?category=${product.category}`)}<div class="nova-products-grid demo-related">${products.filter(item => item.category === product.category && item.id !== product.id).map(item => productCard(item)).join('')}</div></section></div>`;
}

function cartItems() { return products.filter(product => cart[product.id]); }
function cartTotal() { return cartItems().reduce((sum, product) => sum + product.price * cart[product.id], 0); }

function orderSummary() {
  return `<aside class="demo-summary"><h2>خلاصهٔ سبد</h2><p><span>جمع محصولات</span><strong>${money(cartTotal())}</strong></p><p><span>ارسال در دمو</span><span>رایگان</span></p><hr><p><strong>جمع کل</strong><strong>${money(cartTotal())}</strong></p><small>این مبلغ صرفاً نمایشی است و پرداختی انجام نمی‌شود.</small></aside>`;
}

function cartPage() {
  if (!cartItems().length) return emptyState('سبد شما هنوز خالی است', 'یک محصول انتخاب کن و مسیر خرید نمایشی را امتحان کن.');
  return `<div class="nova-shell nova-content"><div class="demo-page-heading"><h1>سبد خرید</h1><a href="#/shop">ادامهٔ خرید ←</a></div><div class="demo-cart-layout"><div class="demo-cart-list">${cartItems().map(product => `<article class="demo-cart-row"><a class="demo-cart-symbol" href="#/product/${product.id}" aria-label="${product.name}">${product.symbol}</a><div class="demo-cart-info"><h2><a href="#/product/${product.id}">${product.name}</a></h2><p>${money(product.price)}</p><div class="demo-quantity"><button data-quantity="${product.id}" data-delta="-1" aria-label="کاهش تعداد ${product.name}">−</button><span>${number.format(cart[product.id])}</span><button data-quantity="${product.id}" data-delta="1" aria-label="افزایش تعداد ${product.name}">+</button></div></div><div><strong>${money(product.price * cart[product.id])}</strong><button class="demo-remove" data-remove="${product.id}">حذف محصول</button></div></article>`).join('')}</div><div>${orderSummary()}<a class="demo-primary demo-wide" href="#/checkout">ادامهٔ مسیر آزمایشی ←</a></div></div></div>`;
}

function checkoutPage() {
  if (!cartItems().length) return emptyState('برای ادامه یک محصول انتخاب کن', 'تسویهٔ آزمایشی از سبد خرید شروع می‌شود.');
  return `<div class="nova-shell nova-content"><h1>تسویه حساب آزمایشی</h1><div class="demo-cart-layout"><section class="demo-panel"><span class="nova-magazine-kicker">مرحلهٔ پایانی تجربهٔ دمو</span><h2>بدون اطلاعات شخصی، بدون پرداخت</h2><p>در این نسخه نیازی به واردکردن نام، شماره تماس یا اطلاعات بانکی نیست. در فروشگاه وردپرسی، مراحل واقعی سفارش توسط WooCommerce انجام می‌شوند.</p><div class="demo-checkout-address"><strong>مشتری نمونه</strong><p>آدرس نمونه — صرفاً جهت نمایش چیدمان سفارش</p></div><label class="demo-check"><input type="checkbox" id="demoOrderConsent"> می‌دانم این سفارش صرفاً آزمایشی است.</label><button type="button" class="demo-primary" id="demoPlaceOrder">پایان خرید نمایشی</button><p class="demo-muted">هیچ درگاه پرداخت یا سفارش واقعی فراخوانی نمی‌شود.</p></section>${orderSummary()}</div></div>`;
}

function accountPage() {
  return `<div class="nova-shell nova-content"><h1>حساب کاربری نمایشی</h1><div class="demo-account-layout"><nav class="demo-panel demo-account-nav" aria-label="حساب نمونه"><a href="#/account" aria-current="page">اطلاعات مشتری نمونه</a><a href="#/cart">سبد خرید آزمایشی</a><a href="#/shop">رفتن به فروشگاه</a></nav><section class="demo-panel"><span class="nova-magazine-kicker">پیش‌نمایش حساب مشتری</span><h2>خوش آمدی، دوست نوا</h2><p>ورود و ثبت‌نام واقعی در این دمو فعال نیست. از اطلاعات ساختگی برای امتحان ظاهر فرم استفاده کنید؛ رمز، ایمیل یا شمارهٔ واقعی وارد نکنید.</p><form id="demoAccountForm" class="demo-account-form"><label>نام نمونه<input name="firstName" value="مشتری" maxlength="40" autocomplete="off" required></label><label>نام خانوادگی نمونه<input name="lastName" value="نوا" maxlength="40" autocomplete="off" required></label><label class="demo-full">یادداشت آزمایشی<textarea name="note" maxlength="200" placeholder="مثلاً ترجیح می‌دهم سفارش عصر تحویل داده شود."></textarea></label><button class="demo-primary" type="submit">امتحان ذخیرهٔ نمایشی</button></form><p class="demo-muted">این فرم داده‌ای به سرور نمی‌فرستد و اطلاعات آن ذخیره نمی‌شود.</p></section></div></div>`;
}

function magazinePage(params) {
  const topic = params.get('topic') || '';
  const query = params.get('q') || '';
  const list = articles.filter(article => (!topic || article.category === topic) && (article.title + article.summary).includes(query));
  return `<div class="nova-shell nova-magazine"><section class="nova-magazine-hero"><div><span class="nova-magazine-kicker">مجلهٔ سبک زندگی و راهنمای خرید</span><h1>انتخاب بهتر،<br><em>زندگی دل‌نشین‌تر.</em></h1><p>ایده‌های تازه، راهنمای خرید و نکته‌هایی برای استفادهٔ بهتر از چیزهایی که دوستشان داریم.</p></div><div class="nova-magazine-art" aria-hidden="true"><span>نــوا</span><small>کمی بیشتر بدانیم.</small></div></section><div class="nova-magazine-toolbar"><div><span class="nova-magazine-kicker">برای خواندن وقت بگذار</span><h2>تازه‌های مجله</h2></div><form class="nova-magazine-search" id="demoMagazineSearch"><label class="screen-reader-text" for="magazineQuery">جستجو در مقاله‌ها</label><input id="magazineQuery" type="search" value="${escapeHtml(query)}" placeholder="دنبال چه موضوعی می‌گردی؟"><button type="submit">جستجو</button></form></div><nav class="nova-magazine-filters" aria-label="موضوع مقاله‌ها"><a href="#/magazine" ${!topic ? 'aria-current="page"' : ''}>همهٔ مطالب</a>${[...new Set(articles.map(article => article.category))].map(category => `<a href="#/magazine?topic=${encodeURIComponent(category)}" ${topic === category ? 'aria-current="page"' : ''}>${category}</a>`).join('')}</nav>${list.length ? `<div class="nova-magazine-grid">${list.map(articleCard).join('')}</div>` : '<div class="nova-magazine-empty"><h2>مقاله‌ای پیدا نشد.</h2><a href="#/magazine">همهٔ مطالب ←</a></div>'}</div>`;
}

function articlePage(id) {
  const article = articles.find(item => item.id === id);
  if (!article) return emptyState('مقاله پیدا نشد', 'از مجله یک مطلب دیگر انتخاب کنید.', '#/magazine', 'رفتن به مجله');
  return `<div class="nova-shell nova-magazine"><nav class="nova-article-breadcrumb"><a href="#/">خانه</a><span>/</span><a href="#/magazine">مجله نوا</a><span>/</span><span>${article.title}</span></nav><article class="nova-article"><header class="nova-article-header"><span class="nova-story-category">${article.category}</span><h1>${article.title}</h1><div class="nova-story-meta"><span>مقالهٔ نمونه برای معرفی دمو</span><span>${number.format(article.minutes)} دقیقه مطالعه</span></div></header><div class="demo-article-cover" aria-hidden="true">${article.symbol}</div><div class="nova-article-content"><p>${article.summary}</p>${article.sections.map(([title, paragraph]) => `<h2>${title}</h2><p>${paragraph}</p>`).join('')}</div></article><section class="nova-related-stories">${sectionHead('بیشتر بخوانید', '#/magazine')}<div class="nova-magazine-grid demo-article-related">${articles.filter(item => item.id !== article.id).map(articleCard).join('')}</div></section></div>`;
}

function render() {
  clearTimeout(toastTimer);
  document.getElementById('demoToast').hidden = true;
  const hash = location.hash.startsWith('#/') ? location.hash.slice(1) : '/';
  const [path, query = ''] = hash.split('?');
  const params = new URLSearchParams(query);
  const [, page = '', id = ''] = path.split('/');
  const pages = { '': () => homePage(), shop: () => shopPage(params), product: () => productPage(id), cart: cartPage, checkout: checkoutPage, account: accountPage, magazine: () => magazinePage(params), article: () => articlePage(id) };
  main.innerHTML = pages[page] ? pages[page]() : emptyState('این صفحه پیدا نشد', 'از صفحهٔ اصلی ادامه بده.', '#/', 'صفحهٔ اصلی');
  document.title = `${main.querySelector('h1')?.textContent.replace(/\s+/g, ' ').trim() || 'فروشگاه اینترنتی'} | نوا — دمو`;
  document.querySelectorAll('.nova-header__nav a,.nova-mobile-menu a').forEach(link => {
    link.removeAttribute('aria-current');
    if (link.hash === (location.hash || '#/')) link.setAttribute('aria-current', 'page');
  });
  document.getElementById('novaMegaMenu').classList.remove('is-open');
  document.getElementById('novaCategoryToggle').setAttribute('aria-expanded', 'false');
  document.getElementById('novaSearchResults').hidden = true;
  saveCart();
}

document.addEventListener('click', event => {
  const add = event.target.closest('[data-add]');
  if (add) {
    const product = findProduct(add.dataset.add);
    if (cart[product.id] >= 10) return notify('حداکثر تعداد در دمو ۱۰ عدد است.');
    cart[product.id] = (cart[product.id] || 0) + 1;
    saveCart();
    notify(`${product.name} به سبد آزمایشی اضافه شد.`);
  }
  const quantity = event.target.closest('[data-quantity]');
  if (quantity) {
    const id = quantity.dataset.quantity;
    cart[id] = Math.min(10, Math.max(0, cart[id] + Number(quantity.dataset.delta)));
    if (!cart[id]) delete cart[id];
    render();
  }
  const remove = event.target.closest('[data-remove]');
  if (remove) { delete cart[remove.dataset.remove]; render(); notify('محصول از سبد حذف شد.'); }
  if (event.target.closest('#demoPlaceOrder')) {
    if (!document.getElementById('demoOrderConsent').checked) return notify('ابتدا تأیید آزمایشی‌بودن سفارش را انتخاب کن.');
    cart = {};
    saveCart();
    main.innerHTML = emptyState('مسیر آزمایشی خرید کامل شد ✓', 'هیچ سفارش یا پرداخت واقعی ثبت نشده است. حالا می‌توانی بخش‌های دیگر دمو را ببینی.');
    main.focus({ preventScroll: true });
    window.scrollTo(0, 0);
  }
});

document.addEventListener('submit', event => {
  if (event.target.id === 'demoSearchForm') {
    event.preventDefault();
    location.hash = `/shop?q=${encodeURIComponent(document.getElementById('novaSearch').value.trim())}`;
    document.getElementById('novaSearchResults').hidden = true;
  }
  if (event.target.id === 'demoMagazineSearch') {
    event.preventDefault();
    location.hash = `/magazine?q=${encodeURIComponent(document.getElementById('magazineQuery').value.trim())}`;
  }
  if (event.target.id === 'demoProductForm') {
    event.preventDefault();
    const id = Number(event.target.dataset.product);
    const quantity = Number(new FormData(event.target).get('quantity'));
    if (!Number.isInteger(quantity) || quantity < 1 || quantity > 10) return;
    cart[id] = Math.min(10, (cart[id] || 0) + quantity);
    saveCart();
    notify('محصول به سبد آزمایشی اضافه شد.');
  }
  if (event.target.id === 'demoAccountForm') {
    event.preventDefault();
    notify('ذخیرهٔ نمایشی انجام شد؛ اطلاعاتی ارسال یا نگهداری نمی‌شود.');
  }
});

document.addEventListener('change', event => {
  if (event.target.id !== 'demoSort') return;
  const params = new URLSearchParams(location.hash.split('?')[1] || '');
  params.set('sort', event.target.value);
  location.hash = `/shop?${params}`;
});

const search = document.getElementById('novaSearch');
const results = document.getElementById('novaSearchResults');
search.addEventListener('input', () => {
  const query = search.value.trim();
  if (query.length < 2) { results.hidden = true; return; }
  const matches = products.filter(product => product.name.includes(query)).slice(0, 5);
  results.innerHTML = matches.length ? matches.map(product => `<a href="#/product/${product.id}"><span aria-hidden="true">${product.symbol}</span><div><b>${product.name}</b><small>${number.format(product.price)} تومان</small></div></a>`).join('') : '<p>کالایی پیدا نشد.</p>';
  results.hidden = false;
});
document.addEventListener('click', event => {
  if (!event.target.closest('.nova-search-wrap')) results.hidden = true;
});
document.addEventListener('keydown', event => { if (event.key === 'Escape') results.hidden = true; });
document.getElementById('demoSkip').addEventListener('click', event => { event.preventDefault(); main.focus(); });
document.getElementById('demoBackTop').addEventListener('click', event => { event.preventDefault(); window.scrollTo({ top: 0, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' }); });
window.addEventListener('hashchange', () => { render(); window.scrollTo(0, 0); main.focus({ preventScroll: true }); });
render();
