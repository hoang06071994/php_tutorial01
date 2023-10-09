<!-- Các bước tạo đường dẫn tĩnh -->
b1: Lấy tên ( Trường tên )
b2: Thay thế
+ Chuyển tất cả các ký tự thành chữ thường
+ Chuyển các chữ tiếng việt có dấu => không dấu
+ Chuyển các ký tự đặc biệt ( bao gồm cả khoảng trắng) => -

b3: Tự động điền vào input slug

<!-- Ngôn ngữ sử dụng -->
1. PHP
- Tạo slug tự động dựa vào tên ( trường slug không nhập)
- Update slug vào CSDL

2. JavaScript
- Tạo slug tự động dựa vào tên ( Bắt sự kiện onkeyup ở trường tên)
- Điền dữ liệu vào trường slug
=> Gõ ký tự ở trường tên => tự động điền vào trương slug