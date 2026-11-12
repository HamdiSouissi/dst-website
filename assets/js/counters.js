/* ==========================================================================
   DST — counters.js
   Compteurs à rouleaux, façon compteur kilométrique.
   ========================================================================== */

export function initCounters(gsap, ScrollTrigger, reduced) {
    const values = document.querySelectorAll('[data-odometer]');

    values.forEach((el) => {
        const raw = el.getAttribute('data-odometer');
        const value = parseInt(raw, 10);
        if (Number.isNaN(value)) return;

        const formatted = new Intl.NumberFormat('fr-FR').format(value).replace(/\u202f|\u00a0/g, ' ');
        const parent = el.closest('.hstat__value, .trust-stat__value');
        if (parent) parent.setAttribute('aria-label', formatted);

        const wrap = document.createElement('span');
        wrap.className = 'odo';
        wrap.setAttribute('aria-hidden', 'true');

        const columns = [];

        Array.from(formatted).forEach((char) => {
            if (/\d/.test(char)) {
                const col = document.createElement('span');
                col.className = 'odo__col';
                const strip = document.createElement('span');
                strip.className = 'odo__strip';
                for (let n = 0; n <= 9; n++) {
                    const digit = document.createElement('span');
                    digit.textContent = String(n);
                    strip.appendChild(digit);
                }
                col.appendChild(strip);
                wrap.appendChild(col);
                columns.push({ strip, digit: parseInt(char, 10) });
            } else {
                const sep = document.createElement('span');
                sep.className = 'odo__sep';
                sep.textContent = char;
                wrap.appendChild(sep);
            }
        });

        el.textContent = '';
        el.appendChild(wrap);

        if (!columns.length) return;

        if (reduced) {
            columns.forEach((c) => gsap.set(c.strip, { yPercent: -c.digit * 10 }));
            return;
        }

        gsap.set(columns.map((c) => c.strip), { yPercent: 0 });

        ScrollTrigger.create({
            trigger: el,
            start: 'top 88%',
            once: true,
            onEnter: () => {
                columns.forEach((c, i) => {
                    gsap.to(c.strip, {
                        yPercent: -c.digit * 10,
                        duration: 1.2 + i * .08,
                        delay: i * .08,
                        ease: 'expo.out',
                    });
                });
            },
        });
    });
}
