/**
 * map.js — carte typographique de l'Île-de-France.
 * Vagues d'apparition depuis Paris, survol/focus d'un département (communes
 * en basilic), info-bulle, et liste texte équivalente accessible.
 */
export default function initMap(DST) {
  const root = document.querySelector('[data-zone-map]');
  if (!root) return;
  const deps = DST.$$('.zone__dep', root);
  const tooltip = root.querySelector('[data-zone-tooltip]');
  const tipName = tooltip?.querySelector('[data-tip-name]');
  const tipDelay = tooltip?.querySelector('[data-tip-delay]');
  const tipList = tooltip?.querySelector('[data-tip-list]');
  const links = DST.$$('[data-zone-link]');
  const svg = root.querySelector('.zone__svg');

  const data = new Map();
  deps.forEach((dep) => {
    const labels = Array.from(dep.querySelectorAll('.zone__label')).map((t) => t.textContent);
    data.set(dep.dataset.dep, {
      name: dep.getAttribute('aria-label') || '',
      delay: dep.dataset.delay || '',
      labels,
    });
  });

  const activate = (code) => {
    deps.forEach((d) => {
      d.classList.toggle('is-active', d.dataset.dep === code);
      d.classList.toggle('is-dim', code !== null && d.dataset.dep !== code);
    });
    links.forEach((l) => l.classList.toggle('is-active', l.dataset.zoneLink === code));
    const info = code ? data.get(code) : null;
    if (tooltip && info) {
      const name = info.name.replace(/\s*\(.*?\)\s*—.*$/, '');
      if (tipName) tipName.textContent = name;
      if (tipDelay) tipDelay.textContent = 'Livraison ' + info.delay;
      if (tipList) tipList.textContent = 'ex. : ' + info.labels.slice(0, 6).join(', ') + '…';
      tooltip.classList.add('is-on');
      tooltip.setAttribute('aria-hidden', 'false');
    } else if (tooltip) {
      tooltip.classList.remove('is-on');
      tooltip.setAttribute('aria-hidden', 'true');
    }
  };

  deps.forEach((dep) => {
    dep.addEventListener('pointerenter', () => activate(dep.dataset.dep));
    dep.addEventListener('pointerleave', () => activate(null));
    dep.addEventListener('focus', () => activate(dep.dataset.dep));
    dep.addEventListener('blur', () => activate(null));
    dep.addEventListener('click', () => activate(dep.dataset.dep));
    dep.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        activate(dep.dataset.dep);
      }
    });
  });

  root.addEventListener('pointerleave', () => activate(null));

  links.forEach((link) => {
    const code = link.dataset.zoneLink;
    link.addEventListener('pointerenter', () => activate(code));
    link.addEventListener('pointerleave', () => activate(null));
    link.addEventListener('focus', () => activate(code));
    link.addEventListener('blur', () => activate(null));
    link.addEventListener('click', () => {
      activate(code);
      deps.find((d) => d.dataset.dep === code)?.focus();
    });
  });

  if (tooltip) {
    root.addEventListener('pointermove', (e) => {
      const r = root.getBoundingClientRect();
      tooltip.style.left = Math.min(r.width - 250, Math.max(0, e.clientX - r.left + 16)) + 'px';
      tooltip.style.top = Math.max(0, e.clientY - r.top + 14) + 'px';
    });
  }

  if (!window.gsap || DST.reduced) return;

  deps.forEach((dep) => {
    const labels = dep.querySelectorAll('.zone__label');
    gsap.from(labels, {
      opacity: 0,
      y: 10,
      duration: 0.7,
      stagger: { each: 0.012, from: 'center' },
      ease: 'power2.out',
      scrollTrigger: { trigger: svg, start: 'top 75%', once: true },
    });
  });
  gsap.from('.zone__shape', {
    opacity: 0,
    duration: 1,
    stagger: 0.06,
    ease: 'power2.out',
    scrollTrigger: { trigger: svg, start: 'top 80%', once: true },
  });
}
