"""Remove the white background from the MLBB rank emblems.

Source: macam macm logo rank mlbb.jpg
Output: public/images/rank-*.webp (transparent, 256x256)

The source is a JPEG with a near-white background. We flood-fill the
background from the crop borders, then soften only the silhouette edge so
white highlights inside the emblem stay intact. Edge colours are
decontaminated against the sampled background to avoid a white fringe.
"""

from pathlib import Path

import numpy as np
from PIL import Image, ImageFilter
from scipy import ndimage

ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / "macam macm logo rank mlbb.jpg"
OUTPUT = ROOT / "public" / "images"
BACKUP = ROOT / "storage" / "app" / "asset-backups" / "rank-emblems-white-bg"

# Crop windows include a little breathing room but stop above the magenta
# rank labels in the source sheet.
CROPS = {
    "rank-grandmaster": (25, 210, 147, 328),
    "rank-epic": (150, 210, 269, 328),
    "rank-legend": (275, 210, 391, 329),
    "rank-mythic": (27, 365, 145, 483),
    "rank-mythic-honor": (150, 368, 269, 483),
    "rank-mythic-glory": (275, 367, 391, 482),
    "rank-mythic-immortal": (153, 537, 267, 656),
}

SIZE = 256
PADDING = 8


def remove_background(crop: Image.Image) -> Image.Image:
    rgb = np.asarray(crop.convert("RGB"), dtype=np.float32)

    # Sample the background from the crop border (JPEG white is not uniform).
    border = np.concatenate([rgb[0], rgb[-1], rgb[:, 0], rgb[:, -1]])
    bg = np.median(border, axis=0)

    # Chebyshev distance from the background colour.
    distance = np.max(np.abs(rgb - bg), axis=2)

    # Background = light pixels connected to the crop border.
    candidates = distance < 20
    labels, _ = ndimage.label(candidates)
    border_labels = set(labels[0]) | set(labels[-1])
    border_labels |= set(labels[:, 0]) | set(labels[:, -1])
    border_labels.discard(0)
    background = np.isin(labels, list(border_labels))

    alpha = np.where(background, 0.0, 1.0)

    # Recover anti-aliased edges: in a narrow band around the silhouette,
    # turn the distance from white into partial alpha. This keeps interior
    # white highlights fully opaque because they are not in the band.
    band = ndimage.binary_dilation(background, iterations=2) & ~background
    soft = np.clip((distance - 10) / (50 - 10), 0.0, 1.0)
    alpha[band] = soft[band]

    alpha = np.asarray(
        Image.fromarray((alpha * 255).astype(np.uint8), "L").filter(
            ImageFilter.GaussianBlur(0.65)
        )
    ).astype(np.float32) / 255.0

    # Unmix the white background from partially transparent edge pixels.
    safe_alpha = np.maximum(alpha, 0.04)[..., None]
    foreground = (rgb - (1.0 - alpha[..., None]) * bg) / safe_alpha
    foreground = np.clip(foreground, 0, 255)

    rgba = np.dstack([foreground, alpha * 255.0]).astype(np.uint8)
    return Image.fromarray(rgba, "RGBA")


def square_canvas(emblem: Image.Image) -> Image.Image:
    # Trim almost-invisible JPEG noise before centring.
    alpha = np.asarray(emblem)[..., 3]
    ys, xs = np.where(alpha > 18)
    if len(xs) == 0:
        raise ValueError("No emblem pixels found")
    emblem = emblem.crop((xs.min(), ys.min(), xs.max() + 1, ys.max() + 1))

    side = max(emblem.size) + PADDING * 2
    canvas = Image.new("RGBA", (side, side), (0, 0, 0, 0))
    canvas.paste(
        emblem,
        ((side - emblem.width) // 2, (side - emblem.height) // 2),
        emblem,
    )
    return canvas.resize((SIZE, SIZE), Image.LANCZOS)


def main() -> None:
    source = Image.open(SOURCE).convert("RGB")
    BACKUP.mkdir(parents=True, exist_ok=True)
    OUTPUT.mkdir(parents=True, exist_ok=True)

    for name, box in CROPS.items():
        target = OUTPUT / f"{name}.webp"
        if target.exists():
            backup_target = BACKUP / f"{name}.webp"
            if not backup_target.exists():
                backup_target.write_bytes(target.read_bytes())

        crop = source.crop(box)
        emblem = square_canvas(remove_background(crop))
        emblem.save(target, "WEBP", lossless=True, quality=100, method=6)
        print(f"{target.name}: {target.stat().st_size // 1024} KiB")


if __name__ == "__main__":
    main()
