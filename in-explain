### 1. Tech Stack
*   **Backend Framework:** Laravel 11.x (menggunakan PHP 8.2)
*   **Frontend:** Blade Templates dipadukan dengan Vite dan Tailwind CSS 3.4 untuk styling.
*   **Database:** MySQL
*   **AI Integration:** OpenAI API (mendukung API kompatibel lainnya seperti Groq, OpenRouter, vLLM, atau Ollama). Komunikasi dilakukan via Laravel HTTP Client dan menggunakan struktur respons `json_schema` yang ketat.

### 2. Cara Kerja Sistem
Sistem ini dirancang sebagai platform (mirip SaaS) yang memungkinkan pengguna meng-generate halaman penjualan (*sales page*) secara instan dengan bantuan AI.
*   **Manajemen Akun & Workspace:** Pengguna harus registrasi dan login. Data dikelola di dalam sistem **Workspace**, sehingga pengguna bisa memisahkan halaman penjualan berdasarkan proyek/bisnis yang berbeda.
*   **Peran AI:** Melalui *System Prompt*, AI diinstruksikan untuk bertindak sebagai *Senior Conversion Copywriter* dan *Brand Designer*. AI bertugas tidak hanya untuk merangkai kata persuasif, tetapi juga menentukan *visual theme* (palet warna dan mood) yang paling cocok untuk produk tersebut.
*   **Sistem Konteks (Context Manager):** Sistem menyimpan riwayat (*history*) pembuatan *sales page* pengguna di workspace terkait agar generasi AI selanjutnya memiliki gaya bahasa dan *tone* yang konsisten.
*   **Mekanisme Fallback:** Apabila integrasi AI bermasalah (seperti timeout atau API key tidak valid), sistem memiliki fungsi *fallback* untuk tetap menghasilkan halaman penjualan menggunakan struktur standar yang diambil dari input mentah pengguna.

### 3. Alur dari Input → Output
1.  **Input Data (User):** Pengguna masuk ke form dan menginputkan informasi dasar produk seperti `product_name`, `description`, `features`, `target_audience`, `price`, `usp` (keunikan), dan `tone` (gaya bahasa).
2.  **Saran Cerdas (Opsional):** Saat pengguna kebingungan mengisi form, mereka bisa meminta saran AI (`suggest()`). AI akan membaca input sementara dan memberikan 3-6 opsi tulisan pendek untuk *field* tertentu secara *real-time*.
3.  **Proses Pengolahan (Sistem):** Saat disubmit, data digabungkan dengan konteks dari `ContextManager`, lalu dikirim ke OpenAI API oleh `SalesPageGenerator` dengan instruksi ketat untuk menghasilkan komponen copy (menggunakan teknik pemasaran AIDA - *Attention, Interest, Desire, Action*).
4.  **Generasi Konten (AI Output):** AI membalas dengan struktur data JSON murni yang terformat rapi berisi:
    *   *Copywriting:* Headline, Subheadline, Deskripsi, Manfaat, Fitur, *Social Proof*, *Call to Action*, Harga.
    *   *Theme:* Palet warna (misal: violet, emerald) dan *mood* (misal: minimal, bold, playful).
5.  **Output Visual (Hasil Akhir):** Sistem menyimpan data JSON tersebut ke database MySQL lalu mengarahkan pengguna ke halaman preview (`/sales-pages/{id}/preview`). Di sana, Blade Template dan Tailwind CSS merender respons JSON tadi menjadi sebuah tampilan visual *Landing/Sales Page* yang utuh, menarik, dan siap pakai.
