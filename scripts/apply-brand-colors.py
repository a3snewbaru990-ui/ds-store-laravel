"""Apply the DS logo palette (steel silver + antique gold on charcoal) globally."""

from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]

REPLACEMENTS = {
    # Steel / silver accents (replaces cyan)
    "#06b6d4": "#9AA5AD",
    "#22d3ee": "#C3CCD3",
    "#a5f3fc": "#E3E8EC",
    "#0e7490": "#5F6B74",
    "#0891b2": "#6F7A83",
    "rgba(6, 182, 212,": "rgba(154, 165, 173,",
    "rgba(34, 211, 238,": "rgba(195, 204, 211,",
    "rgba(165, 243, 252,": "rgba(227, 232, 236,",
    "rgba(14, 116, 144,": "rgba(95, 107, 116,",

    # Antique gold (replaces bright amber)
    "#f59e0b": "#C9A227",
    "#fbbf24": "#E0BE6B",
    "#fde68a": "#F0D696",
    "#d97706": "#8A6D24",
    "rgba(245, 158, 11,": "rgba(201, 162, 39,",
    "rgba(251, 191, 36,": "rgba(224, 190, 107,",
    "rgba(253, 230, 138,": "rgba(240, 214, 150,",

    # Charcoal neutrals (replaces blue slate)
    "#050b14": "#0A0D10",
    "#08111f": "#14181C",
    "#0a1524": "#1B2025",
    "#0d1a2c": "#23292F",
    "#112238": "#2C333A",
    "#172c46": "#3A434B",
    "#071120": "#11161A",
    "#060e19": "#0B0F12",
    "#0b1b2f": "#1A2026",
    "#0f172a": "#14181C",
    "#04121b": "#0A0D10",
    "#020a11": "#070A0D",
    "#1b1002": "#1A1508",
    "rgba(7, 17, 32,": "rgba(17, 22, 26,",
    "rgba(9, 20, 34,": "rgba(20, 24, 28,",
    "rgba(148, 190, 214,": "rgba(170, 178, 185,",
    "rgba(190, 198, 205,": "rgba(190, 198, 205,",

    # Text tones to neutral
    "#edf6fd": "#F2F4F6",
    "#c3d4e3": "#CBD2D8",
    "#8ca4b9": "#9AA5AD",
}


def targets():
    yield from (ROOT / "resources").rglob("*.css")
    yield from (ROOT / "resources").rglob("*.blade.php")
    yield ROOT / "public" / "site.webmanifest"


def main() -> None:
    for path in targets():
        if not path.exists():
            continue
        text = path.read_text(encoding="utf-8")
        original = text
        for old, new in REPLACEMENTS.items():
            text = text.replace(old, new)
        if text != original:
            path.write_text(text, encoding="utf-8")
            print(f"updated {path.relative_to(ROOT)}")


if __name__ == "__main__":
    main()
