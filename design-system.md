# IAM Admin Panel - Design System

Dokumen ini adalah panduan sistem desain untuk antarmuka pengguna (UI) IAM Admin Panel. **Semua pengembangan, modifikasi, dan penambahan halaman baru WAJIB mengikuti panduan ini** untuk menjaga konsistensi visual.

## 1. Core Principles
*   **Tema:** Hanya menggunakan **Dark Theme**. Tidak ada dukungan untuk Light Theme.
*   **Framework CSS:** Tailwind CSS (via CDN).
*   **Komponen UI:** VyrnForge UI (`@vyrnforge/ui-core`, `ui-elements`, `ui-components`, `ui-data-grid`).
*   **Ikonografi:** Lucide Icons.
*   **Font:** 
    *   Utama (Sans): `Plus Jakarta Sans`
    *   Monospace: `JetBrains Mono`

## 2. Color Palette (Tailwind)
Hindari menggunakan warna bawaan Tailwind secara acak. Gunakan palet berikut untuk elemen kustom yang tidak di-cover oleh komponen VyrnForge:
*   **Background Utama (Body):** `bg-[#0A0E1A]`
*   **Background Panel/Card Kustom:** `bg-[#131B2E]` (Biasanya dengan opacity seperti `/80` atau `/90`)
*   **Border Standar:** `border-[#1E293B]`
*   **Teks Utama:** `text-slate-100` atau `text-slate-200`
*   **Teks Muted/Sekunder:** `text-slate-400` atau `text-slate-500`
*   **Aksen Merek (Brand Accents):**
    *   **Primary Accent:** Emerald (`text-emerald-400`, `bg-emerald-500`)
    *   **Secondary Accent:** Cyan (`text-cyan-400`, `bg-cyan-500`)
    *   **Danger:** Rose (`text-rose-400`, `bg-rose-500`)
    *   **Warning:** Amber (`text-amber-400`, `bg-amber-500`)

## 3. Komponen (VyrnForge UI)
Semaksimal mungkin gunakan komponen VyrnForge UI daripada membuat elemen HTML native dari awal:
*   **Input Teks/Email/Number:** `<input class="vf-input w-full" ...>`
*   **Dropdown/Select:** `<select class="vf-select w-full" ...>`
*   **Textarea:** `<textarea class="vf-input w-full" ...>`
*   **Tombol (Button):** 
    *   Primary: `<button class="vf-button vf-button--primary">...</button>`
    *   Subtle/Secondary: `<button class="vf-button vf-button--subtle">...</button>`
    *   Danger: `<button class="vf-button vf-button--danger">...</button>`
*   **Card/Panel:** `<div class="vf-panel">...</div>`

## 4. Animasi & Transisi
*   IAM Admin Panel mengadopsi prinsip "Full 3D Motion" dan UI yang interaktif.
*   **Hover Effects:** Semua elemen yang bisa di-klik (tombol, card, link) wajib memiliki efek transisi halus. Gunakan `transition-all` atau `transition-colors`.
*   Contoh interaktif: `hover:border-emerald-400`, `hover:bg-[#131B2E]/90`.

## 5. Aturan Penulisan Kode UI (CodeIgniter 3 Views)
*   **Modularity:** Jika ada komponen UI kustom yang berulang (seperti modal, alert), pisahkan menjadi file view di dalam folder `application/views/components/` lalu panggil dengan `$this->load->view('components/nama_komponen', $data);`.
*   **Hindari inline CSS:** Jangan gunakan style="" (kecuali untuk warna dinamis dari database).
*   **Dark Mode Classes:** Karena aplikasi dikunci ke Dark Mode, **TIDAK PERLU** lagi menggunakan prefix `dark:` pada class Tailwind. Langsung tulis class warna gelapnya (contoh: langsung gunakan `bg-[#131B2E]`, jangan `bg-white dark:bg-[#131B2E]`).
