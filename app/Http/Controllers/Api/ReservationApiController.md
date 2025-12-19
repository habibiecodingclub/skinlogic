# Reservasi API Documentation

## Base URL
`http://localhost:8000/api/reservations`

---

## 📋 Daftar Endpoints

### 1. ✅ GET Available Time Slots
**URL:** `/api/reservations/available-slots`

**Method:** `GET`

**Deskripsi:** Mendapatkan slot waktu yang tersedia untuk tanggal tertentu

**Parameters:**
```
?tanggal=YYYY-MM-DD
```

**Contoh Request:**
```http
GET /api/reservations/available-slots?tanggal=2024-12-20
```

**Response Sukses (200):**
```json
{
    "success": true,
    "tanggal": "2024-12-20",
    "available_slots": [
        "09:00", "09:30", "10:00", "10:30",
        "11:00", "11:30", "12:00", "12:30",
        "13:00", "13:30", "14:00", "14:30",
        "15:00", "15:30", "16:00", "16:30",
        "17:00"
    ],
    "total_available": 17
}
```

---

### 2. 👨‍⚕️ GET Available Therapists
**URL:** `/api/reservations/available-therapists`

**Method:** `GET`

**Deskripsi:** Mendapatkan terapis yang tersedia pada tanggal dan jam tertentu

**Parameters:**
```
?tanggal=YYYY-MM-DD&jam=HH:mm
```

**Contoh Request:**
```http
GET /api/reservations/available-therapists?tanggal=2024-12-20&jam=14:00
```

**Response Sukses (200):**
```json
{
    "success": true,
    "available_therapists": [
        {
            "id": 1,
            "name": "Dr. Sarah",
            "email": "sarah@klinik.com"
        }
    ],
    "total_available": 1
}
```

---

### 3. 💆 GET All Perawatan
**URL:** `/api/reservations/perawatans`

**Method:** `GET`

**Deskripsi:** Mendapatkan semua layanan perawatan yang tersedia

**Contoh Request:**
```http
GET /api/reservations/perawatans
```

**Response Sukses (200):**
```json
{
    "success": true,
    "perawatans": [
        {
            "id": 1,
            "Nama_Perawatan": "Facial Treatment",
            "Harga": 250000
        },
        {
            "id": 2,
            "Nama_Perawatan": "Body Scrub",
            "Harga": 350000
        }
    ]
}
```

---

### 4. ➕ POST Create Reservation
**URL:** `/api/reservations`

**Method:** `POST`

**Deskripsi:** Membuat reservasi baru

**Headers:**
```http
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "pelanggan": {
        "nama": "John Doe",
        "email": "john@example.com",
        "nomor_telepon": "08123456789",
        "status": "Non Member",
        "pekerjaan": "Karyawan",
        "tanggal_lahir": "1990-01-01"
    },
    "tanggal_reservasi": "2024-12-20",
    "jam_reservasi": "14:00",
    "perawatans": [
        {
            "id": 1,
            "qty": 1
        }
    ],
    "catatan": "Alergi terhadap parfum",
    "terapis_id": 1
}
```

**Contoh curl:**
```bash
curl -X POST "http://localhost:8000/api/reservations" \
  -H "Content-Type: application/json" \
  -d '{
    "pelanggan": {
      "nama": "John Doe",
      "email": "john@example.com",
      "nomor_telepon": "08123456789",
      "status": "Non Membera"
    },
    "tanggal_reservasi": "2024-12-20",
    "jam_reservasi": "14:00",
    "perawatans": [
      {
        "id": 1,
        "qty": 1
      }
    ]
  }'
```

**Response Sukses (201):**
```json
{
    "success": true,
    "message": "Reservasi berhasil dibuat!",
    "data": {
        "reservation_id": 1,
        "reservation_code": "RES-000001",
        "tanggal": "20 Des 2024",
        "jam": "14:00",
        "total_harga": 250000,
        "status": "menunggu",
        "status_label": "Menunggu"
    }
}
```

---

### 5. 🔍 GET Reservation Details
**URL:** `/api/reservations/{identifier}`

**Method:** `GET`

**Deskripsi:** Mendapatkan detail reservasi berdasarkan ID atau kode

**Parameters:**
- `identifier`: Bisa berupa ID numerik atau kode RES-000001

**Contoh Request:**
```http
GET /api/reservations/1
GET /api/reservations/RES-000001
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "reservation_code": "RES-000001",
        "tanggal": "20 Des 2024",
        "jam": "14:00",
        "status": "menunggu",
        "status_label": "Menunggu",
        "total_harga": 250000,
        "pelanggan": {
            "nama": "John Doe",
            "email": "john@example.com",
            "nomor_telepon": "08123456789",
            "status": "Non Member"
        },
        "perawatans": [
            {
                "id": 1,
                "nama": "Facial Treatment",
                "harga": 250000,
                "qty": 1,
                "subtotal": 250000
            }
        ],
        "pesanan": null
    }
}
```

---

### 6. ❌ POST Cancel Reservation
**URL:** `/api/reservations/{id}/cancel`

**Method:** `POST`

**Deskripsi:** Membatalkan reservasi

**Contoh Request:**
```http
POST /api/reservations/1/cancel
```

**Response Sukses (200):**
```json
{
    "success": true,
    "message": "Reservasi berhasil dibatalkan.",
    "data": {
        "reservation_id": 1,
        "status": "batal",
        "status_label": "Batal"
    }
}
```

---

### 7. 📜 GET Reservation History
**URL:** `/api/reservations/history`

**Method:** `GET`

**Deskripsi:** Mendapatkan riwayat reservasi berdasarkan email

**Parameters:**
```
?email=user@example.com
```

**Contoh Request:**
```http
GET /api/reservations/history?email=john@example.com
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": {
        "pelanggan": {
            "nama": "John Doe",
            "email": "john@example.com",
            "status": "Non Member"
        },
        "reservations": [
            {
                "id": 1,
                "reservation_code": "RES-000001",
                "tanggal": "20 Des 2024",
                "jam": "14:00",
                "status": "menunggu",
                "status_label": "Menunggu",
                "total_harga": 250000,
                "perawatan_count": 1
            }
        ],
        "pagination": {
            "total": 1,
            "per_page": 10,
            "current_page": 1,
            "last_page": 1
        }
    }
}
```

---

## 📊 Status Codes

| Code | Status              | Deskripsi                              |
|------|---------------------|----------------------------------------|
| 200  | OK                  | Request berhasil                       |
| 201  | Created             | Data berhasil dibuat                   |
| 400  | Bad Request         | Data tidak valid                       |
| 401  | Unauthorized        | Tidak terautentikasi                   |
| 404  | Not Found           | Data tidak ditemukan                   |
| 409  | Conflict            | Slot waktu sudah terisi                |
| 422  | Unprocessable Entity| Validasi gagal                         |
| 500  | Server Error        | Kesalahan server                       |

---

## 📝 Status Reservasi

| Status         | Kode        | Deskripsi                              |
|----------------|-------------|----------------------------------------|
| Menunggu       | `menunggu`  | Reservasi baru, menunggu konfirmasi    |
| Dikonfirmasi   | `dikonfirmasi` | Sudah dikonfirmasi oleh staff         |
| Dikerjakan     | `dikerjakan` | Sedang dalam proses perawatan         |
| Selesai        | `selesai`   | Perawatan sudah selesai                |
| Batal          | `batal`     | Reservasi dibatalkan                   |

---

## 🧪 Contoh Testing dengan curl

### Test 1: Cek Slot Tersedia
```bash
curl "http://localhost:8000/api/reservations/available-slots?tanggal=2024-12-20"
```

### Test 2: Buat Reservasi
```bash
curl -X POST "http://localhost:8000/api/reservations" \
  -H "Content-Type: application/json" \
  -d '{
    "pelanggan": {
      "nama": "Budi Santoso",
      "email": "budi@example.com",
      "nomor_telepon": "081234567890",
      "status": "Member"
    },
    "tanggal_reservasi": "2024-12-20",
    "jam_reservasi": "10:00",
    "perawatans": [
      {
        "id": 1,
        "qty": 2
      },
      {
        "id": 2,
        "qty": 1
      }
    ]
  }'
```

### Test 3: Cek Detail Reservasi
```bash
curl "http://localhost:8000/api/reservations/1"
```

### Test 4: Riwayat Reservasi
```bash
curl "http://localhost:8000/api/reservations/history?email=budi@example.com"
```

### Test 5: Batalkan Reservasi
```bash
curl -X POST "http://localhost:8000/api/reservations/1/cancel"
```

---

## 🔧 Setup untuk Testing

### 1. Pastikan server berjalan:
```bash
php artisan serve
```

### 2. Jalankan migration:
```bash
php artisan migrate
```

### 3. Buat data dummy (jika ada seeder):
```bash
php artisan db:seed --class=PerawatanSeeder
```

---

## 📁 File-file Penting

| File | Lokasi | Deskripsi |
|------|--------|-----------|
| Controller | `app/Http/Controllers/Api/ReservationApiController.php` | Logic API |
| Model | `app/Models/Reservation.php` | Model reservasi |
| Migration | `database/migrations/[timestamp]_create_reservations_table.php` | Struktur database |
| Routes | `routes/api.php` | Routing API |
| Resource | `app/Filament/Resources/ReservationResource.php` | Admin panel |

---

## ⚠️ Error Handling

### Common Errors:

1. **Slot tidak tersedia** (409):
```json
{
    "success": false,
    "message": "Waktu yang dipilih sudah tidak tersedia. Silakan pilih waktu lain."
}
```

2. **Validasi gagal** (422):
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "tanggal_reservasi": ["Tanggal reservasi harus setelah atau sama dengan hari ini."],
        "jam_reservasi": ["Format jam tidak valid."]
    }
}
```

3. **Reservasi tidak ditemukan** (404):
```json
{
    "success": false,
    "message": "Reservasi tidak ditemukan."
}
```

---

## 🚀 Tips Penggunaan

1. **Untuk frontend booking:**
   - Panggil `GET /perawatans` untuk tampilkan daftar layanan
   - Panggil `GET /available-slots` untuk validasi tanggal
   - Panggil `POST /` untuk submit booking

2. **Untuk tracking:**
   - Simpan `reservation_code` yang diberikan (RES-000001)
   - Gunakan kode tersebut untuk cek status

3. **Untuk admin:**
   - Akses `http://localhost:8000/admin/reservations`
   - Semua reservasi bisa dikelola di Filament panel

---

**Dokumentasi ini bisa di-copy seluruhnya dalam format Markdown.**
