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

| PHISH-01 | Mở hai trang `v1/phishing.php` và `v2/phishing.php` | HTTP 200, có nhãn MÔ PHỎNG HỌC TẬP | HTTP 200, có nhãn MÔ PHỎNG HỌC TẬP |
| PHISH-02 | Nhấn Kiểm tra thông báo | Hiện lời nhắc tự đánh giá | Nêu 3 dấu hiệu nghi vấn và cách xác minh |
| PHISH-03 | Chuyển phiên bản V1/V2 từ menu | Giữ trang Phishing | Giữ cùng kịch bản |
| PHISH-04 | Kiểm tra assets, input, CSP | Không có biểu mẫu nhận mật khẩu/OTP | Các header V2 giữ nguyên, không gửi dữ liệu ra ngoài |

Kiểm thử HTTP/HTML: `bash tests/phishing-checks.sh http://localhost/mid_term_lap_trinh_web`. Kiểm thử tương tác JavaScript vẫn cần quan sát trên trình duyệt.
