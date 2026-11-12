/**
 * chat-form.js — formulaire conversationnel.
 *
 * - une question à la fois, précédée de « DST écrit… » (600 ms) ;
 * - réponse recopiée dans le fil en bulle basilic ;
 * - récapitulatif modifiable, envoi fetch POST vers send.php ;
 * - tampon sticker + coche au succès ;
 * - alternative « formulaire classique » sur le même endpoint ;
 * - réponses conservées en sessionStorage ; conversion propre du véhicule
 *   choisi dans le comparateur de gabarits.
 */
const STEPS = 7;

export default function initChatForm(DST) {
  const root = document.querySelector('[data-chat]');
  if (!root) return;

  const log = root.querySelector('[data-chat-log]');
  const typing = root.querySelector('[data-chat-typing]');
  const stepsRoot = root.querySelector('[data-steps]');
  const steps = DST.$$('.step', stepsRoot);
  const form = root.querySelector('[data-chat-form]');
  const classic = root.querySelector('[data-classic]');
  const classicForm = root.querySelector('[data-classic-form]');
  const stamp = root.querySelector('[data-stamp]');
  const recap = root.querySelector('[data-recap]');
  const restart = root.querySelector('[data-chat-restart]');
  const vehicles = DST.data.vehicles || [];
  const postcodes = DST.data.postcodes || ['75', '77', '78', '91', '92', '93', '94', '95'];
  const data = {};
  let current = 0;
  let busy = false;
  let startedAt = Date.now();

  const store = {
    save() {
      try {
        sessionStorage.setItem('dst_chat', JSON.stringify(data));
      } catch (e) {
        /* stockage indisponible : on continue */
      }
    },
    load() {
      try {
        return JSON.parse(sessionStorage.getItem('dst_chat') || '{}');
      } catch (e) {
        return {};
      }
    },
    clear() {
      try {
        sessionStorage.removeItem('dst_chat');
      } catch (e) {
        /* noop */
      }
    },
  };

  /* ---------------- helpers visuels ---------------- */

  const scrollLog = () => {
    log.scrollTop = log.scrollHeight;
  };

  const bubble = (text, kind = 'dst', html = false) => {
    const el = document.createElement('div');
    el.className = `chat__msg chat__msg--${kind}`;
    const p = document.createElement('p');
    if (html) p.innerHTML = text;
    else p.textContent = text;
    el.appendChild(p);
    log.appendChild(el);
    scrollLog();
    return el;
  };

  const waitTyping = (ms = 600) =>
    new Promise((resolve) => {
      typing.hidden = false;
      scrollLog();
      window.setTimeout(() => {
        typing.hidden = true;
        resolve();
      }, DST.reduced ? 120 : ms);
    });

  const showStep = (n) => {
    steps.forEach((s) => {
      const active = Number(s.dataset.step) === n;
      s.hidden = !active;
      s.classList.toggle('is-active', active);
      if (active) {
        s.querySelector('input, textarea, button')?.focus({ preventScroll: true });
      }
    });
    current = n;
    scrollLog();
  };

  const question = (n) => steps[n - 1]?.dataset.question || '';

  const ask = async (n, extra = '') => {
    showStep(n);
    await waitTyping();
    bubble(question(n) + (extra ? ' ' + extra : ''), 'dst');
    if (n === 5) volumeHint(true);
  };

  /* ---------------- volume & véhicule ---------------- */

  const volumeVehicle = (volume) => {
    let chosen = vehicles.find((v) => volume >= v.min && volume <= v.max);
    if (!chosen) chosen = volume < (vehicles[0]?.min ?? 1) ? vehicles[0] : vehicles[vehicles.length - 1];
    return chosen;
  };

  const volumeHint = (silent = false) => {
    const range = root.querySelector('.step[data-step="5"] [data-volume]');
    const out = root.querySelector('[data-volume-live]');
    const hint = root.querySelector('[data-volume-vehicle]');
    if (!range) return;
    const value = Number(range.value);
    if (out) out.textContent = `${String(value).replace('.', ',')} m³`;
    const v = volumeVehicle(value);
    if (!v) return;
    if (hint) hint.innerHTML = `Le <b>${v.name}</b> (${v.volume}) devrait convenir.`;
    if (!silent) {
      const last = root.querySelector('[data-volume-answer]');
      if (last) last.remove();
      const el = bubble(`Parfait, un ${v.name} devrait convenir pour ${String(value).replace('.', ',')} m³.`, 'note');
      el.dataset.volumeAnswer = '1';
    }
  };

  /* ---------------- validation ---------------- */

  const pcOk = (v) => /^\d{5}$/.test(v);

  const validate = (n) => {
    const step = steps[n - 1];
    if (!step) return true;
    const error = step.querySelector('[data-error]');
    if (error) error.hidden = true;
    if (n === 3) {
      const from = root.querySelector('#pc_from').value.trim();
      const to = root.querySelector('#pc_to').value.trim();
      if (!pcOk(from) || !pcOk(to)) {
        if (error) {
          error.textContent = 'Indiquez deux codes postaux à 5 chiffres.';
          error.hidden = false;
        }
        return false;
      }
      const out = root.querySelector('[data-outzone]');
      const outFrom = !postcodes.includes(from.slice(0, 2));
      const outTo = !postcodes.includes(to.slice(0, 2));
      if (out) {
        out.hidden = !(outFrom || outTo);
        out.textContent = outFrom || outTo
          ? 'C\'est en dehors de l\'Île-de-France : précisez-le dans le message, on étudie volontiers.'
          : '';
      }
      data.pc_from = from;
      data.pc_to = to;
      return true;
    }
    if (n === 6) {
      const name = root.querySelector('#q_name').value.trim();
      const company = root.querySelector('#q_company').value.trim();
      const phone = root.querySelector('#q_phone').value.trim();
      const email = root.querySelector('#q_email').value.trim();
      const consent = root.querySelector('.step[data-step="6"] input[name="consent"]').checked;
      const issues = [];
      if (name.length < 2) issues.push('nom');
      if (company.length < 2) issues.push('entreprise');
      if (!/^\+?[0-9][0-9 ().-]{7,20}$/.test(phone)) issues.push('téléphone');
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) issues.push('email');
      if (!consent) issues.push('accord données');
      if (issues.length) {
        if (error) {
          error.textContent = 'Merci de vérifier : ' + issues.join(', ') + '.';
          error.hidden = false;
        }
        return false;
      }
      data.name = name;
      data.company = company;
      data.phone = phone;
      data.email = email;
      data.message = root.querySelector('#q_message').value.trim();
      data.consent = true;
      return true;
    }
    return true;
  };

  /* ---------------- réponses & bulles ---------------- */

  const answerFor = (n) => {
    switch (n) {
      case 1:
        return data.type;
      case 2:
        return (data.goods || []).join(', ');
      case 3:
        return `${data.pc_from} → ${data.pc_to}`;
      case 4:
        return data.frequency;
      case 5:
        return `${String(data.volume ?? 0).replace('.', ',')} m³`;
      case 6:
        return `${data.name} · ${data.company}`;
      default:
        return '';
    }
  };

  const capture = (n) => {
    if (n === 1) {
      data.type = root.querySelector('.step[data-step="1"] input[name="type"]:checked')?.value || 'Autre';
    }
    if (n === 2) {
      data.goods = DST.$$('.step[data-step="2"] input[name="goods[]"]:checked').map((i) => i.value);
      if (!data.goods.length) data.goods = ['Autre'];
    }
    if (n === 4) {
      data.frequency = root.querySelector('.step[data-step="4"] input[name="frequency"]:checked')?.value || 'À définir';
    }
    if (n === 5) {
      const range = root.querySelector('.step[data-step="5"] [data-volume]');
      data.volume = Number(range.value);
      const v = volumeVehicle(data.volume);
      if (v) data.vehicle = v.name;
    }
    store.save();
  };

  const next = async () => {
    if (busy) return;
    const n = current;
    if (!validate(n)) return;
    capture(n);
    const answer = answerFor(n);
    if (answer) bubble(answer, 'me');
    if (n < 6) {
      await ask(n + 1);
    } else if (n === 6) {
      data.name = root.querySelector('#q_name').value.trim();
      data.company = root.querySelector('#q_company').value.trim();
      data.phone = root.querySelector('#q_phone').value.trim();
      data.email = root.querySelector('#q_email').value.trim();
      data.message = root.querySelector('#q_message').value.trim();
      data.consent = true;
      store.save();
      await buildRecap();
    }
  };

  const back = async () => {
    if (busy) return;
    const n = Math.max(2, current - 1);
    showStep(n);
  };

  /* ---------------- récapitulatif ---------------- */

  const rows = () => [
    ['Type', data.type, 1],
    ['Marchandises', (data.goods || []).join(', '), 2],
    ['Trajet', `${data.pc_from} → ${data.pc_to}`, 3],
    ['Fréquence', data.frequency, 4],
    ['Volume', `${String(data.volume ?? '').replace('.', ',')} m³${data.vehicle ? ' · ' + data.vehicle : ''}`, 5],
    ['Contact', `${data.name} · ${data.company}`, 6],
  ];

  const buildRecap = async () => {
    showStep(7);
    await waitTyping();
    bubble('Voilà ce que j\'ai noté. Vous pouvez modifier chaque ligne.', 'dst');
    const card = document.createElement('div');
    card.className = 'chat__msg chat__msg--dst';
    const box = document.createElement('div');
    box.className = 'recap-card';
    recap.innerHTML = '';
    rows().forEach(([label, value, target]) => {
      if (!value) return;
      const li = document.createElement('div');
      li.className = 'recap__row';
      li.innerHTML = `<span class="recap__k"></span><span class="recap__v"></span><button type="button" class="recap__edit">modifier</button>`;
      li.querySelector('.recap__k').textContent = label;
      li.querySelector('.recap__v').textContent = value;
      li.querySelector('.recap__edit').addEventListener('click', async () => {
        await ask(target);
      });
      box.appendChild(li);
    });
    const extra = document.createElement('p');
    extra.className = 'recap-card__note';
    extra.textContent = data.message ? 'Votre message : ' + data.message : '';
    if (data.message) box.appendChild(extra);
    card.appendChild(box);
    log.appendChild(card);
    if (recap) {
      // la version <ul> du formulaire reste la source pour l'accessibilité
      rows().forEach(([label, value]) => {
        if (!value) return;
        const li = document.createElement('li');
        li.innerHTML = `<span class="recap__k"></span><span class="recap__v"></span>`;
        li.querySelector('.recap__k').textContent = label;
        li.querySelector('.recap__v').textContent = value;
        recap.appendChild(li);
      });
    }
    scrollLog();
  };

  /* ---------------- envoi ---------------- */

  const payload = () => ({
    ...data,
    csrf: form.querySelector('input[name="csrf"]').value,
    elapsed: (Date.now() - startedAt) / 1000,
    website: form.querySelector('input[name="website"]').value,
  });

  const send = async (target) => {
    if (busy) return;
    busy = true;
    const btn = target.querySelector('[data-submit], [type="submit"]');
    if (btn) btn.disabled = true;
    await waitTyping(700);
    try {
      const res = await fetch(DST.data.sendUrl || 'send.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify(target === classicForm ? { ...payload(), ...classicValues() } : payload()),
      });
      const json = await res.json().catch(() => ({ ok: false }));
      if (!res.ok || !json.ok) {
        const msg = json?.errors?.form || 'Oups, l\'envoi n\'a pas fonctionné. Réessayez ou appelez-nous au ' + DST.data.phone + '.';
        bubble(msg, 'error');
        return;
      }
      success(target === classicForm);
    } catch (err) {
      bubble('Oups, l\'envoi n\'a pas fonctionné. Vérifiez votre connexion ou appelez-nous au ' + DST.data.phone + '.', 'error');
    } finally {
      busy = false;
      if (btn) btn.disabled = false;
    }
  };

  const success = (wasClassic) => {
    const first = (data.name || '').split(' ')[0] || '';
    bubble(
      `C'est noté${first ? ', ' + first : ''} ! Nous vous recontactons très vite. Pour une urgence : <a href="${DST.data.phoneHref}">${DST.data.phone}</a>.`,
      'success',
      true
    );
    if (stamp && window.gsap && !DST.reduced) {
      stamp.style.opacity = '1';
      gsap.fromTo(stamp, { scale: 2, rotate: -30, opacity: 0 }, { scale: 1, rotate: -12, opacity: 1, duration: 0.9, ease: 'elastic.out(1, .55)' });
    }
    store.clear();
    if (wasClassic && classicForm) {
      classicForm.querySelectorAll('input, textarea, select').forEach((i) => {
        if (i.type !== 'checkbox') i.value = '';
      });
      bubble('Merci ! Votre demande est bien partie.', 'dst');
    }
    // on masque le panneau de saisie
    form.querySelector('[data-steps]').querySelectorAll('.step').forEach((s) => (s.hidden = true));
    form.querySelector('.chat__switch')?.remove();
  };

  const classicValues = () => ({
    name: classicForm.querySelector('#c_name').value.trim(),
    company: classicForm.querySelector('#c_company').value.trim(),
    phone: classicForm.querySelector('#c_phone').value.trim(),
    email: classicForm.querySelector('#c_email').value.trim(),
    type: classicForm.querySelector('#c_type').value,
    frequency: classicForm.querySelector('#c_freq').value,
    pc_from: classicForm.querySelector('#c_from').value.trim(),
    pc_to: classicForm.querySelector('#c_to').value.trim(),
    goods: Array.from(classicForm.querySelector('#c_goods').selectedOptions).map((o) => o.value),
    message: classicForm.querySelector('#c_message').value.trim(),
    consent: classicForm.querySelector('input[name="consent"]').checked ? 1 : 0,
    csrf: classicForm.querySelector('input[name="csrf"]').value,
    elapsed: (Date.now() - startedAt) / 1000,
    website: classicForm.querySelector('input[name="website"]').value,
  });

  /* ---------------- événements ---------------- */

  stepsRoot.querySelectorAll('[data-next]').forEach((btn) => btn.addEventListener('click', next));
  stepsRoot.querySelectorAll('[data-back]').forEach((btn) => btn.addEventListener('click', back));

  // pastilles : valider directement pour les questions à choix unique
  stepsRoot.querySelectorAll('.step[data-step="1"] input[name="type"]').forEach((input) => {
    input.addEventListener('change', () => {
      if (current === 1) next();
    });
  });
  stepsRoot.querySelectorAll('.step[data-step="4"] input[name="frequency"]').forEach((input) => {
    input.addEventListener('change', () => {
      if (current === 4) next();
    });
  });

  // volume
  const range = root.querySelector('.step[data-step="5"] [data-volume]');
  let volumeTimer = null;
  range?.addEventListener('input', () => {
    window.clearTimeout(volumeTimer);
    volumeTimer = window.setTimeout(() => {
      const last = root.querySelector('[data-volume-answer]');
      last?.remove();
      volumeHint(false);
    }, 260);
    volumeHint(true);
  });

  // touche Entrée pour avancer (hors textarea)
  stepsRoot.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.type !== 'checkbox') {
      const step = e.target.closest('.step');
      if (!step || !step.querySelector('[data-next]')) return;
      e.preventDefault();
      next();
    }
  });

  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    if (validate(6)) send(form);
  });

  // alternative classique
  root.querySelectorAll('[data-classic-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const showClassic = classic.hidden;
      classic.hidden = !showClassic;
      form.querySelector('[data-steps]').hidden = showClassic;
      form.querySelector('.chat__switch').hidden = showClassic;
      if (showClassic) {
        classicForm.querySelector('#c_name')?.focus();
        classicForm.querySelector('[data-elapsed2]').value = String((Date.now() - startedAt) / 1000);
      }
    });
  });

  classicForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    const btn = classicForm.querySelector('[type="submit"]');
    const err = classicForm.querySelector('[data-classic-error]');
    const v = classicValues();
    const issues = [];
    if (v.name.length < 2) issues.push('nom');
    if (v.company.length < 2) issues.push('entreprise');
    if (!/^\+?[0-9][0-9 ().-]{7,20}$/.test(v.phone)) issues.push('téléphone');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.email)) issues.push('email');
    if (!/^\d{5}$/.test(v.pc_from)) issues.push('code postal d\'enlèvement');
    if (!/^\d{5}$/.test(v.pc_to)) issues.push('code postal de livraison');
    if (!v.consent) issues.push('accord données');
    if (issues.length) {
      if (err) {
        err.hidden = false;
        err.textContent = 'Merci de vérifier : ' + issues.join(', ') + '.';
      }
      return;
    }
    if (err) err.hidden = true;
    if (btn) btn.disabled = true;
    send(classicForm);
  });

  // redémarrage
  restart?.addEventListener('click', () => {
    store.clear();
    log.querySelectorAll('.chat__msg:not([data-intro])').forEach((m) => m.remove());
    Object.keys(data).forEach((k) => delete data[k]);
    stamp?.style.setProperty('opacity', '0');
    startedAt = Date.now();
    ask(1);
  });

  // véhicule choisi dans le comparateur
  window.addEventListener('dst:vehicle', (e) => {
    const detail = e.detail || {};
    if (detail.volume) {
      data.volume = detail.volume;
      const r = root.querySelector('.step[data-step="5"] [data-volume]');
      if (r) r.value = String(detail.volume);
      volumeHint(true);
      if (detail.name) {
        const hint = root.querySelector('[data-volume-vehicle]');
        if (hint) hint.innerHTML = `Vous avez choisi le <b>${detail.name}</b>.`;
      }
    }
    store.save();
    const chat = root;
    chat.classList.add('is-notified');
    window.setTimeout(() => chat.classList.remove('is-notified'), 2400);
  });

  // reprise de session
  const saved = store.load() || {};
  if (Object.keys(saved).length) {
    Object.assign(data, saved);
    if (saved.type) {
      const input = root.querySelector(`.step[data-step="1"] input[value="${CSS.escape(saved.type)}"]`);
      if (input) input.checked = true;
    }
    if (saved.goods) {
      DST.$$('.step[data-step="2"] input[name="goods[]"]').forEach((i) => {
        i.checked = saved.goods.includes(i.value);
      });
    }
    if (saved.pc_from) root.querySelector('#pc_from').value = saved.pc_from;
    if (saved.pc_to) root.querySelector('#pc_to').value = saved.pc_to;
    if (saved.frequency) {
      const input = root.querySelector(`.step[data-step="4"] input[value="${CSS.escape(saved.frequency)}"]`);
      if (input) input.checked = true;
    }
    if (saved.volume) {
      const r = root.querySelector('.step[data-step="5"] [data-volume]');
      if (r) r.value = String(saved.volume);
      volumeHint(true);
    }
    ['name', 'company', 'phone', 'email'].forEach((k) => {
      const el = root.querySelector('#q_' + k);
      if (el && saved[k]) el.value = saved[k];
    });
    if (saved.message) {
      const el = root.querySelector('#q_message');
      if (el) el.value = saved.message;
    }
  }

  startedAt = Date.now();
  if (form && form.querySelector('[data-elapsed]')) {
    form.querySelector('[data-elapsed]').value = '0';
  }
  // première question : on attend un peu pour laisser le hero respirer
  showStep(1);
  if (window.gsap && !DST.reduced) {
    ScrollTrigger.create({
      trigger: root,
      start: 'top 65%',
      once: true,
      onEnter: async () => {
        if (root.dataset.started) return;
        root.dataset.started = '1';
        await waitTyping(900);
        bubble('Bonjour ! Quel type de livraison vous faut-il ?', 'dst');
      },
    });
  }
}
