"""Audit the logo-derived palette with OKLCH + WCAG contrast."""

import math
from collections import Counter
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
LOGO = ROOT / "public" / "images" / "logo-mark.png"


def srgb_to_linear(c: float) -> float:
    c /= 255.0
    return c / 12.92 if c <= 0.04045 else ((c + 0.055) / 1.055) ** 2.4


def rgb_to_oklch(rgb):
    r, g, b = (srgb_to_linear(v) for v in rgb)
    l = 0.4122214708 * r + 0.5363325363 * g + 0.0514459929 * b
    m = 0.2119034982 * r + 0.6806995451 * g + 0.1073969566 * b
    s = 0.0883024619 * r + 0.2817188376 * g + 0.6299787005 * b
    l_, m_, s_ = (math.copysign(abs(v) ** (1 / 3), v) for v in (l, m, s))
    L = 0.2104542553 * l_ + 0.793617785 * m_ - 0.0040720468 * s_
    a = 1.9779984951 * l_ - 2.428592205 * m_ + 0.4505937099 * s_
    bb = 0.0259040371 * l_ + 0.7827717662 * m_ - 0.808675766 * s_
    C = math.hypot(a, bb)
    H = math.degrees(math.atan2(bb, a)) % 360
    return L, C, H


def luminance(rgb):
    r, g, b = (srgb_to_linear(v) for v in rgb)
    return 0.2126 * r + 0.7152 * g + 0.0722 * b


def contrast(a, b):
    la, lb = luminance(a), luminance(b)
    hi, lo = max(la, lb), min(la, lb)
    return (hi + 0.05) / (lo + 0.05)


def hexrgb(value):
    value = value.lstrip("#")
    return tuple(int(value[i : i + 2], 16) for i in (0, 2, 4))


def dominant_colors():
    image = Image.open(LOGO).convert("RGBA")
    counts = Counter()
    for r, g, b, a in image.getdata():
        if a < 160 or min(r, g, b) > 240:
            continue
        counts[(r // 16 * 16, g // 16 * 16, b // 16 * 16)] += 1
    return counts.most_common(8)


def main():
    print("Logo dominant colors (quantized):")
    for color, count in dominant_colors():
        L, C, H = rgb_to_oklch(color)
        print(f"  #{color[0]:02X}{color[1]:02X}{color[2]:02X}  n={count:5d}  oklch({L:.3f} {C:.3f} {H:.1f})")

    pairs = [
        ("body text", "#F2F4F6", "#14181C", 4.5),
        ("secondary text", "#CBD2D8", "#14181C", 4.5),
        ("muted text", "#9AA5AD", "#14181C", 4.5),
        ("gold accent text", "#E8C96B", "#14181C", 4.5),
        ("gold button label", "#1A1508", "#C9A227", 4.5),
        ("steel button label", "#1A1508", "#C3CCD3", 4.5),
        ("field edge", "#9AA5AD", "#1A2026", 3.0),
        ("gold edge", "#C9A227", "#14181C", 3.0),
    ]
    print("\nContrast checks:")
    for name, fg, bg, minimum in pairs:
        ratio = contrast(hexrgb(fg), hexrgb(bg))
        status = "PASS" if ratio >= minimum else "FAIL"
        print(f"  {status}  {name:20s} {ratio:5.2f}:1  (min {minimum})")


if __name__ == "__main__":
    main()
