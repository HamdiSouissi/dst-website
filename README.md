# DST — Distribution Services Transports

Site vitrine **one-page** pour une entreprise de transport frigorifique à Paris et en Île-de-France.
PHP natif, aucune base de données, aucun build. Déposer les fichiers sur un hébergement mutualisé, régler `config.php`, c'est en ligne.

- **PHP 8.1+** (testé en 8.3)
- **Aucune dépendance serveur** : ni Composer, ni Node, ni base de données
- **GSAP 3.13**, ScrollTrigger, SplitText, DrawSVG, MotionPath, Flip, ScrambleText + **Lenis** via CDN
- **CSS et JS écrits à la main**, modules ES natifs
- Palette « Givre & Citron » (jaune + prune, neutres chauds) — **aucun bleu**

---

## 1. Installation sur un hébergement mutualisé

1. Se connecter en FTP/SFTP à l'hébergement (OVH, o2switch, Hostinger…).
2. Déposer **tout le contenu du dossier** à la racine du site (`www/`, `public_html/` ou `httpdocs/` selon l'hébergeur) :
   ```
   index.php
   config.php
   send.php
   partials/
   assets/
   storage/
   .htaccess
   robots.txt
   sitemap.xml
   site.webmanifest
   ```
3. Vérifier que le dossier `storage/` est **inscriptible** par PHP (droit `755` ou `775` selon l'hébergeur). Il contient :
   - le `.htaccess` qui interdit l'accès web,
   - `demandes.csv` (créé automatiquement à la première demande),
   - `rate_limits.json` (limitation du formulaire).
4. Vérifier que le module Apache `mod_rewrite` est actif (HTTPS forcé) et que le certificat SSL est bien installé.
5. Ouvrir le site : la page d'accueil doit s'afficher. Tester le formulaire de devis.

> Les fichiers `config.php`, `README.md` et `A_COMPLETER.md` sont protégés par le `.htaccess`.  
> Si votre hébergeur ne lit pas le `.htaccess` (Nginx), bloquez explicitement l'accès à `config.php`, `storage/` et `send.php` côté serveur.

### Test en local

```bash
php -S localhost:8000
```

Puis ouvrir <http://localhost:8000>.

> `php -S` n'envoie pas d'email (fonction `mail()` souvent absente en local). Le formulaire écrit tout de même la demande dans `storage/demandes.csv` : c'est le moyen de vérifier le bon fonctionnement.

---

## 2. Configuration : tout est dans `config.php`

Aucun texte structurant n'est codé en dur dans les pages. Les rubriques modifiables :

| Rubrique | Contenu |
|---|---|
| `company` | Raison sociale, forme juridique, capital, SIRET, RCS, TVA, directeur de publication |
| `contact` | Adresse, code postal, téléphone, email, WhatsApp, horaires, lien Google Maps, coordonnées GPS |
| `socials` | LinkedIn, Facebook, Instagram, WhatsApp, Google (vide = icône masquée) |
| `services_options.frozen` | `true` = ajoute le service « Produits surgelés » et la température -18 °C |
| `stats` | Chiffres du hero et de la bande « engagements » |
| `fleet` | Les 4 véhicules, leurs volumes, charges, usages |
| `zones` | Les 8 départements et les délais indicatifs |
| `services`, `sectors`, `commitments`, `faq` | Textes des sections |
| `testimonials`, `google_rating` | **Vides par défaut** : la section témoignages est masquée |
| `form` | Email de réception, accusé de réception, limite d'envois par heure |
| `smtp` | Envoi SMTP natif optionnel (si `mail()` est peu fiable chez l'hébergeur) |
| `hosting` | Coordonnées de l'hébergeur pour les mentions légales |
| `seo` | Title, description, URL canonique, image Open Graph |
| `credits` | Lien et mention de l'agence (Prime Vision Solutions) |

Les valeurs à fournir par le client sont listées dans **`A_COMPLETER.md`** et signalées par les commentaires
`À COMPLÉTER`, `À REMPLACER`, `À VALIDER` dans `config.php`.

### Changer le numéro de téléphone

Une seule ligne à modifier : `contact.phone` (format international, pour le lien `tel:`) et
`contact.phone_display` (format affiché). Le numéro est repris partout : header, hero, formulaire,
bande de contact, footer, bouton flottant, emails.

### Activer les surgelés

```php
'services_options' => [ 'frozen' => true ],
```
Le site ajoute automatiquement le service, la température `Surgelé -18 °C` dans le formulaire,
et adapte les mentions de température du hero et de la flotte.

---

## 3. Formulaire : sécurité et fiabilité

`send.php` met en œuvre :

- **POST uniquement** ;
- **jeton CSRF** en session (usage unique, renouvelé après un envoi réussi) ;
- **honeypot** (`website`) et **délai minimal de 4 secondes** entre l'affichage et l'envoi ;
- **limitation à 5 envois par IP et par heure** (fichier `storage/rate_limits.json`) ;
- validation stricte de chaque champ, listes blanches pour les choix, nettoyage des en-têtes ;
- **email HTML** à `form.to_email`, avec `Reply-To` sur le prospect ;
- **accusé de réception** au prospect (`form.send_ack`) ;
- **sauvegarde CSV de secours** systématique, même si l'email échoue ;
- **SMTP natif** en option (socket, STARTTLS/SSL, AUTH LOGIN) — aucune librairie ;
- réponse JSON : `{"ok":true}` ou `{"ok":false,"errors":{…},"message":"…"}`.

### SMTP (recommandé chez certains hébergeurs)

```php
'smtp' => [
    'enabled' => true,
    'host'    => 'smtp.votre-hebergeur.fr',
    'port'    => 587,
    'secure'  => 'tls',   // ou 'ssl' (port 465)
    'user'    => 'no-reply@dst-transports.fr',
    'pass'    => '••••••••',
    'from'    => 'no-reply@dst-transports.fr',
],
```
En cas d'échec SMTP, le script retombe automatiquement sur `mail()`.

### Consulter les demandes

Télécharger `storage/demandes.csv` par FTP. Le fichier est en UTF-8 (BOM), séparateur `;`,
ouvrable directement dans Excel ou LibreOffice.

---

## 4. Contenu, assets et remplacement des visuels

Le site est livré **complet et fonctionnel** avec :

- **4 photos détourées de camionnettes** (Master, Transit Custom, Jumpy, Caddy) dans
  `assets/img/flotte/`, en WebP transparent, utilisées dans le hero, le showroom et le formulaire.
  Le **logo officiel DST en noir** est apposé sur le panneau latéral comme un habillage adhésif ;
  ce sont des visuels photoréalistes de démonstration, pas encore les véhicules réels de DST ;
- le **logo officiel** fourni (`assets/img/logo-dst.svg`), décliné en citron pour l'en-tête, le pied
  de page et le préchargeur, et en noir pour les favicons, l'Open Graph et l'habillage des vans ;
- des **scènes de livraison générées à partir des camionnettes DST** dans `assets/img/services/`
  (route, chargement, déchargement, chauffeurs avec badge DST sur le gilet) ;
- des **photographies d'illustration** dans `assets/img/secteurs/` pour le bento des secteurs ;
- **cinq vidéos issues des rushes Google Drive de DST** (camionnettes siglées DST, chauffeurs au gilet
  marqué, chargements et livraisons à Paris), compressées en H.264 sans son, en boucle, utilisées
  exclusivement comme fonds : hero, flotte, engagements, comment ça marche et devis.

Pour utiliser les vraies photos de la flotte :

1. photographier chaque véhicule sur fond uni (vert écran ou fond clair), même orientation trois quarts avant ;
2. détourer (Photoroom, remove.bg, Photopea) et exporter en **WebP transparent** :
   `assets/img/flotte/{master,transit,jumpy,caddy}.webp` (≈1400 px) et `-sm.webp` (≈700 px) ;
3. conserver les noms de fichiers : `config.php` → `fleet[].image`, `image_sm`, `image_w`, `image_h`
   alimente automatiquement le hero, les onglets, le showroom et la suggestion de véhicule ;
4. si le cadrage change, mettre à jour `image_w` / `image_h` (ratio) pour éviter tout décalage ;
5. renseigner les crédits dans `config.php` → `image_credits` s'il s'agit d'images sous licence tierce.

Le hero utilise `assets/img/flotte/master.webp`. Pour un autre véhicule, modifier la balise `<img>`
de `partials/hero.php`.

Les visuels de démonstration ne nécessitent aucune attribution. Si des photos Wikimedia Commons sont
utilisées, leurs crédits (auteur, licence, lien) doivent être ajoutés dans `image_credits` : ils
s'affichent automatiquement dans la modale « Mentions légales ».

---

## 5. Structure des fichiers

```
index.php                  Assemble les partials, démarre la session, génère le jeton CSRF
config.php                 Toutes les données modifiables
send.php                   Traitement du formulaire (JSON)
partials/
  head.php                 Métas, SEO, JSON-LD LocalBusiness + FAQPage, polices
  logo.php                 Logo officiel DST (SVG citron) + lockup
  icons.php                Icônes au trait, réseaux sociaux, secours camionnettes SVG
  preloader.php            Préchargeur thermomètre + portes de caisson
  header.php               Header fixe, nav, menu mobile, barre de progression
  hero.php                 Hero vidéo, photo camionnette, thermomètre, chiffres
  marquee.php              Bandeau des secteurs
  chaine-froid.php         Section épinglée, courbe de température + camion suiveur
  services.php             Liste éditoriale + visuels de la flotte DST
  flotte.php               Showroom 4 véhicules
  zone.php                 Carte réelle de l'Île-de-France (8 départements)
  secteurs.php             Bento photographique
  engagements.php          Engagements + compteurs
  methode.php              Les 3 étapes
  temoignages.php          Section conditionnelle (masquée si aucun avis)
  devis.php                Formulaire multi-étapes
  faq.php                  Accordéon + JSON-LD
  contact-band.php         Bande citron
  footer.php               Footer, grand DST, crédits
  modal-legal.php          Mentions légales
  modal-privacy.php        Politique de confidentialité (RGPD)
  modal-cookies.php        Gestion des cookies
  cookie-banner.php        Bandeau cookies
  floating.php             Appel mobile + WhatsApp
assets/
  css/style.css            Feuille de style unique
  js/main.js               Lenis, GSAP, header, curseur, révélations, fonds
  js/hero.js               Camionnette, thermomètre, parallaxe
  js/route.js              Route lumineuse + point mobile
  js/coldchain.js          Section épinglée
  js/fleet.js              Showroom
  js/map.js                Carte IDF
  js/quote.js              Formulaire multi-étapes
  js/counters.js           Compteurs à rouleaux
  img/                     WebP, posters vidéo, favicons, og-image
  video/                   Fonds vidéo compressés (hero, flotte, engagements, methode, devis)
storage/                   Données du formulaire (protégées)
```

---

## 6. SEO, performance, accessibilité

- `title`, `meta description`, URL canonique, Open Graph et Twitter Card depuis `config.php`.
- **JSON-LD** `LocalBusiness` (adresse, géolocalisation, horaires, `areaServed` pour les 8 départements,
  `sameAs` réseaux sociaux) et `FAQPage`.
- `sitemap.xml`, `robots.txt`, `site.webmanifest`, favicons SVG/PNG.
- Images WebP, `srcset`/`sizes`, `loading="lazy"`, dimensions renseignées (CLS maîtrisé).
- Fonds vidéo `muted playsinline loop autoplay` + poster WebP ; chargés seulement à l'approche de la
  section (IntersectionObserver, `preload="none"`), mis en pause hors écran, coupés si
  `prefers-reduced-motion` ou `saveData`.
- GSAP et Lenis en `defer` ; animations limitées à `transform` / `opacity`.
- `.htaccess` : HTTPS, compression, cache long, en-têtes de sécurité, CSP adaptée aux CDN.
- Navigation clavier complète, focus visible jaune, lien d'évitement, modales avec focus piégé,
  formulaire annoncé en `aria-live`, carte avec équivalent textuel, décorations en `aria-hidden`.
- `prefers-reduced-motion` : Lenis et animations désactivés, contenus immédiatement visibles.

### Après la mise en ligne

1. Remplacer l'URL `seo.url`, le `sitemap.xml` et `robots.txt` par le domaine définitif.
2. Vérifier les **Rich Results** (LocalBusiness + FAQPage) :
   <https://search.google.com/test/rich-results>
3. Passer Lighthouse mobile (objectif Performance ≥ 90, Accessibilité ≥ 95, SEO 100).
   Les CDN de polices et GSAP sont les principaux coûts ; auto-héberger les polices en WOFF2
   est la première optimisation si nécessaire.
4. Déclarer le site dans Google Search Console et créer la fiche Google Business.
5. Supprimer ou remplacer les visuels d'illustration par les photos réelles.

---

## 7. Personnalisation rapide

| Objectif | Où intervenir |
|---|---|
| Couleurs | `assets/css/style.css`, section `:root` |
| Typographies | `partials/head.php` + variables `--font-*` |
| Textes des services / secteurs / FAQ | `config.php` |
| Vidéo des engagements | `assets/video/engagements.mp4` + `assets/img/engagements-poster.webp` |
| Image Open Graph | `assets/img/og-image.jpg` (1200 × 630) |
| Fonds vidéo | `assets/video/{hero,flotte,engagements,methode,devis}.mp4` + `assets/img/*-poster.webp` |
| Grand « DST » du footer | `partials/footer.php` |

---

## 8. Notes techniques

- Le fond de page est interpolé au scroll entre carbone et givre par `main.js` ; les sections vidéo
  (flotte, méthode, devis) gardent un voile sombre local pour rester lisibles, sans casser la
  transition de fond.
- Les vidéos de section sont encodées à partir des rushes DST : 960×540 à 1280×720, 5 à 9 s,
  H.264 sans son, boucles raccordées (fondu enchaîné ou aller-retour) et `faststart` pour un
  démarrage immédiat. Le total des cinq vidéos reste sous 3,1 Mo.
- Les camionnettes sont des photos WebP transparentes, servies en `srcset` (≈1200 px / 700 px) avec
  `sizes`, un préchargement `imagesrcset`/`imagesizes` et des attributs `width`/`height` explicites
  pour éviter tout décalage de mise en page.
- Si les CDN d'animation sont indisponibles, le repli sans GSAP garde le formulaire, le showroom de la
  flotte, la carte et le pied de page géant visibles ; seules les mises en scène disparaissent.
- Une petite camionnette suit la courbe de relevé de température dans la section « chaîne du froid » ;
  le repli sans GSAP la place directement au point de livraison.
- Le titre du hero est piloté uniquement par GSAP : plus aucune transformation CSS résiduelle ne le
  fait disparaître après le préchargeur.
- Le footer reprend le logo officiel en filigrane et déploie « Distribution / Services / Transports »
  sous D, S et T avec la même animation de halo et une apparition en cascade.
- La carte et les icônes restent inline ; le logo officiel est un SVG externe décliné en noir,
  citron et givre pour s’adapter au fond sans perdre en netteté.
- Le formulaire conserve un brouillon dans `sessionStorage` : un retour arrière ne perd aucune saisie.
- Aucun témoignage n'est inventé : `testimonials` est vide et la section n'existe pas tant que le client
  n'a pas fourni de vrais avis.

---

© 2026 — Site conçu et développé par [Prime Vision Solutions](https://primevisionsolutions.fr).
