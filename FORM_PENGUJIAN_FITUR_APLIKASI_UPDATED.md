# FORM PENGUJIAN FITUR APLIKASI (UPDATED)
## Sistem Klasifikasi Penerima Bantuan Subsidi Listrik (Naive Bayes)
### Versi 2.0 - Dengan Admin Account Management

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
| 1.6 | Registrasi dan user role otomatis | Role: (default) | User mendapat role default | ☐ Pass ☐ Fail |
| 1.7 | Registrasi dan user status aktif | Status: (default) | User status: aktif | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Waktu respons: ___ detik
- Error message yang muncul: _______________
- Role yang diberikan: _______________
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
| 2.4 | Login dengan akun tidak aktif | Email: [inactive user], Password: xxx | Error: Akun tidak aktif | ☐ Pass ☐ Fail |
| 2.5 | Login dengan akun yang diblokir admin | Email: [blocked user], Password: xxx | Error: Akun diblokir | ☐ Pass ☐ Fail |
| 2.6 | Login menampilkan dashboard sesuai role | Role: [berbeda] | Dashboard sesuai permission role | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Waktu respons login: ___ detik
- Session berhasil dibuat: ☐ Ya ☐ Tidak
- Dashboard yang ditampilkan sesuai role: ☐ Ya ☐ Tidak
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
- Link expired setelah: ___ jam
- Masalah yang ditemukan: _______________

---

## B. PENGUJIAN FITUR MANAJEMEN AKUN ADMIN (NEW)

### 4. Fitur Lihat Daftar User
**Lokasi**: `/admin/accounts/users`  
**Tujuan**: Menampilkan daftar semua pengguna sistem

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 4.1 | Buka halaman daftar user | Klik menu users | Tabel user ditampilkan | ☐ Pass ☐ Fail |
| 4.2 | Tampilkan kolom user | Cek halaman | Nama, Email, Role, Status ada | ☐ Pass ☐ Fail |
| 4.3 | Paginasi daftar user | Halaman: [1, 2, 3...] | Paginasi berfungsi | ☐ Pass ☐ Fail |
| 4.4 | Search user by nama | Cari: [nama user] | Hasil pencarian tampil | ☐ Pass ☐ Fail |
| 4.5 | Search user by email | Cari: [email user] | Hasil pencarian tampil | ☐ Pass ☐ Fail |
| 4.6 | Filter user by role | Role: [pilih role] | User dengan role tersebut tampil | ☐ Pass ☐ Fail |
| 4.7 | Tampilkan status user | Cek kolom status | Status (Aktif/Tidak Aktif) terlihat | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total user di sistem: ___
- User aktif: ___
- User tidak aktif: ___
- Waktu loading halaman: ___ detik
- Masalah yang ditemukan: _______________

---

### 5. Fitur Edit User
**Lokasi**: `/admin/accounts/users/{id}/edit`  
**Tujuan**: Mengubah data user (nama, email, role, password)

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 5.1 | Edit nama user | Nama: [ubah] | Nama berhasil diubah | ☐ Pass ☐ Fail |
| 5.2 | Edit email user | Email: [ubah] | Email berhasil diubah | ☐ Pass ☐ Fail |
| 5.3 | Edit role user | Role: [ubah ke role lain] | Role berhasil diubah | ☐ Pass ☐ Fail |
| 5.4 | Edit password user | Password: [baru], Confirm: [baru] | Password berhasil diubah | ☐ Pass ☐ Fail |
| 5.5 | Edit dengan email duplikat | Email: [email user lain] | Error: Email sudah digunakan | ☐ Pass ☐ Fail |
| 5.6 | Edit dengan password tidak cocok | Password: xxx, Confirm: yyy | Error: Password tidak cocok | ☐ Pass ☐ Fail |
| 5.7 | Edit tanpa mengubah password | Password: (kosong) | Data lain tetap diupdate, password tidak berubah | ☐ Pass ☐ Fail |
| 5.8 | Edit user dan log activity | Edit user | Activity log tercatat | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- User yang diedit: _______________
- Field yang berhasil diubah: _______________
- Activity log tercatat: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 6. Fitur Toggle Status User
**Lokasi**: `/admin/accounts/users/{id}/toggle-status`  
**Tujuan**: Mengaktifkan atau menonaktifkan akun user

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 6.1 | Nonaktifkan user aktif | Klik toggle (user aktif) | User status berubah menjadi tidak aktif | ☐ Pass ☐ Fail |
| 6.2 | Aktifkan user tidak aktif | Klik toggle (user tidak aktif) | User status berubah menjadi aktif | ☐ Pass ☐ Fail |
| 6.3 | User tidak aktif tidak bisa login | Login dengan user tidak aktif | Error: Akun tidak aktif | ☐ Pass ☐ Fail |
| 6.4 | Nonaktifkan akun sendiri | Toggle akun login user | Error: Akun yang login tidak bisa dinonaktifkan | ☐ Pass ☐ Fail |
| 6.5 | Toggle status dan log activity | Klik toggle | Activity log tercatat | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- User yang di-toggle: _______________
- Status berubah: ☐ Ya ☐ Tidak
- Activity log tercatat: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 7. Fitur Manajemen Role & Permissions
**Lokasi**: `/admin/accounts/permissions`  
**Tujuan**: Mengatur hak akses (permissions) untuk setiap role

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 7.1 | Buka halaman permissions | Klik menu permissions | Halaman manajemen permissions tampil | ☐ Pass ☐ Fail |
| 7.2 | Tampilkan semua role | Cek halaman | Semua role ditampilkan | ☐ Pass ☐ Fail |
| 7.3 | Tampilkan permission groups | Cek halaman | Permission dikelompokkan dengan baik | ☐ Pass ☐ Fail |
| 7.4 | Update permissions role | Pilih permissions → Save | Permissions berhasil diupdate | ☐ Pass ☐ Fail |
| 7.5 | Tidak bisa edit permission admin | Role: Admin | Warning: Admin permission dijaga | ☐ Pass ☐ Fail |
| 7.6 | Multiple permission checkbox | Pilih beberapa permission | Semua permission tersimpan | ☐ Pass ☐ Fail |
| 7.7 | Uncheck permission | Uncheck permission yang aktif | Permission dihapus | ☐ Pass ☐ Fail |
| 7.8 | Update permissions dan log activity | Update permissions | Activity log tercatat | ☐ Pass ☐ Fail |

**Permission yang diuji:**
- manage_users: ☐ Berhasil
- manage_role_permissions: ☐ Berhasil
- view_activity_logs: ☐ Berhasil
- manage_training_data: ☐ Berhasil
- manage_attributes: ☐ Berhasil
- manage_probabilities: ☐ Berhasil
- manage_testing_data: ☐ Berhasil
- run_classification: ☐ Berhasil
- view_reports: ☐ Berhasil

**Catatan Pengujian:**
- Role yang diubah permission: _______________
- Jumlah permission berhasil: ___
- Activity log tercatat: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 8. Fitur Activity Logs
**Lokasi**: `/admin/accounts/activity-logs`  
**Tujuan**: Melihat log semua aktivitas pengguna di sistem

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 8.1 | Buka halaman activity logs | Klik menu activity-logs | Tabel log aktivitas ditampilkan | ☐ Pass ☐ Fail |
| 8.2 | Tampilkan kolom activity log | Cek halaman | User, Action, Description, Timestamp ada | ☐ Pass ☐ Fail |
| 8.3 | Activity log user registration | User register | Log: user.registered | ☐ Pass ☐ Fail |
| 8.4 | Activity log user update | Update user | Log: user.updated | ☐ Pass ☐ Fail |
| 8.5 | Activity log user activate/deactivate | Toggle user status | Log: user.activated / user.deactivated | ☐ Pass ☐ Fail |
| 8.6 | Activity log role permissions | Update permissions | Log: role.permissions_updated | ☐ Pass ☐ Fail |
| 8.7 | Paginasi activity logs | Halaman: [1, 2, 3...] | Paginasi berfungsi | ☐ Pass ☐ Fail |
| 8.8 | Search activity logs by action | Cari: [action] | Hasil pencarian tampil | ☐ Pass ☐ Fail |
| 8.9 | Search activity logs by user | Cari: [user] | Hasil pencarian tampil | ☐ Pass ☐ Fail |
| 8.10 | Filter activity logs by role | Role: [filter] | Log user dengan role tersebut tampil | ☐ Pass ☐ Fail |
| 8.11 | Sort by timestamp newest | Sort: Newest | Log terurut dari terbaru | ☐ Pass ☐ Fail |

**Activity Log Type yang ditest:**
- user.registered: ☐ Tercatat
- user.updated: ☐ Tercatat
- user.activated: ☐ Tercatat
- user.deactivated: ☐ Tercatat
- role.permissions_updated: ☐ Tercatat

**Catatan Pengujian:**
- Total activity logs: ___
- Log terbaru: _______________
- Log tertua: _______________
- Waktu loading halaman: ___ detik
- Masalah yang ditemukan: _______________

---

## C. PENGUJIAN FITUR PERMISSION/KONTROL AKSES

### 9. Fitur Permission Check - Manage Users
**Tujuan**: Hanya user dengan permission manage_users yang bisa mengakses menu user management

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 9.1 | User dengan permission | Akses /admin/accounts/users | Halaman terbuka | ☐ Pass ☐ Fail |
| 9.2 | User tanpa permission | Akses /admin/accounts/users | Access denied / Error 403 | ☐ Pass ☐ Fail |
| 9.3 | Admin (super permission) | Akses /admin/accounts/users | Halaman terbuka | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Permission check berfungsi: ☐ Ya ☐ Tidak
- Error message jelas: ☐ Ya ☐ Tidak

---

### 10. Fitur Permission Check - Manage Attributes
**Tujuan**: Hanya user dengan permission manage_attributes yang bisa mengelola atribut

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 10.1 | User dengan permission | Akses /atribut | Halaman terbuka | ☐ Pass ☐ Fail |
| 10.2 | User tanpa permission | Akses /atribut | Access denied / Error 403 | ☐ Pass ☐ Fail |
| 10.3 | User dengan permission lain | Akses /atribut | Access denied | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Permission check berfungsi: ☐ Ya ☐ Tidak

---

### 11. Fitur Permission Check - Manage Training Data
**Tujuan**: Hanya user dengan permission manage_training_data yang bisa mengelola training data

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 11.1 | User dengan permission | Akses /training | Halaman terbuka | ☐ Pass ☐ Fail |
| 11.2 | User tanpa permission | Akses /training | Access denied / Error 403 | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Permission check berfungsi: ☐ Ya ☐ Tidak

---

### 12. Fitur Permission Check - Manage Testing Data
**Tujuan**: Hanya user dengan permission manage_testing_data yang bisa mengelola testing data

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 12.1 | User dengan permission | Akses /testing | Halaman terbuka | ☐ Pass ☐ Fail |
| 12.2 | User tanpa permission | Akses /testing | Access denied / Error 403 | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Permission check berfungsi: ☐ Ya ☐ Tidak

---

### 13. Fitur Permission Check - Manage Probabilities
**Tujuan**: Hanya user dengan permission manage_probabilities yang bisa hitung probabilitas

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 13.1 | User dengan permission | Akses /probab | Halaman terbuka | ☐ Pass ☐ Fail |
| 13.2 | User tanpa permission | Akses /probab | Access denied / Error 403 | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Permission check berfungsi: ☐ Ya ☐ Tidak

---

### 14. Fitur Permission Check - Run Classification
**Tujuan**: Hanya user dengan permission run_classification yang bisa jalankan klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 14.1 | User dengan permission | Akses /class | Halaman terbuka | ☐ Pass ☐ Fail |
| 14.2 | User tanpa permission | Akses /class | Access denied / Error 403 | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Permission check berfungsi: ☐ Ya ☐ Tidak

---

### 15. Fitur Permission Check - View Reports
**Tujuan**: Hanya user dengan permission view_reports yang bisa lihat laporan

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 15.1 | User dengan permission | Akses /result | Halaman terbuka | ☐ Pass ☐ Fail |
| 15.2 | User tanpa permission | Akses /result | Access denied / Error 403 | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Permission check berfungsi: ☐ Ya ☐ Tidak

---

## D. PENGUJIAN FITUR MANAJEMEN ATRIBUT

### 16. Fitur Tambah Atribut
**Lokasi**: `/atribut`  
**Tujuan**: Menambahkan atribut klasifikasi baru

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 16.1 | Tambah atribut numerik lengkap | Nama: Penghasilan, Tipe: Numerik, Deskripsi: xxx | Atribut berhasil ditambahkan | ☐ Pass ☐ Fail |
| 16.2 | Tambah atribut kategorikal lengkap | Nama: Status Rumah, Tipe: Kategorikal, Deskripsi: xxx | Atribut berhasil ditambahkan | ☐ Pass ☐ Fail |
| 16.3 | Tambah atribut tanpa nama | Nama: (kosong), Tipe: Numerik | Error: Nama wajib diisi | ☐ Pass ☐ Fail |
| 16.4 | Tambah atribut dengan nama duplikat | Nama: Penghasilan (sudah ada) | Error: Nama atribut sudah ada | ☐ Pass ☐ Fail |
| 16.5 | Tambah atribut tanpa memilih tipe | Nama: Test, Tipe: (tidak dipilih) | Error: Tipe wajib dipilih | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Slug yang dihasilkan: _______________
- Database record berhasil dibuat: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 17. Fitur Edit Atribut
**Lokasi**: `/atribut/{id}/edit`  
**Tujuan**: Mengubah data atribut yang sudah ada

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 17.1 | Edit nama atribut | Nama lama: Penghasilan → Penghasilan Tahunan | Atribut berhasil diubah | ☐ Pass ☐ Fail |
| 17.2 | Edit deskripsi atribut | Deskripsi: [isi] → [isi baru] | Atribut berhasil diubah | ☐ Pass ☐ Fail |
| 17.3 | Edit atribut dengan nama duplikat | Nama baru: Nama atribut lain | Error: Nama sudah digunakan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Database record berhasil diperbarui: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 18. Fitur Hapus Atribut
**Lokasi**: `/atribut/{id}`  
**Tujuan**: Menghapus atribut dari sistem

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 18.1 | Hapus atribut yang tidak digunakan | ID: [atribut baru] | Atribut berhasil dihapus | ☐ Pass ☐ Fail |
| 18.2 | Hapus atribut yang sudah ada dalam training data | ID: [atribut lama] | Konfirmasi atau error: Atribut masih digunakan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Cascade delete terjadi: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

### 19. Fitur Kelola Nilai Atribut Kategorikal
**Lokasi**: `/atribut/nilai`  
**Tujuan**: Mengelola nilai untuk atribut kategorikal

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 19.1 | Tambah nilai atribut baru | Atribut: Status Rumah, Nilai: Milik Sendiri | Nilai berhasil ditambahkan | ☐ Pass ☐ Fail |
| 19.2 | Tambah nilai dengan nama duplikat | Atribut: Status Rumah, Nilai: Milik Sendiri (sudah ada) | Error: Nilai sudah ada | ☐ Pass ☐ Fail |
| 19.3 | Edit nilai atribut | Nilai lama: Milik Sendiri → Kepemilikan Sendiri | Nilai berhasil diubah | ☐ Pass ☐ Fail |
| 19.4 | Hapus nilai atribut | Hapus nilai yang sudah ada | Nilai berhasil dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total nilai atribut kategorikal: ___
- Masalah yang ditemukan: _______________

---

## E. PENGUJIAN FITUR MANAJEMEN DATA TRAINING

### 20. Fitur Input Manual Data Training
**Lokasi**: `/training`  
**Tujuan**: Menginput data training secara manual

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 20.1 | Input data training lengkap | Semua field terisi dengan benar | Data berhasil disimpan | ☐ Pass ☐ Fail |
| 20.2 | Input data dengan ID pelanggan duplikat | ID Pelanggan: [sudah ada] | Error: ID Pelanggan sudah ada | ☐ Pass ☐ Fail |
| 20.3 | Input data tanpa nama | Nama: (kosong) | Error: Nama wajib diisi | ☐ Pass ☐ Fail |
| 20.4 | Input data dengan nilai atribut kosong | Salah satu atribut: (kosong) | Error: Semua atribut wajib diisi | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total data training sekarang: ___ record
- Masalah yang ditemukan: _______________

---

### 21. Fitur Upload Data Training
**Lokasi**: `/training` → Upload  
**Tujuan**: Mengimpor data training dari file Excel/CSV

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 21.1 | Upload file Excel valid | File: training.xlsx (format benar) | Data berhasil diimpor | ☐ Pass ☐ Fail |
| 21.2 | Upload file CSV valid | File: training.csv (format benar) | Data berhasil diimpor | ☐ Pass ☐ Fail |
| 21.3 | Upload file dengan format tidak valid | File: training.txt | Error: Format file tidak didukung | ☐ Pass ☐ Fail |
| 21.4 | Upload file yang terlalu besar | File: > 10MB | Error: Ukuran file terlalu besar | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang ditest: _______________
- Data berhasil diimpor: ___ record
- Waktu impor: ___ detik
- Masalah yang ditemukan: _______________

---

### 22. Fitur Lihat Data Training
**Lokasi**: `/training`  
**Tujuan**: Menampilkan dan mengelola data training

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 22.1 | Tampilkan semua data training | Buka halaman | Tabel data training ditampilkan | ☐ Pass ☐ Fail |
| 22.2 | Paginasi data training | Halaman: [1, 2, 3...] | Data dipaginasi dengan benar | ☐ Pass ☐ Fail |
| 22.3 | Search data training | Cari: [nama/ID] | Hasil pencarian ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total records: ___
- Waktu loading halaman: ___ detik
- Masalah yang ditemukan: _______________

---

### 23. Fitur Download Template Data
**Lokasi**: `/template`  
**Tujuan**: Mengunduh template Excel untuk data training

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 23.1 | Download template | Klik download | File template.xlsx diunduh | ☐ Pass ☐ Fail |
| 23.2 | Template memiliki semua atribut | Cek kolom | Semua atribut ada sebagai header | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Masalah yang ditemukan: _______________

---

### 24. Fitur Download Data Training
**Lokasi**: `/training` → Download  
**Tujuan**: Mengekspor data training ke Excel

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 24.1 | Download semua data training | Klik download | File Excel diunduh | ☐ Pass ☐ Fail |
| 24.2 | File berisi semua record | Cek jumlah baris | Semua data ada di file | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Total records: ___
- Masalah yang ditemukan: _______________

---

### 25. Fitur Clear Data Training
**Lokasi**: `/training` → Clear  
**Tujuan**: Menghapus semua data training

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 25.1 | Clear data training dengan konfirmasi | Klik clear + confirm | Semua data training dihapus | ☐ Pass ☐ Fail |
| 25.2 | Clear juga menghapus probability | Cek probability setelah clear | Probability juga terhapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data training sebelum clear: ___ records
- Data training setelah clear: ___ records
- Masalah yang ditemukan: _______________

---

## F. PENGUJIAN FITUR MANAJEMEN DATA TESTING

### 26. Fitur Input Manual Data Testing
**Lokasi**: `/testing`  
**Tujuan**: Menginput data testing secara manual

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 26.1 | Input data testing lengkap | Semua field terisi dengan benar | Data berhasil disimpan | ☐ Pass ☐ Fail |
| 26.2 | Input data testing tanpa status | Status: (kosong) | Data berhasil disimpan (opsional) | ☐ Pass ☐ Fail |
| 26.3 | Input data dengan ID duplikat | ID Pelanggan: [sudah ada] | Error: ID Pelanggan sudah ada | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total data testing sekarang: ___ record
- Masalah yang ditemukan: _______________

---

### 27. Fitur Upload Data Testing
**Lokasi**: `/testing` → Upload  
**Tujuan**: Mengimpor data testing dari file

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 27.1 | Upload file Excel valid | File: testing.xlsx | Data berhasil diimpor | ☐ Pass ☐ Fail |
| 27.2 | Upload file CSV valid | File: testing.csv | Data berhasil diimpor | ☐ Pass ☐ Fail |
| 27.3 | Upload file format tidak valid | File: testing.txt | Error: Format tidak didukung | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang ditest: _______________
- Data berhasil diimpor: ___ record
- Masalah yang ditemukan: _______________

---

### 28. Fitur Lihat Data Testing
**Lokasi**: `/testing`  
**Tujuan**: Menampilkan data testing

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 28.1 | Tampilkan semua data testing | Buka halaman | Tabel data ditampilkan | ☐ Pass ☐ Fail |
| 28.2 | Paginasi data testing | Halaman: [1, 2, 3...] | Paginasi berfungsi | ☐ Pass ☐ Fail |
| 28.3 | Search data testing | Cari: [nama/ID] | Hasil pencarian benar | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total data testing: ___
- Masalah yang ditemukan: _______________

---

### 29. Fitur Clear Data Testing
**Lokasi**: `/testing` → Clear  
**Tujuan**: Menghapus semua data testing

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 29.1 | Clear data testing | Klik clear + confirm | Semua data dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data sebelum: ___ records
- Data setelah: ___ records
- Masalah yang ditemukan: _______________

---

## G. PENGUJIAN FITUR PROBABILITY CALCULATION

### 30. Fitur Hitung Probabilitas
**Lokasi**: `/probab` → Hitung  
**Tujuan**: Menghitung probabilitas untuk model Naive Bayes

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 30.1 | Hitung probabilitas dengan data training lengkap | Klik hitung | Probabilitas berhasil dihitung | ☐ Pass ☐ Fail |
| 30.2 | Hitung ketika data training kosong | Data: 0 records | Error: Data training kosong | ☐ Pass ☐ Fail |
| 30.3 | Hitung prior probability | Cek hasil | Prior P(Layak) dan P(Tidak Layak) ada | ☐ Pass ☐ Fail |
| 30.4 | Hitung likelihood probability | Cek hasil | Likelihood untuk setiap atribut ada | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Data training yang digunakan: ___ records
- Waktu perhitungan: ___ detik
- Masalah yang ditemukan: _______________

---

### 31. Fitur Lihat Probabilitas
**Lokasi**: `/probab`  
**Tujuan**: Menampilkan hasil perhitungan probabilitas

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 31.1 | Tampilkan prior probability | Buka halaman | Tabel prior ditampilkan | ☐ Pass ☐ Fail |
| 31.2 | Tampilkan likelihood probability | Buka halaman | Tabel likelihood ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Masalah yang ditemukan: _______________

---

### 32. Fitur Reset Probabilitas
**Lokasi**: `/probab` → Reset  
**Tujuan**: Menghapus probabilitas yang sudah dihitung

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 32.1 | Reset probabilitas | Klik reset + confirm | Semua probabilitas dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Masalah yang ditemukan: _______________

---

## H. PENGUJIAN FITUR KLASIFIKASI

### 33. Fitur Hitung Klasifikasi
**Lokasi**: `/class` → Hitung  
**Tujuan**: Menjalankan algoritma Naive Bayes untuk klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 33.1 | Hitung klasifikasi data training | Tipe: Training | Klasifikasi berhasil dihitung | ☐ Pass ☐ Fail |
| 33.2 | Hitung klasifikasi data testing | Tipe: Testing | Klasifikasi berhasil dihitung | ☐ Pass ☐ Fail |
| 33.3 | Hitung klasifikasi untuk kedua tipe | Tipe: All | Klasifikasi untuk training & testing | ☐ Pass ☐ Fail |
| 33.4 | Klasifikasi ketika probabilitas belum dihitung | Klik hitung | Error: Probabilitas belum dihitung | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Waktu klasifikasi training: ___ detik
- Waktu klasifikasi testing: ___ detik
- Total hasil klasifikasi: ___ records
- Masalah yang ditemukan: _______________

---

### 34. Fitur Lihat Hasil Klasifikasi
**Lokasi**: `/class`  
**Tujuan**: Menampilkan hasil klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 34.1 | Tampilkan semua hasil klasifikasi | Buka halaman | Tabel hasil ditampilkan | ☐ Pass ☐ Fail |
| 34.2 | Filter hasil training | Filter: Training | Hanya data training ditampilkan | ☐ Pass ☐ Fail |
| 34.3 | Filter hasil testing | Filter: Testing | Hanya data testing ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total hasil klasifikasi: ___
- Masalah yang ditemukan: _______________

---

### 35. Fitur Detail Klasifikasi
**Lokasi**: `/class/{id}/detail`  
**Tujuan**: Menampilkan detail perhitungan Naive Bayes

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 35.1 | Tampilkan detail perhitungan | Klik detail | Halaman detail dibuka | ☐ Pass ☐ Fail |
| 35.2 | Tampilkan prior probability | Cek halaman | Prior probability ditampilkan | ☐ Pass ☐ Fail |
| 35.3 | Tampilkan likelihood calculation | Cek halaman | Perhitungan likelihood untuk setiap atribut | ☐ Pass ☐ Fail |
| 35.4 | Tampilkan posterior probability | Cek halaman | Posterior probability ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Record yang dilihat: _______________
- Masalah yang ditemukan: _______________

---

### 36. Fitur Export Hasil Klasifikasi
**Lokasi**: `/class` → Export  
**Tujuan**: Mengekspor hasil klasifikasi ke Excel

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 36.1 | Export klasifikasi training | Klik export (training) | File Excel training diunduh | ☐ Pass ☐ Fail |
| 36.2 | Export klasifikasi testing | Klik export (testing) | File Excel testing diunduh | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File training: _______________
- File testing: _______________
- Masalah yang ditemukan: _______________

---

### 37. Fitur Reset Klasifikasi
**Lokasi**: `/class` → Reset  
**Tujuan**: Menghapus hasil klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 37.1 | Reset klasifikasi training | Tipe: Training | Data training dihapus | ☐ Pass ☐ Fail |
| 37.2 | Reset klasifikasi testing | Tipe: Testing | Data testing dihapus | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Masalah yang ditemukan: _______________

---

## I. PENGUJIAN FITUR LAPORAN & PERFORMA

### 38. Fitur Lihat Performa Klasifikasi
**Lokasi**: `/result`  
**Tujuan**: Menampilkan metrik performa sistem

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 38.1 | Tampilkan confusion matrix | Buka halaman | Tabel confusion matrix ditampilkan | ☐ Pass ☐ Fail |
| 38.2 | Tampilkan akurasi | Cek halaman | Metrik akurasi ditampilkan | ☐ Pass ☐ Fail |
| 38.3 | Tampilkan precision | Cek halaman | Metrik precision ditampilkan | ☐ Pass ☐ Fail |
| 38.4 | Tampilkan recall | Cek halaman | Metrik recall ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Akurasi Training: ___ %
- Akurasi Testing: ___ %
- Masalah yang ditemukan: _______________

---

### 39. Fitur Laporan Detail Klasifikasi
**Lokasi**: `/result/report/classification`  
**Tujuan**: Menampilkan laporan detail klasifikasi

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 39.1 | Tampilkan laporan detail | Buka halaman | Laporan ditampilkan | ☐ Pass ☐ Fail |
| 39.2 | Laporan berisi semua data | Cek jumlah | Semua record ada di laporan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Total record di laporan: ___
- Masalah yang ditemukan: _______________

---

### 40. Fitur Export Laporan CSV
**Lokasi**: `/result/report/classification/export/csv`  
**Tujuan**: Mengekspor laporan ke format CSV

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 40.1 | Export ke CSV | Klik export CSV | File CSV diunduh | ☐ Pass ☐ Fail |
| 40.2 | File CSV dapat dibuka | Buka file | File dapat dibuka di Excel | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Masalah yang ditemukan: _______________

---

### 41. Fitur Export Laporan Excel
**Lokasi**: `/result/report/classification/export/excel`  
**Tujuan**: Mengekspor laporan ke format Excel

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 41.1 | Export ke Excel | Klik export Excel | File Excel diunduh | ☐ Pass ☐ Fail |
| 41.2 | File Excel dapat dibuka | Buka file | File dapat dibuka di Excel | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Masalah yang ditemukan: _______________

---

### 42. Fitur Export Laporan PDF
**Lokasi**: `/result/report/classification/export/pdf`  
**Tujuan**: Mengekspor laporan ke format PDF

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 42.1 | Export ke PDF | Klik export PDF | File PDF diunduh | ☐ Pass ☐ Fail |
| 42.2 | File PDF dapat dibuka | Buka file | File dapat dibuka di PDF reader | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- File yang diunduh: _______________
- Masalah yang ditemukan: _______________

---

## J. PENGUJIAN FITUR PROFIL PENGGUNA

### 43. Fitur Lihat Profil
**Lokasi**: `/profil`  
**Tujuan**: Menampilkan data profil pengguna

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 43.1 | Buka halaman profil | Klik profil | Halaman profil dibuka | ☐ Pass ☐ Fail |
| 43.2 | Tampilkan data pengguna | Cek halaman | Nama, email ditampilkan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Masalah yang ditemukan: _______________

---

### 44. Fitur Edit Profil
**Lokasi**: `/profil` → Edit  
**Tujuan**: Mengubah data profil pengguna

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 44.1 | Edit nama pengguna | Nama lama: xxx → Nama baru: yyy | Nama berhasil diubah | ☐ Pass ☐ Fail |
| 44.2 | Edit dengan email duplikat | Email: [email user lain] | Error: Email sudah digunakan | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Masalah yang ditemukan: _______________

---

### 45. Fitur Hapus Akun
**Lokasi**: `/profil` → Delete  
**Tujuan**: Menghapus akun pengguna

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 45.1 | Hapus akun dengan konfirmasi | Klik delete + confirm | Akun berhasil dihapus | ☐ Pass ☐ Fail |
| 45.2 | Login dengan akun yang dihapus | Email: [akun dihapus] | Error: Email atau password salah | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Masalah yang ditemukan: _______________

---

### 46. Fitur Logout
**Lokasi**: Logout button  
**Tujuan**: Keluar dari sistem

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 46.1 | Logout dari halaman manapun | Klik logout | Redirect ke login, session clear | ☐ Pass ☐ Fail |
| 46.2 | Akses halaman protected setelah logout | Coba akses /class | Redirect ke login | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Logout berfungsi: ☐ Ya ☐ Tidak
- Session clear: ☐ Ya ☐ Tidak

---

## K. PENGUJIAN FITUR KEAMANAN & PERFORMA

### 47. Pengujian Permission Enforcement
**Tujuan**: Memastikan permission check berfungsi di semua fitur

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 47.1 | User tanpa permission tidak bisa akses | Direct URL access | Redirect atau error 403 | ☐ Pass ☐ Fail |
| 47.2 | User dengan permission dapat akses | Direct URL access | Halaman terbuka | ☐ Pass ☐ Fail |
| 47.3 | Admin memiliki akses semua | Direct URL access | Semua halaman terbuka | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Permission enforcement konsisten: ☐ Ya ☐ Tidak

---

### 48. Pengujian Activity Logging
**Tujuan**: Memastikan semua aktivitas penting tercatat

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 48.1 | Login tercatat di activity log | Login | Activity log terbuat | ☐ Pass ☐ Fail |
| 48.2 | Update user tercatat | Update user | Activity log terbuat | ☐ Pass ☐ Fail |
| 48.3 | Logout tercatat | Logout | Activity log terbuat | ☐ Pass ☐ Fail |
| 48.4 | Hitung probabilitas tercatat | Hitung probab | Activity log terbuat | ☐ Pass ☐ Fail |
| 48.5 | Hitung klasifikasi tercatat | Hitung klasifikasi | Activity log terbuat | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Activity logging berfungsi: ☐ Ya ☐ Tidak
- Semua activity tercatat: ☐ Ya ☐ Tidak

---

### 49. Pengujian User Active Status
**Tujuan**: Memastikan user tidak aktif tidak bisa login

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 49.1 | Admin nonaktifkan user | Toggle status | User tidak aktif | ☐ Pass ☐ Fail |
| 49.2 | User tidak aktif tidak bisa login | Login dengan user tidak aktif | Error: Akun tidak aktif | ☐ Pass ☐ Fail |
| 49.3 | User aktif bisa login normal | Login dengan user aktif | Login berhasil | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- User active status check berfungsi: ☐ Ya ☐ Tidak

---

### 50. Pengujian Performa dengan Multiple Users
**Tujuan**: Memastikan sistem performa baik dengan multiple users

#### Skenario Pengujian:
| No | Skenario | Input | Expected Output | Status |
|----|----------|-------|-----------------|--------|
| 50.1 | Simulasi 10 users login bersamaan | Concurrent login | Semua berhasil login | ☐ Pass ☐ Fail |
| 50.2 | Simulasi 10 users akses halaman bersamaan | Concurrent access | Halaman load normal | ☐ Pass ☐ Fail |
| 50.3 | Response time tetap cepat | Measure response time | < 2 detik | ☐ Pass ☐ Fail |

**Catatan Pengujian:**
- Concurrent users: ___
- Response time: ___ detik
- Error terjadi: ☐ Ya ☐ Tidak
- Masalah yang ditemukan: _______________

---

## L. KESIMPULAN DAN RINGKASAN PENGUJIAN

### Total Fitur yang Diuji: 60 Fitur (Termasuk Admin Features)

| Kategori | Jumlah Fitur | Total Test Case | Pass | Fail |
|----------|--------------|-----------------|------|------|
| Autentikasi | 3 | 20 | ___ | ___ |
| **Admin Account Management (NEW)** | **5** | **30** | **___** | **___** |
| **Permission Control (NEW)** | **6** | **18** | **___** | **___** |
| Manajemen Atribut | 4 | 15 | ___ | ___ |
| Manajemen Training Data | 6 | 22 | ___ | ___ |
| Manajemen Testing Data | 4 | 16 | ___ | ___ |
| Probability Calculation | 3 | 12 | ___ | ___ |
| Klasifikasi | 5 | 18 | ___ | ___ |
| Laporan & Performa | 5 | 19 | ___ | ___ |
| Profil Pengguna | 4 | 9 | ___ | ___ |
| Keamanan & Performa | 4 | 19 | ___ | ___ |
| **TOTAL** | **60** | **198** | **___** | **___** |

---

### Statistik Pengujian

**Tingkat Keberhasilan**: ___ % ( ____ Pass / 198 Total )

**Fitur Admin Baru**:
- User Management: ___ %
- Role & Permissions: ___ %
- Activity Logs: ___ %

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

Aplikasi Klasifikasi Penerima Bantuan Subsidi Listrik dengan Admin Account Management **[LANJUTKAN TESTING / SIAP PRODUKSI / PERLU PERBAIKAN]**

---

**Penguji**: _______________  
**Tanggal**: _______________  
**Tanda Tangan**: _______________

---

*Dokumen ini merupakan form pengujian terupdate dengan fitur admin account management, role-based access control, dan activity logging.*
