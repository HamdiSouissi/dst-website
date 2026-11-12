# À compléter avant la mise en ligne

Tout se passe dans **`config.php`**. Les entrées marquées ⚠️ sont bloquantes (obligation légale
ou fonctionnement du site) ; les autres sont des contenus à valider avec DST.

Pour retrouver un champ dans le fichier : `grep -n "À REMPLACER" config.php` et
`grep -n "À VALIDER" config.php`.

---

## 1. Identité de l'entreprise — ⚠️ obligatoire (mentions légales)

Bloc `company` :

| Champ | À fournir | Usage |
|---|---|---|
| `legal_form` | SAS, SARL, EI… | mentions légales |
| `capital` | capital social | mentions légales |
| `siret` | 14 chiffres | mentions légales, pied de page |
| `rcs` | ville + numéro | mentions légales |
| `vat` | TVA intracommunautaire | mentions légales |
| `publisher` | directeur de la publication | mentions légales |
| `founded` | année de création (facultatif) | section chiffres |

Tant que ces champs sont vides, les lignes correspondantes sont simplement absentes des
mentions légales : le site reste valide, mais **incomplet au regard de la loi LCEN**.

## 2. Contact — ⚠️ obligatoire

Bloc `contact` :

- `phone` (format international, ex. `+33123456789`) ← utilisé par le lien `tel:`
- `phone_display` (ex. `01 23 45 67 89`)
- `email`
- `whatsapp` (ex. `33612345678`) — **laisser vide masque le bouton flottant**
- `whatsapp_text` (message pré-rempli)
- `hours` et `hours_lines` — horaires réels (actuellement « 5h – 19h », à valider)
- `response_time` — délai de réponse promis (actuellement « sous 24 h ouvrées »)

Les valeurs actuelles (`01 00 00 00 00`, `contact@exemple.fr`) sont des **placeholders** :
elles s'affichent telles quelles sur le site.

## 3. Siège social — ⚠️ ne pas transformer en dépôt

Bloc `head_office` :

- `address_line`, `postcode`, `city` : adresse exacte du siège.
- `note` : conserver « Adresse administrative — aucune réception de marchandises. »
- `map` : **laisser à `null`**. Aucune carte, aucun itinéraire, aucune photo de quai.

Rappel de la règle éditoriale : le site ne doit jamais laisser entendre que l'on peut venir
chercher, déposer ou voir des marchandises à cette adresse. Si DST dispose d'un vrai dépôt
ailleurs, il faut l'ajouter comme **nouveau** bloc (avec son propre libellé et ses horaires de
réception) — et non réutiliser `head_office`.

## 4. Hébergement — ⚠️ obligatoire (mentions légales)

Bloc `hosting` : `name`, `address`, `phone`, `site`. Ces quatre champs sont vides ; les mentions
légales les attendent.

## 5. Formulaire — ⚠️ bloquant

Bloc `form` :

- `to_email` : adresse de réception des demandes (actuellement `contact@exemple.fr`).
- `send_ack` : `true` pour envoyer un accusé de réception au visiteur.
- `rate_limit_hour`, `min_seconds` : garder 5 et 4 sauf besoin contraire.
- `hint`, `typing`, `classic_link`, `chat_link`, `reassurance` : libellés d'interface, à
  ajuster seulement si le ton change.

Vérifier ensuite :

1. qu'un envoi de test arrive bien (boîte de réception **et** indésirables) ;
2. que `storage/demandes-2026-11.csv` se crée avec une ligne par demande ;
3. que l'accusé de réception ne part pas dans les indésirables (SPF/DKIM de l'hébergeur).

Si `mail()` est désactivé par l'hébergeur, activer le bloc `smtp` (voir `README.md` § 5).

## 6. Domaine et SEO — ⚠️ bloquant pour l'indexation

- Bloc `seo` → `url` : remplacer `https://www.exemple.fr` par le domaine réel.
- `robots.txt` : la ligne `Sitemap:` contient la même URL de démonstration.
- `sitemap.xml` : `<loc>` contient la même URL. Remplacer les deux (le reste ne change pas).
- Bloc `seo` → `title`, `description` : relire après validation du positionnement.
- `assets/img/og-image.jpg` : visuel 1200 × 630 pour les partages (à refaire avec la vraie
  photo de flotte et le bon logo).
- `google_rating` : à laisser à `null` tant qu'il n'y a pas de fiche Google Business réelle.
  Le renseigner ajoute `AggregateRating` au JSON-LD — donc à ne faire que si les avis existent.

## 7. Visuels — ⚠️ avant toute campagne

Toutes les photos actuelles sont des **illustrations générées** pour la maquette (mention
`photos_note`). À remplacer par des photographies réelles :

| Fichier | Sujet attendu |
|---|---|
| `assets/img/scenes/primeur-etal.jpg` | étal de primeur livré par DST |
| `assets/img/scenes/cuisine-restaurant.jpg` | cuisine de restaurant à la réception |
| `assets/img/scenes/traiteur-buffet.jpg` | buffet / traiteur |
| `assets/img/frais/*.jpg` | produits frais (cagette, tomates, fromages, pain, poisson, fruits rouges) |
| `assets/video/frais.mp4` + `frais-poster.webp` | plan de 6–10 s sur des produits frais |
| `assets/img/og-image.jpg` | visuel de partage |
| `assets/img/flotte/*.svg` | silhouettes vectorielles à l'échelle (déjà propres, à garder) |

Après remplacement, régénérer les variantes `-640`, `-1100` (webp) et `-lqip` si l'on veut
conserver l'effet de chargement progressif — sinon le `.jpg` seul suffit.

Crédits : si des photos sous licence sont utilisées, les déclarer dans `image_credits`
(`label`, `author`, `license`, `url`) — le bloc est prêt mais vide.

## 8. Chiffres et promesses — ⚠️ à valider juridiquement

Bloc `stats` — chaque élément a un drapeau `certain` :

| Valeur | Affichage | Statut |
|---|---|---|
| `0` entrepôt | mis en avant | **exact** (c'est le positionnement) |
| `4` camionnettes types | bento | exact (4 gabarits décrits) |
| `8` départements | bento | exact (Île-de-France) |
| `98 %` livraisons dans le créneau | bento, mention « à valider » | **à prouver** |
| `< 24 h` pour le devis | bento, mention « à valider » | **à prouver** |

Tant que `certain` vaut `false`, la mention « à valider » s'affiche et la valeur n'entre pas
dans le JSON-LD. Dès que la donnée est établie, passer `certain` à `true` et retirer la mention
du texte.

Autres affirmations à confirmer :

- `fleet.notes` : « Conformité ATP » — à valider (attestation à jour ?).
- `zone.notice` : « Livraison J ou J+1 selon les tournées » — engagement commercial.
- `zone.maps[].delay` : délais par département (J / J+1) — à valider un par un.
- `commitments.items` : six engagements (créneaux, hygiène, manipulation, interlocuteur,
  imprévus) — à faire relire par DST avant publication.

## 9. Avis clients — ⚠️ ne pas inventer

`testimonials` est un tableau **vide** et doit le rester jusqu'à obtention d'avis réels.

Pour activer la section, ajouter des entrées de cette forme :

```php
'testimonials' => [
    [
        'quote'   => 'Texte de l’avis, avec l’accord écrit du client.',
        'name'    => 'Prénom Nom',
        'role'    => 'Gérant',
        'company' => 'Nom de l’établissement',
        'sector'  => 'restaurants',
    ],
],
```

Règles : accord écrit du client, pas de réécriture du sens, pas de note inventée. Les mentions
`Review` du JSON-LD sont générées uniquement pour les avis déclarés ici.

## 10. Réseaux sociaux

Bloc `socials` : `linkedin`, `instagram`, `facebook`. Une URL vide masque l'icône
correspondante ; aucune icône « morte » n'est affichée.

## 11. Textes à relire

- `topbar` : bandeau d'information en haut de page.
- `hero` : sur-titre, titre (`title_a` + `highlight`), paragraphe, deux boutons, pastilles.
- Les onze titres de section (`title_a` / `title_b` / `highlight`) : la mise en forme
  (`hl()`) colorie l'expression de `highlight`, qui doit apparaître **exactement** dans
  `title_b`.
- `faq.items` : questions/réponses (alimentent aussi le JSON-LD `FAQPage`).
- `call_band` : bandeau citron avant le pied de page.
- `footer.tagline` et `footer.cookie_notice`.
- Panneaux légaux : `partials/panel-legal.php`, `panel-privacy.php`, `panel-cookies.php`
  (textes génériques à adapter aux traitements réels ; la durée de conservation annoncée est
  de 3 ans).

## 12. Technique — à vérifier après transfert

- [ ] `https://` actif, redirection `www` correcte, aucun contenu mixte.
- [ ] `storage/` accessible en écriture, non listable, non téléchargeable.
- [ ] `.htaccess` transféré (fichier caché) et interprété.
- [ ] `assets/js/vendor/*.js` servis en `application/javascript` (sinon le module est bloqué).
- [ ] Polices servies depuis le domaine (pas de requête tierce, la CSP bloquerait).
- [ ] Test mobile réel (iOS Safari, Android Chrome) : hero, panneaux, formulaire.
- [ ] Test clavier seul : lien d'évitement, menu, panneaux légaux, formulaire.
- [ ] `prefers-reduced-motion` : aucune animation gênante.
- [ ] Formulaire testé avec JavaScript désactivé (version classique).
- [ ] Version 404 : `ErrorDocument` renvoie bien la page.
- [ ] Sauvegarde de `config.php` et de `storage/` dans le plan de sauvegarde de l'hébergeur.

## 13. Fichiers à ne pas déployer

- `tmp-src/` (générateurs Python de tracés, de vans et de polices) — inutile en production.
- `A_COMPLETER.md` (ce document).
- `README.md` si l'on ne souhaite pas exposer la documentation (il est lisible via l'URL
  directe ; le `.htaccess` bloque `partials|lib|storage|tmp-src` mais pas les `.md` — on peut
  ajouter `RedirectMatch 404 \.(md)$` si besoin).
