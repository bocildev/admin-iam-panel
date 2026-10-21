# Universal Master Hub Governance Enforcement

All AI Agents working in this repository MUST strictly comply with `00-Master-Hub.md`:
1. **Wajib Konfirmasi Sebelum Mengubah Kode (Confirmation & Before-After Policy)**: DILARANG langsung mengedit kode tanpa menyajikan proposal singkat (Rencana Tindakan, Before, After) dan mendapatkan persetujuan eksplisit dari User.
2. **Wajib Konfirmasi Sebelum Push ke Git Remote (Git Push Confirmation Policy)**: DILARANG menjalankan perintah `git push` ke remote repository (GitHub/GitLab) secara otomatis. AI Agent WAJIB menyajikan ringkasan commit (hash, pesan, branch target) dan meminta izin User terlebih dahulu.
3. **Pembacaan Terurut Hierarki (Strict Top-Down Order)**: Pembacaan memori wajib terurut dari `00-Master-Hub.md` -> `Category Hub` -> `Project Hub` -> Leaf Docs.
4. **Isolasi Konteks & Token Governance**: Dilarang riset keluar aplikasi/repositori aktif tanpa izin eksplisit.

# IAM Admin Panel - Design System Enforcement

Before making any modifications to the UI, creating new views, or refactoring existing UI components, you MUST read the `design-system.md` file located at the root of this project.

The application strictly adheres to the design constraints specified in that document (e.g., exclusively dark mode, specific Tailwind color palettes, and VyrnForge UI component usage). Ensure all your proposed UI changes comply with those guidelines.

# Custom CodeIgniter 3 Limitations

This project uses a custom, stripped-down version of CodeIgniter 3. Many standard CI3 functions and helpers (e.g., `redirect()`, `config_item()`, `is_ajax_request()`) are either missing or not automatically loaded.
When modifying controllers or core classes, ALWAYS prefer native PHP functions (e.g., `header('Location: ...')`, `$_SERVER` checks) over relying on CI3 globals unless you have explicitly verified their existence.

# Paired Portfolio Integration
This IAM Admin Panel serves as the backend headless CMS & control center for the user's interactive portfolio located at:
`e:\Data Pribadi\web-porto\portofolio-motion` (Nuxt 3 + Vue 3 + Tailwind + GSAP).
The dedicated controller is `application/controllers/Motion_portfolio.php` and its view is `application/views/motion_portfolio/index.php`.
Full memory specification is documented in `e:\Data Pribadi\web-porto\portofolio-motion\MEMORY.md`.
