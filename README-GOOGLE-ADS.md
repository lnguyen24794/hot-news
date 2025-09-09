# Hệ thống Quản lý Quảng cáo Google - Hot News Theme

## Tổng quan

Hệ thống Google Ads Manager được tích hợp vào Hot News theme cho phép bạn quản lý quảng cáo Google AdSense một cách chuyên nghiệp với 2 chế độ:

- **Auto Ads**: Tự động hiển thị quảng cáo thông qua Google AdSense
- **Manual Ads**: Quản lý thủ công từng vị trí quảng cáo cụ thể

## Tính năng chính

### ✅ Đã triển khai

1. **Admin Option Page**: Giao diện quản lý trong WordPress Admin
2. **Tab Interface**: Chia các vị trí quảng cáo theo trang (Trang chủ, Bài viết, Lưu trữ)
3. **Auto Ads Mode**: Tích hợp Google AdSense Auto Ads
4. **Manual Ads Mode**: Quản lý từng vị trí cụ thể
5. **Fallback System**: Hiển thị quảng cáo cũ nếu chưa cấu hình Google Ads
6. **Responsive Design**: Giao diện admin responsive và thân thiện

### 📍 Các vị trí quảng cáo được hỗ trợ

#### Trang chủ (Homepage)
- **Header Advertisement**: Vị trí header phía trên (728x90 hoặc 970x90)
- **Tab News Advertisement**: Khu vực Tab News bên phải (300x250 hoặc 336x280)
- **Sidebar Advertisement**: Sidebar chính (300x250, 300x600 hoặc responsive)

#### Trang bài viết (Single Post)
- **Content Top Ad**: Trên đầu nội dung bài viết (728x90 hoặc responsive)
- **Content Middle Ad**: Giữa nội dung (sau đoạn thứ 2) (300x250 hoặc 728x90)
- **Content Bottom Ad**: Cuối nội dung bài viết (728x90 hoặc 300x250)
- **Single Sidebar Ad**: Sidebar trang bài viết (300x250 hoặc 300x600)

#### Trang lưu trữ (Archive)
- **Archive Header Ad**: Header trang lưu trữ (728x90)
- **Archive Sidebar Banner 1**: Banner đầu tiên trong sidebar
- **Archive Sidebar Banner 2**: Banner thứ hai trong sidebar

#### Sidebar tổng quát
- **General Sidebar Ad**: Áp dụng cho các trang khác (300x250 hoặc responsive)

## Hướng dẫn sử dụng

### Bước 1: Truy cập Google Ads Manager

1. Đăng nhập WordPress Admin
2. Vào **Google Ads** trong menu bên trái
3. Hoặc truy cập: `wp-admin/admin.php?page=hot-news-google-ads`

### Bước 2: Cấu hình Auto Ads

**Cho người dùng muốn đơn giản:**

1. Bật **"Bật Auto Ads"**
2. Nhập **AdSense Client ID** (ví dụ: `ca-pub-1234567890123456`)
3. Tùy chọn: Thêm **Auto Ads Code** tùy chỉnh
4. Lưu cài đặt

### Bước 3: Cấu hình Manual Ads  

**Cho người dùng muốn kiểm soát chi tiết:**

1. Tắt **"Bật Auto Ads"**
2. Chuyển qua các tab: **Trang chủ**, **Bài viết**, **Lưu trữ**
3. Nhập mã AdSense cho từng vị trí cụ thể
4. Lưu cài đặt

### Ví dụ mã AdSense chuẩn

```html
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1234567890123456" crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1234567890123456"
     data-ad-slot="1234567890"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
```

## Tập tin được thay đổi

### Tập tin mới được tạo:
- `inc/admin/google-ads-manager.php` - Class quản lý chính
- `assets/css/admin-google-ads.css` - CSS cho admin interface  
- `assets/js/admin-google-ads.js` - JavaScript cho admin interface
- `README-GOOGLE-ADS.md` - Tài liệu hướng dẫn

### Tập tin được cập nhật:
- `functions.php` - Include Google Ads Manager
- `header.php` - Tích hợp header ads
- `index.php` - Tích hợp homepage ads (tab news, sidebar)
- `single.php` - Tích hợp single post sidebar ads
- `archive.php` - Tích hợp archive sidebar ads
- `sidebar.php` - Tích hợp general sidebar ads

### Functions helper được thêm:

```php
// Hiển thị quảng cáo tại vị trí cụ thể
hot_news_display_ad($position, $fallback_html)

// Kiểm tra Auto Ads có được bật
hot_news_is_auto_ads_enabled()

// Lấy AdSense Client ID
hot_news_get_adsense_client_id()

// Thêm ads vào nội dung bài viết (tự động)
hot_news_add_ads_to_content($content)
```

## Tính năng nâng cao

### Auto Ads Script Injection
- Tự động thêm script AdSense vào `<head>`
- Tự động thêm initialization code vào `<footer>`
- Chỉ load khi Auto Ads được bật

### Manual Ads Validation
- Kiểm tra định dạng AdSense Client ID
- Validate mã AdSense cơ bản
- Preview quảng cáo trước khi lưu
- Copy code nhanh chóng

### Fallback System
- Tự động fallback sang customizer ads nếu chưa cấu hình Google Ads
- Hiển thị placeholder khi chưa có ads nào
- Không break layout khi thiếu quảng cáo

### Responsive & Performance
- CSS và JS chỉ load trên trang admin
- Lazy loading cho ads content
- Mobile-responsive admin interface
- Rate limiting để tránh spam

## Lưu ý quan trọng

### Tuân thủ chính sách Google AdSense
1. **Không click vào ads của chính mình**
2. **Không đặt ads quá gần nhau** (tối thiểu 150px)  
3. **Không đặt quá 3 ads/trang** (đối với Manual Ads)
4. **Tuân thủ vị trí ads** theo policy của Google
5. **Nội dung phải phù hợp** và không vi phạm chính sách

### Performance Tips
1. Sử dụng **Auto Ads** nếu không cần kiểm soát chi tiết
2. **Manual Ads** cho control tốt hơn nhưng cần optimize
3. Test trên thiết bị di động để đảm bảo UX tốt
4. Monitor Core Web Vitals sau khi thêm ads

### Troubleshooting

**Ads không hiển thị:**
- Kiểm tra AdSense Client ID đúng format
- Đảm bảo ads code không bị escape
- Check browser console cho JavaScript errors
- Verify domain được approve bởi AdSense

**Auto Ads không hoạt động:**  
- Kiểm tra Auto Ads được bật trong AdSense dashboard
- Có thể mất 24-48h để Auto Ads hiển thị
- Clear cache nếu có plugin caching

**Manual Ads bị lỗi:**
- Copy code từ AdSense dashboard chính xác
- Không chỉnh sửa mã ads
- Đảm bảo format HTML đúng

## Hỗ trợ

Nếu cần hỗ trợ hoặc có lỗi, vui lòng:

1. Kiểm tra browser console cho JavaScript errors
2. Test với theme mặc định để isolate vấn đề  
3. Disable plugins để check conflict
4. Verify AdSense account status

---

**Phiên bản:** 1.0.0  
**Tương thích:** WordPress 5.0+, PHP 7.4+  
**Theme:** Hot News v1.0.0
