# DST — Distribution Services Transports

Site vitrine **une page** pour DST, transport frigorifique de produits frais à Paris et en
Île-de-France. PHP natif, aucune base de données, aucune compilation, aucun gestionnaire de
paquets : le dossier se dépose tel quel par FTP sur un hébergement mutualisé.

---

## 1. Ce qu'il faut pour le faire tourner

| Élément | Exigence |
|---|---|
| PHP | **8.1 ou plus** (testé en 8.1 / 8.3), extensions `mbstring`, `json`, `fileinfo` |
| Serveur | Apache avec `mod_rewrite` **ou** Nginx (voir § 7) |
| Base de données | aucune |
| Node / npm / build | aucun |
| Droits d'écriture | uniquement sur `storage/` (journal des demandes) |

En local :

```bash
cd dst-website
php -S localhost:8000
# http://localhost:8000
```

Le serveur intégré de PHP ne lit pas `.htaccess` : c'est normal, tout fonctionne quand même.

---

## 2. Mise en ligne (FTP)

1. Envoyer **tout** le contenu du dossier à la racine du site (`www/`, `public_html/`, `htdocs/`…).
2. Vérifier que les fichiers cachés sont bien transférés, en particulier :
   - `.htaccess`
   - `storage/.htaccess`
3. Créer `storage/` avec les droits `755` et s'assurer que PHP peut y écrire (`775` si besoin).
4. Éditer `config.php` (voir `A_COMPLETER.md` pour la liste des champs à remplir).
5. Tester le formulaire : une demande doit arriver par e-mail **et** s'inscrire dans
   `storage/demandes-AAAA-MM.csv`.
6. Activer HTTPS (Let's Encrypt ou certificat de l'hébergeur) : `.htaccess` force déjà la
   redirection.

> À ne **jamais** mettre en ligne : `tmp-src/` (générateurs d'images et de tracés, hors
> production) et `A_COMPLETER.md`. Le `.htaccess` les bloque, mais autant ne pas les transférer.

---

## 3. Structure

```
/
├── index.php              page unique : assemble les sections
├── config.php             TOUT le contenu éditable (21 blocs commentés)
├── send.php               traitement du formulaire (JSON en entrée)
├── security.php           en-têtes, CSP, session, helpers de validation
├── lib/SmtpMailer.php     envoi SMTP natif (sans Composer), optionnel
├── partials/              un fichier par section
│   ├── head.php           <head>, SEO, JSON-LD, polices
│   ├── header.php         barre haute + navigation + logo qui se réduit
│   ├── hero.php           mot géant, vidéo dans les lettres, camionnette
│   ├── circuit.php        01 — circuit direct (comparateur animé)
│   ├── services.php       02 — six cagettes en 3D
│   ├── secteurs.php       03 — scènes sectorielles (Flip)
│   ├── flotte.php         04 — section sombre, comparateur de volume
│   ├── zone.php           05 — carte typographique de l'Île-de-France
│   ├── chiffres.php       06 — bento de chiffres
│   ├── engagements.php    07 — bon de livraison à cocher
│   ├── galerie.php        08 — galerie glissable
│   ├── temoignages.php    09 — masquée tant que `testimonials` est vide
│   ├── devis.php          10 — formulaire conversationnel + version classique
│   ├── faq.php            11 — FAQ collante + JSON-LD FAQPage
│   ├── footer.php         bandeau CTA + pied de page sombre
│   ├── panel-*.php        panneaux légaux (mentions, confidentialité, cookies)
│   ├── helpers.php        e(), asset(), picture(), hl(), icon()
│   ├── icons.php          jeu d'icônes Phosphor (SVG inline)
│   └── hero-glyphs.php    contours des mots du hero (généré, ne pas éditer)
├── assets/
│   ├── css/style.css      tout le style (variables CSS, aucune dépendance)
│   ├── js/                11 modules ES natifs + vendor/ (GSAP, Lenis)
│   ├── fonts/             Bricolage Grotesque, Instrument Sans, Fragment Mono
│   ├── img/               flotte/ (SVG), frais/, scènes, sticker, icônes
│   └── video/frais.mp4    vidéo du hero (+ affiche webp)
├── storage/               journal CSV des demandes (protégé, non listé)
├── .htaccess              redirections, cache, compression, en-têtes
├── robots.txt · sitemap.xml · site.webmanifest
└── tmp-src/               générateurs hors production (à ne pas déployer)
```

### Une seule page, plusieurs URL

`index.php` est la page. Les panneaux légaux sont des `<dialog>`/`<section>` ancrés
(`#mentions`, `#confidentialite`, `#cookies`) : pas de page séparée, pas de rechargement.
`robots.txt` et `sitemap.xml` ne déclarent donc qu'une URL.

---

## 4. Modifier le contenu

**Tout passe par `config.php`.** Aucun texte n'est écrit en dur dans les gabarits, sauf les
libellés d'interface (boutons, aides de saisie) qui restent traduits une fois pour toutes.

Chaque bloc est numéroté et commenté en français. Les valeurs manifestement provisoires sont
marquées `À REMPLACER` ou `À VALIDER` : `A_COMPLETER.md` en donne la liste exhaustive.

Quelques principes du modèle de données :

- **Aucun faux avis.** `testimonials` est un tableau vide : la section 09 disparaît
  entièrement du HTML (ni titre orphelin, ni compteur à zéro). Dès qu'un vrai témoignage est
  ajouté, la section réapparaît avec son balisage `Review`/`AggregateRating` si
  `google_rating` est renseigné.
- **Chiffres.** Chaque statistique porte un drapeau `certain` (vrai/faux). Les valeurs non
  certaines sont affichées avec la mention « à valider » et ne sont **pas** reprises dans le
  JSON-LD.
- **Siège social ≠ dépôt.** `head_office.note` rappelle l'adresse administrative ; aucune
  carte d'itinéraire n'est générée (`head_office.map` reste `null`).
- **Réseaux sociaux.** Une URL vide masque le lien correspondant.
- **Images.** Un fichier absent affiche automatiquement un bloc de couleur de la palette avec
  une icône — jamais une image cassée.

### Images

`picture()` (dans `partials/helpers.php`) attend cette convention, à partir d'un `.jpg` de base :

```
assets/img/scenes/primeur-etal.jpg          (original, 1600 px)
assets/img/scenes/primeur-etal-1100.webp
assets/img/scenes/primeur-etal-640.webp
assets/img/scenes/primeur-etal-lqip.jpg     (30 px, flou de chargement)
```

Si seules certaines tailles existent, le service worker n'est pas en cause : `picture()` teste
la présence des fichiers et retombe proprement sur le `.jpg`. Pour ajouter une photo, il suffit
donc de déposer le `.jpg` et de le référencer dans `config.php` ; les variantes sont un bonus
de performance, pas une obligation.

### Couleurs et typographie

Tout est dans `assets/css/style.css`, section « 1. Réglages » :

```css
:root{
  --cream:#FAF6EE; --lin:#F1EADC; --aubergine:#2B1A2B; --plum:#7A6873;
  --basil:#1F7A4C; --pousse:#CFE8C9; --framboise:#E5334B; --citron:#F5DC4A;
  --abricot:#F39A55; --aubergine-clair:#6B4A6B; --eau:#8FBF9F;
}
```

**Pas de bleu, nulle part.** Les dégradés, ombres et états de focus sont dérivés de ces onze
valeurs ; en changer une suffit à repeindre le site.

---

## 5. Le formulaire

Deux entrées pour la même demande :

1. **Formulaire conversationnel** (par défaut) — sept questions, une à la fois, avec
   récapitulatif et retour en arrière. Le choix de véhicule fait dans la section 04 est repris
   automatiquement (`sessionStorage`).
2. **Formulaire classique** — le même contenu en une colonne, accessible en un clic
   (lien « Préférez un formulaire classique ? ») et utilisable sans JavaScript.

Côté serveur, `send.php` :

- vérifie le jeton **CSRF** (session + `hash_equals`) ;
- rejette les envois trop rapides (< `form.min_seconds`, 4 s par défaut) ;
- limite à `form.rate_limit_hour` envois par IP et par heure (fichier dans `storage/`) ;
- ignore le **honeypot** rempli ;
- valide et nettoie chaque champ, refuse les en-têtes injectés ;
- envoie l'e-mail au format HTML + version texte, avec accusé de réception au visiteur si
  `form.send_ack` est vrai ;
- journalise une ligne dans `storage/demandes-AAAA-MM.csv` (une par demande) ;
- répond en JSON `{ok:true}` ou `{ok:false,errors:{…}}`.

### E-mail : `mail()` ou SMTP

Par défaut `smtp.enabled = false` → `mail()`. Si l'hébergeur l'a désactivé (fréquent), activer
`lib/SmtpMailer.php` :

```php
'smtp' => [
    'enabled' => true,
    'host'    => 'smtp.exemple.fr',
    'port'    => 587,
    'user'    => 'site@exemple.fr',
    'pass'    => '…',
    'from'    => 'site@exemple.fr',
    'from_name' => 'DST — site web',
    'secure'  => true,        // STARTTLS sur 587 ; mettre false en 465 (SSL implicite)
],
```

Aucune bibliothèque externe n'est nécessaire : le client SMTP fait STARTTLS, AUTH LOGIN/PLAIN
et gère l'encodage UTF-8 des en-têtes.

---

## 6. JavaScript

Modules ES natifs, chargés en `<script type="module">`, sans bundler :

| Module | Rôle |
|---|---|
| `main.js` | socle : Lenis, préférences de mouvement, header, menu, curseur, panneaux |
| `hero.js` | vidéo dans les lettres, camionnette masquée par le mot, mot cinétique |
| `circuit.js` | comparateur « circuit classique / direct » |
| `labels.js` | cagettes 3D, inclinaison, bascule texte détaillé |
| `sectors.js` | scènes sectorielles (Flip), lecture auto 7 s |
| `fleet.js` | comparateur de volume, choix du véhicule |
| `map.js` | carte typographique, infobulles, délais par département |
| `stats.js` | compteurs du bento |
| `checklist.js` | cases du bon de livraison, signature DrawSVG |
| `gallery.js` | galerie glissable (Draggable + Inertia) |
| `chat-form.js` | formulaire conversationnel, récapitulatif, envoi |

Sans JavaScript, la page reste lisible et le formulaire classique fonctionne : les animations
sont des améliorations, jamais des conditions d'accès au contenu.

Bibliothèques dans `assets/js/vendor/` (versions figées, servies localement) :

- **GSAP 3.15.1** + ScrollTrigger, SplitText, Flip, Draggable, InertiaPlugin, MotionPathPlugin,
  DrawSVGPlugin, CustomEase
- **Lenis 1.3.27**

Toutes les animations se désactivent avec `prefers-reduced-motion: reduce` (media query CSS +
drapeau `DST.reduced` dans `main.js`) ; le hero se fige sur FRAIS et la vidéo n'est pas lue.

---

## 7. Serveur

### Apache

`.htaccess` fourni :

- HTTPS + `www` → redirections 301 ;
- compression gzip/brotli ;
- cache navigateur : 1 an sur `assets/`, pas de cache sur `.php`/`.html` ;
- en-têtes de sécurité (CSP `default-src 'self'`, `frame-ancestors`, `X-Content-Type-Options`,
  `Referrer-Policy`, `Permissions-Policy`) ;
- `ErrorDocument 404 /index.php` ;
- `storage|lib|partials|tmp-src` interdits (`[F]`).

### Nginx

```nginx
location ~ ^/(storage|lib|partials|tmp-src)/ { deny all; }
location / { try_files $uri /index.php?$query_string; }
add_header Content-Security-Policy "default-src 'self'; img-src 'self' data:; media-src 'self'; style-src 'self' 'unsafe-inline'; frame-ancestors 'none'" always;
```

`'unsafe-inline'` pour les styles est nécessaire : les panneaux légaux et certains états
utilisent des attributs `style` posés par PHP. Le script, lui, n'a **pas** d'exception inline.

---

## 8. Accessibilité

- Structure : un seul `h1`, sections `aria-labelledby`, lien d'évitement vers `#contenu`.
- Contrastes : tous les couples texte/fond ≥ 4,5:1 (les aplats citron et pousse ne portent que
  du texte aubergine, jamais du blanc).
- Clavier : panneaux légaux avec piège de focus, `Échap` pour fermer, focus visible partout.
- Mouvement : `prefers-reduced-motion` respecté, aucune animation en boucle indispensable.
- Vidéo décorative : `muted`, `playsinline`, sans commandes, doublée du texte du `h1`.
- Formulaires : `fieldset`/`legend`, erreurs annoncées (`aria-live`), `autocomplete` renseigné.
- Les zones cliquables font au moins 44 × 44 px sur mobile.

---

## 9. Performance

- Une seule feuille de style (~80 Ko non compressés), un seul script d'entrée.
- Polices en `woff2`, `font-display: swap`, préchargement des deux graisses utiles.
- Images en `webp` + repli `jpg`, `srcset` à trois tailles, `loading="lazy"` hors écran,
  LQIP flou pour les grandes scènes.
- Vidéo du hero : `preload="none"`, lecture seulement à l'entrée dans le viewport, coupée si
  `Save-Data` ou réseau 2G.
- Animations sur `transform`/`opacity`, observateurs d'intersection pour suspendre hors écran.

---

## 10. Limites connues

- `sitemap.xml` et `robots.txt` contiennent l'hôte de démonstration `https://www.exemple.fr` :
  à remplacer (voir `A_COMPLETER.md`).
- Les visuels de produits et de scènes sont des **illustrations** : ils doivent être remplacés
  par des photographies réelles avant mise en ligne commerciale.
- Le journal CSV n'a pas de rotation automatique : au-delà de quelques milliers de lignes,
  l'archiver à la main.
- Pas de back-office : toute modification de contenu passe par `config.php`.

---

## 11. Crédits

- Direction artistique et développement : **Prime Vision Solutions** —
  [primevisionsolutions.fr](https://primevisionsolutions.fr).
- Typographies : Bricolage Grotesque (Mathieu Triay), Instrument Sans (Rodrigo Fuenzalida),
  Fragment Mono (Moonky) — SIL Open Font License 1.1, auto-hébergées.
- Icônes : Phosphor Icons (MIT), exportées en SVG inline.
- Animations : GSAP (licence standard, fichiers locaux), Lenis (MIT).

Le crédit « Site créé par Prime Vision Solutions 2026 » figure dans le pied de page, sous le
lien vers l'agence, conformément à la demande.
