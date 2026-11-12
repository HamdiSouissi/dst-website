/* ==========================================================================
   DST — coldchain.js
   Section épinglée « La chaîne du froid » : étapes, courbe de température,
   camionnette qui progresse sur la route.
   ========================================================================== */

export function initColdChain(gsap, ScrollTrigger, reduced) {
    const section = document.querySelector('.cold');
    if (!section) return;

    const pinEl = section.querySelector('[data-cold-pin]');
    const curve = section.querySelector('[data-cold-curve]');
    const dot = section.querySelector('[data-cold-dot]');
    const van = section.querySelector('[data-cold-van]');
    const steps = Array.from(section.querySelectorAll('[data-cold-step]'));
    const proof = section.querySelector('[data-cold-proof]');
    const delivery = section.querySelector('[data-cold-delivery]');
    const tempFloat = section.querySelector('[data-cold-float]');

    if (!pinEl || !curve) return;

    const curveLength = curve.getTotalLength ? curve.getTotalLength() : 1;

    gsap.set(curve, { strokeDashoffset: 1 });
    if (proof) gsap.set(proof, { opacity: 0, y: 10 });
    if (delivery) gsap.set(delivery, { opacity: 0, y: 8 });
    if (van) gsap.set(van, { xPercent: 0 });

    let active = -1;
    const setStep = (index) => {
        if (index === active) return;
        active = index;
        steps.forEach((step, i) => {
            step.classList.toggle('is-active', i === index);
            step.setAttribute('aria-current', i === index ? 'step' : 'false');
        });
    };

    const apply = (p) => {
        const progress = Math.max(0, Math.min(1, p));

        curve.style.strokeDashoffset = String(1 - progress);

        if (dot && curveLength) {
            const point = curve.getPointAtLength(curveLength * progress);
            dot.setAttribute('cx', point.x);
            dot.setAttribute('cy', point.y);
        }

        if (van) {
            gsap.set(van, { xPercent: progress * 178 });
        }

        const stepIndex = Math.min(3, Math.floor(progress * 4));
        setStep(stepIndex);

        if (delivery) {
            const on = progress > .84;
            delivery.style.opacity = on ? '1' : '0';
            delivery.style.transform = on ? 'translateY(0)' : 'translateY(8px)';
        }

        if (proof) {
            const on = progress > .93;
            proof.classList.toggle('is-visible', on);
            proof.style.opacity = on ? '1' : '0';
            proof.style.transform = on ? 'translateY(0)' : 'translateY(10px)';
        }

        if (tempFloat) {
            tempFloat.style.opacity = progress > .05 ? '1' : '0';
        }
    };

    apply(0);
    setStep(0);

    if (reduced) {
        apply(1);
        steps.forEach((step) => step.classList.add('is-active'));
        curve.style.strokeDashoffset = '0';
        if (proof) {
            proof.classList.add('is-visible');
            proof.style.opacity = '1';
            proof.style.transform = 'none';
        }
        return;
    }

    const mm = gsap.matchMedia();

    mm.add('(min-width: 981px)', () => {
        const trigger = ScrollTrigger.create({
            trigger: pinEl,
            start: 'top top',
            end: '+=280%',
            pin: true,
            pinSpacing: true,
            scrub: true,
            anticipatePin: 1,
            invalidateOnRefresh: true,
            onUpdate: (self) => apply(self.progress),
            onRefresh: (self) => apply(self.progress),
        });

        return () => trigger.kill();
    });

    mm.add('(max-width: 980px)', () => {
        const trigger = ScrollTrigger.create({
            trigger: section,
            start: 'top 70%',
            end: 'bottom 60%',
            scrub: true,
            invalidateOnRefresh: true,
            onUpdate: (self) => apply(self.progress),
            onRefresh: (self) => apply(self.progress),
        });

        return () => trigger.kill();
    });
}
