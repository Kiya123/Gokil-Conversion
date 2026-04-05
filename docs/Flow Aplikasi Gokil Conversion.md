# **🔄 Flow Logis: Gokil Conversion**

Gue pecah alurnya jadi 4 fase utama. Fokus pada **perpindahan data** antar laptop biar poin "Distributed" lu dapet nilai maksimal.

## **💻 1\. Fase Client (Laptop Dier)**

* **Input User:** User pilih foto dari lokal (Laptop Dier).  
* **Front-End Validation:** JS lu ngecek ukuran file dan format (biar server Azkiya nggak meledak).  
* **Packaging Payload:** Gambar dibungkus jadi **FormData**.  
* **Dispatch:** Fetch API nembak IP Laptop Azkiya (via config.js) pakai metode POST.

## **🌐 2\. Fase Network (The "Distributed" Bridge)**

* **Jalur Komunikasi:** Data lewat jaringan WiFi/Hotspot lokal.  
* **Handshake:** Request nembak port **80** (Apache/PHP).  
* **CORS Clearance:** Header PHP Azkiya ngasih izin masuk (Access-Control-Allow-Origin).

## **⚙️ 3\. Fase Processing (Laptop Azkiya \- Server & DB)**

* **Data Reception:** PHP nerima file mentah di folder *temporary*.  
* **Core Processing:** PHP jalanin **GD Library** buat konversi/kompresi (Misal: PNG ke JPG).  
* **Disk Storage:** File hasil proses disimpan di folder uploads/ permanen.  
* **Database Logging:** PHP simpan metadata (nama file, size asli, size baru, timestamp) ke **MySQL** (Port 3306).

## **📥 4\. Fase Feedback (Kembali ke Laptop Dier)**

* **JSON Response:** PHP ngirim balik status sukses dan **URL file** yang udah matang.  
* **UI Update:** JS lu nangkep JSON, matiin *loading state*, dan nampilin link download.  
* **Result:** User di Laptop Dier bisa download hasil konversi yang ada di Laptop Azkiya.

### **💡 Poin Krusial buat Proposal:**

* **Distribusi:** Proses dimulai di **Node A** (Dier), diolah di **Node B** (Azkiya).  
* **Komunikasi:** Menggunakan protokol **HTTP** melalui jaringan lokal.  
* **Processing:** Ada manipulasi *byte* gambar (bukan cuma pindah file).