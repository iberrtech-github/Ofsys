/**
 * OFSYS Education - Scroll Animation Engine
 * Native scrolling + GSAP ScrollTrigger driven effects.
 *
 * Markup hooks:
 *  .fade-anim            data-fade-from="left|right|top|bottom" data-delay=".3" data-fade-offset="40"
 *  .split-anim           heading words slide up one by one
 *  .img-reveal           image uncovers with a clip-path wipe (data-reveal="left|bottom")
 *  [data-parallax]       element drifts on scroll (value = yPercent travel, e.g. "-12")
 *  .scroll-highlight     words light up as the block scrolls through the viewport
 *  .draw-on-scroll path  SVG stroke draws in when visible (scrub with data-scrub)
 *  [data-depth]          follows the mouse inside .mouse-parallax
 *  .hero-intro           played as one timeline after the preloader finishes
 */
(function () {
  'use strict';

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const hasGsap = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';
  const loader = document.querySelector('.ofsys-loader');

  function finishLoader() {
    if (loader && !loader.classList.contains('done')) {
      loader.classList.add('done');
    }
    document.dispatchEvent(new Event('ofsys:loaded'));
  }

  // Without GSAP (CDN blocked) or with reduced motion, show everything as-is.
  if (!hasGsap || reduceMotion) {
    if (loader) {
      window.addEventListener('load', finishLoader);
      setTimeout(finishLoader, 2500);
    }
    document.querySelectorAll('.scroll-highlight .hw').forEach(w => { w.style.opacity = 1; });
    document.querySelectorAll('[data-aos]').forEach(el => el.classList.add('aos-in'));
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  // ------------------------------------------------------------------
  // 1. Anchor links
  //    The page scrolls natively. A JS-driven smooth-scroll library sits
  //    between the wheel and the scrollbar and leaves every scroll a frame
  //    or two behind; only in-page jumps are eased, by the browser itself.
  // ------------------------------------------------------------------
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    const hash = link.getAttribute('href');
    if (hash.length < 2 || link.hasAttribute('data-bs-toggle')) return;
    link.addEventListener('click', e => {
      const target = document.querySelector(hash);
      if (!target) return;
      e.preventDefault();
      const top = target.getBoundingClientRect().top + window.scrollY - 90;
      window.scrollTo({ top: top, behavior: 'smooth' });
    });
  });
  // ------------------------------------------------------------------
  // 2. Split headings into words (keeps <em>, <br> and other inline tags)
  // ------------------------------------------------------------------
  function splitWords(el) {
    const walk = node => {
      Array.from(node.childNodes).forEach(child => {
        if (child.nodeType === Node.TEXT_NODE) {
          const parts = child.textContent.split(/(\s+)/);
          const frag = document.createDocumentFragment();
          parts.forEach(part => {
            if (!part) return;
            if (/^\s+$/.test(part)) {
              frag.appendChild(document.createTextNode(' '));
            } else {
              const outer = document.createElement('span');
              outer.className = 'w';
              const inner = document.createElement('span');
              inner.textContent = part;
              outer.appendChild(inner);
              frag.appendChild(outer);
            }
          });
          child.replaceWith(frag);
        } else if (child.nodeType === Node.ELEMENT_NODE && child.tagName !== 'BR' && !child.classList.contains('no-split')) {
          walk(child);
        }
      });
    };
    walk(el);
    return el.querySelectorAll('.w > span');
  }

  // ------------------------------------------------------------------
  // 3. Hero intro timeline (after preloader)
  // ------------------------------------------------------------------
  const heroItems = gsap.utils.toArray('.hero-intro');
  const heroTitle = document.querySelector('.hero-title-split');
  const heroWords = heroTitle ? splitWords(heroTitle) : [];
  const heroImgs = gsap.utils.toArray('.hero-img, .hai-photo');
  const heroChips = gsap.utils.toArray('.hero-chip, .hero-spin, .hero-orbit-rings');
  const heroSketches = gsap.utils.toArray('.hero-sketch');
  const underline = document.querySelector('.title-underline path');

  if (heroItems.length || heroWords.length) {
    gsap.set(heroItems, { autoAlpha: 0, y: 30 });
    gsap.set(heroWords, { yPercent: 110 });
    gsap.set(heroImgs, { autoAlpha: 0, scale: 0.85 });
    gsap.set(heroChips, { autoAlpha: 0, y: 20 });
    gsap.set(heroSketches, { autoAlpha: 0 });

    const playHero = () => {
      const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
      tl.to(heroItems[0] || {}, { autoAlpha: 1, y: 0, duration: 0.6 })
        .to(heroWords, { yPercent: 0, duration: 0.9, stagger: 0.07 }, '-=0.3')
        .to(heroItems.slice(1), { autoAlpha: 1, y: 0, duration: 0.7, stagger: 0.12 }, '-=0.5')
        .to(heroImgs, { autoAlpha: 1, scale: 1, duration: 1.1, stagger: 0.15, ease: 'expo.out' }, 0.3)
        .to(heroChips, { autoAlpha: 1, y: 0, duration: 0.6, stagger: 0.12 }, '-=0.6')
        .to(heroSketches, { autoAlpha: 1, duration: 1, stagger: 0.1 }, '-=0.8');
      if (underline) tl.to(underline, { strokeDashoffset: 0, duration: 1, ease: 'power2.inOut' }, '-=0.9');
    };

    if (loader) {
      document.addEventListener('ofsys:loaded', playHero, { once: true });
    } else {
      playHero();
    }
  }

  if (loader) {
    window.addEventListener('load', () => setTimeout(finishLoader, 350));
    setTimeout(finishLoader, 3500);
  }

  // ------------------------------------------------------------------
  // 4. Fade animations (same API as the reference template)
  // ------------------------------------------------------------------
  gsap.utils.toArray('.fade-anim').forEach(item => {
    const offset = Number(item.dataset.fadeOffset) || 40;
    const from = item.dataset.fadeFrom || 'bottom';
    const vars = {
      autoAlpha: 0,
      x: 0,
      y: 0,
      duration: Number(item.dataset.duration) || 0.9,
      delay: Number(item.dataset.delay) || 0.1,
      ease: item.dataset.ease || 'power2.out',
      scrollTrigger: { trigger: item, start: 'top 88%', once: true }
    };
    if (from === 'left') vars.x = -offset;
    else if (from === 'right') vars.x = offset;
    else if (from === 'top') vars.y = -offset;
    else vars.y = offset;
    gsap.from(item, vars);
  });

  // Staggered groups: children of .stagger-anim pop in one after another
  gsap.utils.toArray('.stagger-anim').forEach(group => {
    gsap.from(group.children, {
      autoAlpha: 0,
      y: 50,
      duration: 0.8,
      stagger: 0.12,
      ease: 'power3.out',
      scrollTrigger: { trigger: group, start: 'top 85%', once: true }
    });
  });

  // ------------------------------------------------------------------
  // 5. Section headings: word-by-word slide up
  // ------------------------------------------------------------------
  gsap.utils.toArray('.split-anim').forEach(heading => {
    if (heading.classList.contains('hero-title-split')) return;
    const words = splitWords(heading);
    gsap.from(words, {
      yPercent: 110,
      duration: 0.9,
      stagger: 0.06,
      ease: 'power3.out',
      scrollTrigger: { trigger: heading, start: 'top 88%', once: true }
    });
  });

  // ------------------------------------------------------------------
  // 6. Image reveal (clip-path wipe + subtle zoom-out)
  // ------------------------------------------------------------------
  gsap.utils.toArray('.img-reveal').forEach(box => {
    const dir = box.dataset.reveal || 'bottom';
    const start = dir === 'left' ? 'inset(0 100% 0 0)' : dir === 'right' ? 'inset(0 0 0 100%)' : 'inset(100% 0 0 0)';
    const img = box.querySelector('img');
    const tl = gsap.timeline({ scrollTrigger: { trigger: box, start: 'top 85%', once: true } });
    tl.fromTo(box, { clipPath: start }, { clipPath: 'inset(0 0 0 0)', duration: 1.3, ease: 'expo.inOut' });
    if (img) tl.from(img, { scale: 1.3, duration: 1.6, ease: 'expo.out' }, 0.2);
  });

  // ------------------------------------------------------------------
  // 7. Parallax drift
  // ------------------------------------------------------------------
  // Scrubbed effects repaint on every scroll frame, so they run only on
  // wide screens and are skipped on phones, where they cost the most.
  const richScroll = window.matchMedia('(min-width: 992px)').matches;

  if (richScroll) gsap.utils.toArray('[data-parallax]').forEach(el => {
    const amount = Number(el.dataset.parallax) || -10;
    gsap.fromTo(el, { yPercent: -amount / 2 }, {
      yPercent: amount / 2,
      ease: 'none',
      scrollTrigger: { trigger: el.parentElement, start: 'top bottom', end: 'bottom top', scrub: true }
    });
  });

  // ------------------------------------------------------------------
  // 8. Statement text: light up word by word while scrolling
  // ------------------------------------------------------------------
  gsap.utils.toArray('.scroll-highlight').forEach(block => {
    if (!richScroll) { block.querySelectorAll('.hw').forEach(w => { w.style.opacity = 1; }); return; }
    const words = block.querySelectorAll('.hw');
    gsap.to(words, {
      opacity: 1,
      stagger: 0.1,
      ease: 'none',
      scrollTrigger: { trigger: block, start: 'top 80%', end: 'bottom 45%', scrub: true }
    });
  });

  // ------------------------------------------------------------------
  // 9. SVG line drawing
  // ------------------------------------------------------------------
  gsap.utils.toArray('.draw-on-scroll').forEach(svg => {
    const scrub = svg.hasAttribute('data-scrub');
    svg.querySelectorAll('path.draw').forEach(path => {
      const len = path.getTotalLength();
      gsap.set(path, { strokeDasharray: len, strokeDashoffset: len });
      gsap.to(path, {
        strokeDashoffset: 0,
        ease: scrub ? 'none' : 'power2.inOut',
        duration: scrub ? undefined : 1.6,
        scrollTrigger: scrub
          ? { trigger: svg.closest('section') || svg, start: 'top 75%', end: 'center 40%', scrub: true }
          : { trigger: svg, start: 'top 85%', once: true }
      });
    });
  });

  // Journey path points pop in as the line reaches them
  gsap.utils.toArray('.jp-point').forEach((pt, i) => {
    gsap.from(pt.querySelector('.jp-icon'), {
      scale: 0,
      duration: 0.6,
      ease: 'back.out(2.2)',
      scrollTrigger: { trigger: pt.closest('section'), start: `top+=${120 + i * 70} 75%`, once: true }
    });
  });

  // ------------------------------------------------------------------
  // 10. Mouse-follow depth for hero sketches & images
  // ------------------------------------------------------------------
  const mouseArea = document.querySelector('.mouse-parallax');
  if (mouseArea && window.matchMedia('(pointer: fine)').matches) {
    const layers = mouseArea.querySelectorAll('[data-depth]');
    mouseArea.addEventListener('mousemove', e => {
      const r = mouseArea.getBoundingClientRect();
      const mx = (e.clientX - r.left) / r.width - 0.5;
      const my = (e.clientY - r.top) / r.height - 0.5;
      layers.forEach(layer => {
        const d = Number(layer.dataset.depth) || 10;
        gsap.to(layer, { x: mx * d, y: my * d, duration: 1, ease: 'power2.out', overwrite: 'auto' });
      });
    });
  }


  // ------------------------------------------------------------------
  // 11. [data-aos] reveal
  //     Stands in for the AOS library: same markup, but one
  //     IntersectionObserver and a CSS transition instead of a scroll
  //     listener and a second CDN stylesheet plus script.
  // ------------------------------------------------------------------
  const revealItems = document.querySelectorAll('[data-aos]');
  if (revealItems.length) {
    if (!('IntersectionObserver' in window)) {
      revealItems.forEach(el => el.classList.add('aos-in'));
    } else {
      const revealObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const delay = Number(entry.target.dataset.aosDelay) || 0;
          entry.target.style.transitionDelay = delay + 'ms';
          entry.target.classList.add('aos-in');
          obs.unobserve(entry.target);
        });
      }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });

      // Reloading halfway down the page restores the old scroll position, so
      // everything above the viewport never intersects and would stay at
      // opacity 0 for good. Those are shown outright; only what is still
      // below the fold waits for the observer.
      const observeOrShow = el => {
        if (el.classList.contains('aos-in')) return;
        if (el.getBoundingClientRect().bottom <= 0) {
          revealObserver.unobserve(el);
          el.classList.add('aos-in');
          return;
        }
        el.classList.add('aos-prep');
        revealObserver.observe(el);
      };
      revealItems.forEach(observeOrShow);
      // Images and fonts settle after init and push blocks around, and a
      // back/forward restore replays pageshow: re-check both times.
      const recheckReveals = () => revealItems.forEach(observeOrShow);
      window.addEventListener('load', recheckReveals);
      window.addEventListener('pageshow', recheckReveals);
    }
  }

  // ------------------------------------------------------------------
  // 12. Pause looping decoration while its section is off screen
  // ------------------------------------------------------------------
  if ('IntersectionObserver' in window) {
    const sections = document.querySelectorAll('section, footer');
    const idleObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        entry.target.classList.toggle('anim-idle', !entry.isIntersecting);
      });
    }, { rootMargin: '150px 0px' });
    sections.forEach(section => idleObserver.observe(section));
  }
  // ------------------------------------------------------------------
  // 13. Refresh-proofing
  //     ScrollTrigger measures the page at init, before the browser has
  //     restored the previous scroll position and before images have sized
  //     themselves. Without a re-measure, triggers that the page already
  //     sits past never fire and their elements stay in the hidden "from"
  //     state - a reload mid-page then shows empty sections.
  // ------------------------------------------------------------------
  const refreshTriggers = () => ScrollTrigger.refresh();
  window.addEventListener('load', () => {
    refreshTriggers();
    requestAnimationFrame(refreshTriggers);
    setTimeout(refreshTriggers, 400);
    setTimeout(showStranded, 1200);
  });
  // A back/forward (bfcache) restore runs no init at all.
  window.addEventListener('pageshow', e => { if (e.persisted) { refreshTriggers(); showStranded(); } });

  // Last resort: anything still invisible at or above the fold is revealed,
  // so a missed trigger can never leave a blank section behind.
  function showStranded() {
    const fold = window.innerHeight;
    gsap.utils.toArray('.fade-anim, .img-reveal, .stagger-anim > *').forEach(el => {
      const rect = el.getBoundingClientRect();
      if (rect.top > fold) return;
      const cs = getComputedStyle(el);
      if (cs.visibility === 'hidden' || parseFloat(cs.opacity) < 0.05) {
        gsap.set(el, { autoAlpha: 1, x: 0, y: 0, clipPath: 'inset(0 0 0 0)' });
      }
    });
    gsap.utils.toArray('.split-anim .w > span').forEach(word => {
      if (word.getBoundingClientRect().top > fold) return;
      const t = gsap.getProperty(word, 'yPercent');
      if (Math.abs(Number(t) || 0) > 1) gsap.set(word, { yPercent: 0 });
    });
  }
})();
