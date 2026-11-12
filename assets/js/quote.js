/* ==========================================================================
   DST — quote.js
   Formulaire de devis multi-étapes : validation, récapitulatif, suggestion
   de véhicule, progression en forme de route, envoi fetch.
   ========================================================================== */

const STEP_NAMES = ['Votre besoin', 'Logistique', 'Vos coordonnées', 'Récapitulatif'];
const DRAFT_KEY = 'dst-quote-draft';

const VEHICLES = [
    { slug: 'caddy', name: 'Volkswagen Caddy', max: 3 },
    { slug: 'jumpy', name: 'Citroën Jumpy', max: 4 },
    { slug: 'transit', name: 'Ford Transit Custom', max: 6 },
    { slug: 'master', name: 'Renault Master', max: 10 },
];

export function initQuote(gsap, ScrollTrigger, reduced) {
    const root = document.querySelector('[data-quote]');
    if (!root) return;
    const form = root.querySelector('[data-quote-form]');
    if (!form) return;

    const section = root.closest('.quote');
    const phone = section?.getAttribute('data-phone') || '';
    const phoneHref = section?.getAttribute('data-phone-href') || '#';
    const steps = Array.from(form.querySelectorAll('[data-step]'));
    const nextBtn = form.querySelector('[data-quote-next]');
    const prevBtn = form.querySelector('[data-quote-prev]');
    const submitBtn = form.querySelector('[data-quote-submit]');
    const result = root.querySelector('[data-quote-result]');
    const resultText = root.querySelector('[data-quote-result-text]');
    const resultTitle = root.querySelector('[data-quote-result-title]');
    const resetBtn = root.querySelector('[data-quote-reset]');
    const progress = root.querySelector('.quote-progress');
    const fill = root.querySelector('[data-quote-fill]');
    const van = root.querySelector('[data-quote-van]');
    const currentLabel = root.querySelector('[data-quote-current]');
    const stepName = root.querySelector('[data-quote-stepname]');
    const announce = root.querySelector('[data-quote-announce]');
    const stops = Array.from(root.querySelectorAll('[data-quote-stop]'));
    const volume = form.querySelector('#volume');
    const volumeOutput = root.querySelector('[data-volume-output]');
    const suggestName = root.querySelector('[data-suggest-name]');
    const suggestVan = root.querySelector('[data-suggest-van]');
    const announceEl = announce;

    if (!steps.length) return;
    const total = steps.length;
    let current = 1;
    let sending = false;

    /* ------------------------------------------------------------------
       Progression
       ------------------------------------------------------------------ */
    const percentFor = (step) => ((step - 1) / (total - 1)) * 100;

    const paintProgress = () => {
        if (fill) fill.style.transform = `scaleX(${percentFor(current) / 100})`;
        if (van) van.style.left = `${percentFor(current)}%`;
        if (currentLabel) currentLabel.textContent = String(current);
        if (stepName) stepName.textContent = STEP_NAMES[current - 1] || '';
        stops.forEach((stop) => {
            const value = parseInt(stop.getAttribute('data-quote-stop'), 10);
            stop.classList.toggle('is-done', value < current);
            stop.classList.toggle('is-active', value === current);
        });
    };

    const announceStep = () => {
        if (announceEl) announceEl.textContent = `Étape ${current} sur ${total} — ${STEP_NAMES[current - 1]}`;
    };

    const showStep = (step, focus = true) => {
        current = Math.max(1, Math.min(total, step));
        steps.forEach((panel) => {
            panel.classList.toggle('is-active', parseInt(panel.getAttribute('data-step'), 10) === current);
        });
        if (prevBtn) prevBtn.hidden = current === 1;
        if (nextBtn) nextBtn.hidden = current === total;
        if (current === total) buildRecap();
        paintProgress();
        announceStep();

        if (focus) {
            const active = steps[current - 1];
            const first = active?.querySelector('input, select, textarea, button');
            if (first) {
                window.setTimeout(() => first.focus({ preventScroll: true }), 60);
            }
            if (gsap && !reduced) {
                gsap.fromTo(active, { opacity: 0, y: 14 }, { opacity: 1, y: 0, duration: .45, ease: 'power3.out' });
            }
        }
    };

    /* ------------------------------------------------------------------
       Erreurs
       ------------------------------------------------------------------ */
    const errorEl = (name) => root.querySelector(`[data-error-for="${name}"]`);
    const inputEls = (name) => Array.from(form.querySelectorAll(`[name="${name}"]`));

    const setError = (name, message) => {
        const target = errorEl(name);
        if (target) target.textContent = message;
        inputEls(name).forEach((input) => {
            input.classList.toggle('is-error', Boolean(message));
            input.setAttribute('aria-invalid', message ? 'true' : 'false');
        });
        if (name === 'goods') {
            form.querySelectorAll('input[name="goods[]"]').forEach((cb) => {
                cb.classList.toggle('is-error', Boolean(message));
            });
        }
    };

    const clearError = (name) => setError(name, '');

    const valueOf = (name) => {
        const el = form.elements.namedItem(name);
        if (!el) return '';
        if (el instanceof RadioNodeList) return el.value;
        return el.value || '';
    };

    const goods = () => Array.from(form.querySelectorAll('input[name="goods[]"]:checked')).map((cb) => cb.value);

    const validators = {
        service_type: () => (valueOf('service_type') ? '' : 'Choisissez le type de prestation qui vous intéresse.'),
        goods: () => (goods().length ? '' : 'Indiquez au moins un type de marchandises.'),
        pickup_postcode: () => (/^[0-9]{5}$/.test(valueOf('pickup_postcode').trim()) ? '' : 'Indiquez un code postal à 5 chiffres.'),
        delivery_postcode: () => (/^[0-9]{5}$/.test(valueOf('delivery_postcode').trim()) ? '' : 'Indiquez un code postal à 5 chiffres.'),
        start_date: () => {
            const v = valueOf('start_date');
            if (!v) return '';
            const d = new Date(v);
            if (Number.isNaN(d.getTime())) return 'La date souhaitée n\'est pas valide.';
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (d < today) return 'Choisissez une date à venir.';
            return '';
        },
        name: () => (valueOf('name').trim().length >= 2 ? '' : 'Indiquez votre nom et prénom.'),
        company: () => (valueOf('company').trim().length >= 2 ? '' : 'Indiquez le nom de votre entreprise.'),
        phone: () => (/^\+?[0-9 \.\-\(\)]{6,25}$/.test(valueOf('phone').trim()) ? '' : 'Indiquez un numéro pour que nous puissions vous rappeler.'),
        email: () => (/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(valueOf('email').trim()) ? '' : 'Cet email ne semble pas valide. Vérifiez la saisie.'),
        consent: () => (form.querySelector('#consent')?.checked ? '' : 'Merci de cocher cette case pour que nous puissions traiter votre demande.'),
    };

    const stepFields = {
        1: ['service_type', 'goods'],
        2: ['pickup_postcode', 'delivery_postcode', 'start_date'],
        3: ['name', 'company', 'phone', 'email', 'consent'],
        4: [],
    };

    const validateField = (name) => {
        if (!validators[name]) return true;
        const message = validators[name]();
        setError(name, message);
        return !message;
    };

    const validateStep = (step) => {
        let firstInvalid = null;
        (stepFields[step] || []).forEach((name) => {
            if (!validateField(name) && !firstInvalid) firstInvalid = name;
        });
        if (firstInvalid) {
            const el = form.querySelector(`[name="${firstInvalid}"], [name="${firstInvalid}[]"]`);
            if (el) {
                el.focus({ preventScroll: true });
                el.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
            }
            if (announceEl) announceEl.textContent = 'Certains champs demandent une correction.';
            return false;
        }
        return true;
    };

    /* Effacer les erreurs dès correction */
    form.addEventListener('input', (e) => {
        const name = e.target.name?.replace('[]', '');
        if (name && validators[name]) clearError(name);
        if (e.target.name === 'goods[]') clearError('goods');
        saveDraft();
    });
    form.addEventListener('change', (e) => {
        const name = e.target.name?.replace('[]', '');
        if (name && validators[name]) clearError(name);
        if (e.target.name === 'goods[]') clearError('goods');
        if (e.target.name === 'volume') updateVolume();
        saveDraft();
        if (current === total) buildRecap();
    });
    form.addEventListener('blur', (e) => {
        const name = e.target.name?.replace('[]', '');
        if (name && validators[name] && e.target.type !== 'checkbox' && e.target.type !== 'radio') {
            validateField(name);
        }
    }, true);

    /* ------------------------------------------------------------------
       Volume & véhicule conseillé
       ------------------------------------------------------------------ */
    const vehicleFor = (v) => VEHICLES.find((vehicle) => v <= vehicle.max) || VEHICLES[VEHICLES.length - 1];

    const buildSuggestVan = (slug) => {
        if (!suggestVan) return;
        const source = document.querySelector(`#fleet-tab-${slug} .van-svg`);
        if (!source) return;
        let html = source.outerHTML
            .replace(/id="([^"]+)"/g, 'id="$1-suggest"')
            .replace(/url\(#([^)]+)\)/g, 'url(#$1-suggest)')
            .replace(/class="van-svg[^"]*"/, 'class="van-svg van--suggest"');
        suggestVan.innerHTML = html;
    };

    const updateVolume = () => {
        if (!volume) return;
        const v = parseInt(volume.value, 10) || 1;
        if (volumeOutput) volumeOutput.textContent = v >= 10 ? '10 m³ et +' : `${v} m³`;
        const vehicle = vehicleFor(v);
        if (suggestName) suggestName.textContent = vehicle.name;
        buildSuggestVan(vehicle.slug);
    };

    volume?.addEventListener('input', updateVolume);

    /* ------------------------------------------------------------------
       Récapitulatif
       ------------------------------------------------------------------ */
    const formatDate = (value) => {
        if (!value) return 'À définir';
        const d = new Date(value);
        if (Number.isNaN(d.getTime())) return 'À définir';
        return d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' });
    };

    const buildRecap = () => {
        const set = (selector, value) => {
            const el = root.querySelector(selector);
            if (el) el.textContent = value || '—';
        };
        const v = parseInt(volume?.value || '5', 10) || 5;
        set('[data-recap-service]', valueOf('service_type'));
        set('[data-recap-goods]', goods().join(', '));
        set('[data-recap-temperature]', valueOf('temperature'));
        set('[data-recap-route]', `${valueOf('pickup_postcode') || '—'} → ${valueOf('delivery_postcode') || '—'}`);
        set('[data-recap-frequency]', valueOf('frequency'));
        set('[data-recap-date]', formatDate(valueOf('start_date')));
        set('[data-recap-volume]', `${v >= 10 ? '10 m³ et +' : `${v} m³`} · ${vehicleFor(v).name}`);
        set('[data-recap-name]', valueOf('name'));
        set('[data-recap-company]', valueOf('company'));
        set('[data-recap-phone]', valueOf('phone'));
        set('[data-recap-email]', valueOf('email'));
    };

    root.querySelectorAll('[data-goto-step]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const step = parseInt(btn.getAttribute('data-goto-step'), 10);
            showStep(step);
        });
    });

    /* ------------------------------------------------------------------
       Brouillon (sessionStorage)
       ------------------------------------------------------------------ */
    function saveDraft() {
        try {
            const draft = {
                service_type: valueOf('service_type'),
                goods: goods(),
                temperature: valueOf('temperature'),
                pickup_postcode: valueOf('pickup_postcode'),
                delivery_postcode: valueOf('delivery_postcode'),
                frequency: valueOf('frequency'),
                start_date: valueOf('start_date'),
                volume: valueOf('volume'),
                name: valueOf('name'),
                company: valueOf('company'),
                phone: valueOf('phone'),
                email: valueOf('email'),
                message: valueOf('message'),
                consent: form.querySelector('#consent')?.checked || false,
            };
            sessionStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
        } catch (e) {}
    }

    function restoreDraft() {
        let draft = null;
        try {
            draft = JSON.parse(sessionStorage.getItem(DRAFT_KEY) || 'null');
        } catch (e) {
            draft = null;
        }
        if (!draft) return;

        ['service_type', 'temperature'].forEach((name) => {
            if (draft[name]) {
                const el = form.querySelector(`input[name="${name}"][value="${CSS.escape(draft[name])}"]`);
                if (el) el.checked = true;
            }
        });
        if (Array.isArray(draft.goods)) {
            form.querySelectorAll('input[name="goods[]"]').forEach((cb) => {
                cb.checked = draft.goods.includes(cb.value);
            });
        }
        ['pickup_postcode', 'delivery_postcode', 'frequency', 'start_date', 'volume', 'name', 'company', 'phone', 'email', 'message'].forEach((name) => {
            const el = form.elements.namedItem(name);
            if (el && draft[name] !== undefined && draft[name] !== '') {
                el.value = draft[name];
            }
        });
        const consent = form.querySelector('#consent');
        if (consent && draft.consent) consent.checked = true;

        updateVolume();
    }

    /* ------------------------------------------------------------------
       Suggestion depuis la flotte
       ------------------------------------------------------------------ */
    window.addEventListener('dst:vehicle-select', (e) => {
        const vehicle = e.detail?.vehicle;
        if (!vehicle || !volume) return;
        volume.value = String(vehicle.volume);
        updateVolume();
        saveDraft();
        if (gsap && !reduced) {
            gsap.fromTo(volume, { scale: 1 }, { scale: 1.06, duration: .25, yoyo: true, repeat: 1, ease: 'power2.inOut' });
        }
    });

    /* ------------------------------------------------------------------
       Envoi
       ------------------------------------------------------------------ */
    const ensureAlert = () => {
        let alertEl = form.querySelector('[data-quote-alert]');
        if (!alertEl) {
            alertEl = document.createElement('p');
            alertEl.className = 'quote-form__alert';
            alertEl.setAttribute('role', 'alert');
            alertEl.setAttribute('data-quote-alert', '');
            const firstStep = form.querySelector('[data-step="1"]');
            if (firstStep) {
                form.insertBefore(alertEl, firstStep);
            } else {
                form.appendChild(alertEl);
            }
        }
        return alertEl;
    };

    const setLoading = (loading) => {
        if (!submitBtn) return;
        submitBtn.classList.toggle('is-loading', loading);
        submitBtn.disabled = loading;
        submitBtn.setAttribute('aria-busy', loading ? 'true' : 'false');
    };

    const showSuccess = (data) => {
        if (!result) return;
        form.hidden = true;
        form.style.display = 'none';
        result.hidden = false;
        progress?.classList.add('is-done');
        if (fill) fill.style.transform = 'scaleX(1)';
        if (van) van.style.left = '100%';
        if (resultTitle) resultTitle.textContent = 'Demande envoyée.';
        if (resultText) {
            const first = data?.first_name ? String(data.first_name).slice(0, 40) : '';
            resultText.textContent = first
                ? `Merci ${first}, nous revenons vers vous très vite. Pour une urgence : `
                : 'Merci, nous revenons vers vous très vite. Pour une urgence : ';
            const link = document.createElement('a');
            link.href = phoneHref;
            link.textContent = phone;
            resultText.appendChild(link);
            resultText.appendChild(document.createTextNode('.'));
        }
        if (announceEl) announceEl.textContent = 'Votre demande a bien été envoyée.';
        try {
            sessionStorage.removeItem(DRAFT_KEY);
        } catch (e) {}
        if (gsap && !reduced) {
            gsap.fromTo(result, { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: .6, ease: 'power3.out' });
        }
        result.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
    };

    const showFailure = (message) => {
        if (announceEl) announceEl.textContent = message;
        const alertEl = ensureAlert();
        alertEl.innerHTML = '';
        alertEl.textContent = message;
        alertEl.classList.add('is-visible');
        alertEl.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (sending) return;

        for (let s = 1; s < total; s++) {
            if (!validateStep(s)) {
                showStep(s);
                return;
            }
        }
        if (!validateStep(3)) {
            showStep(3);
            return;
        }

        sending = true;
        setLoading(true);
        form.querySelector('[data-quote-alert]')?.classList.remove('is-visible');

        try {
            const response = await fetch(form.getAttribute('action') || 'send.php', {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'fetch' },
                credentials: 'same-origin',
            });

            let data = null;
            try {
                data = await response.json();
            } catch (err) {
                data = null;
            }

            if (!data) {
                showFailure(`L'envoi n'a pas abouti. Réessayez ou appelez-nous directement au ${phone}.`);
                return;
            }

            if (data.ok) {
                showSuccess(data);
                return;
            }

            if (data.errors) {
                Object.entries(data.errors).forEach(([field, message]) => {
                    if (field === 'global') return;
                    setError(field, message);
                });
                const firstField = Object.keys(data.errors).find((f) => f !== 'global');
                if (firstField) {
                    for (let s = 1; s < total; s++) {
                        if ((stepFields[s] || []).includes(firstField)) {
                            showStep(s, false);
                            break;
                        }
                    }
                }
            }

            showFailure(data.message || data.errors?.global || `L'envoi n'a pas abouti. Réessayez ou appelez-nous directement au ${phone}.`);
        } catch (err) {
            showFailure(`L'envoi n'a pas abouti. Réessayez ou appelez-nous directement au ${phone}.`);
        } finally {
            sending = false;
            setLoading(false);
        }
    });

    resetBtn?.addEventListener('click', () => {
        try {
            sessionStorage.removeItem(DRAFT_KEY);
        } catch (e) {}
        window.location.href = '#devis';
        window.location.reload();
    });

    /* ------------------------------------------------------------------
       Navigation
       ------------------------------------------------------------------ */
    nextBtn?.addEventListener('click', () => {
        if (!validateStep(current)) return;
        showStep(current + 1);
    });

    prevBtn?.addEventListener('click', () => showStep(current - 1));

    form.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter') return;
        if (e.target instanceof HTMLTextAreaElement) return;
        if (e.target instanceof HTMLButtonElement) return;
        if (current < total) {
            e.preventDefault();
            nextBtn?.click();
        }
    });

    /* ------------------------------------------------------------------
       Initialisation
       ------------------------------------------------------------------ */
    restoreDraft();
    updateVolume();
    showStep(1, false);
}
