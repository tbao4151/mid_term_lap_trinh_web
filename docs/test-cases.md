# Test matrix — CÁ'S HOA Web Security Lab

| Case | Thao tác | Kỳ vọng V1 | Kỳ vọng V2 |
| --- | --- | --- | --- |
| XSS-01 | Điền chữ Chúc mừng sinh nhật | Hiển thị nội dung | Hiển thị nội dung |
| XSS-02 | Dán payload vô hại từ nút Copy | Đổi text XSS_EXECUTED | Payload hiện như chữ |
| SQLI-01 | Tìm Garden | 1 sản phẩm công khai | 1 sản phẩm công khai |
| SQLI-02 | Tìm ' OR is_public = 0 # | Có 2 sản phẩm ẩn | Không có kết quả |
| CSRF-01 | Login student / demo12345 | Đăng nhập thành công | Đăng nhập thành công |
| CSRF-02 | Top-level GET từ trang attacker | Tên nhận bị đổi | HTTP 405, tên không đổi |
| CSRF-03 | Lưu tên trên profile | GET hợp lệ | POST + token hợp lệ |
| CSRF-04 | POST không có token | Không áp dụng | HTTP 403 |
| UI-01 | Mở trang chủ, 9 ảnh hoa | Hiển thị | Hiển thị |

Bảng này mô tả kết quả mong đợi từ code. Chưa thay thế biên bản kiểm thử thực tế trên XAMPP và ảnh chụp từ trình duyệt.

Lưu ý: Chỉ sử dụng tài khoản giả và database riêng, không thử trên hệ thống của bên thứ ba.
