/* ==========================================================================
   DST — hero.js
   Arrivée de la camionnette, traînées, thermomètre vivant, parallaxe.
   ========================================================================== */

let heroCtx = null;

export function initHero(gsap, ScrollTrigger, reduced) {
    const hero = document.querySelector('.hero');
    if (!hero) return;

    const van = hero.querySelector('[data-hero-van]');
    const stage = hero.querySelector('[data-hero-stage]');
    const lines = Array.from(hero.querySelectorAll('[data-hero-line]'));
    const reveals = Array.from(hero.querySelectorAll('[data-reveal]'));
    const trails = Array.from(hero.querySelectorAll('[data-trail]'));
    const tempEl = hero.querySelector('[data-temp-value]');
    const spark = hero.querySelector('[data-spark]');
    const badge = hero.querySelector('[data-temp-badge]');

    /* --- Logo officiel du header : apparition douce -------------------- */
    const logo = document.querySelector('.logo--header .logo__mark');
    if (logo && !reduced) {
        gsap.fromTo(logo,
            { opacity: 0, scale: .82, transformOrigin: '50% 50%' },
            { opacity: 1, scale: 1, duration: .8, delay: .1, ease: 'power3.out' });
    }

    /* --- Mouvement réduit : tout est affiché, rien ne bouge ------------ */
    if (reduced) {
        gsap.set(lines, { yPercent: 0, opacity: 1 });
        gsap.set(reveals, { clearProps: 'all', opacity: 1, y: 0 });
        if (tempEl) tempEl.textContent = '2,8';
        return;
    }

    /* --- Thermomètre vivant -------------------------------------------- */
    let tempTween = null;
    let tempValue = 2.8;

    const setTemp = (value) => {
        if (!tempEl) return;
        const txt = value.toFixed(1).replace('.', ',');
        if (window.ScrambleTextPlugin) {
            tempTween?.kill();
            tempTween = gsap.to(tempEl, {
                duration: .9,
                scrambleText: { text: txt, chars: '0123456789', speed: .35 },
                ease: 'none',
            });
        } else {
            tempEl.textContent = txt;
        }
    };

    const tickTemp = () => {
        const target = 1.9 + Math.random() * 1.7;
        const obj = { v: tempValue };
        gsap.to(obj, {
            v: target,
            duration: 1.4,
            ease: 'sine.inOut',
            onUpdate: () => {
                tempValue = obj.v;
            },
        });
        setTemp(target);
        window.setTimeout(tickTemp, 3000 + Math.random() * 2000);
    };

    if (tempEl) {
        window.setTimeout(tickTemp, 2200);
    }

    /* Sparkline : petits soubresauts réguliers */
    if (spark) {
        const original = spark.getAttribute('points');
        const wiggle = () => {
            const pts = original.split(' ').map((pair) => pair.split(','));
            const shifted = pts.map(([x, y], i) => `${x},${Math.max(3, Math.min(23, parseFloat(y) + (Math.random() * 4 - 2))) }`);
            gsap.to(spark, {
                duration: .8,
                attr: { points: shifted.join(' ') },
                ease: 'sine.inOut',
                onComplete: () => {
                    gsap.to(spark, { duration: 1.2, attr: { points: original }, ease: 'sine.inOut' });
                },
            });
            window.setTimeout(wiggle, 3400 + Math.random() * 2200);
        };
        window.setTimeout(wiggle, 2600);
    }

    /* --- Traînées lumineuses ------------------------------------------- */
    gsap.set(trails, { scaleX: 0, opacity: 0, transformOrigin: 'right center' });

    /* --- Timeline d'arrivée -------------------------------------------- */
    gsap.set(lines, { yPercent: 112, opacity: 1 });
    gsap.set(reveals, { opacity: 0, y: 24 });
    if (van) {
        gsap.set(van, { xPercent: -120, opacity: 0, rotation: -1.6, filter: 'blur(8px)' });
    }
    if (badge) {
        gsap.set(badge, { opacity: 0, y: 14, scale: .94 });
    }

    const intro = gsap.timeline({ defaults: { ease: 'power3.out' } });

    if (van) {
        intro.to(van, {
            xPercent: 0,
            opacity: 1,
            rotation: 0,
            filter: 'blur(0px)',
            duration: 1.35,
            ease: 'expo.out',
        }, 0);

        /* Freinage : léger tangage + rebond de suspension */
        intro.to(van, { rotation: -1.2, duration: .28, ease: 'power2.out' }, 1.05)
            .to(van, { rotation: 0, duration: .55, ease: 'elastic.out(1, 0.4)' }, 1.33)
            .to(van, { y: -4, duration: .22, ease: 'power2.out' }, 1.62)
            .to(van, { y: 0, duration: .7, ease: 'elastic.out(1, 0.35)' }, 1.84);
    }

    intro.to(trails, {
        scaleX: 1,
        opacity: 1,
        duration: .5,
        stagger: .1,
    }, .1)
        .to(trails, { opacity: 0, duration: .7, stagger: .08 }, .85);

    intro.to(lines, {
        yPercent: 0,
        opacity: 1,
        duration: 1.15,
        stagger: .1,
        ease: 'expo.out',
    }, .35);

    intro.to(reveals, {
        opacity: 1,
        y: 0,
        duration: .9,
        stagger: .1,
    }, .8);

    if (badge) {
        intro.to(badge, { opacity: 1, y: 0, scale: 1, duration: .8, ease: 'power3.out' }, 1.5);
    }

    /* --- Parallaxe souris ---------------------------------------------- */
    if (stage && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        const xTo = gsap.quickTo(stage, 'x', { duration: .9, ease: 'power3' });
        const yTo = gsap.quickTo(stage, 'y', { duration: .9, ease: 'power3' });
        const rTo = gsap.quickTo(stage, 'rotation', { duration: 1.1, ease: 'power3' });

        hero.addEventListener('mousemove', (e) => {
            const r = hero.getBoundingClientRect();
            const nx = (e.clientX - r.left) / r.width - .5;
            const ny = (e.clientY - r.top) / r.height - .5;
            xTo(nx * 18);
            yTo(ny * 12);
            rTo(nx * 1.4);
        });
        hero.addEventListener('mouseleave', () => {
            xTo(0);
            yTo(0);
            rTo(0);
        });
    }

    /* --- Parallaxe au scroll ------------------------------------------- */
    if (van) {
        gsap.to(van, {
            yPercent: -8,
            ease: 'none',
            scrollTrigger: {
                trigger: hero,
                start: 'top top',
                end: 'bottom top',
                scrub: true,
            },
        });
    }

    heroCtx = { gsap, intro };
}

export function refreshHero() {
    if (heroCtx && heroCtx.gsap) {
        window.ScrollTrigger?.refresh();
    }
}
