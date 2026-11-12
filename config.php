<?php
/**
 * DST — Distribution Services Transports
 * Configuration unique du site. Toutes les valeurs modifiables sont ici.
 *
 * Les mentions « À COMPLÉTER / À REMPLACER / À VALIDER » sont reprises
 * dans le fichier A_COMPLETER.md livré à la racine du site.
 */

declare(strict_types=1);

return [

    /* ------------------------------------------------------------------
     *  Entreprise
     * ------------------------------------------------------------------ */
    'company' => [
        'name'         => 'Distribution Services Transports',
        'short'        => 'DST',
        'tagline'      => 'Le frais, livré à l\'heure.',
        'legal_form'   => 'SAS',                        // À COMPLÉTER
        'capital'      => '1 000 €',                    // À COMPLÉTER
        'siret'        => '000 000 000 00000',          // À COMPLÉTER
        'rcs'          => 'RCS Créteil 000 000 000',    // À COMPLÉTER
        'vat'          => 'FR00000000000',              // À COMPLÉTER
        'publisher'    => 'Nom du dirigeant',           // À COMPLÉTER
        'publisher_role' => 'Président',                // À COMPLÉTER
    ],

    /* ------------------------------------------------------------------
     *  Contact
     * ------------------------------------------------------------------ */
    'contact' => [
        'address_line'  => '48 Avenue du Docteur Durand',
        'postcode'      => '94110',
        'city'          => 'Arcueil',
        'country'       => 'France',
        'phone'         => '+33 1 00 00 00 00',          // À REMPLACER
        'phone_display' => '01 00 00 00 00',             // À REMPLACER
        'email'         => 'contact@dst-transports.fr',  // À REMPLACER
        'whatsapp'      => '',                           // ex. '33600000000' (vide = masqué)
        'hours'         => 'Lundi – Samedi · 5h00 – 19h00', // À VALIDER
        'maps_url'      => 'https://www.google.com/maps/search/?api=1&query=48+Avenue+du+Docteur+Durand+94110+Arcueil',
        'geo'           => ['lat' => 48.806, 'lng' => 2.336], // À AJUSTER
    ],

    /* ------------------------------------------------------------------
     *  Réseaux sociaux — vide = icône masquée
     * ------------------------------------------------------------------ */
    'socials' => [
        'linkedin'  => '',
        'facebook'  => '',
        'instagram' => '',
        'whatsapp'  => '',   // si vide, on réutilise contact.whatsapp
        'google'    => '',
    ],

    /* ------------------------------------------------------------------
     *  Options
     * ------------------------------------------------------------------ */
    'services_options' => [
        'frozen' => false,   // true = ajoute « Produits surgelés » et -18 °C
    ],

    /* ------------------------------------------------------------------
     *  Chiffres (À VALIDER avec le client avant mise en ligne)
     * ------------------------------------------------------------------ */
    'stats' => [
        'hero' => [
            ['value' => '0 à +4 °C', 'label' => 'Froid positif maîtrisé',        'mono' => true],
            ['value' => '8',         'label' => 'Départements desservis',        'mono' => true],
            ['value' => '6j/7',      'label' => 'Tournées du lundi au samedi',   'mono' => true],
        ],
        'trust' => [
            ['value' => 98,   'suffix' => ' %', 'label' => 'Livraisons dans le créneau'],
            ['prefix' => '+ ', 'value' => 2500, 'label' => 'Livraisons par an'],
            ['value' => 40,   'suffix' => '+',  'label' => 'Clients professionnels'],
            ['prefix' => '< ', 'value' => 2, 'suffix' => ' h', 'label' => 'Délai de réponse à un devis'],
        ],
    ],

    /* ------------------------------------------------------------------
     *  Flotte — valeurs indicatives, À VALIDER
     * ------------------------------------------------------------------ */
    'fleet' => [
        [
            'slug'     => 'master',
            'name'     => 'Renault Master',
            'gabarit'  => 'Grand fourgon',
            'volume'   => 10,
            'volume_label' => 'jusqu\'à ~10 m³',
            'charge'   => '~1 000 kg',
            'usage'    => 'Tournées complètes, grossistes, gros volumes',
            'tag'      => 'Le volume',
            'image'    => 'assets/img/flotte/master.webp',
            'image_sm' => 'assets/img/flotte/master-sm.webp',
            'image_w'  => 1270,
            'image_h'  => 682,
        ],
        [
            'slug'     => 'transit',
            'name'     => 'Ford Transit Custom',
            'gabarit'  => 'Fourgon moyen',
            'volume'   => 5,
            'volume_label' => '~5 m³',
            'charge'   => '~900 kg',
            'usage'    => 'Restaurants, traiteurs, tournées mixtes',
            'tag'      => 'Le polyvalent',
            'image'    => 'assets/img/flotte/transit.webp',
            'image_sm' => 'assets/img/flotte/transit-sm.webp',
            'image_w'  => 1176,
            'image_h'  => 669,
        ],
        [
            'slug'     => 'jumpy',
            'name'     => 'Citroën Jumpy',
            'gabarit'  => 'Fourgon compact',
            'volume'   => 4,
            'volume_label' => '~4 m³',
            'charge'   => '~900 kg',
            'usage'    => 'Centre de Paris, livraisons multi-points',
            'tag'      => 'Le citadin',
            'image'    => 'assets/img/flotte/jumpy.webp',
            'image_sm' => 'assets/img/flotte/jumpy-sm.webp',
            'image_w'  => 1256,
            'image_h'  => 623,
        ],
        [
            'slug'     => 'caddy',
            'name'     => 'Volkswagen Caddy',
            'gabarit'  => 'Petit utilitaire',
            'volume'   => 3,
            'volume_label' => '~3 m³',
            'charge'   => '~600 kg',
            'usage'    => 'Express, petites commandes, rues étroites',
            'tag'      => 'L\'express',
            'image'    => 'assets/img/flotte/caddy.webp',
            'image_sm' => 'assets/img/flotte/caddy-sm.webp',
            'image_w'  => 1313,
            'image_h'  => 644,
        ],
    ],

    /* ------------------------------------------------------------------
     *  Zone d'intervention — délais À VALIDER
     * ------------------------------------------------------------------ */
    'zones' => [
        ['code' => '75', 'name' => 'Paris',                'delay' => 'Livraison J ou J+1'],
        ['code' => '92', 'name' => 'Hauts-de-Seine',       'delay' => 'Livraison J ou J+1'],
        ['code' => '93', 'name' => 'Seine-Saint-Denis',    'delay' => 'Livraison J ou J+1'],
        ['code' => '94', 'name' => 'Val-de-Marne',         'delay' => 'Livraison J ou J+1'],
        ['code' => '77', 'name' => 'Seine-et-Marne',       'delay' => 'Livraison J+1'],
        ['code' => '78', 'name' => 'Yvelines',             'delay' => 'Livraison J+1'],
        ['code' => '91', 'name' => 'Essonne',              'delay' => 'Livraison J+1'],
        ['code' => '95', 'name' => 'Val-d\'Oise',          'delay' => 'Livraison J+1'],
    ],

    /* ------------------------------------------------------------------
     *  Secteurs
     * ------------------------------------------------------------------ */
    'sectors' => [
        ['slug' => 'restaurant',    'name' => 'Restaurants',                 'desc' => 'Vos livraisons quotidiennes avant le service.'],
        ['slug' => 'traiteur',      'name' => 'Traiteurs',                   'desc' => 'Buffets et réceptions livrés à température, à l\'heure.'],
        ['slug' => 'boulangerie',   'name' => 'Boulangeries & pâtisseries',  'desc' => 'Crèmes, entremets et produits laitiers en toute sécurité.'],
        ['slug' => 'primeur',       'name' => 'Primeurs',                    'desc' => 'Fruits et légumes préservés, du marché à l\'étal.'],
        ['slug' => 'poissonnerie',  'name' => 'Poissonneries',               'desc' => 'Produits de la mer en froid constant.'],
        ['slug' => 'epicerie',      'name' => 'Épiceries fines',             'desc' => 'Fromages, charcuteries et produits d\'exception.'],
        ['slug' => 'dark-kitchen',  'name' => 'Dark kitchens',               'desc' => 'Approvisionnement rapide et flexible.'],
        ['slug' => 'grossiste',     'name' => 'Grossistes & producteurs',    'desc' => 'Distribution vers vos clients professionnels.'],
    ],

    /* ------------------------------------------------------------------
     *  Services
     * ------------------------------------------------------------------ */
    'services' => [
        [
            'name' => 'Livraison de produits frais',
            'desc' => 'Fruits et légumes, viandes, poissons, produits laitiers, plats cuisinés : transport en froid positif de 0 à +4 °C, de l\'entrepôt à votre point de vente.',
        ],
        [
            'name' => 'Tournées de distribution régulières',
            'desc' => 'Des tournées quotidiennes ou hebdomadaires planifiées avec vous, mêmes horaires, mêmes chauffeurs, pour une organisation sans surprise.',
        ],
        [
            'name' => 'Livraison express J / J+1',
            'desc' => 'Un besoin urgent, une commande oubliée, un événement ? Nous intervenons dans la journée selon disponibilité, partout en Île-de-France.',
        ],
        [
            'name' => 'Enlèvement chez vos fournisseurs',
            'desc' => 'Nous allons chercher vos marchandises à Rungis, chez vos producteurs ou grossistes, et les livrons directement dans vos cuisines.', // mention Rungis À VALIDER
        ],
        [
            'name' => 'Événementiel & traiteurs',
            'desc' => 'Livraison de buffets, cocktails et réceptions sur site, avec respect strict des horaires de service.',
        ],
        [
            'name' => 'Véhicule frigorifique avec chauffeur',
            'desc' => 'Mise à disposition d\'une camionnette frigorifique et de son chauffeur, à la demi-journée ou à la journée, pour vos pics d\'activité.',
        ],
    ],

    /* ------------------------------------------------------------------
     *  Engagements
     * ------------------------------------------------------------------ */
    'commitments' => [
        ['title' => 'Température maîtrisée', 'text' => 'Caissons pré-refroidis et contrôle de température à chaque étape de la tournée.', 'icon' => 'thermometer'],
        ['title' => 'Ponctualité', 'text' => 'Des créneaux de livraison respectés, adaptés à vos horaires de réception et de service.', 'icon' => 'clock'],
        ['title' => 'Hygiène & conformité', 'text' => 'Véhicules nettoyés et désinfectés régulièrement, pratiques conformes aux exigences sanitaires du transport alimentaire.', 'icon' => 'shield-check'],
        ['title' => 'Interlocuteur unique', 'text' => 'Un contact direct, joignable, qui connaît vos habitudes. Pas de centre d\'appels.', 'icon' => 'phone'],
        ['title' => 'Réactivité', 'text' => 'Une urgence ? Une commande à ajouter ? Nous trouvons une solution, souvent dans la journée.', 'icon' => 'route'],
    ],

    /* ------------------------------------------------------------------
     *  FAQ — sert aussi à générer le JSON-LD FAQPage
     * ------------------------------------------------------------------ */
    'faq' => [
        [
            'q' => 'Quelles températures pouvez-vous garantir ?',
            'a' => 'Nous transportons en froid positif, de 0 à +4 °C, adapté à la grande majorité des produits frais. Pour d\'autres plages de température, contactez-nous.',
        ],
        [
            'q' => 'Dans quelles zones livrez-vous ?',
            'a' => 'Paris et l\'ensemble de l\'Île-de-France : 75, 92, 93, 94, 77, 78, 91 et 95. Les livraisons hors région peuvent être étudiées sur demande.',
        ],
        [
            'q' => 'Peut-on mettre en place des tournées régulières ?',
            'a' => 'Oui, c\'est le cœur de notre activité. Nous définissons ensemble les jours, horaires et points de livraison, et nous nous adaptons à l\'évolution de vos volumes.',
        ],
        [
            'q' => 'Livrez-vous tôt le matin ?',
            'a' => 'Oui, nous organisons nos tournées selon vos horaires de réception, y compris en début de matinée avant l\'ouverture ou le service.',
        ],
        [
            'q' => 'Combien coûte une livraison ?',
            'a' => 'Le tarif dépend du volume, de la distance, de la fréquence et des contraintes horaires. Nous établissons un devis gratuit et précis, généralement sous 24 h ouvrées.',
        ],
        [
            'q' => 'Comment est garantie la chaîne du froid ?',
            'a' => 'Nos caissons sont pré-refroidis avant chargement et la température est contrôlée pendant la tournée. Les portes ne sont ouvertes qu\'au moment des livraisons.',
        ],
        [
            'q' => 'Pouvez-vous récupérer la marchandise chez notre fournisseur ?',
            'a' => 'Oui, nous effectuons des enlèvements chez vos fournisseurs, grossistes ou producteurs, puis livrons directement vos sites.',
        ],
    ],

    /* ------------------------------------------------------------------
     *  Témoignages — VIDE tant que le client n'a pas fourni de vrais avis.
     *  Format : ['name' => '', 'role' => '', 'company' => '', 'text' => '', 'url' => '']
     * ------------------------------------------------------------------ */
    'testimonials'  => [],
    'google_rating' => null,   // ex. ['score' => 4.9, 'count' => 37, 'url' => 'https://...']

    /* ------------------------------------------------------------------
     *  Formulaire
     * ------------------------------------------------------------------ */
    'form' => [
        'to_email'        => 'contact@dst-transports.fr',  // À REMPLACER
        'send_ack'        => true,
        'rate_limit_hour' => 5,
    ],

    'smtp' => [
        'enabled' => false,
        'host'    => '',
        'port'    => 587,
        'secure'  => 'tls',   // 'tls' (STARTTLS) ou 'ssl'
        'user'    => '',
        'pass'    => '',
        'from'    => '',      // ex. no-reply@dst-transports.fr
    ],

    /* ------------------------------------------------------------------
     *  Hébergeur (mentions légales) — À COMPLÉTER
     * ------------------------------------------------------------------ */
    'hosting' => [
        'name'    => '',
        'address' => '',
        'phone'   => '',
    ],

    /* ------------------------------------------------------------------
     *  Crédits photos (licences tierces). Vide si visuels produits pour DST.
     * ------------------------------------------------------------------ */
    'image_credits' => [
        // ['file' => 'master.webp', 'author' => '…', 'license' => 'CC BY-SA 4.0', 'url' => 'https://…'],
    ],

    /* ------------------------------------------------------------------
     *  SEO
     * ------------------------------------------------------------------ */
    'seo' => [
        'title'       => 'Transport frigorifique Paris & Île-de-France | Distribution Services Transports',
        'description' => 'Livraison et distribution de produits frais en camionnettes frigorifiques à Paris et en Île-de-France. Tournées régulières, express, chaîne du froid maîtrisée. Devis gratuit.',
        'url'         => 'https://www.dst-transports.fr',   // À REMPLACER
        'og_image'    => 'assets/img/og-image.jpg',
    ],

    /* ------------------------------------------------------------------
     *  Crédits agence
     * ------------------------------------------------------------------ */
    'credits' => [
        'agency_url'   => 'https://primevisionsolutions.fr',
        'agency_label' => 'primevisionsolutions.fr',
        'agency_text'  => 'Site créé par Prime Vision Solutions 2026',
    ],
];
