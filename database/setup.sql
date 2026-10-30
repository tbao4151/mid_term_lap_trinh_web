-- XAMPP localhost only. Import in phpMyAdmin. Reset ONLY the isolated lab database.
CREATE DATABASE IF NOT EXISTS cas_hoa_security_lab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cas_hoa_security_lab;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS products;
CREATE TABLE products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(160) NOT NULL,
 price INT UNSIGNED NOT NULL,
 image VARCHAR(160) NOT NULL,
 category VARCHAR(120) NOT NULL,
 occasion VARCHAR(120) NOT NULL,
 is_public TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(60) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 display_name VARCHAR(120) NOT NULL,
 email VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO products(name,price,image,category,occasion,is_public) VALUES
('Lam Tình',400000,'lam-tinh.png','Hoa ly','Tặng người thương',1),
('Garden',380000,'garden.png','Hoa ly','Sinh nhật',1),
('Hoa Ly',350000,'hoa-ly.png','Hoa ly','Chúc mừng',1),
('Ly Xanh',370000,'ly-xanh.png','Hoa ly','Kỷ niệm',1),
('Lily',310000,'lily.png','Lily','Sinh nhật',1),
('Một bó hoa, một lần được nhớ đến',390000,'mot-bo-hoa.png','Hoa hồng','Người thương',1),
('Cẩm tú cầu',450000,'cam-tu-cau.png','Cẩm tú cầu','Chúc mừng',1),
('Phi Yến',370000,'phi-yen.png','Phi yến','Kỷ niệm',1),
('Son sắc thuỷ chung',290000,'son-sac-thuy-chung.png','Hoa hồng','Người thương',1),
('Hồ sơ ẩn - Bộ hoa thử nghiệm',100000,'garden.png','Dữ liệu demo','Nội bộ',0),
('Hồ sơ ẩn - Mẫu hoa chưa công bố',100000,'cam-tu-cau.png','Dữ liệu demo','Nội bộ',0);
-- Both fake users login through the form using student / demo12345.
INSERT INTO users(username,password_hash,display_name,email) VALUES
('student_v1','$2y$12$PgTjM.AE48O5V6kU9mWgsOAI3BvETdWj5vUG2SJBEwpikwcfQvVfm','Khách hàng Demo','v1@example.test'),
('student_v2','$2y$12$PgTjM.AE48O5V6kU9mWgsOAI3BvETdWj5vUG2SJBEwpikwcfQvVfm','Khách hàng Demo','v2@example.test');
