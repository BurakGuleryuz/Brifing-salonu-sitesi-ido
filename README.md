# 🛳️ Dijital Signage ve Toplantı Odası Yönetim Sistemi

> İstanbul Deniz Otobüsleri (İDO) — Bilgi Teknolojileri Birimi  
> **Staj Projesi** | Ağustos 2026 | Burak Güleryüz

---

## 📋 Proje Hakkında

Şirket içi toplantı odalarının rezervasyon, çakışma denetimi ve anlık doluluk gösteriminin tek bir rol tabanlı sistem üzerinden yönetilmesini sağlayan bir web uygulamasıdır.

**Çözülen problemler:**
- ❌ Aynı oda ve saatte birden fazla toplantı ayarlanabiliyordu (çakışma)
- ❌ Odaların doluluk durumu salonun önüne gelinmeden anlaşılamıyordu

---

## ✨ Özellikler

| Özellik | Açıklama |
|---|---|
| 🔒 Kimlik Doğrulama | Kimlik no + şifre ile giriş; bcrypt ile şifreleme |
| 👥 Rol Yönetimi | Personel, Stajyer, Bakım Sorumlusu, Özel Kalem Müdürü |
| 🚫 Çakışma Kontrolü | Aynı oda/saat çakışması otomatik engellenir |
| 📺 Brifing Ekranı | 60 sn'de bir yenilenen, şifresiz erişilebilir signage ekranı |
| 🔧 Arıza Modülü | Bakım sorumlusu oda arızasını işaretleyebilir |
| 📧 Acil Bildirim | Acil toplantılarda tüm kullanıcılara otomatik e-posta |
| 🏛️ Yönetim Kurulu | Gizli toplantı tipi; yalnızca yetkili rol görebilir |

---

## 🛠️ Teknolojiler

- **Backend:** PHP 8 · Laravel 12 · Eloquent ORM
- **Frontend:** Blade · Bootstrap 5 · JavaScript · SVG Animasyonlar
- **Veritabanı:** MySQL (XAMPP)
- **Araçlar:** Git · GitHub · Artisan CLI · Composer

---

## 🚀 Kurulum

### Gereksinimler
- PHP 8.1+
- Composer
- MySQL (XAMPP önerilir)

### Adımlar

```bash
# 1. Repoyu klonla
git clone https://github.com/BurakGuleryuz/Brifing-salonu-sitesi-ido.git
cd Brifing-salonu-sitesi-ido

# 2. Bağımlılıkları kur
composer install

# 3. .env dosyasını oluştur
cp .env.example .env

# 4. Uygulama anahtarı üret
php artisan key:generate
```

### `.env` Veritabanı Ayarları

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brifing_db
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 5. phpMyAdmin'den brifing_db adında veritabanı oluştur

# 6. Tabloları oluştur
php artisan migrate

# 7. Sunucuyu başlat
php artisan serve
```

Uygulama `http://127.0.0.1:8000` adresinde çalışacak.

---

## 👤 Kullanıcı Oluşturma

`/register` sayfasından kayıt ol. İlk kayıt otomatik olarak `000001` kimlik numarasını alır.

Rol atamak için:

```bash
php artisan tinker
```

```php
App\Models\User::where('employee_id', '000001')->update(['role' => 'ozel_kalem_muduru']);
App\Models\User::where('employee_id', '000002')->update(['role' => 'bakim_sorumlusu']);
App\Models\User::where('employee_id', '000003')->update(['role' => 'personel']);
// rol belirtilmezse varsayılan: personel
```

---

## 🔐 Rol Yetki Matrisi

| Rol | Toplantı Oluşturma | Düzenleme | Oda Arıza Durumu |
|---|:---:|:---:|:---:|
| Stajyer | ❌ | ❌ | ❌ |
| Personel | ✅ | ❌ | ❌ |
| Bakım Sorumlusu | ❌ | ❌ | ✅ |
| Özel Kalem Müdürü | ✅ | ✅ | ✅ |

> Yönetim Kurulu toplantıları yalnızca Özel Kalem Müdürü tarafından görülebilir/oluşturulabilir.

---

## 🏗️ Sistem Mimarisi

Klasik Laravel MVC mimarisi kullanılmıştır:

- **Route** → İstekleri controller'a yönlendirir
- **Middleware** → Giriş yapmamış kullanıcıyı engeller  
- **Controller** → İş mantığı + rol tabanlı yetki kontrolü
- **Model** → Veritabanı ilişkileri + çakışma algoritması
- **View** → Blade şablonları ile kullanıcı arayüzü

---

## 📁 Proje Yapısı

brifing-site/
├── app/Http/Controllers/
│ ├── Auth/LoginController.php # Giriş, kayıt, şifre sıfırlama
│ ├── RoomController.php # Oda CRUD + arıza yönetimi
│ ├── MeetingController.php # Toplantı CRUD + çakışma + mail
│ └── SignageController.php # Brifing ekranı
├── app/Models/
│ ├── User.php # Roller, kimlik no
│ ├── Room.php # Aktif/pasif/arızalı durum
│ └── Meeting.php # Çakışma kontrolü (scopeOverlapping)
├── resources/views/
│ ├── auth/ # Giriş, kayıt, şifre sıfırlama
│ ├── rooms/ # Oda yönetim ekranları
│ ├── meetings/ # Toplantı yönetim ekranları
│ ├── signage/ # Brifing ekranı (TV için)
│ └── layouts/app.blade.php # Ortak layout
└── routes/web.php


---

## 📄 Lisans

Bu proje İDO (İstanbul Deniz Otobüsleri) bünyesinde staj kapsamında geliştirilmiştir.

---

<div align="center">
  <sub>Geliştirici: <a href="https://github.com/BurakGuleryuz">Burak Güleryüz</a> · Bilgisayar Mühendisliği Stajyeri · 2026</sub>
</div>