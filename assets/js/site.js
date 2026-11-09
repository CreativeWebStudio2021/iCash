(function () {
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('menu');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  var filterBar = document.querySelector('.filters');
  var products = document.querySelectorAll('.product-card');
  var emptyMsg = document.querySelector('.filter-empty');
  function applyFilter(f) {
    var visible = 0;
    products.forEach(function (card) {
      var show = f === 'all' || card.getAttribute('data-cat') === f;
      card.hidden = !show;
      card.classList.toggle('is-filtered-out', !show);
      if (show) {
        card.classList.add('is-in', 'is-done');
        visible += 1;
      }
    });
    if (emptyMsg) emptyMsg.hidden = visible > 0;
  }
  if (filterBar) {
    filterBar.addEventListener('click', function (ev) {
      var btn = ev.target.closest('[data-filter]');
      if (!btn || !filterBar.contains(btn)) return;
      filterBar.querySelectorAll('[data-filter]').forEach(function (b) {
        b.classList.toggle('is-on', b === btn);
      });
      applyFilter(btn.getAttribute('data-filter') || 'all');
    });
  }

  var params = new URLSearchParams(location.search);
  var cat = params.get('categoria');
  var audience = params.get('audience');
  var allowed = { registratori: 1, touch: 1, contante: 1, periferiche: 1 };
  if (cat && allowed[cat] && filterBar) {
    var match = filterBar.querySelector('[data-filter="' + cat + '"]');
    if (match) match.click();
  } else if (audience && products.length) {
    var visible = 0;
    products.forEach(function (card) {
      var a = card.getAttribute('data-audience') || '';
      var show = a.indexOf(audience) !== -1;
      card.hidden = !show;
      if (show) {
        card.classList.add('is-in', 'is-done');
        visible += 1;
      }
    });
    if (filterBar) {
      filterBar.querySelectorAll('[data-filter]').forEach(function (b) {
        b.classList.remove('is-on');
      });
    }
    if (emptyMsg) emptyMsg.hidden = visible > 0;
  }

  function track(name, href) {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: name, link: href || '' });
  }
  document.addEventListener('click', function (ev) {
    var a = ev.target.closest('[data-track]');
    if (!a) return;
    track(a.getAttribute('data-track'), a.getAttribute('href'));
  });
  document.querySelectorAll('.lead-form').forEach(function (form) {
    form.addEventListener('submit', function (ev) {
      track('form_submit', location.pathname);
      if (!window.RECAPTCHA_KEY || form.getAttribute('data-captcha') === 'ok') return;
      ev.preventDefault();
      grecaptcha.ready(function () {
        grecaptcha.execute(window.RECAPTCHA_KEY, { action: 'lead' }).then(function (token) {
          var input = form.querySelector('input[name="recaptcha"]');
          if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'recaptcha';
            form.appendChild(input);
          }
          input.value = token;
          form.setAttribute('data-captcha', 'ok');
          form.submit();
        });
      });
    });
  });

  document.querySelectorAll('[data-gallery]').forEach(function (root) {
    var main = root.querySelector('[data-gallery-main]');
    var openBtn = root.querySelector('[data-lightbox-open]');
    if (!main) return;
    root.querySelectorAll('[data-gallery-thumb]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var src = btn.getAttribute('data-src') || '';
        var alt = btn.getAttribute('data-alt') || '';
        if (!src) return;
        main.src = src;
        main.alt = alt;
        main.title = alt;
        if (openBtn) {
          openBtn.setAttribute('data-lightbox-src', src);
          openBtn.setAttribute('data-lightbox-alt', alt);
        }
        root.querySelectorAll('[data-gallery-thumb]').forEach(function (b) {
          b.classList.toggle('is-on', b === btn);
        });
      });
      btn.addEventListener('dblclick', function () {
        openLightboxFrom(btn);
      });
    });
  });

  var lightbox = null;
  var lightboxImg = null;
  var lightboxCap = null;
  var lightboxItems = [];
  var lightboxIndex = 0;

  function ensureLightbox() {
    if (lightbox) return lightbox;
    lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.setAttribute('role', 'dialog');
    lightbox.setAttribute('aria-modal', 'true');
    lightbox.setAttribute('aria-label', 'Immagine ingrandita');
    lightbox.innerHTML =
      '<div class="lightbox-inner">' +
      '<button type="button" class="lightbox-close" data-lightbox-close aria-label="Chiudi">×</button>' +
      '<button type="button" class="lightbox-nav lightbox-prev" data-lightbox-prev aria-label="Foto precedente">‹</button>' +
      '<img alt="">' +
      '<button type="button" class="lightbox-nav lightbox-next" data-lightbox-next aria-label="Foto successiva">›</button>' +
      '<p class="lightbox-caption"></p>' +
      '</div>';
    document.body.appendChild(lightbox);
    lightboxImg = lightbox.querySelector('img');
    lightboxCap = lightbox.querySelector('.lightbox-caption');
    lightbox.addEventListener('click', function (ev) {
      if (ev.target === lightbox || ev.target.closest('[data-lightbox-close]')) closeLightbox();
    });
    lightbox.querySelector('[data-lightbox-prev]').addEventListener('click', function (ev) {
      ev.stopPropagation();
      showLightbox(lightboxIndex - 1);
    });
    lightbox.querySelector('[data-lightbox-next]').addEventListener('click', function (ev) {
      ev.stopPropagation();
      showLightbox(lightboxIndex + 1);
    });
    document.addEventListener('keydown', function (ev) {
      if (!lightbox || !lightbox.classList.contains('is-open')) return;
      if (ev.key === 'Escape') closeLightbox();
      if (ev.key === 'ArrowLeft') showLightbox(lightboxIndex - 1);
      if (ev.key === 'ArrowRight') showLightbox(lightboxIndex + 1);
    });
    return lightbox;
  }

  function showLightbox(index) {
    if (!lightboxItems.length) return;
    lightboxIndex = (index + lightboxItems.length) % lightboxItems.length;
    var item = lightboxItems[lightboxIndex];
    ensureLightbox();
    lightboxImg.src = item.src;
    lightboxImg.alt = item.alt || '';
    lightboxCap.textContent = item.alt || '';
    var multi = lightboxItems.length > 1;
    lightbox.querySelector('[data-lightbox-prev]').hidden = !multi;
    lightbox.querySelector('[data-lightbox-next]').hidden = !multi;
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function openLightboxFrom(el) {
    var group = el.closest('[data-lightbox-group]');
    var nodes = group
      ? group.querySelectorAll('[data-lightbox-src], [data-lightbox-open]')
      : [el];
    var seen = {};
    lightboxItems = [];
    nodes.forEach(function (node) {
      var src = node.getAttribute('data-lightbox-src') || node.getAttribute('data-src') || '';
      var alt = node.getAttribute('data-lightbox-alt') || node.getAttribute('data-alt') || '';
      if (!src || seen[src]) return;
      seen[src] = 1;
      lightboxItems.push({ src: src, alt: alt });
    });
    var startSrc = el.getAttribute('data-lightbox-src') || el.getAttribute('data-src') || '';
    var start = 0;
    lightboxItems.forEach(function (item, i) {
      if (item.src === startSrc) start = i;
    });
    showLightbox(start);
  }

  document.addEventListener('click', function (ev) {
    var open = ev.target.closest('[data-lightbox-open]');
    if (!open) return;
    ev.preventDefault();
    openLightboxFrom(open);
  });

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!reduce && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        en.target.classList.add('is-in');
        io.unobserve(en.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
    document.querySelectorAll('.reveal-ready, .split-media, .card.soft, .band, .brands-open, .services-open').forEach(function (el, i) {
      if (el.closest('[data-motion]') || el.hasAttribute('data-motion')) return;
      el.classList.add('reveal');
      el.style.transitionDelay = Math.min(i % 4, 3) * 60 + 'ms';
      io.observe(el);
    });
  } else {
    document.querySelectorAll('.reveal, .product-card, .card').forEach(function (el) {
      el.classList.add('is-in');
    });
  }

  var motionGroups = document.querySelectorAll('[data-motion]');
  if (motionGroups.length) {
    if (reduce || !('IntersectionObserver' in window)) {
      document.querySelectorAll('.enter').forEach(function (el) {
        el.classList.add('is-in', 'is-done');
      });
    } else {
      motionGroups.forEach(function (group) {
        var late = group.getAttribute('data-motion') === 'late';
        var perCard = group.classList.contains('product-grid');
        if (perCard) {
          group.querySelectorAll(':scope > .enter').forEach(function (el) {
            var cardIo = new IntersectionObserver(function (entries) {
              entries.forEach(function (en) {
                if (!en.isIntersecting) return;
                en.target.classList.add('is-in');
                en.target.addEventListener('animationend', function () {
                  en.target.classList.add('is-done');
                }, { once: true });
                cardIo.unobserve(en.target);
              });
            }, { threshold: 0.22, rootMargin: '0px 0px -8% 0px' });
            cardIo.observe(el);
          });
          return;
        }
        var motionIo = new IntersectionObserver(function (entries) {
          entries.forEach(function (en) {
            if (!en.isIntersecting) return;
            group.querySelectorAll('.enter').forEach(function (el) {
              el.classList.add('is-in');
              el.addEventListener('animationend', function () {
                el.classList.add('is-done');
              }, { once: true });
            });
            motionIo.unobserve(group);
          });
        }, {
          threshold: late ? 0.5 : 0.28,
          rootMargin: late ? '0px 0px -16% 0px' : '0px 0px -8% 0px'
        });
        motionIo.observe(group);
      });
    }
  }

  document.querySelectorAll('[data-carousel]').forEach(function (root) {
    var track = root.querySelector('.prod-carousel-track');
    var view = root.querySelector('.prod-carousel-view');
    var prev = root.querySelector('[data-carousel-prev]');
    var next = root.querySelector('[data-carousel-next]');
    if (!track || !view || !track.children.length) return;
    var index = 0;
    var timer = 0;
    function stepSize() {
      var card = track.children[0];
      var gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
      return card.getBoundingClientRect().width + gap;
    }
    function perView() {
      var size = stepSize();
      if (!size) return 1;
      return Math.max(1, Math.round(view.clientWidth / size));
    }
    function go(to) {
      var max = Math.max(0, track.children.length - perView());
      index = (to + max + 1) % (max + 1);
      track.style.transform = 'translateX(' + (-index * stepSize()) + 'px)';
    }
    function stop() { if (timer) window.clearInterval(timer); timer = 0; }
    function start() {
      stop();
      if (reduce) return;
      timer = window.setInterval(function () { go(index + 1); }, 4200);
    }
    if (prev) prev.addEventListener('click', function () { go(index - 1); start(); });
    if (next) next.addEventListener('click', function () { go(index + 1); start(); });
    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    window.addEventListener('resize', function () { go(index); });
    start();
  });

  var hero = document.getElementById('hero');
  if (hero) {
    var slides = Array.prototype.slice.call(hero.querySelectorAll('[data-hero-slide]'));
    var dots = Array.prototype.slice.call(hero.querySelectorAll('.hero-dots button'));
    var index = 0;
    var timer = 0;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function showHero(next) {
      index = (next + slides.length) % slides.length;
      slides.forEach(function (slide, i) {
        if (i === index) return;
        slide.classList.remove('is-on');
        slide.hidden = true;
        slide.setAttribute('aria-hidden', 'true');
      });
      var current = slides[index];
      var replay = !current.classList.contains('is-on');
      current.hidden = false;
      current.setAttribute('aria-hidden', 'false');
      if (replay) {
        current.classList.remove('is-on');
        void current.offsetWidth;
        current.classList.add('is-on');
      }
      dots.forEach(function (dot, i) {
        var on = i === index;
        dot.classList.toggle('is-on', on);
        dot.setAttribute('aria-selected', on ? 'true' : 'false');
      });
    }

    function armHero() {
      window.clearInterval(timer);
      if (reduceMotion || slides.length < 2) return;
      timer = window.setInterval(function () { showHero(index + 1); }, 7000);
    }

    dots.forEach(function (dot, i) {
      dot.addEventListener('click', function () {
        showHero(i);
        armHero();
      });
    });
    hero.addEventListener('mouseenter', function () { window.clearInterval(timer); });
    hero.addEventListener('mouseleave', armHero);
    hero.addEventListener('focusin', function () { window.clearInterval(timer); });
    hero.addEventListener('focusout', function (ev) {
      if (!hero.contains(ev.relatedTarget)) armHero();
    });
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) window.clearInterval(timer);
      else armHero();
    });
    showHero(0);
    armHero();
  }
})();
