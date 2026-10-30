# Kịch bản demo dự kiến (8–12 phút)

1. 0:00–1:00 — Giới thiệu đề số 3, mô hình cửa hàng CÁ'S HOA và giới hạn thử nghiệm localhost.
2. 1:00–2:00 — Kiến trúc Apache / PHP / MySQL, chung giao diện, 2 phiên bản V1 và V2.
3. 2:00–4:00 — XSS: tìm lời nhắn hoa, thử input bình thường, chạy cùng harmless payload trên V1/V2.
4. 4:00–6:00 — SQLi: tìm Garden bình thường, thử boolean payload; đánh giá 2 hồ sơ nội bộ, PDO prepare.
5. 6:00–9:00 — CSRF: login demo, mở attacker origin 127.0.0.1, gửi GET, V1 thay đổi, V2 405, POST hợp lệ.
6. 9:00–11:00 — Phân tích nguyên nhân, bảng so sánh khắc phục, kết luận và giới hạn demo.

Video cuối cùng phải có sự tham gia của tất cả thành viên, tối đa 20 phút. Chỉ trình bày kết quả đã kiểm tra và ảnh chụp thật trên XAMPP.
