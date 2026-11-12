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

> À ne **jamais** mettre en ligne : `tools/` (script de génération des tailles d'images),
> `tmp-src/` s'il existe encore (générateurs de tracés) et `A_COMPLETER.md`. Le `.htaccess`
> bloque `tmp-src`, `storage`, `lib` et `partials` ; les `.md` restent lisibles si on les
> dépose — voir § 7 de `A_COMPLETER.md`.

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
│   ├── hero.php           film en boucle, mot géant découpé dedans
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
│   ├── img/
│   │   ├── brand/         logo officiel (4 teintes)
│   │   ├── photos/        la flotte en photos (640/1100/1600 + webp + lqip)
│   │   ├── flotte/        silhouettes à l'échelle (comparateur de gabarits)
│   │   └── frais/ scenes/ produits et décors (héritage de la v1)
│   └── video/frais.mp4    film du hero, en boucle (+ affiche webp/jpg)
├── storage/               journal CSV des demandes (protégé, non listé)
├── .htaccess              redirections, cache, compression, en-têtes
├── robots.txt · sitemap.xml · site.webmanifest
└── tools/photos.py        régénère les tailles d'une photo (hors production)
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

### La marque

Le logo officiel (monogramme **DS**, texte circulaire « Distribution Services / Transports »,
deux camionnettes) est fourni en quatre teintes dans `assets/img/brand/` :

| Fichier | Usage |
|---|---|
| `logo.svg` | source, `currentColor` — pour l'inline |
| `logo-ink.svg` | aubergine, sur fond clair (en-tête) |
| `logo-cream.svg` | crème, sur fond sombre (pied de page) |
| `logo-yellow.svg` | jaune, pour les aplats aubergine |

`brand_mark($class, $tone)` (dans `partials/logo.php`) pose l'image ; `favicon.svg`,
`favicon-32.png`, `apple-touch-icon.png`, `icon-192/512.png` et le manifest en dérivent. Les
tracés d'origine contenaient un disque plein qui masquait le monogramme : il a été retiré, le
reste est rendu en `fill-rule="evenodd"`.

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
  --cream:#FAF6EE; --linen:#F1EADC; --aubergine:#2B1A2B; --plum:#7A6873;
  --basil:#F3C300;        /* le jaune DST (ex-vert basilic) */
  --basil-deep:#6E5200;   /* la même famille, pour le texte sur fond clair */
  --sprout:#FBF0BE; --raspberry:#E5334B; --lemon:#F5DC4A;
  --apricot:#F39A55; --aubergine-light:#6B4A6B; --water:#E7C97F;
}
```

**Pas de bleu, nulle part.** Les dégradés, ombres et états de focus sont dérivés de ces onze
valeurs ; en changer une suffit à repeindre le site.

> Le jaune `--basil` ne porte jamais de texte blanc : les aplats jaunes reçoivent de
> l'aubergine, et les textes colorés sur fond clair utilisent `--basil-deep` (contraste 7:1).
> Le nom de variable a été conservé pour ne pas casser les 40 règles qui l'utilisent.

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
| `hero.js` | film en boucle (pause hors écran), mot cinétique découpé dans le bandeau |
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
- `storage|lib|partials|tmp-src|tools` interdits (`[F]`).

### Nginx

```nginx
location ~ ^/(storage|lib|partials|tmp-src|tools)/ { deny all; }
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
- Film du hero : 10,3 s, 1440 × 810, quatre plans (rue, trois-quarts, chargement au marché,
  arrière de caisse sur les quais), 1,7 Mo, `preload="none"`, lecture seulement à l'entrée
  dans le viewport, coupée si `Save-Data` ou réseau 2G. La boucle est fabriquée en
  enchaînant les plans puis en revenant au premier : la dernière image rejoint la première.
- Animations sur `transform`/`opacity`, observateurs d'intersection pour suspendre hors écran.

---

## 10. Limites connues

- `sitemap.xml` et `robots.txt` contiennent l'hôte de démonstration `https://www.exemple.fr` :
  à remplacer (voir `A_COMPLETER.md`).
- Les photographies de la flotte (`assets/img/photos/`) sont des **visuels de démonstration
  générés** : véhicules, chauffeurs et lieux ne sont pas ceux de DST. Ils portent pourtant
  l'habillage DST et doivent être remplacés par un reportage réel avant toute campagne.
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
