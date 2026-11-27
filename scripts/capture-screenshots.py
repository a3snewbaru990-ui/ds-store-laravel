"""Capture report screenshots for DS Store using Playwright + installed Chrome."""

from pathlib import Path

from playwright.sync_api import sync_playwright

BASE = "http://127.0.0.1:8000"
CHROME = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
OUT = Path(__file__).resolve().parents[1] / "docs" / "bagian-3" / "03-Screenshots"


def login(page, email: str) -> None:
    page.goto(f"{BASE}/login", wait_until="networkidle")
    page.fill("#email", email)
    page.fill("#password", "password")
    page.get_by_role("button", name="Masuk ke dashboard").click()
    page.wait_for_load_state("networkidle")


def submit_tracking(page) -> None:
    page.fill("#order_code", "DS-DEMO-001")
    page.fill("#whatsapp", "081200000000")
    page.get_by_role("button", name="Cek status pesanan").click()
    page.wait_for_load_state("networkidle")


def capture(page, name: str, url: str, width: int, height: int, *, auth: str | None = None, action=None) -> None:
    page.set_viewport_size({"width": width, "height": height})
    if auth:
        login(page, auth)
    page.goto(f"{BASE}{url}", wait_until="networkidle")
    if action:
        action(page)
        page.wait_for_timeout(400)
    path = OUT / name
    page.screenshot(path=str(path))
    print(f"{name}: {path.stat().st_size // 1024} KiB")


def main() -> None:
    OUT.mkdir(parents=True, exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(executable_path=CHROME, headless=True)

        def new_page():
            ctx = browser.new_context(viewport={"width": 1440, "height": 1000})
            return ctx, ctx.new_page()

        jobs = [
            ("01-home-desktop.png", "/", 1440, 1600, None, None),
            ("02-home-mobile.png", "/", 390, 844, None, None),
            ("03-mobile-nav.png", "/", 390, 844, None, lambda pg: pg.locator("[data-nav-toggle]").click()),
            ("04-login.png", "/login", 1440, 1100, None, None),
            ("05-order-form.png", "/order/mythic", 1440, 1600, "demo@dsstore.id", None),
            ("06-tracking-result.png", "/lacak", 1440, 1500, None, submit_tracking),
            ("07-customer-dashboard.png", "/dashboard", 1440, 1200, "demo@dsstore.id", None),
            ("08-customer-order-detail.png", "/dashboard/orders/1", 1440, 1500, "demo@dsstore.id", None),
            ("09-admin-dashboard.png", "/admin", 1440, 1250, "admin@dsstore.id", None),
            ("10-admin-orders.png", "/admin/orders", 1440, 1150, "admin@dsstore.id", None),
            ("11-admin-packages.png", "/admin/packages", 1440, 1150, "admin@dsstore.id", None),
            ("12-admin-order-detail.png", "/admin/orders/1", 1440, 1600, "admin@dsstore.id", None),
        ]

        for name, url, width, height, auth, action in jobs:
            ctx, page = new_page()
            try:
                capture(page, name, url, width, height, auth=auth, action=action)
            finally:
                ctx.close()

        browser.close()


if __name__ == "__main__":
    main()
