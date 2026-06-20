# FORM PENGUJIAN FITUR APLIKASI
## Sistem Klasifikasi Penerima Bantuan Subsidi Listrik (Naive Bayes)

---

## A. PENGUJIAN FITUR AUTENTIKASI

### 1. Fitur Registrasi Pengguna
**Lokasi**: `/register`  
**Tujuan**: Membuat akun pengguna baru

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 1.1 | Registrasi dengan data lengkap | Email: user@test.com, Password: 12345678, Confirm: 12345678 | Berhasil registrasi, redirect ke login | ☐ Pass ☐ Fail |
| 1.2 | Registrasi dengan email sudah terdaftar | Email: existing@test.com | Error: Email sudah terdaftar | ☐ Pass ☐ Fail |
| 1.3 | Registrasi dengan password tidak sama | Password: 12345678, Confirm: 87654321 | Error: Password tidak cocok | ☐ Pass ☐ Fail |
| 1.4 | Registrasi dengan email kosong | Email: (kosong) | Error: Email wajib diisi | ☐ Pass ☐ Fail |
| 1.5 | Registrasi dengan password kurang dari 8 karakter | Password: 1234567 | Error: Password minimal 8 karakter | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Waktu respons: ___ detik
- Error message yang muncul: _______________
- Masalah yang ditemukan: _______________

---

### 2. Fitur Login
**Lokasi**: `/login`  
**Tujuan**: Masuk ke sistem dengan akun yang sudah terdaftar

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 2.1 | Login dengan email dan password benar | Email: user@test.com, Password: 12345678 | Berhasil login, redirect ke dashboard | ☐ Pass ☐ Fail |
| 2.2 | Login dengan email tidak terdaftar | Email: notexist@test.com, Password: 12345678 | Error: Email atau password salah | ☐ Pass ☐ Fail |
| 2.3 | Login dengan password salah | Email: user@test.com, Password: wrongpass | Error: Email atau password salah | ☐ Pass ☐ Fail |
| 2.4 | Login dengan email kosong | Email: (kosong), Password: 12345678 | Error: Email wajib diisi | ☐ Pass ☐ Fail |
| 2.5 | Login dengan password kosong | Email: user@test.com, Password: (kosong) | Error: Password wajib diisi | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Waktu respons login: ___ detik
- Session berhasil dibuat: ☐ Ya ☐ Tidak
- Masalah keamanan yang ditemukan: _______________

---

### 3. Fitur Lupa Password
**Lokasi**: `/password`  
**Tujuan**: Reset password yang lupa

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 3.1 | Request reset password dengan email terdaftar | Email: user@test.com | Email reset password dikirim | ☐ Pass ☐ Fail |
| 3.2 | Request reset password dengan email tidak terdaftar | Email: notexist@test.com | Email tidak ditemukan atau notif sukses | ☐ Pass ☐ Fail |
| 3.3 | Reset password dengan link yang valid | Link reset + Password baru + Confirm | Password berhasil direset | ☐ Pass ☐ Fail |
| 3.4 | Reset password dengan link yang expired | Link reset (expired) | Error: Link sudah kadaluarsa | ☐ Pass ☐ Fail |
| 3.5 | Reset password dengan password tidak sama | Password: 12345678, Confirm: 87654321 | Error: Password tidak cocok | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Email dikirim ke: _______________
- Waktu penerimaan email: ___ menit
- Masalah yang ditemukan: _______________

---

## B. PENGUJIAN FITUR MANAJEMEN ATRIBUT

### 4. Fitur Tambah Atribut
**Lokasi**: `/atribut`  
**Tujuan**: Menambahkan atribut klasifikasi baru

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 4.1 | Tambah atribut numerik lengkap | Nama: Penghasilan, Tipe: Numerik, Deskripsi: xxx | Atribut berhasil ditambahkan | ☐ Pass ☐ Fail |
| 4.2 | Tambah atribut kategorikal lengkap | Nama: Status Rumah, Tipe: Kategorikal, Deskripsi: xxx | Atribut berhasil ditambahkan | ☐ Pass ☐ Fail |
| 4.3 | Tambah atribut tanpa nama | Nama: (kosong), Tipe: Numerik | Error: Nama wajib diisi | ☐ Pass ☐ Fail |
| 4.4 | Tambah atribut dengan nama duplikat | Nama: Penghasilan (sudah ada) | Error: Nama atribut sudah ada | ☐ Pass ☐ Fail |
| 4.5 | Tambah atribut tanpa memilih tipe | Nama: Test, Tipe: (tidak dipilih) | Error: Tipe wajib dipilih | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Slug yang dihasilkan: _______________
- Database record berhasil dibuat: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 5. Fitur Edit Atribut
**Lokasi**: `/atribut/{id}/edit`  
**Tujuan**: Mengubah data atribut yang sudah ada

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 5.1 | Edit nama atribut | Nama lama: Penghasilan → Penghasilan Tahunan | Atribut berhasil diubah | ☐ Pass ☐ Fail |
| 5.2 | Edit deskripsi atribut | Deskripsi: [isi] → [isi baru] | Atribut berhasil diubah | ☐ Pass ☐ Fail |
| 5.3 | Edit atribut dengan nama duplikat | Nama baru: Nama atribut lain | Error: Nama sudah digunakan | ☐ Pass ☐ Fail |
| 5.4 | Edit atribut yang sudah digunakan dalam data training | Nama: [ubah] | Edit berhasil / Warning: Data training terpengaruh | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Database record berhasil diperbarui: ☐ Ya ☐ Tidak
- Data yang terpengaruh: _______________
- Masalah yang ditemukan: _______________

---

### 6. Fitur Hapus Atribut
**Lokasi**: `/atribut/{id}`  
**Tujuan**: Menghapus atribut dari sistem

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 6.1 | Hapus atribut yang tidak digunakan | ID: [atribut baru] | Atribut berhasil dihapus | ☐ Pass ☐ Fail |
| 6.2 | Hapus atribut yang sudah ada dalam training data | ID: [atribut lama] | Konfirmasi atau error: Atribut masih digunakan | ☐ Pass ☐ Fail |
| 6.3 | Hapus atribut yang sudah ada dalam testing data | ID: [atribut lama] | Konfirmasi atau error: Atribut masih digunakan | ☐ Pass ☐ Fail |
| 6.4 | Hapus dengan konfirmasi | Klik Hapus + Confirm | Atribut berhasil dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Cascade delete terjadi: ☐ Ya ☐ Tidak
- Data dependent yang terhapus: _______________
- Masalah yang ditemukan: _______________

---

### 7. Fitur Kelola Nilai Atribut Kategorikal
**Lokasi**: `/atribut/nilai`  
**Tujuan**: Mengelola nilai untuk atribut kategorikal

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 7.1 | Tambah nilai atribut baru | Atribut: Status Rumah, Nilai: Milik Sendiri | Nilai berhasil ditambahkan | ☐ Pass ☐ Fail |
| 7.2 | Tambah nilai dengan nama duplikat | Atribut: Status Rumah, Nilai: Milik Sendiri (sudah ada) | Error: Nilai sudah ada | ☐ Pass ☐ Fail |
| 7.3 | Edit nilai atribut | Nilai lama: Milik Sendiri → Kepemilikan Sendiri | Nilai berhasil diubah | ☐ Pass ☐ Fail |
| 7.4 | Hapus nilai atribut | Hapus nilai yang sudah ada | Nilai berhasil dihapus | ☐ Pass ☐ Fail |
| 7.5 | Hapus nilai yang digunakan dalam data | Hapus nilai yang ada di training/testing | Konfirmasi atau error | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total nilai atribut kategorikal: ___
- Masalah yang ditemukan: _______________

---

## C. PENGUJIAN FITUR MANAJEMEN DATA TRAINING

### 8. Fitur Input Manual Data Training
**Lokasi**: `/training`  
**Tujuan**: Menginput data training secara manual

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 8.1 | Input data training lengkap | Semua field terisi dengan benar | Data berhasil disimpan | ☐ Pass ☐ Fail |
| 8.2 | Input data dengan ID pelanggan duplikat | ID Pelanggan: [sudah ada] | Error: ID Pelanggan sudah ada | ☐ Pass ☐ Fail |
| 8.3 | Input data tanpa nama | Nama: (kosong) | Error: Nama wajib diisi | ☐ Pass ☐ Fail |
| 8.4 | Input data dengan nilai atribut kosong | Salah satu atribut: (kosong) | Error: Semua atribut wajib diisi | ☐ Pass ☐ Fail |
| 8.5 | Input data dengan status tidak valid | Status: [selain 0/1] | Error: Status harus Layak atau Tidak Layak | ☐ Pass ☐ Fail |
| 8.6 | Input data dengan atribut numerik negatif | Atribut numerik: -100 | Error: Nilai tidak boleh negatif / Berhasil (jika diperbolehkan) | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total data training sekarang: ___ record
- Data duplikat ditemukan: ___
- Masalah yang ditemukan: _______________

---

### 9. Fitur Upload Data Training (Excel/CSV)
**Lokasi**: `/training` → Upload  
**Tujuan**: Mengimpor data training dari file

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 9.1 | Upload file Excel valid | File: training.xlsx (format benar) | Data berhasil diimpor | ☐ Pass ☐ Fail |
| 9.2 | Upload file CSV valid | File: training.csv (format benar) | Data berhasil diimpor | ☐ Pass ☐ Fail |
| 9.3 | Upload file dengan format tidak valid | File: training.txt | Error: Format file tidak didukung | ☐ Pass ☐ Fail |
| 9.4 | Upload file yang terlalu besar | File: > 10MB | Error: Ukuran file terlalu besar | ☐ Pass ☐ Fail |
| 9.5 | Upload file dengan header tidak sesuai | Header: [berbeda dengan template] | Error atau warning: Header tidak sesuai | ☐ Pass ☐ Fail |
| 9.6 | Upload file dengan data kosong | File: [tanpa data] | Error atau warning: File kosong | ☐ Pass ☐ Fail |
| 9.7 | Upload file dengan ID duplikat | File: [beberapa ID sama] | Warning: n data duplikat diabaikan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang ditest: _______________
- Data berhasil diimpor: ___ record
- Data yang ditolak: ___
- Waktu impor: ___ detik
- Masalah yang ditemukan: _______________

---

### 10. Fitur Lihat Data Training
**Lokasi**: `/training`  
**Tujuan**: Menampilkan dan mengelola data training

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 10.1 | Tampilkan semua data training | Buka halaman | Tabel data training ditampilkan | ☐ Pass ☐ Fail |
| 10.2 | Paginasi data training | Halaman: [1, 2, 3...] | Data dipaginasi dengan benar | ☐ Pass ☐ Fail |
| 10.3 | Search data training | Cari: [nama/ID] | Hasil pencarian ditampilkan | ☐ Pass ☐ Fail |
| 10.4 | Sort data training | Sort by: [kolom] | Data tersort dengan benar | ☐ Pass ☐ Fail |
| 10.5 | Edit data training dari tabel | Klik edit → ubah data | Data berhasil diupdate | ☐ Pass ☐ Fail |
| 10.6 | Hapus data training | Klik delete + confirm | Data berhasil dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total records: ___
- Waktu loading halaman: ___ detik
- Pagination berfungsi: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 11. Fitur Download Template Data
**Lokasi**: `/template`  
**Tujuan**: Mengunduh template Excel untuk data training

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 11.1 | Download template | Klik download | File template.xlsx diunduh | ☐ Pass ☐ Fail |
| 11.2 | Template memiliki semua atribut | Cek kolom | Semua atribut ada sebagai header | ☐ Pass ☐ Fail |
| 11.3 | Template memiliki contoh data | Cek data | Contoh data tersedia | ☐ Pass ☐ Fail |
| 11.4 | Template dapat dibuka | Buka file | File dapat dibuka di Excel | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Ukuran file: ___ KB
- Format file: ☐ .xlsx ☐ .xls ☐ Lain
- Masalah yang ditemukan: _______________

---

### 12. Fitur Download Data Training
**Lokasi**: `/training` → Download  
**Tujuan**: Mengekspor data training ke Excel

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 12.1 | Download semua data training | Klik download | File Excel diunduh | ☐ Pass ☐ Fail |
| 12.2 | File berisi semua record | Cek jumlah baris | Semua data ada di file | ☐ Pass ☐ Fail |
| 12.3 | File dapat dibuka | Buka file | File dapat dibuka | ☐ Pass ☐ Fail |
| 12.4 | Data formatting benar | Cek format | Format sesuai dengan template | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Total records: ___
- Format file: ☐ Sesuai ☐ Tidak Sesuai
- Masalah yang ditemukan: _______________

---

### 13. Fitur Clear Data Training
**Lokasi**: `/training` → Clear  
**Tujuan**: Menghapus semua data training

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 13.1 | Clear data training dengan konfirmasi | Klik clear + confirm | Semua data training dihapus | ☐ Pass ☐ Fail |
| 13.2 | Clear tanpa konfirmasi | Close confirm dialog | Data tidak dihapus | ☐ Pass ☐ Fail |
| 13.3 | Clear juga menghapus probability | Cek probability setelah clear | Probability juga terhapus | ☐ Pass ☐ Fail |
| 13.4 | Clear juga menghapus classification | Cek classification setelah clear | Classification juga terhapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data training sebelum clear: ___ records
- Data training setelah clear: ___ records
- Related data juga terhapus: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

## D. PENGUJIAN FITUR MANAJEMEN DATA TESTING

### 14. Fitur Input Manual Data Testing
**Lokasi**: `/testing`  
**Tujuan**: Menginput data testing secara manual

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 14.1 | Input data testing lengkap | Semua field terisi dengan benar | Data berhasil disimpan | ☐ Pass ☐ Fail |
| 14.2 | Input data testing tanpa status | Status: (kosong) | Data berhasil disimpan (opsional) | ☐ Pass ☐ Fail |
| 14.3 | Input data testing dengan ID duplikat | ID Pelanggan: [sudah ada] | Error: ID Pelanggan sudah ada | ☐ Pass ☐ Fail |
| 14.4 | Input data dengan value kategorikal salah | Atribut kategorikal: [value tidak ada] | Error: Value tidak valid | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total data testing sekarang: ___ record
- Status opsional: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 15. Fitur Upload Data Testing
**Lokasi**: `/testing` → Upload  
**Tujuan**: Mengimpor data testing dari file

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 15.1 | Upload file Excel valid | File: testing.xlsx | Data berhasil diimpor | ☐ Pass ☐ Fail |
| 15.2 | Upload file CSV valid | File: testing.csv | Data berhasil diimpor | ☐ Pass ☐ Fail |
| 15.3 | Upload file format tidak valid | File: testing.txt | Error: Format tidak didukung | ☐ Pass ☐ Fail |
| 15.4 | Upload file terlalu besar | File: > 10MB | Error: File terlalu besar | ☐ Pass ☐ Fail |
| 15.5 | Upload file dengan data kosong | File: [tanpa data] | Error: File kosong | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang ditest: _______________
- Data berhasil diimpor: ___ record
- Waktu impor: ___ detik
- Masalah yang ditemukan: _______________

---

### 16. Fitur Lihat Data Testing
**Lokasi**: `/testing`  
**Tujuan**: Menampilkan data testing

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 16.1 | Tampilkan semua data testing | Buka halaman | Tabel data ditampilkan | ☐ Pass ☐ Fail |
| 16.2 | Paginasi data testing | Halaman: [1, 2, 3...] | Paginasi berfungsi | ☐ Pass ☐ Fail |
| 16.3 | Search data testing | Cari: [nama/ID] | Hasil pencarian benar | ☐ Pass ☐ Fail |
| 16.4 | Sort data testing | Sort by: [kolom] | Data tersort benar | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total data testing: ___
- Data dengan status: ___
- Data tanpa status: ___
- Masalah yang ditemukan: _______________

---

### 17. Fitur Validasi Data Testing
**Lokasi**: `/testing` → Count/Validate  
**Tujuan**: Mengecek kualitas data testing

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 17.1 | Hitung data duplikat | Klik validate | Jumlah duplikat ditampilkan | ☐ Pass ☐ Fail |
| 17.2 | Hitung data kosong | Klik validate | Jumlah data kosong ditampilkan | ☐ Pass ☐ Fail |
| 17.3 | Deteksi ID duplikat | Cek hasil | ID duplikat teridentifikasi | ☐ Pass ☐ Fail |
| 17.4 | Deteksi nilai hilang | Cek hasil | Nilai hilang teridentifikasi | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data duplikat ditemukan: ___
- Data kosong ditemukan: ___
- Masalah validasi: _______________

---

### 18. Fitur Download Data Testing
**Lokasi**: `/testing` → Download  
**Tujuan**: Mengekspor data testing

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 18.1 | Download data testing | Klik download | File Excel diunduh | ☐ Pass ☐ Fail |
| 18.2 | File berisi semua record | Cek jumlah | Semua data ada | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Total records: ___
- Masalah yang ditemukan: _______________

---

### 19. Fitur Clear Data Testing
**Lokasi**: `/testing` → Clear  
**Tujuan**: Menghapus semua data testing

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 19.1 | Clear data testing | Klik clear + confirm | Semua data dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data sebelum: ___ records
- Data setelah: ___ records
- Masalah yang ditemukan: _______________

---

## E. PENGUJIAN FITUR PROBABILITY CALCULATION

### 20. Fitur Hitung Probabilitas
**Lokasi**: `/probab` → Hitung  
**Tujuan**: Menghitung probabilitas untuk model Naive Bayes

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 20.1 | Hitung probabilitas dengan data training lengkap | Klik hitung | Probabilitas berhasil dihitung | ☐ Pass ☐ Fail |
| 20.2 | Hitung ketika data training kosong | Data: 0 records | Error: Data training kosong | ☐ Pass ☐ Fail |
| 20.3 | Hitung prior probability | Cek hasil | Prior P(Layak) dan P(Tidak Layak) ada | ☐ Pass ☐ Fail |
| 20.4 | Hitung likelihood probability | Cek hasil | Likelihood untuk setiap atribut ada | ☐ Pass ☐ Fail |
| 20.5 | Prior probability sum to 1 | Cek hasil | P(Layak) + P(Tidak Layak) = 1 | ☐ Pass ☐ Fail |
| 20.6 | Likelihood probability > 0 | Cek hasil | Semua likelihood > 0 | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data training yang digunakan: ___ records
- Total probabilitas terhitung: ___
- Waktu perhitungan: ___ detik
- Nilai Prior P(Layak): ___
- Nilai Prior P(Tidak Layak): ___
- Masalah yang ditemukan: _______________

---

### 21. Fitur Lihat Probabilitas
**Lokasi**: `/probab`  
**Tujuan**: Menampilkan hasil perhitungan probabilitas

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 21.1 | Tampilkan prior probability | Buka halaman | Tabel prior ditampilkan | ☐ Pass ☐ Fail |
| 21.2 | Tampilkan likelihood probability | Buka halaman | Tabel likelihood ditampilkan | ☐ Pass ☐ Fail |
| 21.3 | Likelihood untuk atribut numerik | Cek hasil | Mean dan Std Dev ditampilkan | ☐ Pass ☐ Fail |
| 21.4 | Likelihood untuk atribut kategorikal | Cek hasil | Nilai untuk setiap kategori ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total prior probability: ___
- Total likelihood probability: ___
- Masalah yang ditemukan: _______________

---

### 22. Fitur Reset Probabilitas
**Lokasi**: `/probab` → Reset  
**Tujuan**: Menghapus probabilitas yang sudah dihitung

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 22.1 | Reset probabilitas | Klik reset + confirm | Semua probabilitas dihapus | ☐ Pass ☐ Fail |
| 22.2 | Reset juga mereset classification | Cek setelah reset | Classification juga dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Probabilitas sebelum reset: ___ records
- Probabilitas setelah reset: ___ records
- Masalah yang ditemukan: _______________

---

## F. PENGUJIAN FITUR KLASIFIKASI

### 23. Fitur Hitung Klasifikasi
**Lokasi**: `/class` → Hitung  
**Tujuan**: Menjalankan algoritma Naive Bayes untuk klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 23.1 | Hitung klasifikasi data training | Tipe: Training | Klasifikasi berhasil dihitung | ☐ Pass ☐ Fail |
| 23.2 | Hitung klasifikasi data testing | Tipe: Testing | Klasifikasi berhasil dihitung | ☐ Pass ☐ Fail |
| 23.3 | Hitung klasifikasi untuk kedua tipe | Tipe: All | Klasifikasi untuk training & testing | ☐ Pass ☐ Fail |
| 23.4 | Klasifikasi ketika probabilitas belum dihitung | Klik hitung | Error: Probabilitas belum dihitung | ☐ Pass ☐ Fail |
| 23.5 | Klasifikasi dengan data kosong | Tipe: [dengan 0 data] | Error: Data kosong | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Waktu klasifikasi training: ___ detik
- Waktu klasifikasi testing: ___ detik
- Total hasil klasifikasi: ___ records
- Masalah yang ditemukan: _______________

---

### 24. Fitur Lihat Hasil Klasifikasi
**Lokasi**: `/class`  
**Tujuan**: Menampilkan hasil klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 24.1 | Tampilkan semua hasil klasifikasi | Buka halaman | Tabel hasil ditampilkan | ☐ Pass ☐ Fail |
| 24.2 | Filter hasil training | Filter: Training | Hanya data training ditampilkan | ☐ Pass ☐ Fail |
| 24.3 | Filter hasil testing | Filter: Testing | Hanya data testing ditampilkan | ☐ Pass ☐ Fail |
| 24.4 | Tampilkan probabilitas | Cek kolom | Kolom prob true dan false ada | ☐ Pass ☐ Fail |
| 24.5 | Tampilkan prediksi | Cek kolom | Kolom predicted ada | ☐ Pass ☐ Fail |
| 24.6 | Tampilkan nilai aktual | Cek kolom | Kolom real/actual ada | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total hasil klasifikasi: ___
- Hasil Training: ___
- Hasil Testing: ___
- Masalah yang ditemukan: _______________

---

### 25. Fitur Detail Klasifikasi
**Lokasi**: `/class/{id}/detail`  
**Tujuan**: Menampilkan detail perhitungan Naive Bayes untuk satu record

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 25.1 | Tampilkan detail perhitungan | Klik detail | Halaman detail dibuka | ☐ Pass ☐ Fail |
| 25.2 | Tampilkan prior probability | Cek halaman | Prior probability ditampilkan | ☐ Pass ☐ Fail |
| 25.3 | Tampilkan likelihood calculation | Cek halaman | Perhitungan likelihood untuk setiap atribut | ☐ Pass ☐ Fail |
| 25.4 | Tampilkan posterior probability | Cek halaman | Posterior probability ditampilkan | ☐ Pass ☐ Fail |
| 25.5 | Tampilkan formula Naive Bayes | Cek halaman | Formula ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Record yang dilihat: _______________
- Prior P(Layak): ___
- Prior P(Tidak Layak): ___
- Posterior P(Layak): ___
- Posterior P(Tidak Layak): ___
- Prediksi akhir: ___
- Masalah yang ditemukan: _______________

---

### 26. Fitur Export Hasil Klasifikasi
**Lokasi**: `/class` → Export  
**Tujuan**: Mengekspor hasil klasifikasi ke Excel

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 26.1 | Export klasifikasi training | Klik export (training) | File Excel training diunduh | ☐ Pass ☐ Fail |
| 26.2 | Export klasifikasi testing | Klik export (testing) | File Excel testing diunduh | ☐ Pass ☐ Fail |
| 26.3 | File berisi semua kolom | Cek file | Semua kolom ada (nama, ID, prediksi, probabilitas, dll) | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File training: _______________
- File testing: _______________
- Records dalam file: ___
- Masalah yang ditemukan: _______________

---

### 27. Fitur Reset Klasifikasi
**Lokasi**: `/class` → Reset  
**Tujuan**: Menghapus hasil klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 27.1 | Reset klasifikasi training | Tipe: Training | Data training dihapus | ☐ Pass ☐ Fail |
| 27.2 | Reset klasifikasi testing | Tipe: Testing | Data testing dihapus | ☐ Pass ☐ Fail |
| 27.3 | Reset semua klasifikasi | Tipe: All | Semua klasifikasi dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Klasifikasi sebelum reset: ___
- Klasifikasi setelah reset: ___
- Masalah yang ditemukan: _______________

---

## G. PENGUJIAN FITUR LAPORAN & PERFORMA

### 28. Fitur Lihat Performa Klasifikasi
**Lokasi**: `/result`  
**Tujuan**: Menampilkan metrik performa sistem

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 28.1 | Tampilkan confusion matrix training | Buka halaman | Tabel confusion matrix ditampilkan | ☐ Pass ☐ Fail |
| 28.2 | Tampilkan confusion matrix testing | Buka halaman | Tabel confusion matrix ditampilkan | ☐ Pass ☐ Fail |
| 28.3 | Tampilkan akurasi | Cek halaman | Metrik akurasi ditampilkan | ☐ Pass ☐ Fail |
| 28.4 | Tampilkan precision | Cek halaman | Metrik precision ditampilkan | ☐ Pass ☐ Fail |
| 28.5 | Tampilkan recall | Cek halaman | Metrik recall ditampilkan | ☐ Pass ☐ Fail |
| 28.6 | Tampilkan specificity | Cek halaman | Metrik specificity ditampilkan | ☐ Pass ☐ Fail |
| 28.7 | Tampilkan F1-score | Cek halaman | Metrik F1-score ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Akurasi Training: ___ %
- Akurasi Testing: ___ %
- Precision: ___ %
- Recall: ___ %
- Specificity: ___ %
- F1-Score: ___
- Masalah yang ditemukan: _______________

---

### 29. Fitur Laporan Klasifikasi Detail
**Lokasi**: `/result/report/classification`  
**Tujuan**: Menampilkan laporan detail klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 29.1 | Tampilkan laporan detail | Buka halaman | Laporan ditampilkan | ☐ Pass ☐ Fail |
| 29.2 | Laporan berisi semua data | Cek jumlah | Semua record ada di laporan | ☐ Pass ☐ Fail |
| 29.3 | Laporan berisi kolom yang benar | Cek kolom | Nama, ID, atribut, prediksi, aktual ada | ☐ Pass ☐ Fail |
| 29.4 | Laporan memiliki statistik ringkas | Cek bagian atas | Statistik ringkas ada | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total record di laporan: ___
- Waktu generate laporan: ___ detik
- Masalah yang ditemukan: _______________

---

### 30. Fitur Export Laporan CSV
**Lokasi**: `/result/report/classification/export/csv`  
**Tujuan**: Mengekspor laporan ke format CSV

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 30.1 | Export ke CSV | Klik export CSV | File CSV diunduh | ☐ Pass ☐ Fail |
| 30.2 | File CSV dapat dibuka | Buka file | File dapat dibuka di Excel/text editor | ☐ Pass ☐ Fail |
| 30.3 | CSV memiliki header yang benar | Cek baris pertama | Header sesuai | ☐ Pass ☐ Fail |
| 30.4 | CSV berisi semua data | Cek jumlah baris | Semua data ada | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Encoding: ☐ UTF-8 ☐ Lain
- Total baris: ___
- Masalah yang ditemukan: _______________

---

### 31. Fitur Export Laporan Excel
**Lokasi**: `/result/report/classification/export/excel`  
**Tujuan**: Mengekspor laporan ke format Excel

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 31.1 | Export ke Excel | Klik export Excel | File Excel diunduh | ☐ Pass ☐ Fail |
| 31.2 | File Excel dapat dibuka | Buka file | File dapat dibuka di Excel | ☐ Pass ☐ Fail |
| 31.3 | Excel memiliki multiple sheets | Cek sheets | Laporan, data, statistik sheets ada | ☐ Pass ☐ Fail |
| 31.4 | Excel memiliki formatting | Cek format | Header memiliki warna, border, dll | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Format file: ☐ .xlsx ☐ Lain
- Jumlah sheets: ___
- Masalah yang ditemukan: _______________

---

### 32. Fitur Export Laporan PDF
**Lokasi**: `/result/report/classification/export/pdf`  
**Tujuan**: Mengekspor laporan ke format PDF

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 32.1 | Export ke PDF | Klik export PDF | File PDF diunduh | ☐ Pass ☐ Fail |
| 32.2 | File PDF dapat dibuka | Buka file | File dapat dibuka di PDF reader | ☐ Pass ☐ Fail |
| 32.3 | PDF memiliki halaman yang rapi | Cek tampilan | Halaman terformat dengan baik | ☐ Pass ☐ Fail |
| 32.4 | PDF berisi semua data | Cek isi | Semua data ada di PDF | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Jumlah halaman: ___
- Masalah yang ditemukan: _______________

---

## H. PENGUJIAN FITUR PROFIL PENGGUNA

### 33. Fitur Lihat Profil
**Lokasi**: `/profil`  
**Tujuan**: Menampilkan data profil pengguna

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 33.1 | Buka halaman profil | Klik profil | Halaman profil dibuka | ☐ Pass ☐ Fail |
| 33.2 | Tampilkan data pengguna | Cek halaman | Nama, email ditampilkan | ☐ Pass ☐ Fail |
| 33.3 | Tampilkan form edit | Cek halaman | Form untuk edit data ada | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data yang ditampilkan: _______________
- Masalah yang ditemukan: _______________

---

### 34. Fitur Edit Profil
**Lokasi**: `/profil` → Edit  
**Tujuan**: Mengubah data profil pengguna

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 34.1 | Edit nama pengguna | Nama lama: xxx → Nama baru: yyy | Nama berhasil diubah | ☐ Pass ☐ Fail |
| 34.2 | Edit dengan email duplikat | Email: [email user lain] | Error: Email sudah digunakan | ☐ Pass ☐ Fail |
| 34.3 | Edit dengan data kosong | Field: (kosong) | Error: Field wajib diisi | ☐ Pass ☐ Fail |
| 34.4 | Edit dengan email tidak valid | Email: notanemail | Error: Email tidak valid | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data profil sebelum edit: _______________
- Data profil setelah edit: _______________
- Perubahan tersimpan di database: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 35. Fitur Hapus Akun
**Lokasi**: `/profil` → Delete  
**Tujuan**: Menghapus akun pengguna

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 35.1 | Hapus akun dengan konfirmasi | Klik delete + confirm | Akun berhasil dihapus | ☐ Pass ☐ Fail |
| 35.2 | Hapus tanpa konfirmasi | Close confirm | Akun tidak dihapus | ☐ Pass ☐ Fail |
| 35.3 | Login dengan akun yang dihapus | Email: [akun dihapus] | Error: Email atau password salah | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Akun berhasil dihapus: ☐ Ya ☐ Tidak
- Cascade delete terjadi: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 36. Fitur Logout
**Lokasi**: Logout button  
**Tujuan**: Keluar dari sistem

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 36.1 | Logout dari halaman manapun | Klik logout | Redirect ke login, session clear | ☐ Pass ☐ Fail |
| 36.2 | Akses halaman protected setelah logout | Coba akses /class | Redirect ke login | ☐ Pass ☐ Fail |
| 36.3 | Session dihapus setelah logout | Cek session | Session berhasil dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Logout berfungsi: ☐ Ya ☐ Tidak
- Session clear: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

## I. PENGUJIAN FITUR KEAMANAN & PERFORMA

### 37. Pengujian Keamanan - SQL Injection
**Tujuan**: Memastikan sistem terlindungi dari SQL Injection

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 37.1 | SQL Injection pada login | Email: ' OR '1'='1 | Error atau system aman | ☐ Pass ☐ Fail |
| 37.2 | SQL Injection pada search | Search: '; DROP TABLE-- | Query error atau system aman | ☐ Pass ☐ Fail |
| 37.3 | SQL Injection pada input data | Field: ', DROP TABLE users;-- | Data tidak diproses / Error | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- SQL Injection berhasil: ☐ Ya (VULNERABLE) ☐ Tidak (AMAN)
- Masalah keamanan: _______________
- Rekomendasi: _______________

---

### 38. Pengujian Keamanan - XSS (Cross-Site Scripting)
**Tujuan**: Memastikan sistem terlindungi dari XSS

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 38.1 | XSS pada input teks | Field: <script>alert('XSS')</script> | Script tidak dijalankan | ☐ Pass ☐ Fail |
| 38.2 | XSS pada file upload | File name: <script></script>.xlsx | Filename di-sanitize | ☐ Pass ☐ Fail |
| 38.3 | XSS pada URL parameter | URL: ?id=1<script> | Script tidak dijalankan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- XSS berhasil: ☐ Ya (VULNERABLE) ☐ Tidak (AMAN)
- Masalah keamanan: _______________

---

### 39. Pengujian Keamanan - CSRF (Cross-Site Request Forgery)
**Tujuan**: Memastikan sistem terlindungi dari CSRF

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 39.1 | CSRF token ada di form | Inspect form | CSRF token ada | ☐ Pass ☐ Fail |
| 39.2 | POST tanpa CSRF token | Kirim POST request tanpa token | Error atau reject | ☐ Pass ☐ Fail |
| 39.3 | POST dengan CSRF token invalid | Kirim dengan token salah | Error atau reject | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- CSRF Protection aktif: ☐ Ya ☐ Tidak
- Masalah keamanan: _______________

---

### 40. Pengujian Autentikasi & Otorisasi
**Tujuan**: Memastikan kontrol akses berfungsi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 40.1 | Akses halaman tanpa login | URL: /class | Redirect ke login | ☐ Pass ☐ Fail |
| 40.2 | Akses dengan session invalid | Session: [invalid] | Redirect ke login | ☐ Pass ☐ Fail |
| 40.3 | Akses data user lain | Ubah parameter ID | Akses ditolak atau data user sendiri | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Otorisasi berfungsi: ☐ Ya ☐ Tidak
- Masalah keamanan: _______________

---

### 41. Pengujian Performa - Loading Time
**Tujuan**: Mengukur kecepatan loading halaman

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 41.1 | Loading halaman login | Buka /login | Loading < 2 detik | ☐ Pass ☐ Fail |
| 41.2 | Loading halaman dashboard | Buka / | Loading < 2 detik | ☐ Pass ☐ Fail |
| 41.3 | Loading tabel data (1000 rows) | Buka /testing | Loading < 3 detik | ☐ Pass ☐ Fail |
| 41.4 | Loading halaman klasifikasi | Buka /class | Loading < 3 detik | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Halaman tercepat: ___ (waktu: ___ detik)
- Halaman terlambat: ___ (waktu: ___ detik)
- Rata-rata loading time: ___ detik

---

### 42. Pengujian Performa - Database Query
**Tujuan**: Mengukur kecepatan query database

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 42.1 | Query training data (1000 rows) | Jalankan query | Query < 1 detik | ☐ Pass ☐ Fail |
| 42.2 | Query testing data (1000 rows) | Jalankan query | Query < 1 detik | ☐ Pass ☐ Fail |
| 42.3 | Query klasifikasi results | Jalankan query | Query < 1 detik | ☐ Pass ☐ Fail |
| 42.4 | Hitung probabilitas (1000 data) | Jalankan hitung | Selesai < 5 detik | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Query tercepat: ___ (waktu: ___ detik)
- Query terlambat: ___ (waktu: ___ detik)
- Database indexes: ☐ Ada ☐ Tidak ada

---

### 43. Pengujian Performa - Memory Usage
**Tujuan**: Mengukur penggunaan memori

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 43.1 | Memory saat login | Buka login | Memory < 50 MB | ☐ Pass ☐ Fail |
| 43.2 | Memory saat upload 100 MB file | Upload file | Memory < 200 MB | ☐ Pass ☐ Fail |
| 43.3 | Memory saat hitung probabilitas | Jalankan hitung | Memory < 100 MB | ☐ Pass ☐ Fail |
| 43.4 | Memory leak testing | Refresh 10x | Memory stabil | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Memory awal: ___ MB
- Memory puncak: ___ MB
- Memory akhir: ___ MB
- Memory leak terdeteksi: ☐ Ya ☐ Tidak

---

## J. PENGUJIAN FITUR RESPONSIVITAS

### 44. Pengujian Responsive Design - Desktop
**Tujuan**: Memastikan tampilan baik di desktop

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 44.1 | Resolusi 1920x1080 | Buka di resolusi ini | Layout sempurna | ☐ Pass ☐ Fail |
| 44.2 | Resolusi 1366x768 | Buka di resolusi ini | Layout sempurna | ☐ Pass ☐ Fail |
| 44.3 | Tombol dapat diklik | Click tombol | Berfungsi dengan baik | ☐ Pass ☐ Fail |
| 44.4 | Form dapat diisi | Input form | Tidak ada error | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Resolusi desktop terbaik: ___
- Masalah tampilan: _______________

---

### 45. Pengujian Responsive Design - Tablet
**Tujuan**: Memastikan tampilan baik di tablet

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 45.1 | Resolusi tablet (768x1024) | Buka di resolusi ini | Layout responsive | ☐ Pass ☐ Fail |
| 45.2 | Navigasi di tablet | Cek menu | Menu terlihat jelas | ☐ Pass ☐ Fail |
| 45.3 | Tabel di tablet | Scroll horizontal | Tabel dapat dibaca | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Masalah tampilan: _______________

---

### 46. Pengujian Responsive Design - Mobile
**Tujuan**: Memastikan tampilan baik di mobile

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 46.1 | Resolusi mobile (375x812) | Buka di resolusi ini | Layout responsive | ☐ Pass ☐ Fail |
| 46.2 | Navigasi di mobile | Cek hamburger menu | Menu dapat diakses | ☐ Pass ☐ Fail |
| 46.3 | Form di mobile | Input form | Form mudah diisi | ☐ Pass ☐ Fail |
| 46.4 | Tabel di mobile | Scroll horizontal | Tabel dapat dibaca | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Masalah tampilan: _______________

---

## K. PENGUJIAN EDGE CASES & ERROR HANDLING

### 47. Pengujian Error Handling - Database Error
**Tujuan**: Sistem menangani database error dengan baik

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 47.1 | Database tidak terkoneksi | Akses aplikasi | Error message user-friendly | ☐ Pass ☐ Fail |
| 47.2 | Query timeout | Jalankan query lama | Timeout error tertangani | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Error message ditampilkan: ☐ Ya ☐ Tidak
- User dapat memahami error: ☐ Ya ☐ Tidak

---

### 48. Pengujian Error Handling - File Upload Error
**Tujuan**: Sistem menangani file upload error

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 48.1 | Upload file terlalu besar | File: 100 MB | Error message | ☐ Pass ☐ Fail |
| 48.2 | Upload file format salah | File: .txt | Error message | ☐ Pass ☐ Fail |
| 48.3 | Upload file corrupt | File: corrupt | Error message | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Error message jelas: ☐ Ya ☐ Tidak

---

### 49. Pengujian Edge Cases - Data Boundary
**Tujuan**: Sistem menangani data di batas nilai

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|---|--------|
| 49.1 | Input nilai 0 pada atribut | Atribut: 0 | Diterima atau error | ☐ Pass ☐ Fail |
| 49.2 | Input nilai negatif | Atribut: -100 | Error atau diterima | ☐ Pass ☐ Fail |
| 49.3 | Input nilai sangat besar | Atribut: 999999999 | Diterima atau error | ☐ Pass ☐ Fail |
| 49.4 | Input teks sangat panjang | Teks: [5000 karakter] | Diterima atau truncate | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Validasi boundary berfungsi: ☐ Ya ☐ Tidak

---

### 50. Pengujian Edge Cases - Empty/Null Data
**Tujuan**: Sistem menangani data kosong

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 50.1 | Klasifikasi dengan training data kosong | Data: 0 records | Error message | ☐ Pass ☐ Fail |
| 50.2 | Laporan dengan testing data kosong | Data: 0 records | Kosong atau warning | ☐ Pass ☐ Fail |
| 50.3 | Hitung probabilitas tanpa data | Data: 0 records | Error message | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Null/empty handling baik: ☐ Ya ☐ Tidak

---

## L. KESIMPULAN DAN RINGKASAN PENGUJIAN

### Total Fitur yang Diuji: 50 Fitur

| Kategori | Jumlah Fitur | Total Test Case | Pass | Fail |
|----------|--------------|-----------------|------|------|
| Autentikasi | 3 | 15 | ___ | ___ |
| Manajemen Atribut | 4 | 22 | ___ | ___ |
| Manajemen Training Data | 6 | 40 | ___ | ___ |
| Manajemen Testing Data | 6 | 35 | ___ | ___ |
| Probability Calculation | 3 | 16 | ___ | ___ |
| Klasifikasi | 5 | 27 | ___ | ___ |
| Laporan & Performa | 5 | 28 | ___ | ___ |
| Profil Pengguna | 4 | 12 | ___ | ___ |
| Keamanan | 5 | 15 | ___ | ___ |
| Performa | 3 | 13 | ___ | ___ |
| Responsivitas | 3 | 12 | ___ | ___ |
| Error Handling | 3 | 10 | ___ | ___ |
| **TOTAL** | **50** | **265** | **___** | **___** |

---

### Statistik Pengujian

**Tingkat Keberhasilan**: ___ % ( ____ Pass / 265 Total )

**Keberhasilan Per Kategori**:
- Autentikasi: ___ %
- Manajemen Data: ___ %
- Klasifikasi: ___ %
- Laporan: ___ %
- Keamanan: ___ %
- Performa: ___ %

---

### Masalah Kritis yang Ditemukan

1. _______________
2. _______________
3. _______________
4. _______________
5. _______________

---

### Rekomendasi Perbaikan

1. _______________
2. _______________
3. _______________
4. _______________
5. _______________

---

### Kesimpulan Akhir

Aplikasi Klasifikasi Penerima Bantuan Subsidi Listrik dengan metode Naive Bayes **[LANJUTKAN TESTING / SIAP PRODUKSI / PERLU PERBAIKAN]**

**Keterangan**:
- LANJUTKAN TESTING: Masalah kecil, bisa dilanjutkan testing lebih lanjut
- SIAP PRODUKSI: Semua test case lulus, siap untuk production
- PERLU PERBAIKAN: Masalah kritis ditemukan, perlu perbaikan sebelum produksi

---

**Penguji**: _______________  
**Tanggal**: _______________  
**Tanda Tangan**: _______________

---

*Dokumen ini dapat digunakan sebagai form pengujian (testing form) dalam laporan penelitian Anda.*
