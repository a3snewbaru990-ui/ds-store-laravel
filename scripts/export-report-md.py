"""Export the generated report DOCX to an editable Markdown version."""

from pathlib import Path

from docx import Document
from docx.table import Table
from docx.text.paragraph import Paragraph
from docx.oxml.ns import qn

ROOT = Path(__file__).resolve().parents[1]
DOCX = ROOT / "docs" / "bagian-3" / "01-Laporan" / "Laporan-DS-Store.docx"
OUT = ROOT / "docs" / "bagian-3" / "01-Laporan" / "Laporan-DS-Store.md"


def iter_block_items(doc):
    for child in doc.element.body.iterchildren():
        if child.tag == qn("w:p"):
            yield Paragraph(child, doc)
        elif child.tag == qn("w:tbl"):
            yield Table(child, doc)


def main():
    doc = Document(DOCX)
    lines = []
    for block in iter_block_items(doc):
        if isinstance(block, Paragraph):
            text = block.text.strip()
            if not text:
                continue
            if block.style.name.startswith("Heading"):
                level = int(block.style.name.split()[-1])
                lines.append("#" * min(level + 1, 6) + " " + text)
            elif block.style.name == "Title":
                lines.append("# " + text)
            else:
                lines.append(text)
            lines.append("")
        else:
            rows = [[cell.text.strip().replace("\n", " ") for cell in row.cells] for row in block.rows]
            if not rows:
                continue
            lines.append("| " + " | ".join(rows[0]) + " |")
            lines.append("| " + " | ".join(["---"] * len(rows[0])) + " |")
            for row in rows[1:]:
                lines.append("| " + " | ".join(row) + " |")
            lines.append("")

    OUT.write_text("\n".join(lines), encoding="utf-8")
    print(f"{OUT.name}: {OUT.stat().st_size // 1024} KiB")


if __name__ == "__main__":
    main()
