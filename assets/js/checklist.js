/**
 * checklist.js — bon de livraison : les cases se cochent au scroll,
 * puis la signature se dessine (DrawSVG).
 */
export default function initChecklist(DST) {
  const doc = document.querySelector('[data-bl]');
  if (!doc) return;
  const lines = DST.$$('[data-bl-line]', doc);
  const boxes = lines.map((l) => l.querySelector('.bl__box path'));
  const signature = doc.querySelector('[data-sign-path]');

  if (DST.reduced || !window.gsap) {
    boxes.forEach((p) => p && (p.style.strokeDashoffset = '0'));
    if (signature) signature.style.strokeDashoffset = '0';
    return;
  }

  gsap.set(boxes, { strokeDashoffset: 26 });
  if (signature) gsap.set(signature, { strokeDashoffset: 900 });

  const tl = gsap.timeline({ scrollTrigger: { trigger: doc, start: 'top 72%', once: true } });

  tl.from(doc, { y: 50, opacity: 0, rotate: -2.2, duration: 0.9, ease: 'power3.out', clearProps: 'transform,opacity' });

  lines.forEach((line, i) => {
    const at = 0.45 + i * 0.26;
    tl.to(boxes[i], { strokeDashoffset: 0, duration: 0.45, ease: 'power2.inOut' }, at)
      .fromTo(line.querySelector('.bl__box'), { scale: 0.85 }, { scale: 1, duration: 0.35, ease: 'back.out(2.2)' }, at)
      .fromTo(
        line,
        { backgroundColor: 'rgba(245,220,74,0)' },
        { backgroundColor: 'rgba(245,220,74,.24)', duration: 0.3, yoyo: true, repeat: 1, ease: 'sine.inOut' },
        at
      );
  });

  if (signature) {
    tl.to(signature, { strokeDashoffset: 0, duration: 1.5, ease: 'power1.inOut' }, '-=0.15');
    tl.from('.bl__sign-label', { opacity: 0, y: 8, duration: 0.4 }, '-=0.5');
  }
}
