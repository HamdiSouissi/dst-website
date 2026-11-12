/**
 * stats.js — chiffres « imprimés » ligne par ligne (masque horizontal),
 * avec un léger décalage typographique entre le nombre et son libellé.
 */
export default function initStats(DST) {
  const values = DST.$$('.stat__value');
  if (!values.length) return;
  if (DST.reduced || !window.gsap) return;

  DST.$$('.stat').forEach((stat, i) => {
    const value = stat.querySelector('.stat__value');
    const label = stat.querySelector('.stat__label');
    const text = stat.querySelector('.stat__text');
    const parts = [value, label, text].filter(Boolean);
    gsap.set(value, { clipPath: 'inset(0 0 100% 0)' });
    gsap.set([label, text], { opacity: 0, y: 10 });

    const tl = gsap.timeline({
      scrollTrigger: { trigger: stat, start: 'top 88%', once: true },
      delay: (i % 3) * 0.08,
    });
    tl.to(value, { clipPath: 'inset(0 0 0% 0)', duration: 0.75, ease: 'power3.inOut' })
      .to([label, text], { opacity: 1, y: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out' }, '-=0.25');
  });

  // légère respiration du chiffre « 0 » (seul chiffre certain)
  const zero = document.querySelector('.stat--certain .stat__value');
  if (zero) {
    gsap.to(zero, {
      y: -6,
      duration: 2.6,
      repeat: -1,
      yoyo: true,
      ease: 'sine.inOut',
    });
  }
}
