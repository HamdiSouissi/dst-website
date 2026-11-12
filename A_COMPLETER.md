# À COMPLÉTER — DST

Liste de **toutes** les informations à fournir ou à valider avant la mise en ligne.
Chaque point renvoie au fichier et à la clé à modifier. Les valeurs de démonstration sont
volontairement neutres : aucun chiffre, avis ou engagement n'est présenté comme vérifié.

> Tant que les champs marqués **P0** ne sont pas remplis, le site reste techniquement en ligne
> mais ne doit pas être communiqué au public (téléphone et email factices, mentions légales incomplètes).

---

## 1. Identité de l'entreprise — `config.php` → `company` — **P0**

| Clé | Valeur actuelle | À fournir |
|---|---|---|
| `legal_form` | `SAS` | Forme juridique exacte (SAS, SARL, EI…) |
| `capital` | `1 000 €` | Capital social |
| `siret` | `000 000 000 00000` | Numéro SIRET (14 chiffres) |
| `rcs` | `RCS Créteil 000 000 000` | Ville du greffe + numéro RCS |
| `vat` | `FR00000000000` | Numéro de TVA intracommunautaire |
| `publisher` | `Nom du dirigeant` | Nom du directeur de la publication |
| `publisher_role` | `Président` | Fonction exacte |

Ces données alimentent la modale « Mentions légales » et le JSON-LD.

---

## 2. Coordonnées — `config.php` → `contact` — **P0**

| Clé | Valeur actuelle | À fournir |
|---|---|---|
| `address_line` | `48 Avenue du Docteur Durand` | Adresse du siège — **à confirmer** (l'adresse a été reprise de la fiche publique, elle doit être validée par le client) |
| `postcode` / `city` | `94110` / `Arcueil` | À confirmer |
| `phone` | `+33 1 00 00 00 00` | Numéro réel, format international (utilisé dans les liens `tel:`) |
| `phone_display` | `01 00 00 00 00` | Même numéro, format affiché |
| `email` | `contact@dst-transports.fr` | Adresse réelle et relevée |
| `whatsapp` | *(vide)* | Numéro WhatsApp au format `33612345678`, sinon laisser vide |
| `hours` | `Lundi – Samedi · 5h00 – 19h00` | Horaires réels d'exploitation |
| `maps_url` | Lien de recherche Google | Lien exact de la fiche Google Business |
| `geo.lat` / `geo.lng` | `48.806` / `2.336` | Coordonnées GPS exactes du siège |

> Le téléphone est repris automatiquement dans le header, le hero, la bande de contact, le footer,
> les boutons flottants et les emails. **Une seule modification suffit.**

---

## 3. Formulaire — `config.php` → `form` et `smtp` — **P0**

- `form.to_email` : adresse qui reçoit les demandes de devis (**actuellement factice**).
- `form.send_ack` : `true` pour envoyer un accusé de réception au prospect, `false` sinon.
- `form.rate_limit_hour` : 5 par défaut, à ajuster si besoin.
- `smtp.enabled` : passer à `true` si `mail()` n'est pas fiable chez l'hébergeur, puis renseigner
  `host`, `port`, `secure` (`tls` ou `ssl`), `user`, `pass`, `from`.
- Vérifier les droits d'écriture sur `storage/` (le script y écrit `demandes.csv` et `rate_limits.json`).

**Test à faire avant livraison :** envoyer un devis complet, vérifier la réception de l'email,
l'accusé de réception, la ligne dans `storage/demandes.csv`, puis un second envoi depuis la même IP
pour contrôler la limite (5/heure).

---

## 4. Hébergeur — `config.php` → `hosting` — **P0**

Champs `name`, `address`, `phone` vides : à compléter avec les coordonnées de l'hébergeur
(OVH, o2switch, Hostinger…). Obligatoire pour les mentions légales.

---

## 5. Domaine et SEO — `config.php` → `seo` — **P0**

- `seo.url` : remplacer `https://www.dst-transports.fr` par le domaine définitif (sans slash final).
  Cette URL alimente la balise canonique, l'Open Graph, le JSON-LD et le `sitemap.xml`.
- `sitemap.xml` : remplacer l'URL et la date `lastmod`.
- `robots.txt` : remplacer la ligne `Sitemap:` par l'URL définitive.
- Vérifier `assets/img/og-image.jpg` après changement de nom de domaine (l'URL est absolue).
- Déclarer le site dans Google Search Console + créer la fiche Google Business.

---

## 6. Chiffres et affirmations — `config.php` → `stats`, `fleet`, `zones` — **P0 (validation)**

Les valeurs suivantes sont **indicatives** et doivent être confirmées par l'exploitant.
Elles ne doivent pas être publiées telles quelles si elles sont fausses.

| Donnée | Valeur actuelle | Statut |
|---|---|---|
| Livraisons dans le créneau | `98 %` | À valider |
| Livraisons par an | `2 500` | À valider |
| Clients professionnels | `40+` | À valider |
| Délai de réponse devis | `< 2 h` | À valider |
| Température | `0 à +4 °C` | À valider (plage réelle) |
| Départements desservis | `8` | À valider |
| Tournées | `6j/7` | À valider |
| Véhicules | Master ~10 m³, Transit Custom ~5 m³, Jumpy ~4 m³, Caddy ~3 m³ | À valider (volumes, charges, modèles réels) |
| Délais par département | `J ou J+1` / `J+1` | À valider |
| Enlèvement à Rungis | mention dans le service 4 | À valider (le client y va-t-il réellement ?) |

Si un chiffre n'est pas confirmable, le supprimer de `config.php` : la mise en page s'adapte
(la bande de chiffres du hero et la bande d'engagements sont générées à partir du tableau).

---

## 7. Témoignages — `config.php` → `testimonials` / `google_rating` — **P1**

- `testimonials` est **vide** : la section « 08 Témoignages » n'est pas rendue du tout.
  Ne la remplir qu'avec de **vrais avis clients**, avec accord écrit d'utilisation
  (nom, fonction, entreprise, texte, URL éventuelle).
- `google_rating` est `null` : ne renseigner que si la note Google est réelle et à jour
  (`['score' => 4.9, 'count' => 37, 'url' => '…']`).

---

## 8. Visuels — **P1**

Le site est livré avec des **visuels d'illustration** pour ne jamais afficher d'image cassée.

| Emplacement | Fichier actuel | À remplacer par |
|---|---|---|
| Photo du hero (poster) | `assets/img/hero-poster.webp` (+ `-sm`) | Photo réelle d'une camionnette DST en tournée, Paris en arrière-plan |
| Vidéo du hero | `assets/video/hero.mp4` | Plan court (8–12 s), sans son, 1280×720 ou 1920×1080, < 3 Mo |
| Camionnettes | SVG dans `partials/icons.php` (`dst_van()`) | Photos détourées (WebP transparent) ou SVG définitifs |
| Secteurs (8 tuiles) | `assets/img/secteurs/*.webp` (+ `-sm`) | Photos réelles : restaurant, traiteur, boulangerie, primeur, poissonnerie, épicerie, dark kitchen, grossiste |
| Engagements | `assets/img/chargement.webp` (+ `-sm`) | Photo du chargement d'un caisson, porte ouverte |
| Image Open Graph | `assets/img/og-image.jpg` | Recréer avec le visuel définitif (1200 × 630) |
| Logo | `partials/logo.php` + `assets/img/favicon.svg` | Logo officiel DST (monogramme actuel = proposition typographique) |

**Les visuels actuels sont des images d'illustration générées pour la maquette.** Ils ne représentent
pas la flotte réelle de DST et ne doivent pas être présentés comme tels. Aucune attribution n'est
nécessaire pour ces visuels de démonstration ; si des photos sous licence tierce sont ajoutées,
renseigner `image_credits` (auteur, licence, lien) — les crédits s'affichent dans les mentions légales.

**Procédure de remplacement :** conserver les mêmes noms de fichiers et les mêmes dimensions
(`1400×933` pour les secteurs, `1200×1500` pour le chargement, `1600×900` pour le poster)
pour ne rien casser. Les versions `-sm` sont les variantes mobile.

---

## 9. Réseaux sociaux — `config.php` → `socials` — **P2**

Toutes les entrées sont vides : les icônes correspondantes sont **masquées** dans le header,
le footer et le JSON-LD (`sameAs`). Renseigner les URL complètes des profils réellement actifs.
`socials.whatsapp` vide réutilise `contact.whatsapp`.

---

## 10. Services et options — `config.php` → `services_options` — **P2**

- `frozen` est `false` : la mention surgelés n'apparaît pas. Passer à `true` **uniquement** si DST
  transporte réellement des surgelés (ajoute le service et la température -18 °C dans le formulaire).
- Vérifier que les 6 services correspondent à l'offre réelle (tournées régulières, express,
  enlèvement fournisseurs, événementiel, véhicule avec chauffeur).

---

## 11. Mentions légales et RGPD — **P1**

- `partials/modal-legal.php` : vérifier les blocs marqués `À COMPLÉTER` (hébergeur, assurance,
  médiateur de la consommation, propriété intellectuelle).
- `partials/modal-privacy.php` : la politique de confidentialité décrit le formulaire, la durée de
  conservation (12 mois), les droits RGPD et le contact DPO. Faire relire par le client ;
  adapter si un outil de mesure d'audience est ajouté.
- Le bandeau cookies ne propose aujourd'hui que « Tout accepter » et « Continuer sans accepter » :
  aucun cookie de mesure n'est déposé. Si Google Analytics ou un pixel est ajouté, il faudra une
  catégorie « Mesure d'audience » et un vrai consentement préalable (le code du bandeau est dans
  `partials/cookie-banner.php` et `assets/js/main.js`).

---

## 12. Crédits agence — `config.php` → `credits` — **P2**

Le footer affiche le lien `primevisionsolutions.fr` puis, en dessous, centré :
« Site créé par Prime Vision Solutions 2026 ». Mettre à jour l'année à chaque nouvelle version.

---

## 13. Après la mise en ligne — **P1**

1. Tester le site sur mobile (360 px), tablette et 2560 px.
2. Vérifier HTTPS, la redirection `http → https`, `www` vs sans `www`.
3. Vérifier que `config.php`, `README.md`, `A_COMPLETER.md` et `storage/` ne sont pas accessibles
   depuis un navigateur.
4. Tester le formulaire complet (email + CSV + accusé + limite).
5. Valider les Rich Results : <https://search.google.com/test/rich-results>.
6. Lighthouse mobile (Performance, Accessibilité, SEO).
7. Sauvegarder `storage/demandes.csv` avant toute mise à jour du site.
8. Supprimer ce fichier `A_COMPLETER.md` du serveur une fois toutes les données fournies.

---

### Récapitulatif des priorités

| Priorité | Bloc |
|---|---|
| **P0** | Entreprise, coordonnées, email du formulaire, hébergeur, domaine/SEO, validation des chiffres |
| **P1** | Témoignages (ou absence), visuels réels, mentions légales/RGPD, tests post-déploiement |
| **P2** | Réseaux sociaux, options surgelés, crédits agence |
