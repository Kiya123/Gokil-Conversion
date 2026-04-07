# **🛠️ Kebutuhan Sistem: Gokil Conversion**

Daftar *tools* dan infrastruktur minimal buat jalanin proyek terdistribusi ini di lingkungan Windows.

## **💻 1\. Node 1: Client (Laptop Azkiya)**

* **OS:** Windows 10/11 (Dual Boot).  
* **PHP CLI:** Dipakai buat menjalankan script client secara langsung via terminal (tanpa web server). PHP harus sudah ter-install dan bisa dipanggil dari CMD/PowerShell.  
* **cURL Extension:** Wajib aktif di `php.ini` — dipakai script PHP buat kirim HTTP request ke server Dier.  
* **VS Code / Cursor:** *Code editor* utama.  
* **Git for Windows:** Buat sinkronisasi kode ke GitHub.

## **🖥️ 2\. Node 2: Server & DB (Laptop Dier)**

* **XAMPP:** Paket wajib (Apache \+ MySQL).  
* **PHP 8.x:** *Backend* manipulasi gambar (GD Library wajib ON).  
* **MySQL Server:** Port 3306 buat *logging* data.  
* **phpMyAdmin:** Buat cek tabel database secara visual.

## **🌐 3\. Infrastruktur & Komunikasi Jaringan**

* **Hotspot Portable:** HP sebagai *hub* jaringan lokal (LAN).  
* **Static IP Mapping:** IP Laptop Dier harus dicatat dan di-*hardcode* ke config.js Azkiya.  
* **Protokol HTTP:** Kurir data utama.  
* **GitHub:** Sinkronisasi repo biar gak ada drama "eh kodenya di laptop lu".

## **🛡️ 4\. Tips Kritis (Network & Firewall) \- WAJIB BACA\!**

Biar laptop lu sama Dier bisa "salaman" lewat jaringan:

* **Network Profile:** Ubah koneksi WiFi dari **Public** ke **Private** di kedua laptop. Windows lebih "longgar" kalau profilnya Private.  
* **Inbound Rules:** Izinkan koneksi masuk ke port **80** (Dier) dan **3000** (Azkiya) di Windows Defender Firewall.  
* **ICMP Ping:** Pastikan laptop Azkiya bisa ping IP laptop Dier lewat CMD. Kalau RTO, berarti firewall masih ngeblokir.  
* **Antivirus:** Matiin sementara antivirus pihak ketiga (Avast, McAfee, dll) pas demo, seringkali mereka ngeblokir port sembarangan.