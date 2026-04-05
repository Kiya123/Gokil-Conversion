# **🛠️ Kebutuhan Sistem: Gokil Conversion**

Daftar *tools* dan infrastruktur minimal buat jalanin proyek terdistribusi ini di lingkungan Windows.

## **💻 1\. Node 1: Client (Laptop Dier)**

* **OS:** Windows 10/11 (Dual Boot).  
* **Node.js:** Dipakai murni buat *serving* file statis (HTML/JS) via port 3000\.  
* **VS Code / Cursor:** *Code editor* utama.  
* **Web Browser:** Chrome/Firefox (Wajib pake *Network Tab* buat pamer *distributed fetch*).  
* **Git for Windows:** Buat sinkronisasi kode ke GitHub.

## **🖥️ 2\. Node 2: Server & DB (Laptop Azkiya)**

* **XAMPP:** Paket wajib (Apache \+ MySQL).  
* **PHP 8.x:** *Backend* manipulasi gambar (GD Library wajib ON).  
* **MySQL Server:** Port 3306 buat *logging* data.  
* **phpMyAdmin:** Buat cek tabel database secara visual.

## **🌐 3\. Infrastruktur & Komunikasi Jaringan**

* **Hotspot Portable:** HP sebagai *hub* jaringan lokal (LAN).  
* **Static IP Mapping:** IP Laptop Azkiya harus dicatat dan di-*hardcode* ke config.js Dier.  
* **Protokol HTTP:** Kurir data utama.  
* **GitHub:** Sinkronisasi repo biar gak ada drama "eh kodenya di laptop lu".

## **🛡️ 4\. Tips Kritis (Network & Firewall) \- WAJIB BACA\!**

Biar laptop lu sama Azkiya bisa "salaman" lewat jaringan:

* **Network Profile:** Ubah koneksi WiFi dari **Public** ke **Private** di kedua laptop. Windows lebih "longgar" kalau profilnya Private.  
* **Inbound Rules:** Izinkan koneksi masuk ke port **80** (Azkiya) dan **3000** (Dier) di Windows Defender Firewall.  
* **ICMP Ping:** Pastikan laptop Dier bisa ping IP laptop Azkiya lewat CMD. Kalau RTO, berarti firewall masih ngeblokir.  
* **Antivirus:** Matiin sementara antivirus pihak ketiga (Avast, McAfee, dll) pas demo, seringkali mereka ngeblokir port sembarangan.