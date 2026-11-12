/* ==========================================================================
   DST — coldchain.js
   Section épinglée « La chaîne du froid » : étapes, courbe de température,
   petite camionnette qui suit la courbe de température.
   ========================================================================== */

export function positionColdCurveVan(progress = 1) {
    const curve = document.querySelector('[data-cold-curve]');
    const van = document.querySelector('[data-cold-van]');
    if (!curve || !van || !curve.getPointAtLength) return;

    const length = curve.getTotalLength();
    const p = Math.max(0, Math.min(1, progress));
    const at = (t) => curve.getPointAtLength(length * Math.max(0, Math.min(1, t)));
    const point = at(p);
    const before = at(p - .006);
    const after = at(p + .006);
    const angle = Math.atan2(after.y - before.y, after.x - before.x) * 180 / Math.PI;
    const tilt = Math.max(-12, Math.min(12, angle));
    van.setAttribute('transform', `translate(${point.x.toFixed(2)} ${point.y.toFixed(2)}) rotate(${tilt.toFixed(2)})`);
}

export function initColdChain(gsap, ScrollTrigger, reduced) {
    const section = document.querySelector('.cold');
    if (!section) return;

    const pinEl = section.querySelector('[data-cold-pin]');
    const curve = section.querySelector('[data-cold-curve]');
    const dot = section.querySelector('[data-cold-dot]');
    const steps = Array.from(section.querySelectorAll('[data-cold-step]'));
    const proof = section.querySelector('[data-cold-proof]');
    const delivery = section.querySelector('[data-cold-delivery]');
    const tempFloat = section.querySelector('[data-cold-float]');

    if (!pinEl || !curve) return;

    const curveLength = curve.getTotalLength ? curve.getTotalLength() : 1;

    gsap.set(curve, { strokeDashoffset: 1 });
    if (proof) gsap.set(proof, { opacity: 0, y: 10 });
    if (delivery) gsap.set(delivery, { opacity: 0, y: 8 });
    positionColdCurveVan(0);

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

        positionColdCurveVan(progress);

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
