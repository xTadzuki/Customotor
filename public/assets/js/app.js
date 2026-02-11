// public/assets/js/app.js
(() => {
  "use strict";

  /* ===========================
     HELPERS & INIT
     =========================== */
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  // Remove loading class immediately (script is deferred, so DOM is ready)
  document.documentElement.classList.remove("js-loading");

  const isReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ===========================
     HEADER: burger + menu mobile
     =========================== */
  const initHeader = () => {
    const burger = $("[data-cm-burger]");
    const menu = $("[data-cm-menu]");
    if (!burger || !menu) return;

    const openMenu = () => {
      menu.classList.add("open");
      burger.setAttribute("aria-expanded", "true");
      document.documentElement.classList.add("cm-menu-open");
    };

    const closeMenu = () => {
      menu.classList.remove("open");
      burger.setAttribute("aria-expanded", "false");
      document.documentElement.classList.remove("cm-menu-open");
    };

    burger.addEventListener("click", (e) => {
      e.stopPropagation();
      menu.classList.contains("open") ? closeMenu() : openMenu();
    });

    $$("a", menu).forEach((a) => a.addEventListener("click", closeMenu));

    document.addEventListener("click", (e) => {
      if (
        menu.classList.contains("open") &&
        !menu.contains(e.target) &&
        !burger.contains(e.target)
      ) {
        closeMenu();
      }
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") closeMenu();
    });

    window.addEventListener(
      "resize",
      () => {
        if (window.innerWidth > 900) closeMenu();
      },
      { passive: true }
    );
  };

  /* ===========================
     NAVBAR SCROLL & HEADER OFFSET
     =========================== */
  const initScrollNav = () => {
    const cmNav = $(".cm-nav");
    const header = $(".cm-header");

    if (cmNav) {
      const updateNav = () => cmNav.classList.toggle("scrolled", window.scrollY > 20);
      window.addEventListener("scroll", updateNav, { passive: true });
      updateNav();
    }

    if (header) {
      const setHeaderVar = () => {
        document.documentElement.style.setProperty(
          "--cm-header-h",
          `${header.offsetHeight}px`
        );
      };
      setHeaderVar();
      window.addEventListener("resize", setHeaderVar, { passive: true });
    }
  };

  /* ===========================
   CAROUSEL (Infinite) 
   =========================== */
const initCarousel = () => {
  const carousel = document.querySelector("[data-carousel]");
  if (!carousel) return;

  const viewport = carousel.querySelector(".cm-carousel__viewport");
  const track = carousel.querySelector("[data-track]");
  const prev = carousel.querySelector("[data-prev]");
  const next = carousel.querySelector("[data-next]");
  if (!viewport || !track) return;

  if (track.dataset.infiniteReady === "1") return;
  track.dataset.infiniteReady = "1";

  const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  // Helpers
  const getImgVar = (el) =>
    (el.style.getPropertyValue("--img") || "").trim() ||
    (getComputedStyle(el).getPropertyValue("--img") || "").trim();

  const bake = (el, imgVar) => {
    if (!imgVar) imgVar = getImgVar(el);
    if (!imgVar) return;
    el.style.backgroundImage = imgVar;       // ✅ stable
    el.style.backgroundSize = "cover";
    el.style.backgroundPosition = "center";
    el.style.backgroundRepeat = "no-repeat";
  };

  // 1) Origine
  const originals = Array.from(track.children);
  const originalCount = originals.length;
  if (!originalCount) return;

  originals.forEach((el) => bake(el));

  // 2) Clones (x1) 
  const clones = originals.map((orig) => {
    const c = orig.cloneNode(true);
    c.setAttribute("aria-hidden", "true");
    const imgVar = getImgVar(orig);
    if (imgVar) {
      c.style.setProperty("--img", imgVar);
      bake(c, imgVar);
    }
    return c;
  });
  clones.forEach((c) => track.appendChild(c));

  // 3) Mesures exactes 
  let half = 0;
  let step = 360;

  const measure = () => {
    const items = Array.from(track.children).slice(0, originalCount);
    const gap = parseFloat(getComputedStyle(track).gap || "0");

    let w = 0;
    items.forEach((el, i) => {
      w += el.getBoundingClientRect().width;
      if (i < items.length - 1) w += gap;
    });

    half = Math.round(w); 

    const first = items[0];
    if (first) {
      step = Math.round(first.getBoundingClientRect().width + (isNaN(gap) ? 0 : gap));
    }
  };

  // 4) Animation (px/sec)
  let offset = 0;
  let paused = false;
  let raf = null;
  let lastT = 0;

  //  la vitesse (px par seconde)
  const SPEED_PX_PER_SEC = 80;

  const apply = () => {
    track.style.transform = `translate3d(${-Math.round(offset)}px, 0, 0)`;
  };

  const loop = (t) => {
    if (!lastT) lastT = t;
    const dt = Math.min((t - lastT) / 1000, 0.05);
    lastT = t;

    if (!paused && !reduced && half > 0) {
      offset += SPEED_PX_PER_SEC * dt;

      // boucle
      if (offset >= half) offset -= half;
      if (offset < 0) offset += half;

      apply();
    }

    raf = requestAnimationFrame(loop);
  };

  // Pause events
  carousel.addEventListener("mouseenter", () => (paused = true));
  carousel.addEventListener("mouseleave", () => (paused = false));
  carousel.addEventListener("touchstart", () => (paused = true), { passive: true });
  carousel.addEventListener("touchend", () => (paused = false), { passive: true });

  // Buttons
  const nudge = (dir) => {
    paused = true;
    offset += dir * step;

    if (offset >= half) offset -= half;
    if (offset < 0) offset += half;

    apply();

    setTimeout(() => {
      if (!carousel.matches(":hover")) paused = false;
    }, 650);
  };

  prev?.addEventListener("click", () => nudge(-1));
  next?.addEventListener("click", () => nudge(1));

  // Resize: re-mesure + recalage
  let rt = null;
  window.addEventListener(
    "resize",
    () => {
      clearTimeout(rt);
      rt = setTimeout(() => {
        measure();
        offset = offset % (half || 1);
        apply();
      }, 150);
    },
    { passive: true }
  );

  // Start
  requestAnimationFrame(() => {
    measure();
    apply();
    raf = requestAnimationFrame(loop);
  });
};


  /* ===========================
     AUTH UX (Password Toggle & Meter)
     =========================== */
  const initAuthUX = () => {
    const SVG_OIL = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C9 6.2 6 9.4 6 13.2A6 6 0 0 0 12 19a6 6 0 0 0 6-5.8C18 9.4 15 6.2 12 2Z"/></svg>`;
    const SVG_OIL_SLASH = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C9 6.2 6 9.4 6 13.2A6 6 0 0 0 12 19a6 6 0 0 0 6-5.8C18 9.4 15 6.2 12 2Z"/><path d="M4 20L20 4"/></svg>`;

    const setIcon = (btn, show) => {
      const holder = btn.querySelector(".cm-passicon");
      if (holder) holder.innerHTML = show ? SVG_OIL_SLASH : SVG_OIL;
    };

    $$("[data-toggle-pass]").forEach((btn) => setIcon(btn, false));

    document.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-toggle-pass]");
      if (!btn) return;

      const input = btn.closest(".cm-input-wrap")?.querySelector("input");
      if (!input) return;

      const show = input.type === "password";
      input.type = show ? "text" : "password";
      setIcon(btn, show);
    });

    const scorePassword = (p) => {
      if (!p) return { score: 0, label: "Vide" };
      let s = 0;
      if (p.length >= 8) s++;
      if (p.length >= 12) s++;
      if (/[a-z]/.test(p)) s++;
      if (/[A-Z]/.test(p)) s++;
      if (/\d/.test(p)) s++;
      if (/[^A-Za-z0-9]/.test(p)) s++;
      const score = Math.min(5, Math.max(0, s - 1));
      const labels = ["Très faible", "Faible", "Moyen", "Bon", "Fort", "Très fort"];
      return { score, label: labels[score] };
    };

    $$("input[type='password'][autocomplete='new-password']").forEach((input) => {
      if (isReduced) return;

      const field = input.closest(".cm-field");
      if (!field) return;

      const meter = document.createElement("div");
      meter.className = "cm-passmeter";
      meter.innerHTML = `<div class="cm-passmeter__bar"><div class="cm-passmeter__fill" style="width:0%"></div></div>`;
      field.appendChild(meter);

      const update = () => {
        const { score } = scorePassword(input.value);
        meter.dataset.level = String(score);
        const fill = meter.querySelector(".cm-passmeter__fill");
        if (fill) fill.style.width = `${(score / 5) * 100}%`;
      };

      input.addEventListener("input", update);
      update();
    });
  };

  /* ===========================
     ANIMATIONS (Reveal, Parallax, Tilt)
     =========================== */
  const initAnimations = () => {
    // 1) Reveal
    const reveals = $$(".reveal");

    if (reveals.length) {
      const observer = new IntersectionObserver(
        (entries, obs) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("is-visible");
              obs.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.12, rootMargin: "0px 0px -10% 0px" }
      );

      reveals.forEach((el) => observer.observe(el));

      // HERO ARRIVAL (cinematic)
      $$(".perf-hero, .cm-hero").forEach((el) => el.classList.add("is-visible"));
      $$(".perf-hero.reveal, .cm-hero.reveal").forEach((el) => el.classList.add("is-visible"));
    } else {
      $$(".perf-hero, .cm-hero").forEach((el) => el.classList.add("is-visible"));
    }

    // 2) Parallax (viewport-based)
    const parallaxEls = $$("[data-parallax]");
    if (parallaxEls.length && !isReduced) {
      let ticking = false;

      const update = () => {
        const vh = window.innerHeight;

        parallaxEls.forEach((el) => {
          const rect = el.getBoundingClientRect();
          if (rect.bottom <= 0 || rect.top >= vh) return;

          const speed = parseFloat(el.dataset.parallax || "0.05");
          const progress = (vh - rect.top) / (vh + rect.height); // 0..1
          const translate = (progress - 0.5) * 60 * speed;

          el.style.transform = `translate3d(0, ${translate}px, 0)`;
        });

        ticking = false;
      };

      const onScroll = () => {
        if (!ticking) {
          ticking = true;
          requestAnimationFrame(update);
        }
      };

      window.addEventListener("scroll", onScroll, { passive: true });
      window.addEventListener("resize", onScroll, { passive: true });
      update();
    }

    // 3) Counters
    const counters = $$(".counter-val");
    if (counters.length) {
      const statsObserver = new IntersectionObserver(
        (entries, obs) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            const el = entry.target;
            const target = parseInt(el.dataset.target || "0", 10);

            if (!target) {
              obs.unobserve(el);
              return;
            }

            const duration = 2000;
            const start = performance.now();

            const step = (now) => {
              const progress = Math.min((now - start) / duration, 1);
              const ease = 1 - Math.pow(1 - progress, 3);
              const val = Math.floor(ease * target);
              el.textContent = `+${val}`;

              if (progress < 1) requestAnimationFrame(step);
              else el.textContent = `+${target}`;
            };

            requestAnimationFrame(step);
            obs.unobserve(el);
          });
        },
        { threshold: 0.5 }
      );

      counters.forEach((c) => statsObserver.observe(c));
    }

    // 4) Tilt
    if (!isReduced) {
      $$("[data-tilt]").forEach((el) => {
        el.addEventListener("mousemove", (e) => {
          const rect = el.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          el.style.setProperty("--mouse-x", `${x}px`);
          el.style.setProperty("--mouse-y", `${y}px`);
        });
      });
    }
  };

  /* ===========================
     TABS (Services)
     =========================== */
  const initTabs = () => {
    const tabs = $$(".perf-tab-btn");
    if (!tabs.length) return;

    tabs.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();

        $$(".perf-tab-btn").forEach((b) => b.classList.remove("active"));
        $$(".perf-tab-pane").forEach((p) => p.classList.remove("active"));

        btn.classList.add("active");

        const targetId = btn.dataset.tab;
        const targetPane = targetId ? $(targetId) : null;

        if (targetPane) {
          targetPane.classList.add("active");
          $$(".reveal", targetPane).forEach((el) => el.classList.add("is-visible"));
        }
      });
    });
  };

  /* ===========================
     BOOTSTRAP
     =========================== */
  const init = () => {
    const safeInit = (fn, name) => {
      try {
        fn();
      } catch (e) {
        console.error(`Error init ${name}:`, e);
      }
    };

    safeInit(initHeader, "Header");
    safeInit(initScrollNav, "ScrollNav");
    safeInit(initCarousel, "Carousel");
    safeInit(initAuthUX, "AuthUX");
    safeInit(initAnimations, "Animations");
    safeInit(initTabs, "Tabs");
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
  /* ===========================
   LOOKBOOK — Reveal + Stagger (smooth)
   + Hero cinematic (.lb-hero gets .is-visible)
   =========================== */
(() => {
  "use strict";

  const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  // 1) HERO
  const singleTargets = document.querySelectorAll(
    ".lb-hero, .lb-pagehead, .lb-desc, .lb-gallery"
  );

  // 2) Items list 
  const items = document.querySelectorAll(".lb-item");

  // Ajoute la classe reveal
  singleTargets.forEach((el) => el.classList.add("reveal"));
  items.forEach((el) => el.classList.add("reveal"));

  // Stagger doux sur les cards
  items.forEach((el, i) => {
    const delay = Math.min(i * 130, 780);
    el.style.transitionDelay = `${delay}ms`;
  });

  if (prefersReduced) {
    [...singleTargets, ...items].forEach((el) => el.classList.add("is-visible"));
    return;
  }

  const io = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) {
        if (!entry.isIntersecting) continue;
        entry.target.classList.add("is-visible");
        io.unobserve(entry.target);
      }
    },
    {
      threshold: 0.12,
      rootMargin: "0px 0px -22% 0px",
    }
  );

  [...singleTargets, ...items].forEach((el) => io.observe(el));
})();


})();
