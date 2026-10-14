/**
 * OFSYS Education - Premium Frontend Interactions & Scripts
 * Senior UI/UX Architectural Scripts
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Sticky Header & Scroll Effects
  const header = document.querySelector('.site-header');
  window.addEventListener('scroll', function () {
    if (window.scrollY > 40) {
      header && header.classList.add('scrolled');
    } else {
      header && header.classList.remove('scrolled');
    }
  });

  // 1a. Courses dropdown on small screens (desktop opens it on hover)
  document.querySelectorAll('.nav-drop-toggle').forEach(function (toggle) {
    const item = toggle.closest('.dropdown');
    if (!item) return;
    toggle.addEventListener('click', function () {
      const open = item.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  // 1b. Hero Banner Slider (fade + circular autoplay progress + slide counter)
  const heroEl = document.querySelector('.hero-swiper');
  if (heroEl && window.Swiper) {
    const heroBanner = heroEl.closest('.hero-banner');
    const progressEl = heroBanner.querySelector('.hero-progress');
    const currentEl = heroBanner.querySelector('.hero-current');
    const lineFill = heroBanner.querySelector('.hero-line-fill');
    const total = heroEl.querySelectorAll('.swiper-slide').length;

    new Swiper(heroEl, {
      effect: 'fade',
      fadeEffect: { crossFade: true },
      loop: true,
      speed: 1200,
      autoplay: { delay: 6500, disableOnInteraction: false },
      navigation: {
        prevEl: heroBanner.querySelector('.hero-prev'),
        nextEl: heroBanner.querySelector('.hero-next')
      },
      on: {
        autoplayTimeLeft(swiper, time, progress) {
          if (progressEl) progressEl.style.setProperty('--progress', 1 - progress);
        },
        slideChange(swiper) {
          const idx = swiper.realIndex + 1;
          if (currentEl) currentEl.textContent = String(idx).padStart(2, '0');
          if (lineFill) lineFill.style.height = (idx / total * 100) + '%';
        },
        init() {
          if (lineFill) lineFill.style.height = (1 / total * 100) + '%';
        }
      }
    });
  }

  // 1c. Success Stories Slider
  const testiEl = document.querySelector('.testi-swiper');
  if (testiEl && window.Swiper) {
    new Swiper(testiEl, {
      slidesPerView: 1,
      spaceBetween: 24,
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      navigation: { prevEl: '.testi-prev', nextEl: '.testi-next' },
      breakpoints: { 768: { slidesPerView: 2 }, 1200: { slidesPerView: 3 } }
    });
  }

  // 1c-2. Homepage course slider (bleeds off the right edge)
  const courseEl = document.querySelector('.course-swiper');
  if (courseEl && window.Swiper) {
    new Swiper(courseEl, {
      slidesPerView: 1.15,
      spaceBetween: 18,
      speed: 900,
      loop: true,
      autoplay: { delay: 4000, disableOnInteraction: false, pauseOnMouseEnter: true },
      navigation: { prevEl: '.cs-prev', nextEl: '.cs-next' },
      breakpoints: { 576: { slidesPerView: 1.6 }, 768: { slidesPerView: 2.2, spaceBetween: 22 }, 1200: { slidesPerView: 2.6, spaceBetween: 24 } }
    });
  }

  // 1c-3. Placed students slider
  const placedEl = document.querySelector('.placed-swiper');
  if (placedEl && window.Swiper) {
    new Swiper(placedEl, {
      slidesPerView: 1.1,
      spaceBetween: 20,
      speed: 800,
      navigation: { prevEl: '.pl-prev', nextEl: '.pl-next' },
      breakpoints: { 576: { slidesPerView: 2 }, 992: { slidesPerView: 3 }, 1200: { slidesPerView: 4, spaceBetween: 24 } }
    });
  }

  // 1c-4. Testimonials: fade slider driven by avatar thumbnails
  const insTestiEl = document.querySelector('.ins-testi-swiper');
  if (insTestiEl && window.Swiper) {
    const thumbs = document.querySelectorAll('.testi-thumbs button');
    const insTesti = new Swiper(insTestiEl, {
      effect: 'fade',
      fadeEffect: { crossFade: true },
      autoHeight: true,
      speed: 700,
      autoplay: { delay: 6000, disableOnInteraction: false },
      on: {
        slideChange(swiper) {
          thumbs.forEach((t, i) => t.classList.toggle('active', i === swiper.activeIndex));
        }
      }
    });
    thumbs.forEach(t => t.addEventListener('click', () => insTesti.slideTo(Number(t.dataset.index))));
  }

  // 1d. Journey stacking cards -> highlight active step dot
  const stackCards = document.querySelectorAll('.stack-card');
  const stepDots = document.querySelectorAll('.step-dot');
  if (stackCards.length && stepDots.length && 'IntersectionObserver' in window) {
    const stepObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const step = entry.target.getAttribute('data-step');
          stepDots.forEach(dot => dot.classList.toggle('active', dot.getAttribute('data-step') === step));
        }
      });
    }, { rootMargin: '-45% 0px -45% 0px' });
    stackCards.forEach(card => stepObserver.observe(card));
  }

  // 2. Animated Counters for Achievements (each counter starts when visible)
  function runCounter(counter) {
    const target = parseInt(counter.getAttribute('data-target') || '0', 10);
    const suffix = counter.getAttribute('data-suffix') || '';
    let count = 0;
    const step = Math.ceil(target / 40) || 1;

    const timer = setInterval(() => {
      count += step;
      if (count >= target) {
        counter.textContent = target + suffix;
        clearInterval(timer);
      } else {
        counter.textContent = count + suffix;
      }
    }, 40);
  }

  const metricNumbers = document.querySelectorAll('.counter-val');
  if ('IntersectionObserver' in window) {
    const counterObserver = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          runCounter(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });
    metricNumbers.forEach(counter => counterObserver.observe(counter));
  }

  // 3. Discount Modal Automatic Trigger
  //    The homepage (marked with data-auto-enquiry) opens it on every visit
  //    and on every refresh; other pages only open it from a button.
  const discountModalEl = document.getElementById('enrollDiscountModal');
  if (discountModalEl && window.bootstrap) {
    const discountModal = new bootstrap.Modal(discountModalEl);

    if (document.body.dataset.autoEnquiry === '1') {
      setTimeout(() => {
        if (!document.querySelector('.modal.show')) {
          discountModal.show();
        }
      }, 1200);
    }

    // Trigger modal from any element with class .trigger-discount-modal
    document.querySelectorAll('.trigger-discount-modal').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const courseName = this.getAttribute('data-course');
        if (courseName) {
          const select = discountModalEl.querySelector('#modalCourse');
          if (select) {
            select.value = courseName;
          }
        }
        discountModal.show();
      });
    });
  }

  // 4. AJAX Form Submission for Forms (Discount Modal & Contact Form)
  // After a successful submit the visitor picks how to send the enquiry on:
  // a small popup with WhatsApp / Mail, both pre-filled by form-handler.php
  // (rebuilt here from the site's contact details if the server sent none).
  function showLeadChoice(info, form, leadId) {
    const modalEl = document.getElementById('leadChoiceModal');
    const c = window.OFSYS_CONTACT || {};
    if (!modalEl || !window.bootstrap) return false;

    if (!info || !info.whatsapp || !info.email) {
      if (!c.whatsapp || !c.email) return false;
      const nameField = form && form.querySelector('[name="full_name"]');
      const name = nameField ? nameField.value : '';
      const text = 'Hello OFSYS Education, I just submitted an enquiry on your website.'
                 + (name ? '\n\nName: ' + name : '');
      info = {
        whatsapp: { url: 'https://wa.me/' + c.whatsapp + '?text=' + encodeURIComponent(text) },
        email: {
          url: 'mailto:' + c.email + '?subject=' + encodeURIComponent('Course Enquiry')
               + '&body=' + encodeURIComponent(text)
        }
      };
    }

    const wa = modalEl.querySelector('#leadChoiceWa');
    const mail = modalEl.querySelector('#leadChoiceMail');
    if (wa) {
      wa.href = info.whatsapp.url;
      // WhatsApp always opens in its own tab, so the site stays put
      wa.target = '_blank';
      wa.rel = 'noopener noreferrer';
    }
    if (mail) mail.href = info.email.url;

    modalEl.dataset.leadId = leadId || '';

    const choice = bootstrap.Modal.getOrCreateInstance(modalEl);
    const status = modalEl.querySelector('.lc-status');
    const opts = modalEl.querySelector('.lc-opts');

    // Fresh popup every time
    modalEl.classList.remove('is-sent');
    if (status) { status.textContent = ''; status.className = 'lc-status'; }
    if (opts) opts.style.display = '';
    if (mail) {
      mail.classList.remove('is-busy');
      mail.innerHTML = mail.dataset.markup || mail.innerHTML;
      if (!mail.dataset.markup) mail.dataset.markup = mail.innerHTML;
    }

    if (!modalEl.dataset.bound) {
      modalEl.dataset.bound = '1';

      // WhatsApp just opens in its own tab
      const waLink = modalEl.querySelector('#leadChoiceWa');
      if (waLink) waLink.addEventListener('click', () => setTimeout(() => choice.hide(), 400));

      // Mail is sent by the server, straight to the admissions inbox
      const mailLink = modalEl.querySelector('#leadChoiceMail');
      if (mailLink) {
        mailLink.addEventListener('click', function (e) {
          const leadId = modalEl.dataset.leadId || '';
          if (!leadId || this.classList.contains('is-busy')) return; // no id: let the mailto run
          e.preventDefault();

          this.classList.add('is-busy');
          const keep = this.innerHTML;
          this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span><strong>Sending…</strong></span>';

          const body = new FormData();
          body.append('lead_id', leadId);

          fetch((window.OFSYS_CONTACT && window.OFSYS_CONTACT.mailEndpoint) || 'mail-enquiry.php', {
            method: 'POST',
            body: body
          })
            .then(r => r.json())
            .then(res => {
              this.classList.remove('is-busy');
              this.innerHTML = keep;
              if (res.status === 'success') {
                modalEl.classList.add('is-sent');
                if (opts) opts.style.display = 'none';
                if (status) {
                  status.className = 'lc-status is-ok';
                  status.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + res.message;
                }
                setTimeout(() => choice.hide(), 2600);
              } else if (status) {
                status.className = 'lc-status is-err';
                status.textContent = res.message || 'Could not send the mail.';
                if (res.fallback) this.href = res.fallback;
              }
            })
            .catch(() => {
              this.classList.remove('is-busy');
              this.innerHTML = keep;
              if (status) {
                status.className = 'lc-status is-err';
                status.textContent = 'Could not reach the mail service. Tap again to use your own mail app.';
              }
              modalEl.dataset.leadId = '';
            });
        });
      }
    }

    const openModal = form.closest('.modal');

    if (openModal && openModal !== modalEl) {
      // Never stack two modals: close the form's one, then open this
      openModal.addEventListener('hidden.bs.modal', () => choice.show(), { once: true });
      const openInstance = bootstrap.Modal.getInstance(openModal);
      if (openInstance) openInstance.hide();
      else choice.show();
    } else {
      choice.show();
    }
    return true;
  }

  const ajaxForms = document.querySelectorAll('.ajax-enquiry-form');
  ajaxForms.forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Submitting...';

      const formData = new FormData(form);

      fetch(form.getAttribute('action') || 'form-handler.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalText;

        const responseAlert = form.querySelector('.form-response-alert');
        if (data.status === 'success') {
          form.reset();
          // The two options come up in their own popup
          const shown = showLeadChoice(data.follow_up, form, data.lead_id);
          if (!shown) {
            if (responseAlert) {
              responseAlert.className = 'form-response-alert alert alert-success mt-3';
              responseAlert.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i> ' + data.message;
              responseAlert.style.display = 'block';
            } else {
              alert(data.message);
            }
          } else if (responseAlert) {
            responseAlert.style.display = 'none';
          }
        } else {
          if (responseAlert) {
            responseAlert.className = 'form-response-alert alert alert-danger mt-3';
            responseAlert.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-2"></i> ' + (data.message || 'Please check your inputs.');
            responseAlert.style.display = 'block';
          } else {
            alert(data.message || 'An error occurred. Please try again.');
          }
        }
      })
      .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        console.error('Error submitting form:', error);
        alert('Thank you! Your enquiry has been received. Our team will contact you shortly.');
        form.reset();
      });
    });
  });

  // 5. Course Filter on Courses Page
  const courseFilterBtns = document.querySelectorAll('.course-filter-btn');
  const courseItems = document.querySelectorAll('.course-filter-item');

  if (courseFilterBtns.length > 0 && courseItems.length > 0) {
    courseFilterBtns.forEach(btn => {
      btn.addEventListener('click', function () {
        courseFilterBtns.forEach(b => b.classList.remove('active', 'btn-ofsys-primary'));
        courseFilterBtns.forEach(b => b.classList.add('btn-ofsys-outline'));
        this.classList.remove('btn-ofsys-outline');
        this.classList.add('active', 'btn-ofsys-primary');

        const filter = this.getAttribute('data-filter');

        courseItems.forEach(item => {
          if (filter === 'all' || item.getAttribute('data-category') === filter) {
            item.style.display = 'block';
            item.classList.add('animate-fadeIn');
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  }

  // 6. Gallery Filter Tabs
  const galleryFilterBtns = document.querySelectorAll('.gallery-filter-btn');
  const galleryCards = document.querySelectorAll('.gallery-grid-item');

  if (galleryFilterBtns.length > 0 && galleryCards.length > 0) {
    galleryFilterBtns.forEach(btn => {
      btn.addEventListener('click', function () {
        galleryFilterBtns.forEach(b => b.classList.remove('active', 'btn-ofsys-primary'));
        galleryFilterBtns.forEach(b => b.classList.add('btn-ofsys-outline'));
        this.classList.remove('btn-ofsys-outline');
        this.classList.add('active', 'btn-ofsys-primary');

        const filter = this.getAttribute('data-filter');

        galleryCards.forEach(card => {
          if (filter === 'all' || card.getAttribute('data-category') === filter) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }
});

/* ==========================================================================
   Course "What You Will Learn" cards: on phones they collapse into an
   accordion - only the heading shows, tapping it reveals the module list.
   Desktop keeps every card fully open.
   ========================================================================== */
(function () {
  const grids = Array.from(document.querySelectorAll('.cd-modules'));
  if (!grids.length) return;

  const mq = window.matchMedia('(max-width: 767px)');
  const modules = [];

  grids.forEach(grid => {
    grid.querySelectorAll('.cd-module').forEach(mod => {
      const head = mod.querySelector('.cd-module-head');
      const list = mod.querySelector('.cd-module-list');
      if (!head || !list) return;

      const toggle = () => {
        if (!mq.matches) return;
        setOpen(mod, !mod.classList.contains('is-open'));
      };
      head.addEventListener('click', toggle);
      head.addEventListener('keydown', e => {
        if (e.key !== 'Enter' && e.key !== ' ') return;
        e.preventDefault();
        toggle();
      });

      modules.push({ mod, head, list });
    });
  });

  function setOpen(mod, open) {
    const entry = modules.find(m => m.mod === mod);
    mod.classList.toggle('is-open', open);
    if (entry) entry.head.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  function apply() {
    const mobile = mq.matches;
    grids.forEach(grid => grid.classList.toggle('is-accordion', mobile));
    modules.forEach(({ mod, head, list }) => {
      if (mobile) {
        head.setAttribute('role', 'button');
        head.setAttribute('tabindex', '0');
        head.setAttribute('aria-controls', list.id);
        setOpen(mod, false);
      } else {
        ['role', 'tabindex', 'aria-controls', 'aria-expanded'].forEach(a => head.removeAttribute(a));
        mod.classList.remove('is-open');
      }
    });
  }

  apply();
  if (mq.addEventListener) mq.addEventListener('change', apply);
  else mq.addListener(apply);
})();
