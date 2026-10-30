# CÁ'S HOA — Web Security Midterm (XAMPP)

Bài giữa kỳ môn Lập trình Web — **Common Website Attacks and Countermeasures (đề số 3)**. Demo mượn thiết kế cửa hàng hoa và 9 ảnh sản phẩm thật từ dự án cuối kỳ CÁ'S HOA, nhưng backend PHP + MySQL là **dự án riêng dành cho XAMPP**.

Không cần Next.js, Vercel, Supabase, Aiven hoặc Node.js. Không dùng tài khoản thật và dữ liệu thật.

## Chuẩn bị

- Windows + XAMPP (Apache, MySQL, PHP 8.1 trở lên, extension pdo_mysql).
- Trình duyệt Chrome hoặc Edge.
- Chỉ chạy ở localhost. V1 cố tình có XSS, SQL Injection và CSRF. Mã nguồn không được đưa lên hosting công khai.

## Cài và chạy trên XAMPP

1. Mở XAMPP Control Panel, Start cả Apache lẫn MySQL.
2. Clone repository vào C:\xampp\htdocs\mid_term_lap_trinh_web:

       cd C:\xampp\htdocs
       git clone https://github.com/tbao4151/mid_term_lap_trinh_web.git

3. Mở http://localhost/phpmyadmin, chọn tab Import, nạp file database/setup.sql. File tự tạo database cas_hoa_security_lab, 11 sản phẩm demo (9 công khai, 2 nội bộ) và 2 tài khoản. Cảnh báo: import lại sẽ reset dữ liệu của lab.
4. Mở http://localhost/mid_term_lap_trinh_web/ (chuyển tới phiên bản V2). Nếu Apache chạy port 8080, đổi localhost thành localhost:8080.
5. Hai chế độ ở http://localhost/mid_term_lap_trinh_web/v1/ và http://localhost/mid_term_lap_trinh_web/v2/.
6. Các ảnh hoa đã được commit đầy đủ tại assets/images, sử dụng offline. Nếu thiếu file sau khi tải, chạy scripts/download-images.bat để phục hồi.

Nếu MySQL của bạn đã thay đổi tài khoản hoặc mật khẩu, cấu hình biến môi trường Apache: CAS_DB_HOST, CAS_DB_PORT, CAS_DB_NAME, CAS_DB_USER, CAS_DB_PASSWORD. Mặc định là 127.0.0.1:3306, cas_hoa_security_lab, root và password rỗng của XAMPP local.

## Cấu trúc demo

| Trang | V1 cố ý có lỗi | V2 đã vá |
| --- | --- | --- |
| Cửa hàng hoa | 9 ảnh mẫu và danh sách hoa | Giao diện và dữ liệu giống V1 |
| xss.php (lời nhắn hoa) | Reflected XSS do xuất raw HTML | htmlspecialchars, CSP |
| sqli.php (tìm hoa) | SQL từ input nối chuỗi trực tiếp | PDO prepared statements |
| profile.php (tài khoản) | GET đổi tên, không CSRF token | POST + session CSRF token |
| login.php | Dữ liệu demo riêng | Dữ liệu demo riêng |

Phiên bản V1 và V2 dùng người dùng khác nhau trong cùng database để không bị lẫn kết quả, nhưng đăng nhập đều dùng username: student, password: demo12345.

## Kiểm thử XSS

Vào V1/xss.php, bấm nút Sao chép payload rồi dán vào trường lời nhắn và gửi. Nếu XSS xảy ra, nhãn trạng thái sẽ chuyển thành XSS_EXECUTED (payload không đọc cookie, không gửi dữ liệu ra ngoài). Lặp lại trên V2/xss.php; HTML chỉ được hiển thị như chữ, script không chạy.

## Kiểm thử SQL Injection

Vào V1/sqli.php, tìm từ Garden: ra 1 sản phẩm công khai. Tiếp theo nhập chính xác:

    ' OR is_public = 0 #

V1 cho thấy dữ liệu demo bị đánh dấu nội bộ; V2 không trả về kết quả. Chỉ sử dụng trên database lab, không thử trên website khác.

## Kiểm thử CSRF

1. Đăng nhập V1 tại http://localhost/mid_term_lap_trinh_web/v1/login.php bằng student / demo12345.
2. Ở tab khác mở http://127.0.0.1/mid_term_lap_trinh_web/attacker/csrf.html (lưu ý dùng 127.0.0.1 khác localhost).
3. Attacker page có sẵn URL của V1. Bấm Gửi GET giả mạo. Trở lại profile V1 tại localhost; tên nhận sẽ đổi thành CSRF-Attacked.
4. Đăng nhập V2 tại http://localhost/mid_term_lap_trinh_web/v2/login.php. Trong attacker page đổi đường dẫn v1 thành v2 và bấm cùng nút. HTTP 405; tên không đổi.
5. Dùng form profile V2 để đổi tên hợp lệ; đây là POST kèm token session. Thử POST thiếu token sẽ nhận HTTP 403.

Nếu Apache dùng port khác, thay toàn bộ URL cho đúng. Đây là mô phỏng qua top-level GET navigation, dùng cookie SameSite=Lax, không phải đánh cắp cookie.

## File quan trọng

- v1/ và v2/: endpoint của hai phiên bản.
- pages/: giao diện và xử lý nghiệp vụ dùng chung.
- lib/: cấu hình MySQL PDO, session, escaping, security headers, layout.
- database/setup.sql: tạo bảng và seed dữ liệu giả.
- assets/images/: 9 ảnh hoa thực tế được lấy từ tbao4151/Lap_trinh_web.
- attacker/csrf.html: trang gửi yêu cầu mô phỏng, chỉ cho phép localhost.
- docs/: test matrix, kịch bản demo, đề cương báo cáo.

## Giới hạn và an toàn

Không cấu hình public hosting hoặc port forwarding cho ứng dụng V1. Không dùng dữ liệu khách hàng thật. Repo là mã nguồn thực hành; cần tự chạy trên XAMPP và ghi lại ảnh chụp trình duyệt trước khi kết luận test pass. Nguồn ảnh: https://github.com/tbao4151/Lap_trinh_web.


## Khôi phục dữ liệu khi quay video nhiều lần

Khi thử CSRF trên V1, tên CSRF-Attacked được lưu trong MySQL. Đăng xuất chỉ xóa session, nên đăng nhập lại vẫn hiển thị tên đã lưu. Để quay lại từ đầu, đăng nhập vào V1 hoặc V2, mở profile.php và bấm **Đặt lại dữ liệu demo**.

Nút này chỉ đặt lại tên tài khoản demo của phiên bản đang xem về **Khách hàng Demo**. Nó sử dụng POST và CSRF token, không xóa bảng hay sản phẩm. Hành vi logout vẫn đúng nghĩa: chỉ kết thúc phiên đăng nhập.
