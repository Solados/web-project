// Mobile menu toggle
const toggle = document.querySelector('.menu-toggle');
const links = document.getElementById('nav-links');
if (toggle && links) {
  toggle.addEventListener('click', () => {
    const expanded = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!expanded));
    links.classList.toggle('show');
    // mirror menu state on body so we can reveal mobile-only elements (search) via CSS
    document.body.classList.toggle('menu-open');
  });
}

// Smooth scroll for internal links
document.addEventListener('click', (e) => {
  const target = e.target;
  if (target && target.matches('a[href^="#"]')) {
    const id = target.getAttribute('href');
    const el = document.querySelector(id);
    if (el) {
      e.preventDefault();
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      if (links && links.classList.contains('show')) {
        links.classList.remove('show');
        toggle.setAttribute('aria-expanded', 'false');
      }
    }
  }
});

// Carousel Slider JavaScript
let nextDom = document.getElementById('next');
let prevDom = document.getElementById('prev');

let carouselDom = document.querySelector('.carousel');
let isRTL = document.documentElement.dir === 'rtl';

if (carouselDom) {
    let SliderDom = carouselDom.querySelector('.carousel .list');
    let thumbnailBorderDom = document.querySelector('.carousel .thumbnail');
    let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');

    // حافظ على السلوك الحالي
    thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);

    let timeRunning = 500;
    

    
    nextDom.onclick = function(){
        showSlider('next');
    }

    prevDom.onclick = function(){
        showSlider('prev');
    }

    let runTimeOut;
    let runNextAuto = setTimeout(() => {
        nextDom.click();
    }, timeAutoNext);

    function showSlider(type){

      const dir = isRTL ? (type === 'next' ? 'prev' : 'next') : type;

        let SliderItemsDom = SliderDom.querySelectorAll('.carousel .list .item');
        let thumbnailItemsDom = document.querySelectorAll('.carousel .thumbnail .item');

        if(dir === 'next'){
            SliderDom.appendChild(SliderItemsDom[0]);
            thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
            carouselDom.classList.add('next');
        } else {
            SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
            thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
            carouselDom.classList.add('prev');
        }

        clearTimeout(runTimeOut);
        runTimeOut = setTimeout(() => {
            carouselDom.classList.remove('next');
            carouselDom.classList.remove('prev');
        }, timeRunning);

        clearTimeout(runNextAuto);
        runNextAuto = setTimeout(() => {
            nextDom.click();
        }, timeAutoNext);
    }
}




// القائمة المنسدلة للمناطق
const dropdown = document.querySelector('.dropdown');
const dropbtn = dropdown?.querySelector('.dropbtn');

if (dropdown && dropbtn) {
  // عند الضغط على زر القائمة فقط
  dropbtn.addEventListener('click', function (e) {
    e.preventDefault(); // يمنع التنقل عن الزر نفسه فقط
    dropdown.classList.toggle('show');
  });

  // إغلاق القائمة إذا ضغط المستخدم خارجها
  window.addEventListener('click', function (e) {
    if (!e.target.closest('.dropdown')) {
      dropdown.classList.remove('show');
    }
  });
}

// Search form handler: redirect for region keywords OR highlight matches on the current page
const searchForm = document.querySelector('.nav-search-form');
const searchInput = document.querySelector('.nav-search-input');
if (searchForm && searchInput) {
  const routes = [
    { keys: ['north', 'شمال'], url: 'North.html' },
    { keys: ['central', 'center', 'وسط', 'central.html', 'centeral'], url: 'Central.html' },
    { keys: ['east', 'شرق', 'dammam', 'khobar', 'al-ahsa', 'al ahsa'], url: 'East.html' },
    { keys: ['west', 'غرب', 'jeddah', 'makkah', 'medina', 'taif'], url: 'West.html' },
    { keys: ['south', 'جنوب', 'asir', 'jazan', 'najran', 'al-baha'], url: 'South.html' },
    { keys: ['central', 'riyadh', 'qassim', 'hail', 'hail region', 'center', 'وسط', 'central.html', 'centeral'], url: 'Central.html' },
    { keys: ['quiz', 'quizzes', 'test', 'quiz.html'], url: 'QUIZ.html' }
  ];

  function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  function removeHighlights(container) {
    const marks = container.querySelectorAll('mark.search-highlight');
    marks.forEach((m) => {
      const txt = document.createTextNode(m.textContent);
      m.parentNode.replaceChild(txt, m);
    });
  }

  // create status UI (count + clear) appended to the form
  const statusWrap = document.createElement('div');
  statusWrap.className = 'nav-search-status';
  const countSpan = document.createElement('span');
  countSpan.className = 'nav-search-count';
  countSpan.textContent = '';
  const clearBtn = document.createElement('button');
  clearBtn.type = 'button';
  clearBtn.className = 'nav-search-clear';
  clearBtn.textContent = 'Clear';
  statusWrap.appendChild(countSpan);
  statusWrap.appendChild(clearBtn);
  searchForm.appendChild(statusWrap);

  clearBtn.addEventListener('click', function () {
    const container = document.querySelector('main') || document.body;
    removeHighlights(container);
    searchInput.value = '';
    countSpan.textContent = '';
    searchInput.focus();
  });

  function highlightInContainer(container, query) {
    if (!query) return 0;
    removeHighlights(container);
    let regex;
    try {
      regex = new RegExp(escapeRegExp(query), 'gi');
    } catch (err) {
      return 0;
    }
    let count = 0;
    // operate on innerHTML of the container to keep markup simple for this static site
    // limit to container.innerHTML to avoid touching header/nav/footer
    const original = container.innerHTML;
    const replaced = original.replace(regex, (m) => {
      count++;
      return `<mark class="search-highlight">${m}</mark>`;
    });
    if (count > 0) container.innerHTML = replaced;
    return count;
  }

  searchForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const q = searchInput.value.trim();
    if (!q) {
      // empty search -> remove highlights and stay on page
      const container = document.querySelector('main') || document.body;
      removeHighlights(container);
      return;
    }

    const qLower = q.toLowerCase();

    // check for route keywords first
    for (const r of routes) {
      for (const k of r.keys) {
        if (qLower.includes(k)) {
          window.location.href = r.url;
          return;
        }
      }
    }

    // otherwise perform on-page search & highlight
    const container = document.querySelector('main') || document.body;
    const found = highlightInContainer(container, q);
    countSpan.textContent = found > 0 ? `${found} match${found>1?'es':''}` : '';
    if (found > 0) {
      // scroll to first match
      const first = container.querySelector('mark.search-highlight');
      if (first) {
        first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // give brief focus outline for keyboard users
        first.setAttribute('tabindex', '-1');
        first.focus({ preventScroll: true });
      }
    } else {
      countSpan.textContent = '';
      // fallback: try matching filenames
      const pages = ['North.html', 'Central.html', 'East.html', 'West.html', 'South.html', 'QUIZ.html', 'index.html'];
      for (const p of pages) {
        if (p.toLowerCase().includes(qLower)) {
          window.location.href = p;
          return;
        }
      }
      alert('No matches on this page. Try a different keyword or use region names.');
    }
  });

  // --- Autocomplete / instant suggestions using server API ---
  const suggestionsWrap = document.createElement('div');
  suggestionsWrap.className = 'search-suggestions';
  suggestionsWrap.style.position = 'absolute';
  suggestionsWrap.style.zIndex = 1200;
  suggestionsWrap.style.display = 'none';
  searchForm.style.position = 'relative';
  searchForm.appendChild(suggestionsWrap);

  let acTimeout = null;
  function hideSuggestions() { suggestionsWrap.style.display = 'none'; suggestionsWrap.innerHTML = ''; }

  async function fetchSuggestions(q) {
    if (!q) { hideSuggestions(); return; }
    try {
      const res = await fetch(`search.php?q=${encodeURIComponent(q)}&limit=6`);
      if (!res.ok) { hideSuggestions(); return; }
      const data = await res.json();
      const hits = data.hits || [];
      suggestionsWrap.innerHTML = '';
      if (hits.length === 0) { hideSuggestions(); return; }
      for (const h of hits) {
        const row = document.createElement('div');
        row.className = 'search-suggestion-item';
        row.innerHTML = `<a href="${h.url}"><strong>${escapeHtml(h.title)}</strong><div class="suggest-excerpt">${h.excerpt || ''}</div></a>`;
        row.querySelector('a').addEventListener('click', (ev) => {
          // allow normal navigation
        });
        suggestionsWrap.appendChild(row);
      }
      suggestionsWrap.style.display = 'block';
    } catch (err) {
      hideSuggestions();
    }
  }

  function escapeHtml(s) { return String(s).replace(/[&<>\"]/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]; }); }

  searchInput.addEventListener('input', function (e) {
    const q = e.target.value.trim();
    clearTimeout(acTimeout);
    acTimeout = setTimeout(() => fetchSuggestions(q), 220);
  });

  document.addEventListener('click', function (e) {
    if (!e.target.closest('.nav-search-form') && !e.target.closest('.search-suggestions')) {
      hideSuggestions();
    }
  });
}
