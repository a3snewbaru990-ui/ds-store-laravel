"""Package the Laravel source for submission, excluding heavy generated folders."""

from pathlib import Path
from zipfile import ZIP_DEFLATED, ZipFile

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "docs" / "bagian-3" / "04-Source-Code" / "DS-STORE-Source.zip"

EXCLUDE_DIRS = {
    "vendor", "node_modules", "docs", ".git",
}
EXCLUDE_FILES = {
    ".env", "composer-setup.php", "DS-STORE-Source.zip",
    ".DS_Store", "Thumbs.db",
}
# Keep Laravel's storage skeleton (.gitignore placeholders) but skip logs,
# compiled views, sessions, and the rank-image backups.
def should_skip(rel: Path) -> bool:
    parts = rel.parts
    if any(part in EXCLUDE_DIRS for part in parts[:-1]):
        return True
    if rel.name in EXCLUDE_FILES:
        return True
    if parts[0] == "storage" and rel.name != ".gitignore":
        return True
    return False


def main() -> None:
    OUT.parent.mkdir(parents=True, exist_ok=True)
    files = []
    for path in ROOT.rglob("*"):
        if not path.is_file():
            continue
        rel = path.relative_to(ROOT)
        if should_skip(rel):
            continue
        files.append((rel, path))

    with ZipFile(OUT, "w", ZIP_DEFLATED, compresslevel=8) as zf:
        for rel, path in sorted(files):
            zf.write(path, rel.as_posix())

    print(f"{OUT.name}: {len(files)} files, {OUT.stat().st_size // 1024} KiB")
    print(f"{OUT.parent / 'CARA-MENJALANKAN.md'} is outside the ZIP by design.")


if __name__ == "__main__":
    main()
