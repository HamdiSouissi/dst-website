/* ==========================================================================
   DST — route.js
   La route lumineuse qui relie toute la page + le point mobile au scroll.
   ========================================================================== */

export function initRoute(gsap, ScrollTrigger, reduced) {
    const layer = document.querySelector('[data-route-layer]');
    if (!layer) return;
    const path = layer.querySelector('[data-route-path]');
    const dot = layer.querySelector('[data-route-dot]');
    if (!path || !dot) return;

    if (reduced) {
        path.style.strokeDashoffset = '0';
        dot.style.top = '100%';
        dot.style.left = '50%';
        return;
    }

    const length = path.getTotalLength();

    const update = (progress) => {
        const p = Math.max(0, Math.min(1, progress));
        path.style.strokeDashoffset = String(1 - p);

        const point = path.getPointAtLength(length * p);
        dot.style.left = `${(point.x / 100) * 100}%`;
        dot.style.top = `${(point.y / 1000) * 100}%`;
    };

    update(0);

    ScrollTrigger.create({
        start: 0,
        end: 'max',
        onUpdate: (self) => update(self.progress),
        onRefresh: (self) => update(self.progress),
    });
}
