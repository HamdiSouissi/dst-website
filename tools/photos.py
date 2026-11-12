#!/usr/bin/env python3
"""Régénère les variantes d'une photo : 640 / 1100 / 1600 (jpg + webp) et le lqip.

    pip install pillow
    python3 tools/photos.py assets/img/photos/ma-photo.jpg
"""
import sys, os
from PIL import Image, ImageFilter

for src in sys.argv[1:]:
    base = os.path.splitext(src)[0]
    im = Image.open(src).convert('RGB')
    w, h = im.size
    for width in (1600, 1100, 640):
        nw = min(width, w)
        nh = round(h * nw / w)
        out = im.resize((nw, nh), Image.LANCZOS)
        out.save(f'{base}-{width}.jpg', quality=82, optimize=True, progressive=True)
        out.save(f'{base}-{width}.webp', quality=78, method=6)
    lq = im.resize((32, max(1, round(32 * h / w))), Image.LANCZOS).filter(ImageFilter.GaussianBlur(1.2))
    lq.save(f'{base}-lqip.jpg', quality=40)
    print('ok', base)
