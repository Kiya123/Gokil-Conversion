# **🏗️ Arsitektur Sistem: Gokil Conversion**

Gunakan deskripsi ini sebagai panduan menggambar diagram. Ingat: **Node** adalah hardware/mesin, **Port** adalah pintu masuk layanan di dalam mesin.

## **🏢 Node 1: Client Side (Laptop Azkiya)**

* **Komponen:** PHP CLI (Command-Line Interface).  
* **Fungsi:** Menyediakan logika client dan memicu **Request** awal ke server.  
* **Environment:** Menjalankan script PHP via terminal yang menggunakan **cURL** untuk HTTP request.

## **🏢 Node 2: Server Side (Laptop Dier)**

Di sini ada dua layanan yang bekerja secara independen:

1. **Application Server (PHP):** Memproses logika konversi gambar.  
2. **Database Server (MySQL):** Menyimpan metadata dan log aktivitas.

## **⚡ Perbedaan Komunikasi: Node vs Port**

Ini adalah **"Senjata Rahasia"** lu buat jawab pertanyaan kritis dosen.

| Jenis Komunikasi | Jalur (Path) | Protokol | Penjelasan |
| :---- | :---- | :---- | :---- |
| **Inter-Node** (Antar Node) | Laptop Azkiya ↔ Laptop Dier | **HTTP** | Terjadi lewat jaringan WiFi/Hotspot. Menggunakan **IP Address**. |
| **Inter-Port** (Antar Port) | PHP ↔ MySQL (Internal Dier) | **TCP/SQL** | Terjadi di dalam mesin yang sama. Menggunakan **Port Number** (80 ke 3306). |

### **💡 Mengapa Ini Penting?**

* **Keamanan:** Komunikasi antar port biasanya tertutup dari luar (Localhost only), sedangkan antar node harus dibuka (Firewall).  
* **Distributed Claim:** Karena ada komunikasi antar **Node**, project ini sah disebut **Distributed System**.  
* **Processing Claim:** Karena ada komunikasi antar **Port**, project ini sah disebut memiliki **Arsitektur Client-Server-Database**.

### **🧠 Analogi Update**

* **Inter-Node:** Seperti lu ngirim paket lewat Kurir (WiFi) dari rumah lu ke rumah Dier.  
* **Inter-Port:** Seperti Dier nerima paket di depan rumah, lalu dia jalan ke gudang belakang buat naruh catatan di buku stok. Semuanya terjadi di dalam rumah Dier.