📌 AbsensiKu — Aplikasi Absensi Modern

AbsensiKu adalah aplikasi absensi simpel dan modern buat nyatet kehadiran dengan cepat, akurat, dan tanpa ribet. Cocok dipakai sekolah, kantor, organisasi, atau event yang butuh sistem presensi yang tertata dan efisien.

🚀 Features

Login & Authentication – akses aman buat tiap user.

Dashboard Real-Time – langsung lihat siapa yang hadir.

Check-In / Check-Out – proses absensi cepat sekali klik.

Riwayat Kehadiran – data tersimpan rapi per hari/bulan.

Role Management – admin & user punya akses berbeda.

Responsive UI – layout enak dipake di HP maupun desktop.

🛠️ Tech Stack
Layer	Tools
Frontend	Bootstrap,JavaScript 
Backend	 Laravel
Database	MySQL 
UI Icons	Bootstrap Icons / Font Awesome
📦 Installation

Clone repository

git clone https://github.com/yourname/AbsensiKu.git
cd AbsensiKu


Install dependencies

npm install
# atau
composer install


Setup environment

cp .env.example .env


Lalu masukin konfigurasi database, URL, dan key.

Generate key / build project

php artisan key:generate
npm run build


Run server

php artisan serve
# atau
npm run dev

📊 How It Works

User login.

User melakukan Check-In saat datang.

Check-Out saat pulang.

Admin bisa nge-cek laporan presensi lengkap.

Semua disimpan otomatis ke database.

📷 Screenshots

(Bisa ditambah nanti setelah UI fix)

🧑‍💻 Author

Dikembangkan oleh Fajar Kusuma (Jar)
Project dibuat sebagai latihan dan pengembangan sistem absensi modern.

📄 License

MIT License – bebas dipakai, dimodifikasi, dan dikembangin lagi.
