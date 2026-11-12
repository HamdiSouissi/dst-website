/**
 * fleet.js — comparateur de gabarits.
 * Le curseur de volume allume le véhicule adapté ; un clic mémorise le choix
 * pour le formulaire de devis (CustomEvent « dst:vehicle »).
 */
export default function initFleet(DST) {
  const section = document.getElementById('flotte');
  if (!section) return;
  const slider = section.querySelector('[data-volume]');
  const out = section.querySelector('[data-volume-out]');
  const items = DST.$$('.fleet__item', section);
  const vehicles = DST.data.vehicles || [];
  if (!items.length || !vehicles.length) return;

  const pick = (volume) => {
    let chosen = vehicles.find((v) => volume >= v.min && volume <= v.max);
    if (!chosen) chosen = volume < vehicles[0].min ? vehicles[0] : vehicles[vehicles.length - 1];
    return chosen;
  };

  const light = (slug) => {
    items.forEach((item) => {
      const on = item.dataset.vehicle === slug;
      item.classList.toggle('is-lit', on);
      item.querySelector('[data-vehicle-btn]')?.setAttribute('aria-pressed', String(on));
    });
  };

  const fmt = (value) => String(value).replace('.', ',');

  const update = (fromUser = false) => {
    const value = Number(slider.value);
    if (out) out.textContent = `${fmt(value)} m³`;
    const chosen = pick(value);
    light(chosen.slug);
    if (fromUser) {
      sessionStorage.setItem('dst_volume', String(value));
      window.dispatchEvent(new CustomEvent('dst:vehicle', { detail: { slug: chosen.slug, name: chosen.name, volume: value } }));
    }
  };

  slider?.addEventListener('input', () => update(true));

  // reprise d'un volume mémorisé
  const savedVolume = Number(sessionStorage.getItem('dst_volume') || 0);
  if (savedVolume >= 1 && savedVolume <= 10 && slider) {
    slider.value = String(savedVolume);
  }
  update(false);

  items.forEach((item) => {
    item.querySelector('[data-vehicle-btn]')?.addEventListener('click', () => {
      const slug = item.dataset.vehicle;
      const v = vehicles.find((x) => x.slug === slug);
      if (!v) return;
      light(slug);
      if (slider) {
        const next = Math.min(10, Math.max(1, (v.min + Math.min(v.max, 10)) / 2));
        slider.value = String(Math.round(next * 2) / 2);
        if (out) out.textContent = `${fmt(slider.value)} m³`;
      }
      sessionStorage.setItem('dst_vehicle', slug);
      sessionStorage.setItem('dst_volume', slider ? String(slider.value) : String(v.min));
      window.dispatchEvent(
        new CustomEvent('dst:vehicle', { detail: { slug, name: v.name, volume: Number(slider?.value || v.min) } })
      );
      const chat = document.querySelector('[data-chat]');
      if (chat) {
        chat.classList.add('is-notified');
        window.setTimeout(() => chat.classList.remove('is-notified'), 2400);
      }
    });
  });

  if (window.gsap && !DST.reduced) {
    const list = section.querySelector('[data-fleet-list]');
    if (list) {
      gsap.from(items, {
        x: -30,
        opacity: 0,
        duration: 0.8,
        stagger: 0.08,
        ease: 'power3.out',
        scrollTrigger: { trigger: list, start: 'top 85%', once: true },
        clearProps: 'opacity,transform',
      });
    }
  }
}
