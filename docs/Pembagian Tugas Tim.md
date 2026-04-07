# **👥 Pembagian Tugas (Separation of Concern)**

Biar pembagian kerja lu dan Dier kelihatan profesional di mata Ibu Lilis.

## **🛠️ Azkiya (Lead System Architect & Client-Side)**

* **System Design:** Merancang alur komunikasi antar *node* dan topologi jaringan.  
* **Client Development:** Membangun script **PHP CLI** yang berjalan di terminal — bukan browser.  
* **HTTP Communication:** Mengimplementasikan **cURL** di PHP buat kirim `multipart/form-data` ke server Dier.  
* **Network Setup:** Bertanggung jawab atas konektivitas antar perangkat dan bypass firewall.

## **⚙️ Dier (Lead Back-End & Database)**

* **API Development:** Membangun **REST API** menggunakan PHP murni untuk menangani *upload* dan *processing*.  
* **Data Processing Logic:** Mengimplementasikan **GD Library** untuk konversi dan kompresi gambar di sisi server.  
* **Database Design:** Merancang skema tabel MySQL untuk mencatat metadata pemrosesan data.  
* **Server Management:** Mengelola konfigurasi Apache dan MySQL di lingkungan **XAMPP**.

### **💡 Justifikasi buat Dosen:**

"Kami menerapkan **Separation of Concern** secara ekstrem untuk mensimulasikan lingkungan industri, di mana tim *Front-end* dan *Back-end* bekerja di *environment* (Node) yang berbeda namun terintegrasi melalui protokol HTTP."