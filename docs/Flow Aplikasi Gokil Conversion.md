# **🔄 Flow Logis: Gokil Conversion**

Gue pecah alurnya jadi 4 fase utama. Fokus pada **perpindahan data** antar laptop biar poin "Distributed" lu dapet nilai maksimal.

## **💻 1\. Fase Client (Laptop Azkiya — PHP CLI)**

* **Input User:** User run script PHP via terminal, path file gambar dilempar sebagai argumen.  
* **Validasi Lokal:** Script PHP ngecek eksistensi file, ukuran, dan format sebelum dikirim.  
* **Packaging Payload:** Gambar dimasukkan ke dalam `multipart/form-data` request pakai **cURL**.  
* **Dispatch:** Script PHP nembak IP Laptop Dier (via `config.php`) pakai metode POST melalui **cURL**.

## **🌐 2\. Fase Network (The "Distributed" Bridge)**

* **Jalur Komunikasi:** Data lewat jaringan WiFi/Hotspot lokal.  
* **Handshake:** Request nembak port **80** (Apache/PHP).  
* **CORS Clearance:** Header PHP Dier ngasih izin masuk (Access-Control-Allow-Origin).

## **⚙️ 3\. Fase Processing (Laptop Dier \- Server & DB)**

* **Data Reception:** PHP nerima file mentah di folder *temporary*.  
* **Core Processing:** PHP jalanin **GD Library** buat konversi/kompresi (Misal: PNG ke JPG).  
* **Disk Storage:** File hasil proses disimpan di folder uploads/ permanen.  
* **Database Logging:** PHP simpan metadata (nama file, size asli, size baru, timestamp) ke **MySQL** (Port 3306).

## **📥 4\. Fase Feedback (Kembali ke Laptop Azkiya)**

* **JSON Response:** PHP ngirim balik status sukses dan **URL file** yang udah matang.  
* **UI Update:** JS lu nangkep JSON, matiin *loading state*, dan nampilin link download.  
* **Result:** User di Laptop Azkiya bisa download hasil konversi yang ada di Laptop Dier.

### **💡 Poin Krusial buat Proposal:**

* **Distribusi:** Proses dimulai di **Node A** (Azkiya), diolah di **Node B** (Dier).  
* **Komunikasi:** Menggunakan protokol **HTTP** melalui jaringan lokal.  
* **Processing:** Ada manipulasi *byte* gambar (bukan cuma pindah file).