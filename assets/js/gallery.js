/**
 * gallery.js — galerie draggable.
 * Draggable + Inertia : on attrape la bande, on la lance, elle s'incline
 * légèrement selon la vitesse. Barre de progression + flèches clavier.
 */
export default function initGallery(DST) {
  const viewport = document.querySelector('[data-gallery-viewport]');
  const rail = document.querySelector('[data-gallery-rail]');
  const bar = document.querySelector('[data-gallery-progress]');
  if (!viewport || !rail) return;

  const items = Array.from(rail.children);
  let maxScroll = 0;
  let currentX = 0;
  const proxy = document.createElement('div');
  let leanTimer = 0;

  const measure = () => {
    const style = getComputedStyle(rail);
    const padRight = parseFloat(style.paddingRight) || 0;
    maxScroll = Math.max(0, rail.scrollWidth - viewport.clientWidth + padRight * 0.4);
  };

  measure();
  window.addEventListener('resize', measure);
  window.addEventListener('load', measure);

  const paint = (x) => {
    if (!bar) return;
    const ratio = maxScroll ? Math.min(1, Math.max(0, -x / maxScroll)) : 0;
    bar.style.width = `${12 + ratio * 88}%`;
  };

  const lean = (velocity) => {
    if (DST.reduced) return;
    const clamp = Math.max(-5, Math.min(5, velocity / 380));
    items.forEach((item) => {
      const img = item.querySelector('.gallery__img img');
      if (img) img.style.transform = `rotate(${clamp.toFixed(2)}deg) scale(1.02)`;
    });
    window.clearTimeout(leanTimer);
    leanTimer = window.setTimeout(() => {
      items.forEach((item) => {
        const img = item.querySelector('.gallery__img img');
        if (img) img.style.transform = '';
      });
    }, 260);
  };

  if (window.Draggable && !DST.reduced) {
    viewport.style.touchAction = 'pan-y';
    Draggable.create(proxy, {
      type: 'x',
      trigger: viewport,
      inertia: true,
      dragResistance: 0.05,
      allowNativeTouchScrolling: true,
      bounds: { minX: () => -maxScroll, maxX: 0 },
      onPress() {
        viewport.classList.add('is-dragging');
        measure();
        this.applyBounds({ minX: -maxScroll, maxX: 0 });
      },
      onDrag() {
        currentX = this.x;
        rail.style.transform = `translate3d(${this.x}px,0,0)`;
        paint(this.x);
        lean(this.getVelocity('x'));
      },
      onThrowUpdate() {
        currentX = this.x;
        rail.style.transform = `translate3d(${this.x}px,0,0)`;
        paint(this.x);
        lean(this.getVelocity('x'));
      },
      onRelease() {
        viewport.classList.remove('is-dragging');
        lean(0);
      },
    });
  } else {
    viewport.style.overflowX = 'auto';
    viewport.style.scrollBehavior = 'auto';
    viewport.addEventListener('scroll', () => {
      const ratio = viewport.scrollLeft / Math.max(1, viewport.scrollWidth - viewport.clientWidth);
      paint(-ratio * maxScroll);
    });
  }

  const move = (x) => {
    currentX = Math.max(-maxScroll, Math.min(0, x));
    rail.style.transform = `translate3d(${currentX}px,0,0)`;
    paint(currentX);
  };

  rail.addEventListener('keydown', (e) => {
    if (!['ArrowRight', 'ArrowLeft', 'Home', 'End'].includes(e.key)) return;
    e.preventDefault();
    const step = Math.min(viewport.clientWidth * 0.8, 620);
    if (e.key === 'ArrowRight') move(currentX - step);
    if (e.key === 'ArrowLeft') move(currentX + step);
    if (e.key === 'Home') move(0);
    if (e.key === 'End') move(-maxScroll);
  });

  viewport.addEventListener(
    'wheel',
    (e) => {
      if (Math.abs(e.deltaX) <= Math.abs(e.deltaY)) return;
      e.preventDefault();
      move(currentX - e.deltaX);
    },
    { passive: false }
  );

  if (window.gsap && !DST.reduced) {
    gsap.from(items, {
      y: 40,
      opacity: 0,
      duration: 0.9,
      stagger: 0.06,
      ease: 'power3.out',
      scrollTrigger: { trigger: viewport, start: 'top 85%', once: true },
      clearProps: 'opacity,transform',
    });
  }
}
