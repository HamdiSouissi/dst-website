/**
 * circuit.js — comparaison des deux circuits.
 *
 * Une tomate part en même temps sur les deux lignes. Côté classique, elle
 * s'arrête à chaque étape : la jauge de fraîcheur baisse, « +1 manipulation »
 * apparaît, la tomate se ternit. Côté DST, elle monte dans la camionnette et
 * arrive bien plus tôt, toujours à 100 %.
 */
export default function initCircuit(DST) {
  const section = document.getElementById('circuit');
  if (!section) return;
  const rows = {
    classic: section.querySelector('[data-row="classic"]'),
    direct: section.querySelector('[data-row="direct"]'),
  };
  const FRESH = [100, 92, 85, 79, 74];

  const st = {
    classic: {
      traveler: rows.classic?.querySelector('[data-traveler="classic"]'),
      steps: rows.classic ? Array.from(rows.classic.querySelectorAll('.circuit__step')) : [],
    },
    direct: {
      traveler: rows.direct?.querySelector('[data-traveler="direct"]'),
      steps: rows.direct ? Array.from(rows.direct.querySelectorAll('.circuit__step')) : [],
    },
  };

  const q = (el, sel) => el?.querySelector(sel);

  const positions = (which) => {
    const row = rows[which];
    const traveler = st[which].traveler;
    const lane = traveler.closest('.circuit__lane');
    const laneRect = lane.getBoundingClientRect();
    return st[which].steps.map((s) => {
      const dot = s.querySelector('.circuit__dot') || s.querySelector('.circuit__van');
      const r = dot.getBoundingClientRect();
      return r.left + r.width / 2 - laneRect.left;
    });
  };

  const setGauge = (which, value, immediate = false) => {
    const val = q(st[which].traveler, '[data-gauge-val]');
    const fill = q(st[which].traveler, '[data-gauge-fill]');
    if (immediate) {
      if (val) val.textContent = Math.round(value) + ' %';
      if (fill) fill.style.transform = `scaleX(${value / 100})`;
    }
  };

  const reset = () => {
    ['classic', 'direct'].forEach((which) => {
      const t = st[which].traveler;
      if (!t) return;
      t.classList.remove('circuit__traveler--dim');
      setGauge(which, 100, true);
      gsap.set(t, { clearProps: 'transform' });
      const img = t.querySelector('img');
      if (img) img.style.filter = '';
    });
    section.querySelectorAll('.circuit__manip').forEach((m) => gsap.set(m, { opacity: 0, y: 6 }));
  };

  const finalState = () => {
    const c = st.classic;
    const d = st.direct;
    if (c.traveler) {
      gsap.set(c.traveler, { x: () => positions('classic').slice(-1)[0] });
      setGauge('classic', 74, true);
      c.traveler.classList.add('circuit__traveler--dim');
      const img = c.traveler.querySelector('img');
      if (img) img.style.filter = 'saturate(.5) brightness(.97)';
    }
    if (d.traveler) {
      const lane = d.traveler.closest('.circuit__lane');
      gsap.set(d.traveler, { x: () => lane.clientWidth - d.traveler.offsetWidth - 8 });
      setGauge('direct', 100, true);
    }
    section.querySelectorAll('.circuit__manip').forEach((m) => gsap.set(m, { opacity: 1, y: 0 }));
  };

  const play = () => {
    if (!st.classic.traveler || !st.direct.traveler) return;
    reset();
    if (DST.reduced || !window.gsap) {
      finalState();
      return;
    }

    const cm = positions('classic');
    const dm = positions('direct');
    gsap.set(st.classic.traveler, { x: cm[0] });
    gsap.set(st.direct.traveler, { x: dm[0] });

    const tl = gsap.timeline({ defaults: { ease: 'power2.inOut' } });

    // circuit DST : un seul trajet, arrive bien plus tôt
    const directLane = st.direct.traveler.closest('.circuit__lane');
    tl.to(
      st.direct.traveler,
      {
        x: () => directLane.clientWidth - st.direct.traveler.offsetWidth - 8,
        duration: 2.1,
        ease: 'power2.inOut',
      },
      0.1
    );
    const vanBox = rows.direct.querySelector('.circuit__van');
    if (vanBox) tl.from(vanBox, { scale: 0.75, opacity: 0, duration: 0.7, ease: 'back.out(1.8)' }, 0.15);

    // circuit classique : 4 arrêts, la fraîcheur baisse à chaque manipulation
    const stops = cm.slice(1);
    stops.forEach((pos, i) => {
      const arrival = 0.55 + i * 0.62;
      tl.to(st.classic.traveler, { x: pos, duration: 0.46 }, arrival);
      tl.to(
        { v: FRESH[i] },
        {
          v: FRESH[i + 1],
          duration: 0.36,
          onUpdate() {
            const val = q(st.classic.traveler, '[data-gauge-val]');
            const fill = q(st.classic.traveler, '[data-gauge-fill]');
            const v = this.targets()[0].v;
            if (val) val.textContent = Math.round(v) + ' %';
            if (fill) fill.style.transform = `scaleX(${v / 100})`;
          },
        },
        arrival + 0.4
      );
      const manip = st.classic.steps[i + 1]?.querySelector('.circuit__manip');
      if (manip) tl.to(manip, { opacity: 1, y: 0, duration: 0.3 }, arrival + 0.38);
      const img = st.classic.traveler.querySelector('img');
      if (img) {
        tl.to(
          img,
          {
            filter: `saturate(${(1 - (i + 1) * 0.15).toFixed(2)}) brightness(${(1 - (i + 1) * 0.03).toFixed(2)})`,
            duration: 0.4,
          },
          arrival + 0.38
        );
      }
      if (i === stops.length - 1) {
        tl.call(() => st.classic.traveler.classList.add('circuit__traveler--dim'), null, arrival + 0.55);
      }
    });
  };

  // bouton Rejouer
  section.querySelector('[data-circuit-replay]')?.addEventListener('click', (e) => {
    e.preventDefault();
    play();
  });

  if (DST.reduced || !window.gsap) {
    finalState();
    return;
  }

  // déclenchement au scroll (une fois par passage)
  ScrollTrigger.create({
    trigger: section.querySelector('.circuit__board'),
    start: 'top 70%',
    onEnter: play,
    onLeaveBack: () => {
      reset();
    },
  });
}
