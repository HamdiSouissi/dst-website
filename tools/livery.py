#!/usr/bin/env python3
"""
Habillage des camionnettes : pose le logo officiel DST en perspective sur une photo.

    from livery import panel, wrap

    # les 4 coins du panneau, dans l'ordre : haut-gauche, haut-droit, bas-droit, bas-gauche
    quad = [(255, 205), (860, 232), (860, 628), (255, 630)]
    wrap('assets/img/photos/src-hero-side.jpg', 'assets/img/photos/hero-01.jpg', quad,
         center=(0.46, 0.44), width=0.34)

Le logo est appliqué en mode « produit » (multiply) : il prend la lumière, les reflets et
les ombres du panneau, exactement comme un adhésif imprimé. Aucune retouche de couleur.
"""
from __future__ import annotations

import math
import os
from PIL import Image, ImageChops, ImageDraw, ImageFilter, ImageStat

HERE = os.path.dirname(os.path.abspath(__file__))
BRAND = os.path.join(HERE, '..', 'assets', 'img', 'brand')
_cache: dict[str, Image.Image] = {}


# --------------------------------------------------------------------------- #
#  algèbre : homographie 4 points -> 4 points
# --------------------------------------------------------------------------- #
def _solve(a: list[list[float]], b: list[float]) -> list[float]:
    """Gauss-Jordan sur un système 8×8 (sans numpy)."""
    n = len(b)
    m = [row[:] + [b[i]] for i, row in enumerate(a)]
    for col in range(n):
        pivot = max(range(col, n), key=lambda r: abs(m[r][col]))
        m[col], m[pivot] = m[pivot], m[col]
        d = m[col][col]
        if abs(d) < 1e-12:
            raise ValueError('système dégénéré')
        m[col] = [v / d for v in m[col]]
        for r in range(n):
            if r != col and m[r][col]:
                f = m[r][col]
                m[r] = [m[r][k] - f * m[col][k] for k in range(n + 1)]
    return [m[i][n] for i in range(n)]


def homography(src: list[tuple[float, float]], dst: list[tuple[float, float]]) -> list[float]:
    """Retourne les 9 coefficients (h22 = 1) qui envoient src sur dst."""
    a, b = [], []
    for (x, y), (u, v) in zip(src, dst):
        a.append([x, y, 1, 0, 0, 0, -u * x, -u * y]); b.append(u)
        a.append([0, 0, 0, x, y, 1, -v * x, -v * y]); b.append(v)
    h = _solve(a, b) + [1.0]
    return h


def _invert(h: list[float]) -> list[float]:
    """Inverse d'une matrice 3×3 donnée à plat."""
    m = [h[0:3], h[3:6], h[6:9]]
    det = (m[0][0] * (m[1][1] * m[2][2] - m[1][2] * m[2][1])
           - m[0][1] * (m[1][0] * m[2][2] - m[1][2] * m[2][0])
           + m[0][2] * (m[1][0] * m[2][1] - m[1][1] * m[2][0]))
    cof = [
        [m[1][1] * m[2][2] - m[1][2] * m[2][1], -(m[0][1] * m[2][2] - m[0][2] * m[2][1]), m[0][1] * m[1][2] - m[0][2] * m[1][1]],
        [-(m[1][0] * m[2][2] - m[1][2] * m[2][0]), m[0][0] * m[2][2] - m[0][2] * m[2][0], -(m[0][0] * m[1][2] - m[0][2] * m[1][0])],
        [m[1][0] * m[2][1] - m[1][1] * m[2][0], -(m[0][0] * m[2][1] - m[0][1] * m[2][0]), m[0][0] * m[1][1] - m[0][1] * m[1][0]],
    ]
    return [cof[r][c] / det for r in range(3) for c in range(3)]


def project(h: list[float], x: float, y: float) -> tuple[float, float]:
    w = h[6] * x + h[7] * y + h[8]
    return ((h[0] * x + h[1] * y + h[2]) / w, (h[3] * x + h[4] * y + h[5]) / w)


# --------------------------------------------------------------------------- #
#  le décalque
# --------------------------------------------------------------------------- #
def logo_image(tone: str = 'ink', px: int = 1200) -> Image.Image:
    key = f'{tone}-{px}'
    if key in _cache:
        return _cache[key]
    # on rasterise le SVG du logo à la volée (resvg hors production) ou on réutilise le PNG
    png = os.path.join(BRAND, f'logo-{tone}.png')
    if not os.path.exists(png):
        raise FileNotFoundError(f'rendre {png} avec tools/logo_png.sh')
    im = Image.open(png).convert('RGBA')
    im = im.resize((px, px), Image.LANCZOS)
    _cache[key] = im
    return im


def decal(tone: str = 'ink', rule: bool = True, rule_color=(243, 195, 0)) -> Image.Image:
    """Le visuel complet de l'habillage : le logo + le filet jaune, fond blanc (neutre)."""
    logo = logo_image(tone)
    w = logo.size[0]
    rule_h = 0
    gap = 0
    if rule:
        rule_h = int(w * 0.055)
        gap = int(w * 0.075)
    # fond transparent : seules les encres multiplient la peinture, pas un rectangle blanc
    canvas = Image.new('RGBA', (w, w + gap + rule_h), (255, 255, 255, 0))
    canvas.paste(logo, (0, 0), logo)
    if rule:
        d = ImageDraw.Draw(canvas)
        d.rectangle([int(w * 0.06), w + gap, int(w * 0.94), w + gap + rule_h],
                    fill=rule_color + (255,))
    return canvas


def vest_patch(tone: str = 'ink', bg=(250, 246, 238)) -> Image.Image:
    """Écusson textile : le logo imprimé sur une étiquette crème cousue sur le gilet."""
    logo = logo_image(tone)
    w = logo.size[0]
    pad = int(w * 0.11)
    canvas = Image.new('RGBA', (w + 2 * pad, w + 2 * pad), (0, 0, 0, 0))
    d = ImageDraw.Draw(canvas)
    d.rounded_rectangle([0, 0, canvas.size[0] - 1, canvas.size[1] - 1],
                        radius=int(w * 0.08), fill=bg + (255,))
    canvas.paste(logo, (pad, pad), logo)
    return canvas


# --------------------------------------------------------------------------- #
#  pose
# --------------------------------------------------------------------------- #
def _panel_h(quad):
    """Homographie (0,0)-(1,1) -> quad."""
    return homography([(0, 0), (1, 0), (1, 1), (0, 1)], quad)


def _apply(img: Image.Image, dec: Image.Image, dst_quad, strength: float = 1.0,
           blur: float = 0.0) -> Image.Image:
    """Applique `dec` dans `dst_quad` en mode produit (l'encre prend la lumière du panneau)."""
    W, H = img.size
    dw, dh = dec.size
    h = homography([(0, 0), (dw, 0), (dw, dh), (0, dh)], dst_quad)
    inv = _invert(h)
    coeffs = tuple(inv[i] for i in (0, 1, 2, 3, 4, 5, 6, 7))
    warped = dec.transform((W, H), Image.PERSPECTIVE, coeffs, resample=Image.BICUBIC)
    if blur:
        warped = warped.filter(ImageFilter.GaussianBlur(blur))
    mask = warped.split()[3]
    if strength < 1.0:
        mask = mask.point(lambda v: int(v * strength))
    prod = ImageChops.multiply(img.convert('RGB'), warped.convert('RGB'))
    return Image.composite(prod, img.convert('RGB'), mask)


def wrap(src: str, dst: str, quad, center=(0.46, 0.44), width=0.34,
         tone='ink', rule=True, strength=0.96, blur=0.6, quality=90):
    """Pose l'habillage sur un panneau décrit par ses 4 coins (HG, HD, BD, BG)."""
    img = Image.open(src).convert('RGB')
    dec = decal(tone=tone, rule=rule)
    hp = _panel_h(quad)
    dw_dh = dec.size[1] / dec.size[0]
    w = width
    panel_w = math.dist(quad[0], quad[1])
    panel_h = math.dist(quad[0], quad[3])
    h = w * dw_dh * (panel_w / panel_h)
    cx, cy = center
    u0, v0 = cx - w / 2, cy - h / 2
    corners = [project(hp, u, v) for u, v in
               [(u0, v0), (u0 + w, v0), (u0 + w, v0 + h), (u0, v0 + h)]]
    out = _apply(img, dec, corners, strength=strength, blur=blur)
    os.makedirs(os.path.dirname(dst) or '.', exist_ok=True)
    out.save(dst, quality=quality, optimize=True, progressive=True)
    return out


def patch(src: str, dst: str, quad, tone='ink', strength=0.96, blur=0.35, quality=92):
    """Écusson sur un vêtement : `quad` = les 4 coins de l'étiquette sur le tissu.

    Contrairement au panneau (mode produit), on compose normalement : une étiquette crème
    ne peut pas s'obtenir en multipliant du jaune. On réinjecte la lumière du tissu pour
    que l'écusson ne « flotte » pas.
    """
    img = Image.open(src).convert('RGB')
    dec = vest_patch(tone=tone)
    W, H = img.size
    dw, dh = dec.size
    h = homography([(0, 0), (dw, 0), (dw, dh), (0, dh)], quad)
    inv = _invert(h)
    coeffs = tuple(inv[i] for i in (0, 1, 2, 3, 4, 5, 6, 7))
    warped = dec.transform((W, H), Image.PERSPECTIVE, coeffs, resample=Image.BICUBIC)
    if blur:
        warped = warped.filter(ImageFilter.GaussianBlur(blur))
    mask = warped.split()[3]
    if strength < 1.0:
        mask = mask.point(lambda v: int(v * strength))
    # lumière locale : l'écusson suit les plis du gilet et prend sa teinte
    blur_r = max(1.5, dw / 18)
    lum = img.convert('L').filter(ImageFilter.GaussianBlur(blur_r))
    stat = ImageStat.Stat(lum.crop(tuple(int(v) for v in
             (min(p[0] for p in quad), min(p[1] for p in quad),
              max(p[0] for p in quad), max(p[1] for p in quad)))))
    mean = max(30.0, stat.mean[0])
    tint = lum.point(lambda v: max(0, min(255, int(255 * (v / mean)))))
    lit = ImageChops.multiply(warped.convert('RGB'), Image.merge('RGB', (tint, tint, tint)))
    out = Image.composite(lit, img, mask)
    os.makedirs(os.path.dirname(dst) or '.', exist_ok=True)
    out.save(dst, quality=quality, optimize=True, progressive=True)
    return out
