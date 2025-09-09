# Hệ thống Quản lý Affiliate - Hot News Theme

## 🎯 Tổng quan

Hệ thống Affiliate Manager được tích hợp vào Hot News theme cho phép bạn:

- **Quản lý affiliate links** một cách chuyên nghiệp
- **Hiển thị modal popup** với affiliate links trên trang bài viết
- **Theo dõi clicks và thống kê** hiệu suất
- **Tùy chỉnh thời gian và cách hiển thị** modal

## ✅ Tính năng chính

### 🔧 Admin Management Panel
- ✅ **CRUD Operations**: Thêm, sửa, xóa affiliate links
- ✅ **Image Upload**: Tải lên hình ảnh từ Media Library
- ✅ **Status Toggle**: Bật/tắt affiliate links nhanh chóng
- ✅ **Click Tracking**: Theo dõi số lượt click cho mỗi link
- ✅ **Real-time Stats**: Thống kê tổng quan và hiệu suất
- ✅ **Responsive Interface**: Giao diện thân thiện với mobile

### 🎭 Frontend Modal System
- ✅ **Popup Modal**: Hiển thị affiliate ngẫu nhiên trên single post
- ✅ **Blur Background**: Làm mờ nền để tập trung vào modal
- ✅ **Smart Timing**: Tùy chỉnh thời gian delay hiển thị
- ✅ **Exit Intent**: Hiển thị khi người dùng có ý định rời khỏi trang
- ✅ **User Engagement**: Thông minh theo dõi tương tác người dùng
- ✅ **Mobile Responsive**: Hoạt động mượt mà trên mọi thiết bị

### 🎨 UI/UX Features  
- ✅ **Beautiful Gradients**: Giao diện đẹp với gradient background
- ✅ **Smooth Animations**: Hiệu ứng mượt mà và chuyên nghiệp
- ✅ **Accessibility**: Tuân thủ các tiêu chuẩn접근성
- ✅ **Keyboard Navigation**: Điều hướng bằng phím
- ✅ **Focus Management**: Quản lý focus đúng cách

## 📍 Cấu trúc Database

### Table: `wp_hot_news_affiliates`

| Field | Type | Description |
|-------|------|-------------|
| `id` | INT(11) | Primary key, auto increment |
| `title` | VARCHAR(255) | Tiêu đề affiliate |
| `url` | VARCHAR(500) | URL affiliate link |
| `image_url` | VARCHAR(500) | URL hình ảnh |
| `is_active` | TINYINT(1) | Trạng thái hoạt động (0/1) |
| `click_count` | INT(11) | Số lượt click |
| `created_at` | DATETIME | Thời gian tạo |
| `updated_at` | DATETIME | Thời gian cập nhật |

## 🚀 Hướng dẫn sử dụng

### Bước 1: Truy cập Admin Panel

1. Đăng nhập WordPress Admin
2. Vào menu **"Affiliate"** (icon 🔗)
3. Hoặc truy cập: `wp-admin/admin.php?page=hot-news-affiliate`

### Bước 2: Cài đặt Modal

**Cấu hình hiển thị modal:**
- ☑️ **"Hiển thị Modal"**: Bật/tắt modal trên trang bài viết
- ⏱️ **"Thời gian delay"**: Thời gian chờ trước khi hiển thị (1-30 giây)

### Bước 3: Quản lý Affiliate Links

**Thêm affiliate mới:**
1. Click **"Thêm mới"**
2. Nhập **Tiêu đề** (tên sản phẩm/dịch vụ)
3. Nhập **URL Affiliate** (link commission)
4. **Chọn hình ảnh** từ Media Library
5. Tick **"Kích hoạt ngay"** nếu muốn hiển thị luôn
6. Click **"Lưu"**

**Chỉnh sửa affiliate:**
1. Click **"Sửa"** tại hàng cần chỉnh sửa
2. Cập nhật thông tin trong modal
3. Click **"Lưu"**

**Xóa affiliate:**
1. Click **"Xóa"** tại hàng cần xóa
2. Xác nhận trong popup

**Bật/tắt affiliate:**
- Toggle switch ở cột **"Trạng thái"**
- Chỉ affiliate **đang hoạt động** mới hiển thị trong modal

### Bước 4: Theo dõi hiệu suất

**Dashboard thống kê:**
- 📊 **Tổng số links**: Tất cả affiliate đã tạo
- ✅ **Links đang hoạt động**: Số lượng active
- 👆 **Tổng clicks**: Tổng lượt click trên tất cả links

**Chi tiết từng link:**
- Xem số clicks trong cột **"Clicks"** 
- Clicks tự động tăng khi user click vào affiliate button

## 💡 Cách hoạt động Frontend

### Modal Display Logic

1. **Chỉ hiển thị trên Single Post** (trang bài viết chi tiết)
2. **Timer delay** theo cài đặt admin (mặc định 3 giây)
3. **Exit Intent**: Hiển thị ngay khi mouse ra khỏi cửa sổ
4. **User Engagement**: Delay thêm nếu user đang scroll/click
5. **Random Selection**: Chọn ngẫu nhiên từ các affiliate đang hoạt động
6. **One-time Show**: Chỉ hiển thị 1 lần mỗi session

### User Interactions

**Mở modal:**
- Tự động sau thời gian delay
- Exit intent (chuột rời khỏi cửa sổ)

**Đóng modal:**
- Click nút ❌ (Close button)
- Click ra ngoài modal (backdrop)
- Nhấn phím ESC
- Tự động sau 3 giây nếu có lỗi

**Blur effect:**
- Nền website bị làm mờ khi modal mở
- Người dùng không đọc được nội dung → tập trung vào modal

## 🎨 Customization

### CSS Customization

**Thay đổi màu sắc modal:**
```css
.modal-content {
    background: linear-gradient(135deg, #your-color-1, #your-color-2);
}
```

**Tùy chỉnh button:**
```css
.affiliate-button {
    background: rgba(your-color, 0.3);
    border-color: rgba(your-color, 0.5);
}
```

**Thay đổi blur intensity:**
```css
body.affiliate-modal-open .site-main {
    filter: blur(8px); /* Tăng giá trị để mờ hơn */
}
```

### JavaScript Hooks

**Custom event sau khi modal hiển thị:**
```javascript
$(document).on('affiliate-modal-shown', function(e, affiliateData) {
    // Your custom code here
    console.log('Modal shown with:', affiliateData);
});
```

**Custom event khi affiliate được click:**
```javascript
$(document).on('affiliate-link-clicked', function(e, affiliateId) {
    // Your custom tracking code
    gtag('event', 'affiliate_click', {affiliate_id: affiliateId});
});
```

## 🔧 Tập tin được tạo/sửa

### Tập tin mới:
- `inc/admin/affiliate-manager.php` - Class quản lý chính  
- `assets/css/admin-affiliate.css` - CSS admin interface
- `assets/js/admin-affiliate.js` - JavaScript admin
- `assets/css/affiliate-modal.css` - CSS frontend modal
- `assets/js/affiliate-modal.js` - JavaScript frontend modal
- `README-AFFILIATE.md` - Tài liệu hướng dẫn

### Tập tin được cập nhật:
- `functions.php` - Include Affiliate Manager

### Database:
- Tạo bảng `wp_hot_news_affiliates` tự động

## ⚠️ Lưu ý quan trọng

### Compliance & Legal
1. **Disclosure**: Tuân thủ quy định về tiết lộ affiliate links
2. **Attribution**: Sử dụng `rel="sponsored"` cho affiliate links  
3. **Privacy**: Cân nhắc GDPR compliance nếu cần
4. **Terms**: Kiểm tra terms của affiliate partners

### Performance
1. **Image Optimization**: Nên tối ưu hình ảnh trước khi upload
2. **Kích thước khuyến nghị**: 400x300px cho hình ảnh modal
3. **Loading**: Modal preload image để UX tốt hơn
4. **Caching**: Compatible với caching plugins

### UX Best Practices
1. **Không spam**: Chỉ hiển thị 1 lần/session
2. **Timing**: Không hiển thị quá sớm (tối thiểu 3 giây)
3. **Relevance**: Chọn affiliate phù hợp với nội dung
4. **Value**: Đảm bảo affiliate mang giá trị cho người đọc

## 🐛 Troubleshooting

### Modal không hiển thị:
- ✅ Check **"Hiển thị Modal"** đã bật
- ✅ Có ít nhất 1 affiliate với status **"Hoạt động"**
- ✅ Đang ở trang **single post** (không phải homepage)
- ✅ Clear cache nếu có caching plugin

### Database error:
- ✅ Check quyền database user  
- ✅ Kiểm tra prefix table đúng không
- ✅ Re-activate theme để tạo lại tables

### Image không hiển thị:
- ✅ Check URL image có accessible không
- ✅ Kiểm tra quyền Media Library
- ✅ File image vẫn tồn tại trên server

### JavaScript errors:
- ✅ Check browser console để xem lỗi
- ✅ Kiểm tra conflict với plugins khác
- ✅ Test với theme mặc định để isolate

## 📈 Analytics & Tracking

### Built-in Tracking:
- ✅ **Click Count**: Tự động đếm clicks cho mỗi affiliate
- ✅ **Database Logging**: Lưu tất cả interactions
- ✅ **Real-time Stats**: Dashboard cập nhật real-time

### Google Analytics Integration:
```javascript
// Thêm vào affiliate-modal.js để track với GA
$('#affiliate-modal-link').on('click', function() {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'affiliate_click', {
            'affiliate_id': affiliateData.id,
            'affiliate_title': affiliateData.title
        });
    }
});
```

### Facebook Pixel Integration:
```javascript
// Track affiliate clicks với Facebook Pixel
$('#affiliate-modal-link').on('click', function() {
    if (typeof fbq !== 'undefined') {
        fbq('trackCustom', 'AffiliateClick', {
            affiliate_id: affiliateData.id,
            content_name: affiliateData.title
        });
    }
});
```

## 🚀 Advanced Features (Future)

### Có thể mở rộng:
- [ ] **A/B Testing**: Test nhiều version modal
- [ ] **Geo-targeting**: Hiển thị affiliate theo vùng địa lý  
- [ ] **Time-based**: Hiển thị affiliate theo thời gian
- [ ] **Category-based**: Affiliate theo category bài viết
- [ ] **User behavior**: AI-based recommendation
- [ ] **Conversion tracking**: Track sales từ affiliate
- [ ] **Multi-modal**: Hiển thị nhiều modal types
- [ ] **Popup frequency**: Tùy chỉnh tần suất hiển thị

---

**Phiên bản:** 1.0.0  
**Tương thích:** WordPress 5.0+, PHP 7.4+  
**Theme:** Hot News v1.0.0  
**Database:** MySQL 5.6+
