document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('nova-article-image-select');
  if (!select || !window.wp?.media) return;
  const input = document.getElementById('nova-article-image');
  const changed = document.getElementById('nova-article-image-changed');
  const preview = document.getElementById('nova-article-image-preview');
  const update = (id, url = '') => {
    input.value = id;
    changed.value = '1';
    preview.replaceChildren();
    if (url) {
      const image = document.createElement('img');
      image.src = url;
      image.alt = 'پیش‌نمایش عکس مقاله';
      image.style.cssText = 'max-width:100%;height:auto;border-radius:10px';
      preview.appendChild(image);
    }
    // Keep the block editor's featured image and the extra panel in sync.
    if (wp.data?.select('core/editor')?.getCurrentPostId()) {
      wp.data.dispatch('core/editor').editPost({ featured_media: id });
    }
  };
  let frame;
  select.addEventListener('click', () => {
    if (frame) { frame.open(); return; }
    frame = wp.media({ title: 'عکس مقالهٔ مجله نوا', button: { text: 'انتخاب عکس مقاله' }, library: { type: 'image' }, multiple: false });
    frame.on('select', () => {
      const image = frame.state().get('selection').first().toJSON();
      update(image.id, image.sizes?.medium?.url || image.url);
    });
    frame.open();
  });
  document.getElementById('nova-article-image-remove').addEventListener('click', () => update(0));
});
