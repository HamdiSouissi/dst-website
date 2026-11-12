<?php
/**
 * Distribution Services Transports — configuration centrale.
 * ---------------------------------------------------------
 * Toutes les données modifiables du site vivent ici.
 * Les valeurs marquées « À COMPLÉTER » / « À VALIDER » doivent être
 * confirmées par le client avant la mise en ligne (voir A_COMPLETER.md).
 *
 * PHP 8.1+ — aucun framework, aucune base de données.
 */

declare(strict_types=1);

return [

    /* ------------------------------------------------------------------
     * 1. Entreprise
     * ---------------------------------------------------------------- */
    'company' => [
        'name'      => 'Distribution Services Transports',
        'short'     => 'DST',
        'tagline'   => 'Du fournisseur à votre porte.',
        'legal_form'=> '',   // À COMPLÉTER — ex. SAS / SARL / EI
        'capital'   => '',   // À COMPLÉTER — ex. 1 000 €
        'siret'     => '',   // À COMPLÉTER
        'rcs'       => '',   // À COMPLÉTER — ex. RCS Créteil 000 000 000
        'vat'       => '',   // À COMPLÉTER — TVA intracommunautaire
        'publisher' => '',   // À COMPLÉTER — directeur de la publication
        'founded'   => '',   // À COMPLÉTER (année de création, facultatif)
    ],

    /* ------------------------------------------------------------------
     * 2. Siège social — ATTENTION : ce n'est pas un dépôt.
     *    Ne jamais présenter cette adresse comme un lieu de réception
     *    de marchandises, de stockage ou d'itinéraire de livraison.
     * ---------------------------------------------------------------- */
    'head_office' => [
        'label'        => 'Siège social',
        'address_line' => '48 Avenue du Docteur Durand',
        'postcode'     => '94110',
        'city'         => 'Arcueil',
        'note'         => 'Adresse administrative — aucune réception de marchandises.',
        'map'          => null, // pas de carte d'itinéraire : le siège n'est pas un lieu de livraison
    ],

    /* ------------------------------------------------------------------
     * 3. Contact
     * ---------------------------------------------------------------- */
    'contact' => [
        'phone'         => '+33 1 00 00 00 00',   // À REMPLACER (format international, pour tel:)
        'phone_display' => '01 00 00 00 00',      // À REMPLACER (affichage)
        'email'         => 'contact@exemple.fr',  // À REMPLACER
        'whatsapp'      => '',                    // À REMPLACER — ex. 33612345678 ; vide = masqué
        'whatsapp_text' => 'Bonjour DST, j’aimerais un devis pour une livraison de produits frais.',
        'hours'         => 'Lundi – Samedi · 5h – 19h', // À VALIDER
        'hours_lines'   => [
            'Lun – Ven' => '5h – 19h',
            'Samedi'    => '5h – 13h',
            'Dimanche'  => 'Fermé',
        ],
        'response_time' => 'sous 24 h ouvrées', // À VALIDER
    ],

    'socials' => [
        'linkedin'  => '', // vide = masqué
        'instagram' => '',
        'facebook'  => '',
    ],

    /* ------------------------------------------------------------------
     * 4. Bandeau d'information
     * ---------------------------------------------------------------- */
    'topbar' => 'Livraison de produits frais en camionnettes frigorifiques · Paris & Île-de-France · Devis sous 24 h',

    /* ------------------------------------------------------------------
     * 5. Hero
     * ---------------------------------------------------------------- */
    'hero_words' => ['FRAIS', 'DIRECT', 'À L\'HEURE'],
    'hero' => [
        'kicker'      => 'Transport frigorifique · 0 à +4 °C',
        'title_a'     => 'Du fournisseur',
        'title_b'     => 'à votre porte.',
        'highlight'   => 'à votre porte',
        'text'        => 'DST livre vos produits frais en camionnettes frigorifiques, directement, sans passage par un entrepôt. Moins de manipulations, plus de fraîcheur, partout à Paris et en Île-de-France.',
        'cta'         => 'Demander un devis',
        'cta_secondary' => 'Comment ça marche',
        'pills'       => ['0 → +4 °C', '75 92 93 94 77 78 91 95', 'Lun → Sam'], // jours À VALIDER
        'video'       => 'assets/video/frais.mp4',
        'poster'      => 'assets/video/frais-poster.webp',
        'video_credit'=> 'Plan de démonstration — à remplacer par un film de la flotte DST.',
    ],

    /* ------------------------------------------------------------------
     * 6. Le circuit direct (section signature)
     * ---------------------------------------------------------------- */
    'circuit' => [
        'number' => '01',
        'label'  => 'Le circuit direct',
        'title_a'=> 'Pas d\'entrepôt.',
        'title_b'=> 'Pas de détour.',
        'highlight' => 'Pas de détour',
        'intro'  => 'Chez DST, vos marchandises ne dorment jamais dans un entrepôt. Nous les chargeons chez votre fournisseur et les livrons directement chez vous, dans la foulée. C\'est notre façon de protéger la fraîcheur.',
        'classic' => [
            'label'  => 'Circuit classique',
            'steps'  => ['Fournisseur', 'Entrepôt', 'Stockage', 'Préparation', 'Tournée', 'Vous'],
            'freshness' => [100, 92, 85, 79, 74],
            'summary' => '5 étapes · 4 manipulations',
        ],
        'direct' => [
            'label'  => 'Circuit DST',
            'steps'  => ['Fournisseur', 'Camionnette frigorifique DST', 'Vous'],
            'summary' => '1 trajet · 1 seule manipulation',
        ],
        'note'   => 'Schéma illustratif : comparaison d\'un circuit avec stockage intermédiaire et d\'une livraison directe.',
        'benefits' => [
            ['icon' => 'leaf',  'title' => 'Plus frais', 'text' => 'Vos produits passent moins de temps hors de vos frigos et de ceux de vos fournisseurs.'],
            ['icon' => 'clock', 'title' => 'Plus rapide', 'text' => 'Enlèvement et livraison dans la même tournée, sans attente de groupage.'],
            ['icon' => 'snow',  'title' => 'Plus sûr', 'text' => 'Moins de manipulations, c\'est moins de casse, moins d\'erreurs et une chaîne du froid jamais interrompue.'],
        ],
        'replay' => 'Rejouer',
    ],

    /* ------------------------------------------------------------------
     * 7. Services — étiquettes de cagette
     * ---------------------------------------------------------------- */
    'services' => [
        'number' => '02',
        'label'  => 'Services',
        'title_a'=> 'Ce que nous',
        'title_b'=> 'livrons pour vous.',
        'highlight' => 'livrons pour vous',
        'items' => [
            [
                'num' => '01', 'color' => '#1F7A4C', 'tone' => 'basil', 'icon' => 'truck',
                'title' => 'Tournées régulières', 'lead' => 'Vos livraisons, à jour fixe.',
                'detail' => 'Tournées quotidiennes ou hebdomadaires définies avec vous : mêmes créneaux, même chauffeur, aucune surprise. Idéal pour les restaurants et commerces de bouche.',
                'meta' => 'Restaurants · Commerces',
            ],
            [
                'num' => '02', 'color' => '#E5334B', 'tone' => 'framboise', 'icon' => 'clock',
                'title' => 'Express jour même', 'lead' => 'Une urgence ? On y va.',
                'detail' => 'Commande oubliée, rupture, imprévu de dernière minute : nous intervenons dans la journée selon disponibilité, partout en Île-de-France.',
                'meta' => 'Selon disponibilité',
            ],
            [
                'num' => '03', 'color' => '#F5DC4A', 'tone' => 'citron', 'icon' => 'package',
                'title' => 'Enlèvement fournisseur', 'lead' => 'On va chercher, on vous livre.',
                'detail' => 'Nous récupérons vos marchandises chez vos producteurs, grossistes ou au marché de gros, et les livrons directement chez vous, sans stockage intermédiaire.',
                'meta' => 'Marché de gros — à valider',
            ],
            [
                'num' => '04', 'color' => '#F39A55', 'tone' => 'abricot', 'icon' => 'handshake',
                'title' => 'Traiteurs & événements', 'lead' => 'Le buffet arrive avant les invités.',
                'detail' => 'Livraison de buffets, plateaux et réceptions sur site, à température et à l\'heure exacte du service.',
                'meta' => 'Sur site · Au service',
            ],
            [
                'num' => '05', 'color' => '#6B4A6B', 'tone' => 'aubergine-clair', 'icon' => 'map',
                'title' => 'Distribution B2B', 'lead' => 'Vos produits chez vos clients.',
                'detail' => 'Producteurs, artisans, marques alimentaires : nous livrons vos clients professionnels (restaurants, épiceries, revendeurs) en votre nom.',
                'meta' => 'En votre nom',
            ],
            [
                'num' => '06', 'color' => '#CFE8C9', 'tone' => 'pousse', 'icon' => 'snow',
                'title' => 'Camionnette + chauffeur', 'lead' => 'Un renfort quand il faut.',
                'detail' => 'Mise à disposition d\'une camionnette frigorifique avec chauffeur, à la demi-journée ou à la journée, pour vos pics d\'activité.',
                'meta' => 'Demi-journée · Journée',
            ],
        ],
    ],

    /* ------------------------------------------------------------------
     * 8. Secteurs clients
     * ---------------------------------------------------------------- */
    'sectors' => [
        'number' => '03',
        'label'  => 'Pour qui ?',
        'title_a'=> 'Ceux qui ne transigent pas',
        'title_b'=> 'sur la fraîcheur.',
        'highlight' => 'sur la fraîcheur',
        'items' => [
            ['slug' => 'restaurants', 'name' => 'Restaurants', 'color' => '#E5334B', 'image' => 'assets/img/scenes/cuisine-restaurant.jpg',
             'text' => 'Vos produits arrivent avant la mise en place, à la bonne température, dans vos chambres froides. Vous vous concentrez sur la cuisine.',
             'tags' => ['Mise en place', 'Chambre froide', 'Avant le service']],
            ['slug' => 'traiteurs', 'name' => 'Traiteurs & événementiel', 'color' => '#F39A55', 'image' => 'assets/img/scenes/traiteur-buffet.jpg',
             'text' => 'Des livraisons calées sur l\'heure du service, des véhicules propres et des chauffeurs soigneux, même pour les lieux de réception difficiles d\'accès.',
             'tags' => ['Buffets', 'Plateaux', 'Sur site']],
            ['slug' => 'primeurs', 'name' => 'Primeurs & producteurs', 'color' => '#1F7A4C', 'image' => 'assets/img/scenes/primeur-etal.jpg',
             'text' => 'Du champ ou du marché de gros jusqu\'à l\'étal, vos fruits et légumes gardent leur éclat.',
             'tags' => ['Fruits & légumes', 'Marché de gros', 'Étal']],
            ['slug' => 'boulangeries', 'name' => 'Boulangeries & pâtisseries', 'color' => '#F5DC4A', 'image' => 'assets/img/frais/pain.jpg',
             'text' => 'Crèmes, entremets, produits laitiers : transportés au frais, livrés intacts.',
             'tags' => ['Crèmes', 'Entremets', 'Produits laitiers']],
            ['slug' => 'epiceries', 'name' => 'Épiceries fines & fromagers', 'color' => '#6B4A6B', 'image' => 'assets/img/frais/fromages.jpg',
             'text' => 'Fromages, charcuteries, produits d\'exception : le respect que méritent vos références.',
             'tags' => ['Fromages', 'Charcuterie', 'Produits d\'exception']],
            ['slug' => 'poissonneries', 'name' => 'Poissonneries', 'color' => '#8FBF9F', 'image' => 'assets/img/frais/poisson-glace.jpg',
             'text' => 'Produits de la mer en froid constant, livrés tôt pour l\'ouverture.',
             'tags' => ['Froid constant', 'Tôt le matin', 'Écailler']],
        ],
    ],

    /* ------------------------------------------------------------------
     * 9. Flotte
     * ---------------------------------------------------------------- */
    'fleet' => [
        'number' => '04',
        'label'  => 'La flotte',
        'title_a'=> 'Le bon gabarit,',
        'title_b'=> 'pour chaque livraison.',
        'highlight' => 'pour chaque livraison',
        'text'   => 'Du petit utilitaire qui se faufile dans les rues de Paris au grand fourgon pour les tournées complètes. Tous nos véhicules sont équipés d\'un groupe frigorifique.',
        'slider_label' => 'De quel volume avez-vous besoin ?',
        'ideal_badge'  => 'Idéal pour vous',
        'notes'  => ['Froid positif 0 à +4 °C', 'Véhicules entretenus et nettoyés régulièrement', 'Conformité ATP'], // ATP À VALIDER
        'vehicles' => [
            ['slug' => 'caddy', 'name' => 'Volkswagen Caddy', 'gabarit' => 'Petit utilitaire', 'volume' => '~3 m³', 'volume_min' => 1, 'volume_max' => 3.4,
             'charge' => '~600 kg', 'length' => 4.50, 'height' => 1.84, 'image' => 'assets/img/flotte/caddy.svg',
             'usage' => 'Express, petites commandes, centre de Paris'],
            ['slug' => 'jumpy', 'name' => 'Citroën Jumpy', 'gabarit' => 'Fourgon compact', 'volume' => '~4 m³', 'volume_min' => 3.5, 'volume_max' => 4.8,
             'charge' => '~900 kg', 'length' => 4.96, 'height' => 1.94, 'image' => 'assets/img/flotte/jumpy.svg',
             'usage' => 'Livraisons multi-points en ville'],
            ['slug' => 'transit-custom', 'name' => 'Ford Transit Custom', 'gabarit' => 'Fourgon moyen', 'volume' => '~5 m³', 'volume_min' => 4.9, 'volume_max' => 6.6,
             'charge' => '~900 kg', 'length' => 5.34, 'height' => 1.99, 'image' => 'assets/img/flotte/transit-custom.svg',
             'usage' => 'Restaurants, traiteurs, tournées mixtes'],
            ['slug' => 'master', 'name' => 'Renault Master', 'gabarit' => 'Grand fourgon', 'volume' => '~8–10 m³', 'volume_min' => 6.7, 'volume_max' => 10,
             'charge' => '~1 000 kg', 'length' => 5.55, 'height' => 2.49, 'image' => 'assets/img/flotte/master.svg',
             'usage' => 'Tournées complètes, gros volumes'],
        ],
        'ruler' => ['0 m', '1 m', '2 m', '3 m', '4 m', '5 m', '6 m'],
    ],

    /* ------------------------------------------------------------------
     * 10. Zone — carte typographique de l'Île-de-France
     * ---------------------------------------------------------------- */
    'zone' => [
        'number' => '05',
        'label'  => 'Zone',
        'title_a'=> 'Paris et toute',
        'title_b'=> 'l\'Île-de-France.',
        'highlight' => 'l\'Île-de-France',
        'text'   => 'Notre siège social est à Arcueil, aux portes de Paris. Nos camionnettes, elles, sont sur la route : elles partent de chez vos fournisseurs et arrivent directement chez vous, dans les 8 départements franciliens.',
        'notice' => 'Livraison J ou J+1 selon les tournées. Hors Île-de-France ? Parlons-en.', // À VALIDER
        'notice_bold' => 'Hors Île-de-France ? Parlons-en.',
        'maps'   => [
        [
            'code' => '75',
            'name' => "Paris",
            'delay' => "J",
            'shape' => '564.0,400.0 562.6,412.0 558.5,423.4 551.8,433.9 542.8,442.8 532.0,449.9 519.8,454.8 506.7,457.3 493.3,457.3 480.2,454.8 468.0,449.9 457.2,442.8 448.2,433.9 441.5,423.4 437.4,412.0 436.0,400.0 437.4,388.0 441.5,376.6 448.2,366.1 457.2,357.2 468.0,350.1 480.2,345.2 493.3,342.7 506.7,342.7 519.8,345.2 532.0,350.1 542.8,357.2 551.8,366.1 558.5,376.6 562.6,388.0',
            'labels' => [
                ['n'=>'1er','x'=>523.7,'y'=>403.2,'s'=>8.2], ['n'=>'2e','x'=>508.8,'y'=>420.1,'s'=>8.2], ['n'=>'3e','x'=>485.0,'y'=>416.9,'s'=>8.2], ['n'=>'4e','x'=>476.3,'y'=>396.8,'s'=>8.2], ['n'=>'5e','x'=>491.2,'y'=>379.9,'s'=>8.2], ['n'=>'6e','x'=>515.0,'y'=>383.1,'s'=>8.2], ['n'=>'7e','x'=>540.0,'y'=>400.0,'s'=>8.2], ['n'=>'8e','x'=>528.3,'y'=>425.5,'s'=>8.2], ['n'=>'9e','x'=>500.0,'y'=>436.0,'s'=>8.2], ['n'=>'10e','x'=>471.7,'y'=>425.5,'s'=>8.2], ['n'=>'11e','x'=>460.0,'y'=>400.0,'s'=>8.2], ['n'=>'12e','x'=>471.7,'y'=>374.5,'s'=>8.2], ['n'=>'13e','x'=>500.0,'y'=>364.0,'s'=>8.2], ['n'=>'14e','x'=>528.3,'y'=>374.5,'s'=>8.2], ['n'=>'15e','x'=>551.7,'y'=>417.0,'s'=>8.0], ['n'=>'16e','x'=>509.5,'y'=>448.8,'s'=>8.0], ['n'=>'17e','x'=>457.8,'y'=>431.8,'s'=>8.0], ['n'=>'18e','x'=>448.3,'y'=>383.0,'s'=>8.0], ['n'=>'19e','x'=>490.5,'y'=>351.2,'s'=>8.0], ['n'=>'20e','x'=>542.2,'y'=>368.2,'s'=>8.0],
            ],
        ],
        [
            'code' => '92',
            'name' => "Hauts-de-Seine",
            'delay' => "J",
            'shape' => '353.7,482.3 339.4,458.4 331.8,432.2 330.9,405.3 336.0,379.3 345.8,354.9 358.9,332.5 374.5,311.8 392.8,292.8 413.9,276.0 438.2,262.3 465.2,252.8 494.1,248.4 497.5,335.2 485.0,336.6 473.0,339.9 461.8,345.0 451.8,351.8 443.3,360.1 436.4,369.6 431.5,380.0 428.7,391.0 428.0,402.3 429.6,413.5 433.2,424.3 438.9,434.3',
            'labels' => [
                ['n'=>'Boulogne-Bill.','x'=>406.3,'y'=>379.3,'s'=>12.0], ['n'=>'Nanterre','x'=>346.0,'y'=>437.8,'s'=>12.0], ['n'=>'Issy','x'=>417.4,'y'=>398.2,'s'=>10.8], ['n'=>'Neuilly','x'=>407.1,'y'=>313.4,'s'=>10.8], ['n'=>'Levallois','x'=>343.1,'y'=>395.6,'s'=>10.8], ['n'=>'Clichy','x'=>460.6,'y'=>311.5,'s'=>10.8], ['n'=>'Montrouge','x'=>463.7,'y'=>289.9,'s'=>10.8], ['n'=>'Antony','x'=>424.1,'y'=>353.0,'s'=>9.4], ['n'=>'Clamart','x'=>362.7,'y'=>419.6,'s'=>9.4], ['n'=>'Meudon','x'=>381.6,'y'=>455.4,'s'=>9.4], ['n'=>'Courbevoie','x'=>366.0,'y'=>340.6,'s'=>9.4], ['n'=>'Puteaux','x'=>411.2,'y'=>419.3,'s'=>9.4], ['n'=>'Rueil-Malmaison','x'=>435.0,'y'=>337.8,'s'=>9.4], ['n'=>'Colombes','x'=>410.1,'y'=>290.2,'s'=>9.4], ['n'=>'Asnières','x'=>481.9,'y'=>268.8,'s'=>9.4], ['n'=>'Gennevilliers','x'=>375.8,'y'=>359.1,'s'=>9.4], ['n'=>'Sceaux','x'=>392.8,'y'=>434.8,'s'=>9.4], ['n'=>'Suresnes','x'=>438.8,'y'=>273.8,'s'=>9.4],
            ],
        ],
        [
            'code' => '93',
            'name' => "Seine-Saint-Denis",
            'delay' => "J",
            'shape' => '517.7,248.8 532.7,250.9 547.4,254.3 561.7,259.0 575.5,264.8 588.6,271.6 601.0,279.3 612.7,288.0 623.5,297.5 633.4,307.9 642.2,319.0 649.9,330.8 656.2,343.2 566.8,375.7 564.1,370.4 560.8,365.3 557.1,360.6 552.9,356.1 548.3,352.0 543.3,348.2 538.0,344.9 532.3,342.1 526.4,339.7 520.2,337.8 513.9,336.4 507.5,335.6',
            'labels' => [
                ['n'=>'Saint-Denis','x'=>561.4,'y'=>310.3,'s'=>12.0], ['n'=>'Aubervilliers','x'=>535.4,'y'=>282.8,'s'=>12.0], ['n'=>'Montreuil','x'=>565.4,'y'=>330.4,'s'=>10.8], ['n'=>'Aulnay-s-Bois','x'=>576.1,'y'=>354.2,'s'=>10.8], ['n'=>'Pantin','x'=>630.1,'y'=>341.1,'s'=>10.8], ['n'=>'Le Blanc-Mesnil','x'=>553.3,'y'=>266.3,'s'=>10.8], ['n'=>'Bobigny','x'=>614.2,'y'=>311.9,'s'=>10.8], ['n'=>'Bondy','x'=>584.5,'y'=>281.8,'s'=>9.4], ['n'=>'Sevran','x'=>521.3,'y'=>326.7,'s'=>9.4],
            ],
        ],
        [
            'code' => '94',
            'name' => "Val-de-Marne",
            'delay' => "J",
            'shape' => '663.0,363.4 666.0,397.0 659.7,429.7 645.9,459.8 626.8,487.0 603.3,511.5 575.1,532.6 541.6,548.2 504.0,555.5 465.5,552.6 429.8,539.7 399.4,519.5 374.9,494.4 444.8,441.7 456.5,451.6 470.3,459.0 485.6,463.5 501.7,464.8 517.6,462.8 532.7,457.7 546.1,449.8 557.2,439.3 565.5,426.9 570.5,413.1 572.0,398.7 569.9,384.3',
            'labels' => [
                ['n'=>'Créteil','x'=>447.4,'y'=>507.2,'s'=>12.0], ['n'=>'Vitry','x'=>454.9,'y'=>463.0,'s'=>12.0], ['n'=>'Champigny-s-Marne','x'=>579.9,'y'=>515.6,'s'=>10.8], ['n'=>'Saint-Maur','x'=>581.1,'y'=>427.7,'s'=>10.8], ['n'=>'Ivry-s-Seine','x'=>505.6,'y'=>477.8,'s'=>10.8], ['n'=>'Le Kremlin-Bic.','x'=>523.3,'y'=>539.6,'s'=>10.8], ['n'=>'Villejuif','x'=>588.1,'y'=>404.0,'s'=>10.8], ['n'=>'Alfortville','x'=>417.6,'y'=>486.3,'s'=>9.4], ['n'=>'Maisons-Alfort','x'=>623.7,'y'=>466.9,'s'=>9.4], ['n'=>'Choisy-le-Roi','x'=>575.4,'y'=>489.8,'s'=>9.4], ['n'=>'Vincennes','x'=>643.1,'y'=>399.0,'s'=>9.4], ['n'=>'Fontenay-s-Bois','x'=>586.3,'y'=>448.6,'s'=>9.4], ['n'=>'Nogent-s-Marne','x'=>488.9,'y'=>523.6,'s'=>9.4], ['n'=>'Arcueil','x'=>635.2,'y'=>382.0,'s'=>9.4], ['n'=>'Cachan','x'=>419.6,'y'=>471.0,'s'=>9.4], ['n'=>'Saint-Mandé','x'=>647.9,'y'=>430.7,'s'=>9.4], ['n'=>'Orly','x'=>408.7,'y'=>505.3,'s'=>9.4], ['n'=>'Rungis','x'=>575.0,'y'=>474.8,'s'=>9.4],
            ],
        ],
        [
            'code' => '78',
            'name' => "Yvelines",
            'delay' => "J ou J+1",
            'shape' => '198.0,583.4 178.4,556.1 162.9,526.7 151.7,495.8 145.0,464.0 142.7,431.9 144.6,400.0 150.2,368.8 159.1,338.5 170.7,309.4 184.7,281.5 200.8,254.8 218.7,229.2 354.1,311.4 345.1,324.8 337.6,339.0 331.7,353.7 327.4,368.9 324.9,384.4 324.0,400.0 324.9,415.6 327.4,431.1 331.7,446.3 337.6,461.0 345.1,475.2 354.1,488.6',
            'labels' => [
                ['n'=>'Versailles','x'=>293.9,'y'=>318.8,'s'=>15.4], ['n'=>'Sartrouville','x'=>313.2,'y'=>377.3,'s'=>15.4], ['n'=>'Mantes-la-Jolie','x'=>279.8,'y'=>481.3,'s'=>14.2], ['n'=>'Poissy','x'=>289.9,'y'=>417.5,'s'=>14.2], ['n'=>'Saint-Germain','x'=>209.9,'y'=>401.7,'s'=>14.2], ['n'=>'Les Mureaux','x'=>220.5,'y'=>268.5,'s'=>14.2], ['n'=>'Trappes','x'=>213.9,'y'=>322.6,'s'=>14.2], ['n'=>'Montigny-le-B.','x'=>194.2,'y'=>438.0,'s'=>12.8], ['n'=>'Guyancourt','x'=>239.8,'y'=>350.6,'s'=>12.8], ['n'=>'Élancourt','x'=>187.4,'y'=>484.1,'s'=>12.8], ['n'=>'Plaisir','x'=>255.4,'y'=>288.6,'s'=>12.8], ['n'=>'Conflans','x'=>185.1,'y'=>512.4,'s'=>12.8], ['n'=>'Houilles','x'=>268.9,'y'=>503.8,'s'=>12.8], ['n'=>'Chatou','x'=>172.8,'y'=>351.5,'s'=>12.8], ['n'=>'Maisons-Laffitte','x'=>250.0,'y'=>535.1,'s'=>12.8], ['n'=>'Vélizy','x'=>310.2,'y'=>342.2,'s'=>12.8], ['n'=>'Rambouillet','x'=>181.9,'y'=>382.4,'s'=>12.8], ['n'=>'Houdan','x'=>214.2,'y'=>554.0,'s'=>12.8],
            ],
        ],
        [
            'code' => '95',
            'name' => "Val-d'Oise",
            'delay' => "J ou J+1",
            'shape' => '255.6,202.0 270.9,186.5 287.2,171.7 304.4,157.8 322.8,144.7 342.1,132.8 362.5,122.0 383.8,112.7 405.9,104.8 428.8,98.4 452.2,93.8 476.0,90.9 500.0,89.7 500.0,241.6 487.7,242.0 475.5,243.1 463.4,245.1 451.5,247.7 439.8,251.2 428.4,255.3 417.4,260.1 406.7,265.7 396.5,271.9 386.9,278.7 377.7,286.1 369.2,294.0',
            'labels' => [
                ['n'=>'Argenteuil','x'=>438.9,'y'=>136.0,'s'=>15.4], ['n'=>'Cergy','x'=>446.0,'y'=>163.8,'s'=>15.4], ['n'=>'Sarcelles','x'=>314.7,'y'=>202.1,'s'=>14.2], ['n'=>'Franconville','x'=>326.7,'y'=>233.7,'s'=>14.2], ['n'=>'Garges-l-Gonesse','x'=>433.1,'y'=>221.0,'s'=>14.2], ['n'=>'Bezons','x'=>348.1,'y'=>157.2,'s'=>14.2], ['n'=>'Taverny','x'=>435.2,'y'=>198.0,'s'=>14.2], ['n'=>'Ermont','x'=>409.8,'y'=>247.1,'s'=>12.8], ['n'=>'Villiers-le-Bel','x'=>366.1,'y'=>273.1,'s'=>12.8], ['n'=>'Gonesse','x'=>297.3,'y'=>177.6,'s'=>12.8], ['n'=>'Herblay','x'=>415.6,'y'=>113.4,'s'=>12.8], ['n'=>'Pontoise','x'=>471.3,'y'=>105.8,'s'=>12.8], ['n'=>'Montigny-l-C.','x'=>355.8,'y'=>137.1,'s'=>12.8], ['n'=>'Sannois','x'=>374.3,'y'=>182.0,'s'=>12.8], ['n'=>'Enghien','x'=>355.1,'y'=>252.4,'s'=>12.8], ['n'=>'Domont','x'=>488.6,'y'=>188.9,'s'=>12.8], ['n'=>'Persan','x'=>489.4,'y'=>157.8,'s'=>12.8],
            ],
        ],
        [
            'code' => '77',
            'name' => "Seine-et-Marne",
            'delay' => "J ou J+1",
            'shape' => '556.6,37.4 627.7,53.2 694.2,80.3 754.5,117.1 807.2,162.4 850.3,215.5 881.2,275.1 897.8,339.0 899.0,404.2 885.9,467.5 861.0,526.9 827.2,581.6 786.3,632.0 630.8,506.0 649.8,483.2 663.9,457.6 672.8,430.2 676.0,401.8 673.5,373.4 665.4,345.8 651.9,320.0 633.5,296.8 610.8,276.9 584.4,261.0 555.4,249.6 524.5,243.1',
            'labels' => [
                ['n'=>'Meaux','x'=>750.2,'y'=>177.7,'s'=>15.4], ['n'=>'Chelles','x'=>816.1,'y'=>326.8,'s'=>15.4], ['n'=>'Melun','x'=>762.5,'y'=>212.3,'s'=>14.2], ['n'=>'Pontault-Combault','x'=>629.1,'y'=>279.2,'s'=>14.2], ['n'=>'Savigny-le-T.','x'=>731.1,'y'=>452.7,'s'=>14.2], ['n'=>'Villeparisis','x'=>723.6,'y'=>502.6,'s'=>14.2], ['n'=>'Champs-s-Marne','x'=>828.4,'y'=>267.6,'s'=>14.2], ['n'=>'Torcy','x'=>619.5,'y'=>232.1,'s'=>12.8], ['n'=>'Roissy-en-Brie','x'=>556.4,'y'=>135.8,'s'=>12.8], ['n'=>'Combs-la-Ville','x'=>681.9,'y'=>258.2,'s'=>12.8], ['n'=>'Provins','x'=>680.3,'y'=>216.9,'s'=>12.8], ['n'=>'Fontainebleau','x'=>825.2,'y'=>231.1,'s'=>12.8], ['n'=>'Nemours','x'=>824.8,'y'=>450.5,'s'=>12.8], ['n'=>'Coulommiers','x'=>797.5,'y'=>523.4,'s'=>12.8], ['n'=>'Brie-C-Robert','x'=>870.9,'y'=>411.6,'s'=>12.8], ['n'=>'Lagny-s-Marne','x'=>724.9,'y'=>541.6,'s'=>12.8], ['n'=>'Serris','x'=>813.2,'y'=>542.7,'s'=>12.8], ['n'=>'Mitry-Mory','x'=>619.0,'y'=>76.4,'s'=>12.8], ['n'=>'Clay-Souilly','x'=>869.4,'y'=>354.6,'s'=>12.8], ['n'=>'Dammarie','x'=>792.4,'y'=>422.7,'s'=>12.8],
            ],
        ],
        [
            'code' => '91',
            'name' => "Essonne",
            'delay' => "J ou J+1",
            'shape' => '720.1,636.1 686.9,662.3 650.1,685.4 610.0,704.6 566.7,718.8 521.2,726.9 474.5,728.2 428.2,722.5 383.7,709.9 342.1,691.4 304.3,667.8 270.7,640.2 241.2,609.7 369.2,506.0 385.3,520.1 403.3,532.3 422.8,542.4 443.7,550.1 465.4,555.3 487.7,558.0 510.2,558.1 532.6,555.7 554.4,550.6 575.3,543.2 595.0,533.3 613.1,521.3',
            'labels' => [
                ['n'=>'Évry-Courcouronnes','x'=>366.6,'y'=>554.8,'s'=>15.4], ['n'=>'Corbeil-Essonnes','x'=>599.7,'y'=>568.4,'s'=>15.4], ['n'=>'Massy','x'=>449.4,'y'=>706.5,'s'=>14.2], ['n'=>'Savigny-s-Orge','x'=>639.1,'y'=>633.3,'s'=>14.2], ['n'=>'Athis-Mons','x'=>438.4,'y'=>627.5,'s'=>14.2], ['n'=>'Palaiseau','x'=>429.4,'y'=>678.9,'s'=>14.2], ['n'=>'Draveil','x'=>390.1,'y'=>606.6,'s'=>14.2], ['n'=>'Yerres','x'=>360.7,'y'=>579.2,'s'=>12.8], ['n'=>'Ris-Orangis','x'=>269.6,'y'=>600.0,'s'=>12.8], ['n'=>'Grigny','x'=>574.7,'y'=>688.1,'s'=>12.8], ['n'=>'Ste-Geneviève','x'=>346.2,'y'=>676.8,'s'=>12.8], ['n'=>'Brétigny','x'=>334.5,'y'=>640.1,'s'=>12.8], ['n'=>'Les Ulis','x'=>503.6,'y'=>590.1,'s'=>12.8], ['n'=>'Orsay','x'=>307.6,'y'=>620.7,'s'=>12.8], ['n'=>'Gif-s-Yvette','x'=>413.7,'y'=>648.9,'s'=>12.8], ['n'=>'Étampes','x'=>494.0,'y'=>608.1,'s'=>12.8], ['n'=>'Dourdan','x'=>503.1,'y'=>639.7,'s'=>12.8], ['n'=>'Juvisy','x'=>616.5,'y'=>606.9,'s'=>12.8], ['n'=>'Montgeron','x'=>500.4,'y'=>713.9,'s'=>12.8], ['n'=>'Longjumeau','x'=>591.0,'y'=>652.2,'s'=>12.8],
            ],
        ],
        ],
        'list'   => [
            ['75', 'Paris'], ['92', 'Hauts-de-Seine'], ['93', 'Seine-Saint-Denis'], ['94', 'Val-de-Marne'],
            ['77', 'Seine-et-Marne'], ['78', 'Yvelines'], ['91', 'Essonne'], ['95', 'Val-d\'Oise'],
        ],
        'postcodes' => ['75', '77', '78', '91', '92', '93', '94', '95'],
    ],

    /* ------------------------------------------------------------------
     * 11. Chiffres clés — TOUS À VALIDER (sauf « 0 entrepôt »)
     * ---------------------------------------------------------------- */
    'stats' => [
        'number' => '06',
        'label'  => 'En chiffres',
        'items' => [
            ['value' => '0', 'unit' => '', 'label' => 'entrepôt', 'text' => 'Et c\'est voulu : vos produits vont directement chez vous.', 'certain' => true],
            ['value' => '4', 'unit' => '', 'label' => 'camionnettes types', 'text' => 'Du Caddy au Master.', 'certain' => false],
            ['value' => '8', 'unit' => '', 'label' => 'départements desservis', 'text' => 'Toute l\'Île-de-France.', 'certain' => false],
            ['value' => '98', 'unit' => '%', 'label' => 'livraisons dans le créneau', 'text' => 'À VALIDER — sur les 12 derniers mois.', 'certain' => false],
            ['value' => '< 24', 'unit' => 'h', 'label' => 'pour recevoir votre devis', 'text' => 'À VALIDER.', 'certain' => false],
        ],
        'photo' => 'assets/img/frais/legumes-cagette.jpg',
    ],

    /* ------------------------------------------------------------------
     * 12. Engagements — bon de livraison
     * ---------------------------------------------------------------- */
    'commitments' => [
        'number' => '07',
        'label'  => 'Nos engagements',
        'title_a'=> 'Nos engagements,',
        'title_b'=> 'noir sur blanc.',
        'highlight' => 'noir sur blanc',
        'doc_title' => 'Bon de livraison',
        'doc_number' => 'BL-2026-0001',
        'doc_sign' => 'Livré conforme',
        'doc_client' => 'Client',
        'doc_vehicle' => 'Véhicule',
        'columns' => ['Engagement', 'Détail'],
        'items' => [
            ['title' => 'Chaîne du froid respectée', 'text' => 'Caissons pré-refroidis, température contrôlée pendant la tournée.'],
            ['title' => 'Créneau respecté', 'text' => 'Nous livrons à l\'heure convenue, selon vos horaires de réception.'],
            ['title' => 'Hygiène irréprochable', 'text' => 'Véhicules nettoyés et désinfectés régulièrement.'],
            ['title' => 'Manipulation soignée', 'text' => 'Chauffeurs formés au transport de denrées fragiles.'],
            ['title' => 'Un interlocuteur direct', 'text' => 'Vous parlez à quelqu\'un qui connaît vos livraisons. Pas de plateforme, pas de ticket.'],
            ['title' => 'Prévenu en cas d\'imprévu', 'text' => 'Un retard possible ? Vous êtes informé, avec une solution.'],
        ],
    ],

    /* ------------------------------------------------------------------
     * 13. Galerie
     * ---------------------------------------------------------------- */
    'gallery' => [
        'number' => '08',
        'label'  => 'Sur la route',
        'title_a'=> 'Sur la route,',
        'title_b'=> 'tous les matins.',
        'highlight' => 'tous les matins',
        'hint' => 'Glisser',
        'items' => [
            ['src' => 'assets/img/frais/legumes-cagette.jpg', 'time' => '05:40', 'caption' => 'Chargement', 'ratio' => '4/5'],
            ['src' => 'assets/img/scenes/cuisine-restaurant.jpg', 'time' => '06:15', 'caption' => 'Paris 13e', 'ratio' => '3/2'],
            ['src' => 'assets/img/frais/tomates.jpg', 'time' => '06:40', 'caption' => 'Primeur, Alfortville', 'ratio' => '1/1'],
            ['src' => 'assets/img/frais/poisson-glace.jpg', 'time' => '07:00', 'caption' => 'Mise en place', 'ratio' => '4/5'],
            ['src' => 'assets/img/scenes/traiteur-buffet.jpg', 'time' => '08:30', 'caption' => 'Traiteur, Neuilly', 'ratio' => '3/2'],
            ['src' => 'assets/img/frais/pain.jpg', 'time' => '09:10', 'caption' => 'Boulangerie, Vincennes', 'ratio' => '1/1'],
            ['src' => 'assets/img/frais/fromages.jpg', 'time' => '10:00', 'caption' => 'Fromagerie, Paris 11e', 'ratio' => '4/5'],
            ['src' => 'assets/img/frais/fruits-rouges.jpg', 'time' => '10:45', 'caption' => 'Retour de tournée', 'ratio' => '3/2'],
        ],
        'note' => 'Moments illustratifs — à remplacer par de vraies photos DST dès que possible.',
    ],

    /* ------------------------------------------------------------------
     * 14. Témoignages — VIDE tant qu'il n'y a pas de vrais avis.
     *     Exemple de structure :
     *     ['quote' => '…', 'name' => '…', 'role' => '…', 'company' => '…', 'sector' => 'restaurants']
     * ---------------------------------------------------------------- */
    'testimonials' => [],
    'testimonials_title' => 'Ils nous confient leurs livraisons.',
    'google_rating' => null, // ex. ['value' => 4.8, 'count' => 27, 'url' => 'https://g.page/…']

    /* ------------------------------------------------------------------
     * 15. Devis / formulaire
     * ---------------------------------------------------------------- */
    'form' => [
        'number' => '10',
        'label'  => 'Devis',
        'title_a'=> 'Racontez-nous',
        'title_b'=> 'vos livraisons.',
        'highlight' => 'vos livraisons',
        'reassurance' => ['Devis gratuit', 'Sans engagement', 'Réponse rapide'],
        'to_email' => 'contact@exemple.fr',   // À REMPLACER
        'send_ack' => true,
        'rate_limit_hour' => 5,
        'min_seconds' => 4,
        'consent_label' => 'J\'accepte que mes données soient utilisées pour traiter ma demande.',
        'hint' => 'DST · Réponse ' . 'sous 24 h',
        'typing' => 'DST écrit',
        'classic_link' => 'Préférez un formulaire classique ?',
        'chat_link' => 'Revenir à la conversation',
        'privacy_notice' => 'Vos données servent uniquement à traiter votre demande. Elles sont conservées 3 ans puis supprimées.',
    ],

    /* ------------------------------------------------------------------
     * 16. FAQ
     * ---------------------------------------------------------------- */
    'faq' => [
        'number' => '11',
        'label'  => 'Questions fréquentes',
        'items' => [
            ['q' => 'Avez-vous un entrepôt ?', 'a' => 'Non, et c\'est un choix. Nous chargeons vos marchandises chez votre fournisseur et les livrons directement chez vous, sans stockage intermédiaire. Résultat : moins de manipulations et des produits plus frais.'],
            ['q' => 'Où se trouve DST ?', 'a' => 'Notre siège social est situé à Arcueil (94). Il s\'agit d\'une adresse administrative : nous n\'y réceptionnons pas de marchandises.'],
            ['q' => 'Quelles températures assurez-vous ?', 'a' => 'Nous transportons en froid positif, de 0 à +4 °C, adapté à la grande majorité des produits frais. Pour un besoin spécifique, contactez-nous.'],
            ['q' => 'Dans quelles zones livrez-vous ?', 'a' => 'Paris et l\'ensemble de l\'Île-de-France (75, 92, 93, 94, 77, 78, 91, 95). Au-delà, nous étudions chaque demande.'],
            ['q' => 'Pouvez-vous livrer tôt le matin ?', 'a' => 'Oui, nos tournées s\'organisent selon vos horaires de réception, y compris avant l\'ouverture ou la mise en place.'],
            ['q' => 'Comment est calculé le prix ?', 'a' => 'Selon le volume, la distance, la fréquence et les contraintes horaires. Le devis est gratuit et sans engagement.'],
            ['q' => 'Travaillez-vous avec les particuliers ?', 'a' => 'Nous travaillons principalement avec les professionnels de l\'alimentation. Pour une demande particulière, écrivez-nous.'],
        ],
    ],

    /* ------------------------------------------------------------------
     * 17. Bande d'appel, footer
     * ---------------------------------------------------------------- */
    'call_band' => [
        'text' => 'Une livraison à caler demain matin ?',
        'cta'  => 'Appeler DST',
        'mobile_cta' => 'Appeler',
    ],
    'footer' => [
        'tagline' => 'Transport frigorifique de produits frais à Paris et en Île-de-France.',
        'columns' => ['Siège social', 'Contact', 'Suivez-nous'],
        'legal_links' => ['Mentions légales', 'Confidentialité', 'Cookies'],
        'cookie_notice' => 'Ce site n\'utilise que des cookies techniques.',
        'cookie_ok' => 'OK',
    ],

    /* ------------------------------------------------------------------
     * 18. Hébergement & crédits (mentions légales)
     * ---------------------------------------------------------------- */
    'hosting' => [
        'name'    => '',   // À COMPLÉTER
        'address' => '',   // À COMPLÉTER
        'phone'   => '',   // À COMPLÉTER
        'site'    => '',   // À COMPLÉTER
    ],
    'image_credits' => [
        // ['label' => '…', 'author' => '…', 'license' => '…', 'url' => '…'],
    ],
    'photos_note' => 'Visuels d\'illustration générés pour la maquette : non contractuels, à remplacer par des photographies de la flotte et des produits DST.',

    /* ------------------------------------------------------------------
     * 19. SEO
     * ---------------------------------------------------------------- */
    'seo' => [
        'title'       => 'Livraison de produits frais en camion frigorifique — Paris & Île-de-France | DST',
        'description' => 'DST livre vos produits frais en camionnettes frigorifiques, directement du fournisseur à votre porte, sans entrepôt. Paris et toute l\'Île-de-France. Devis gratuit.',
        'url'         => 'https://www.exemple.fr', // À REMPLACER
        'locale'      => 'fr_FR',
        'keywords'    => ['livraison produits frais Paris', 'transport frigorifique Île-de-France', 'camionnette frigorifique avec chauffeur', 'livraison restaurants Paris', 'transporteur frais Val-de-Marne'],
        'og_image'    => 'assets/img/og-image.jpg',
        'theme_color' => '#1F7A4C',
    ],

    /* ------------------------------------------------------------------
     * 20. SMTP (optionnel, sans Composer) — activable plus tard
     * ---------------------------------------------------------------- */
    'smtp' => [
        'enabled' => false,
        'host'    => '',
        'port'    => 587,
        'user'    => '',
        'pass'    => '',
        'from'    => '',
        'from_name' => 'DST — site web',
        'secure'  => true, // STARTTLS
    ],

    /* ------------------------------------------------------------------
     * 21. Crédit agence
     * ---------------------------------------------------------------- */
    'credits' => [
        'agency_url'   => 'https://primevisionsolutions.fr',
        'agency_label' => 'primevisionsolutions.fr',
        'agency_text'  => 'Site créé par Prime Vision Solutions 2026',
    ],

    'site' => [
        'lang'    => 'fr',
        'version' => '2.0',
        'timezone'=> 'Europe/Paris',
    ],
];
