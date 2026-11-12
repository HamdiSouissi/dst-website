/* ==========================================================================
   DST — fleet.js
   Showroom de la flotte : sortie / entrée des camionnettes, Flip visuel,
   fiche technique en ScrambleText, onglets, clavier et glisser.
   ========================================================================== */

export function initFleet(gsap, ScrollTrigger, reduced) {
    const root = document.querySelector('[data-fleet]');
    if (!root) return;

    const dataEl = document.getElementById('fleet-data');
    if (!dataEl) return;

    let vehicles = [];
    try {
        vehicles = JSON.parse(dataEl.textContent || '[]');
    } catch (e) {
        return;
    }
    if (!vehicles.length) return;

    const stage = root.querySelector('[data-fleet-stage]');
    const vanHolder = root.querySelector('[data-fleet-van]');
    const nameEl = root.querySelector('[data-fleet-name]');
    const tagEl = root.querySelector('[data-fleet-tag]');
    const gabaritEl = root.querySelector('[data-fleet-gabarit]');
    const volumeEl = root.querySelector('[data-fleet-volume]');
    const chargeEl = root.querySelector('[data-fleet-charge]');
    const usageEl = root.querySelector('[data-fleet-usage]');
    const gaugeEl = root.querySelector('[data-fleet-gauge]');
    const counterEl = root.querySelector('[data-fleet-counter]');
    const tabs = Array.from(root.querySelectorAll('[data-fleet-tab]'));
    const indicator = root.querySelector('[data-fleet-indicator]');
    const prevBtn = root.querySelector('[data-fleet-prev]');
    const nextBtn = root.querySelector('[data-fleet-next]');

    let index = 0;
    let busy = false;

    const pad = (n) => String(n).padStart(2, '0');

    const positionIndicator = () => {
        const tab = tabs[index];
        if (!tab || !indicator) return;
        indicator.style.width = `${tab.offsetWidth}px`;
        indicator.style.transform = `translateX(${tab.offsetLeft}px)`;
    };

    const vanMarkup = (targetIndex) => {
        const source = tabs[targetIndex]?.querySelector('.van-svg');
        if (!source) return '';
        let html = source.outerHTML;
        html = html
            .replace(/id="([^"]+)"/g, 'id="$1-stage"')
            .replace(/url\(#([^)]+)\)/g, 'url(#$1-stage)')
            .replace(/class="van-svg van--tab"/, 'class="van-svg van--fleet"')
            .replace(/class="van-svg([^"]*)van--tab([^"]*)"/, 'class="van-svg$1van--fleet$2"');
        return html;
    };

    const setSpecs = (v) => {
        if (!reduced && window.ScrambleTextPlugin) {
            const scramble = (el, text, chars) => {
                if (!el) return;
                gsap.to(el, {
                    duration: .8,
                    scrambleText: { text, chars, speed: .5 },
                    ease: 'none',
                });
            };
            scramble(volumeEl, v.volume_label, '0123456789m³~');
            scramble(chargeEl, v.charge, '0123456789kg~ ');
            scramble(counterEl, `${pad(index + 1)} / 0${vehicles.length}`, '0123456789/ ');
        } else {
            if (volumeEl) volumeEl.textContent = v.volume_label;
            if (chargeEl) chargeEl.textContent = v.charge;
            if (counterEl) counterEl.textContent = `${pad(index + 1)} / 0${vehicles.length}`;
        }
        if (gabaritEl) gabaritEl.textContent = v.gabarit;
        if (usageEl) usageEl.textContent = v.usage;
        if (tagEl) tagEl.textContent = v.tag;
        if (gaugeEl) gaugeEl.style.width = `${Math.min(100, (v.volume / 10) * 100)}%`;
    };

    const setActiveTab = () => {
        tabs.forEach((tab, i) => {
            const active = i === index;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-pressed', String(active));
        });
        positionIndicator();
    };

    const swapContent = (nextIndex) => {
        const v = vehicles[nextIndex];
        const markup = vanMarkup(nextIndex);
        if (vanHolder && markup) {
            vanHolder.innerHTML = markup;
        }
        setSpecs(v);
        if (nameEl) nameEl.textContent = v.name;
    };

    const go = (next, direction = 1) => {
        const target = (next + vehicles.length) % vehicles.length;
        if (target === index || busy) return;
        busy = true;

        const dir = target > index ? 1 : -1;
        const travel = 160;

        if (reduced) {
            index = target;
            swapContent(index);
            setActiveTab();
            busy = false;
            return;
        }

        const outgoing = vanHolder;
        const name = nameEl;

        gsap.to(outgoing, {
            x: dir * travel,
            opacity: 0,
            filter: 'blur(7px)',
            duration: .42,
            ease: 'power2.in',
            onComplete: () => {
                index = target;
                swapContent(index);
                setActiveTab();

                if (name) {
                    gsap.fromTo(name, { opacity: .25, x: dir * 30 }, { opacity: 1, x: 0, duration: .6, ease: 'power3.out' });
                }

                gsap.fromTo(outgoing,
                    { x: -dir * travel * 1.2, opacity: 0, filter: 'blur(7px)', rotation: dir * 1.4 },
                    {
                        x: 0,
                        opacity: 1,
                        filter: 'blur(0px)',
                        rotation: 0,
                        duration: .95,
                        ease: 'expo.out',
                        onComplete: () => {
                            gsap.fromTo(outgoing, { y: -3 }, { y: 0, duration: .55, ease: 'elastic.out(1, .35)' });
                            busy = false;
                        },
                    }
                );
            },
        });

        /* Petites traînées de mouvement */
        if (stage && !stage.querySelector('.fleet__trail')) {
            for (let i = 0; i < 3; i++) {
                const trail = document.createElement('span');
                trail.className = 'fleet__trail';
                trail.style.top = `${52 + i * 5}%`;
                stage.appendChild(trail);
                gsap.fromTo(trail,
                    { scaleX: 0, opacity: .7, transformOrigin: dir > 0 ? 'left center' : 'right center' },
                    {
                        scaleX: 1,
                        opacity: 0,
                        duration: .8,
                        delay: i * .05,
                        ease: 'power2.out',
                        onComplete: () => trail.remove(),
                    }
                );
            }
        }
    };

    tabs.forEach((tab, i) => {
        tab.addEventListener('click', () => {
            if (i === index) return;
            go(i, i > index ? 1 : -1);
        });
        tab.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                const next = (i + 1) % tabs.length;
                tabs[next].focus();
                go(next, 1);
            }
            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                const prev = (i - 1 + tabs.length) % tabs.length;
                tabs[prev].focus();
                go(prev, -1);
            }
            if (e.key === 'Home') {
                e.preventDefault();
                tabs[0].focus();
                go(0, -1);
            }
            if (e.key === 'End') {
                e.preventDefault();
                tabs[tabs.length - 1].focus();
                go(tabs.length - 1, 1);
            }
        });
    });

    prevBtn?.addEventListener('click', () => go(index - 1, -1));
    nextBtn?.addEventListener('click', () => go(index + 1, 1));

    /* Glisser / swipe */
    if (stage) {
        let startX = null;
        let startY = null;
        stage.addEventListener('pointerdown', (e) => {
            if (e.pointerType === 'mouse' && e.button !== 0) return;
            if (e.target.closest('a, button')) return;
            startX = e.clientX;
            startY = e.clientY;
        });
        stage.addEventListener('pointerup', (e) => {
            if (startX === null) return;
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            startX = null;
            startY = null;
            if (Math.abs(dx) > 46 && Math.abs(dx) > Math.abs(dy)) {
                go(index + (dx < 0 ? 1 : -1), dx < 0 ? 1 : -1);
            }
        });
        stage.addEventListener('pointercancel', () => {
            startX = null;
            startY = null;
        });
    }

    /* Navigation depuis la fiche vers le formulaire */
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-vehicle-cta]')) return;
        window.dispatchEvent(new CustomEvent('dst:vehicle-select', {
            detail: { vehicle: vehicles[index] },
        }));
    });

    window.addEventListener('resize', positionIndicator);
    if (window.ResizeObserver) {
        new ResizeObserver(positionIndicator).observe(root);
    }
    window.addEventListener('load', positionIndicator);
    requestAnimationFrame(positionIndicator);
    document.fonts?.ready.then(positionIndicator).catch(() => {});

    setActiveTab();
    ScrollTrigger.create({
        trigger: root,
        start: 'top 80%',
        once: true,
        onEnter: () => {
            if (!reduced) {
                gsap.from(root.querySelectorAll('.fleet-tab'), {
                    opacity: 0,
                    y: 16,
                    duration: .7,
                    stagger: .08,
                    ease: 'power3.out',
                });
            }
        },
    });
}
