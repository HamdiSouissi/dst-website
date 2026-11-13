#!/usr/bin/env python3
"""
Habillage DST : applique le logo officiel sur les caisses et les gilets.

    /tmp/venv/bin/python tools/wrap_photos.py [nom ...]

Les photos sources (`src-*.jpg`) sont des prises de vue de véhicules **vierges** ; le logo
est posé ici en perspective (mode produit) pour épouser la lumière du panneau.
"""
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import livery  # noqa: E402

PH = 'assets/img/photos'

# quad = 4 coins du panneau, dans l'ordre : haut-gauche, haut-droit, bas-droit, bas-gauche
JOBS = [
    # --- véhicules ---
    dict(name='hero-01', src=f'{PH}/src-hero-side.jpg',
         panel=[(255, 205), (858, 232), (858, 628), (255, 630)],
         center=(0.44, 0.42), width=0.42),

    dict(name='hero-02', src=f'{PH}/src-hero-rear.jpg',
         panel=[(572, 264), (697, 258), (697, 575), (572, 580)],
         center=(0.52, 0.38), width=0.84),

    dict(name='route-01', src=f'{PH}/src-route.jpg',
         panel=[(640, 252), (858, 205), (860, 500), (643, 480)],
         center=(0.50, 0.36), width=0.44),

    dict(name='flotte-01', src=f'{PH}/src-flotte.jpg',
         panels=[
             ([(205, 352), (344, 362), (346, 510), (205, 522)], (0.44, 0.50), 0.52),
             ([(452, 335), (636, 325), (636, 502), (452, 515)], (0.26, 0.55), 0.40),
             ([(1062, 212), (1330, 190), (1335, 480), (1062, 505)], (0.50, 0.44), 0.42),
         ]),

    dict(name='livraison-01', src=f'{PH}/src-livraison.jpg',
         panel=[(455, 300), (525, 296), (528, 520), (458, 524)],
         center=(0.50, 0.18), width=0.60,
         vests=[[(647, 322), (683, 319), (685, 357), (649, 360)]]),

    dict(name='boulangerie-01', src=f'{PH}/src-boulangerie.jpg',
         panel=[(60, 340), (330, 315), (332, 570), (60, 610)],
         center=(0.45, 0.42), width=0.55,
         vests=[[(709, 385), (739, 382), (741, 414), (711, 417)]]),

    dict(name='chargement-01', src=f'{PH}/src-chargement.jpg',
         vests=[[(583, 355), (621, 352), (623, 392), (585, 395)],
                [(811, 349), (851, 346), (852, 388), (812, 391)]]),

    dict(name='traiteur-01', src=f'{PH}/src-traiteur.jpg',
         panel=[(958, 132), (1237, 120), (1237, 505), (958, 468)],
         center=(0.52, 0.40), width=0.34,
         vests=[[(670, 310), (710, 306), (712, 346), (672, 350)]]),

    dict(name='epicerie-01', src=f'{PH}/src-epicerie.jpg',
         panel=[(527, 150), (717, 132), (717, 635), (527, 650)],
         center=(0.50, 0.40), width=0.66,
         vests=[[(789, 361), (827, 358), (829, 398), (791, 401)]]),
]


def run(only=None):
    for job in JOBS:
        if only and job['name'] not in only:
            continue
        out = f"{PH}/{job['name']}.jpg"
        if 'panels' in job:
            from PIL import Image
            img = Image.open(job['src']).convert('RGB')
            for quad, center, width in job['panels']:
                tmp = '/tmp/_step.jpg'
                img.save(tmp, quality=95)
                img = livery.wrap(tmp, tmp, quad, center=center, width=width)
            img.save(out, quality=90, optimize=True, progressive=True)
        elif 'panel' in job:
            livery.wrap(job['src'], out, job['panel'],
                        center=job['center'], width=job['width'])
        else:
            from PIL import Image
            Image.open(job['src']).convert('RGB').save(out, quality=92,
                                                       optimize=True, progressive=True)
        for quad in job.get('vests', []):
            livery.patch(out, out, quad)
        print('ok', job['name'])


if __name__ == '__main__':
    run(sys.argv[1:] or None)
