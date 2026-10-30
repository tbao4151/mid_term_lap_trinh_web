# Audit giao diện CÁ'S HOA — Web Security Studio

## Nguồn và phạm vi

- Bản cũ được xem từ ảnh chụp màn hình trang XSS V1 trên Chrome, 1649×928, do người thực hiện cung cấp.
- Mã nguồn PHP/CSS được kiểm tra tại repo này.
- Quy tắc tham chiếu: tbao4151/ui-ux-skill/.claude/skills/ui-ux-pro-max/SKILL.md, references/quick-reference.md và design-system/SKILL.md.
- Phạm vi chỉnh sửa: giao diện dùng chung V1/V2, không thay đổi payload, truy vấn SQL hay các cơ chế xác thực phiên bản.

## Phát hiện trước chỉnh sửa

| Vấn đề | Tác động | Cách xử lý |
| --- | --- | --- |
| H1 tiếng Việt dùng Georgia kích thước lớn bị xuống dòng xấu | Giảm chất lượng thương hiệu và khả năng đọc | H1/H2/H3 chuyển sang Segoe UI với text-wrap balance và giới hạn độ dài |
| Khối tiêu đề chiếm nhiều chiều cao | Form và trạng thái demo thấp, gây cuộn nhiều | Hero dạng lưới ngắn; ảnh hoa thật ở bên phải |
| Ảnh sản phẩm nhỏ bên trong form | Giao diện ít giống trang bán hoa | Tăng ảnh sản phẩm đầu trang, giữ product preview nhỏ trong form |
| Text/nhãn 10–11px và màu nhạt | Khó đọc trên màn hình thường | Tăng nhãn 12–14px, body 16px, dùng màu tương phản hơn |
| Nút chuyển V1/V2 luôn về trang chủ | Mất vị trí và input kiểm thử | Chuyển trên cùng route, bảo toàn q hoặc term hợp lệ |
| Không có skip link và focus rõ ràng | Trải nghiệm bàn phím chưa tốt | Skip link, focus ring, aria-current, role=status |
| Khu vực payload và cửa hàng chưa phân tầng rõ | Lẫn thao tác dành cho người mua và người kiểm thử | Lab card riêng, 3 bước quan sát và code block riêng |

## Design tokens

- Nền: #f8f7f2; bề mặt: #fffefa; chữ chính: #273c30; chữ phụ: #59685d.
- Primary: #4c6651 trên nền trắng; trạng thái an toàn: #386946; cảnh báo: #a54837.
- Font: Segoe UI / Arial / Tahoma; không cần tải font qua mạng để chạy XAMPP.
- Layout desktop 1192px, grid hero + sidebar; responsive tại 1120/900/680/370px.
- Không sử dụng icon và không thêm dependency frontend.

## Kiểm tra tương phản tĩnh (WCAG)

Tính theo công thức sRGB, đây là kiểm tra màu CSS, **không phải kết quả audit WCAG toàn diện**:

| Cặp màu | Tỉ lệ |
| --- | ---: |
| #59685d trên #fffefa | 5.84:1 |
| #59685d trên #f8f7f2 | 5.49:1 |
| #4c6651 trên #fffefa | 6.26:1 |
| #ffffff trên #4c6651 | 6.31:1 |
| #a54837 trên #fff0e9 | 5.27:1 |
| #273c30 trên #f8f7f2 | 11.03:1 |

## Kiểm thử

CI tự động kiểm tra cú pháp PHP, dữ liệu MySQL mẫu và luồng XSS / SQLi / CSRF, đồng thời đảm bảo CSS và ảnh mẫu có thể được phục vụ cục bộ.

Phải mở trên XAMPP Windows và kiểm tra bằng trình duyệt thật trước khi chấp nhận kết quả visual QA: 1440px, 1024px, 768px, 390px và 375px; tab keyboard, focus, tải ảnh, input dài, chế độ reduced-motion. Không thay thế ảnh chụp của người thực hiện.
