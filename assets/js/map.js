/* ==========================================================================
   DST — map.js
   Carte schématique de l'Île-de-France : dessin des départements,
   routes depuis Arcueil, survol / focus / toucher.
   ========================================================================== */

export function initMap(gsap, ScrollTrigger, reduced) {
    const map = document.querySelector('[data-zone-map]');
    if (!map) return;

    const petals = Array.from(map.querySelectorAll('.zone__petal[data-code]'));
    const routes = Array.from(map.querySelectorAll('.zone__route'));
    const tooltip = map.querySelector('[data-zone-tooltip]');
    const tooltipName = map.querySelector('[data-zone-tooltip-name]');
    const tooltipDelay = map.querySelector('[data-zone-tooltip-delay]');
    const list = document.querySelectorAll('[data-zone-item]');

    if (!petals.length) return;

    const zones = {};
    list.forEach((li) => {
        zones[li.getAttribute('data-zone-item')] = {
            name: li.getAttribute('data-zone-name') || '',
            delay: li.getAttribute('data-zone-delay') || '',
            el: li,
        };
    });

    /* --- Dessin d'entrée ----------------------------------------------- */
    if (!reduced) {
        petals.forEach((petal) => {
            try {
                const length = petal.getTotalLength();
                gsap.set(petal, { strokeDasharray: length, strokeDashoffset: length, fillOpacity: 0 });
            } catch (e) {
                gsap.set(petal, { opacity: 0 });
            }
        });
        gsap.set(routes, { opacity: 0 });

        const tl = gsap.timeline({
            scrollTrigger: { trigger: map, start: 'top 78%', once: true },
        });

        tl.to(petals, {
            strokeDashoffset: 0,
            fillOpacity: 1,
            duration: .9,
            stagger: .07,
            ease: 'power2.inOut',
        }, 0)
            .to(routes, { opacity: .9, duration: .7, stagger: .06 }, .5)
            .to({}, { duration: .15 });
    }

    /* --- Info-bulle ----------------------------------------------------- */
    let pinned = null;

    const positionTooltip = (petal) => {
        const mapRect = map.getBoundingClientRect();
        const rect = petal.getBoundingClientRect();
        if (!tooltip) return;
        const left = rect.left + rect.width / 2 - mapRect.left;
        const top = rect.top - mapRect.top;
        tooltip.style.left = `${Math.max(70, Math.min(mapRect.width - 70, left))}px`;
        tooltip.style.top = `${top}px`;
    };

    const show = (petal) => {
        const code = petal.getAttribute('data-code');
        const zone = zones[code];
        if (!zone || !tooltip) return;
        if (tooltipName) tooltipName.textContent = `${zone.name} (${code})`;
        if (tooltipDelay) tooltipDelay.textContent = zone.delay;
        positionTooltip(petal);
        tooltip.classList.add('is-visible');
        tooltip.setAttribute('aria-hidden', 'false');
        petals.forEach((p) => p.classList.toggle('is-active', p === petal));
        list.forEach((li) => li.classList.toggle('is-active', li.getAttribute('data-zone-item') === code));
    };

    const hide = () => {
        if (!tooltip) return;
        tooltip.classList.remove('is-visible');
        tooltip.setAttribute('aria-hidden', 'true');
        petals.forEach((p) => p.classList.remove('is-active'));
        list.forEach((li) => li.classList.remove('is-active'));
        pinned = null;
    };

    petals.forEach((petal) => {
        petal.addEventListener('mouseenter', () => show(petal));
        petal.addEventListener('mouseleave', () => {
            if (!pinned) hide();
        });
        petal.addEventListener('focus', () => show(petal));
        petal.addEventListener('blur', () => hide());
        petal.addEventListener('click', (e) => {
            e.stopPropagation();
            if (pinned === petal) {
                hide();
            } else {
                pinned = petal;
                show(petal);
            }
        });
        petal.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                petal.click();
            }
        });
    });

    list.forEach((li) => {
        const code = li.getAttribute('data-zone-item');
        const petal = petals.find((p) => p.getAttribute('data-code') === code);
        if (!petal) return;
        li.addEventListener('mouseenter', () => {
            petal.classList.add('is-active');
            li.classList.add('is-active');
        });
        li.addEventListener('mouseleave', () => {
            if (!pinned) {
                petal.classList.remove('is-active');
                li.classList.remove('is-active');
            }
        });
        li.addEventListener('click', () => {
            petal.focus();
        });
    });

    map.addEventListener('click', (e) => {
        if (!e.target.closest('.zone__petal')) hide();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') hide();
    });

    window.addEventListener('resize', () => {
        const active = petals.find((p) => p.classList.contains('is-active'));
        if (active) positionTooltip(active);
    });
}
