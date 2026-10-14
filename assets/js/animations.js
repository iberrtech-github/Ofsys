/**
 * OFSYS Education - Scroll Animation Engine
 * Lenis smooth scroll + GSAP ScrollTrigger driven effects.
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
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  // ------------------------------------------------------------------
  // 1. Lenis smooth scroll
  // ------------------------------------------------------------------
  let lenis = null;
  if (typeof window.Lenis !== 'undefined') {
    lenis = new Lenis({ lerp: 0.09, smoothWheel: true });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(time => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
    window.ofsysLenis = lenis;

    // Pause page scrolling while a Bootstrap modal is open
    document.addEventListener('show.bs.modal', () => lenis.stop());
    document.addEventListener('hidden.bs.modal', () => lenis.start());

    // Smooth in-page anchor links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      const hash = link.getAttribute('href');
      if (hash.length < 2 || link.hasAttribute('data-bs-toggle')) return;
      link.addEventListener('click', e => {
        const target = document.querySelector(hash);
        if (!target) return;
        e.preventDefault();
        lenis.scrollTo(target, { offset: -90, duration: 1.4 });
      });
    });
  }

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
  gsap.utils.toArray('[data-parallax]').forEach(el => {
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

  // Recalculate positions once images/fonts have loaded
  window.addEventListener('load', () => ScrollTrigger.refresh());
})();
