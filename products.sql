-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 11, 2026 lúc 04:35 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `avg_store`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`, `description`, `created_at`) VALUES
(1, 'ÁO THÊU NHÚN THUN', 'Áo Sơ Mi & Blazer', '1.299.000', 'https://static.zara.net/assets/public/12e4/1d86/99284949b788/fe004d93592f/06014508701-000-p/06014508701-000-p.jpg?ts=1778670713910&w=1024', '', '2026-06-06 15:48:43'),
(2, 'ÁO SƠ MI SÁT NÁCH THẮT NƠ KẺ SỌC', 'Áo Sơ Mi & Blazer', '1.199.000', 'https://static.zara.net/assets/public/03a6/e4e4/86704c9bb419/ef0d55502bac/03091320944-p/03091320944-p.jpg?ts=1776944757134&w=1024', '', '2026-06-07 08:27:52'),
(3, 'ĐẦM MIDI KÈM THẮT LƯNG', 'Váy & Đầm', '1.899.000', 'https://static.zara.net/assets/public/1da4/4b04/6e3c49548e96/d60b9f322284/04745031405-p/04745031405-p.jpg?ts=1776852182877&w=1024', '', '2026-06-07 08:33:20'),
(4, 'ĐẦM MIDI VẢI LENIN KÈM THẮT LƯNG', 'Váy & Đầm', '1.899.000', 'https://static.zara.net/assets/public/a0b1/5ed3/094b4bd8a5c1/eff617431ec8/04745037052-p/04745037052-p.jpg?ts=1776248707216&w=1024', '', '2026-06-07 09:20:58'),
(5, 'QUẦN VẢI MỀM', 'Quần & Chân Váy', '1.399.000', 'https://static.zara.net/assets/public/a699/6d1d/62874e6383bc/5cdafa6b7227/04391405916-a1/04391405916-a1.jpg?ts=1766395330883&w=1126', '', '2026-06-08 06:20:39'),
(6, 'ÁO KHOÁC DÁNG ÔM CÓ KHUY ZW COLLECTION', 'Áo Sơ Mi & Blazer', '3.199.000 VND', 'https://static.zara.net/assets/public/614d/b7f4/7ece43fa917a/68d720be1616/03811240712-p/03811240712-p.jpg?ts=1780646444135&w=1024', 'Áo khoác được may bằng sợi pha cotton. Cổ cao và tay dài. Túi giả may viền phía trước. Gấu dáng suông. Cài phía trước bằng móc kim loại.', '2026-06-08 07:03:13'),
(7, 'ÁO SƠ MI VẢI LINEN DÁNG NGẮN', 'Áo Sơ Mi & Blazer', '899.000 VND', 'https://static.zara.net/assets/public/cfa6/5343/97524ac0aea9/61d718ee52b6/02187813403-e1/02187813403-e1.jpg?ts=1780578254969&w=1024', 'Áo sơ mi vải linen. Cổ ve lật, cổ chữ V, cộc tay. Có túi đáp phía trước. Cài khuy phía trước.', '2026-06-08 07:03:13'),
(8, 'QUẦN BOMBACHO CHẤM BI ZW COLLECTION', 'Quần & Chân Váy', '1.899.000 VND', 'https://static.zara.net/assets/public/f026/136c/adf140c088be/993a57f99bd4/09479084401-a1/09479084401-a1.jpg?ts=1780651387479&w=1024', 'Quần cạp vừa được may bằng sợi viscose. Túi ẩn ở đường may hai bên. Chi tiết chấm bi tương phản. Gấu quần phồng có dây buộc. Cài bên hông bằng khóa kéo ẩn trong đường may.', '2026-06-08 07:03:13'),
(9, 'TÚI SHOPPER CỠ LỚN', 'Phụ Kiện', '1.199.000 VND', 'https://static.zara.net/assets/public/d917/e4c5/339e407d96ff/2bf82896a82d/16031710203-000-p/16031710203-000-p.jpg?ts=1773312330538&w=1024', 'Túi đeo vai shopper cỡ lớn. Chi tiết đường chỉ khâu nổi trên thân túi. Ví bên trong có khóa kéo kim loại. Tay cầm đôi. Đóng bằng nam châm.', '2026-06-08 07:03:13'),
(10, 'QUẦN BOMBACHO REN ZW COLLECTION', 'Quần & Chân Váy', '1.899.000 VND', 'https://static.zara.net/assets/public/cca7/dae8/97444dae8308/a91952f76ec5/05107105251-p/05107105251-p.jpg?ts=1780651089961&w=1024', 'Quần có chất liệu vải chính được làm từ cotton. Cạp lỡ và cạp chun có thể điều chỉnh bằng dây buộc. Chi tiết đắp ren cùng tông màu. Gấu quần bo chun.', '2026-06-08 07:08:41'),
(19, 'QUẦN VẢI RŨ ỐNG RỘNG', 'Quần & Chân Váy', '999.000 VND', 'https://static.zara.net/assets/public/324d/6326/90974c0e872c/2cc982ffe7ef/02298249700-a1/02298249700-a1.jpg?ts=1778668521291&w=1126', 'Kích thước sản phẩm\r\nChất liệu, cách chăm sóc & nguồn gốc\r\nKiểm tra tình trạng còn hàng tại cửa hàng\r\n\r\nGửi, Đổi và Hoàn trả hàng', '2026-06-10 03:21:06'),
(20, 'ÁO MĂNG TÔ NGẮN CÓ ĐỆM VAI VÀ THẮT LƯNG', 'Áo Sơ Mi & Blazer', '2.199.000 VND', 'https://static.zara.net/assets/public/c45b/3414/6b784e068312/5f6e1cdc9e33/06318233743-e1/06318233743-e1.jpg?ts=1778687813520&w=750', '', '2026-06-10 03:23:08'),
(21, 'KÍNH MÁT GỌNG KIM LOẠI HÌNH CHỮ NHẬT', 'Phụ Kiện', '199.000 VND', 'https://static.zara.net/assets/public/4daa/40ef/32ab49aa9952/e843820f259a/04431206303-e2/04431206303-e2.jpg?ts=1780481674329&w=1430', '', '2026-06-10 03:25:35'),
(22, 'VÒNG CỔ DÂY THỪNG HÌNH RẮN', 'Phụ Kiện', '599.000 VND', 'https://static.zara.net/assets/public/5afd/5d98/f1124cc09ee5/0b60038616e8/04548204303-e1/04548204303-e1.jpg?ts=1779441150618&w=750', '', '2026-06-10 03:27:06'),
(23, 'SET 2 VÒNG CỔ DÂY DÙ VÀ MẶT NHỰA RESIN', 'Phụ Kiện', '599.000 VND', 'https://static.zara.net/assets/public/e730/1924/0d414fb2a438/be3f4c85edf8/04548029330-e1/04548029330-e1.jpg?ts=1773220654501&w=1126', '', '2026-06-10 03:29:58'),
(24, 'MŨ LƯỠI TRAI 100% COTTON THÊU HỌA TIẾT TƯƠNG PHẢN', 'Phụ Kiện', '699.000 VND', 'https://static.zara.net/assets/public/c88f/c14e/824e43ae8a8c/37c852b2f384/01023053620-e1/01023053620-e1.jpg?ts=1772800509893&w=1126', '', '2026-06-10 06:41:28'),
(25, 'QUẦN DÀI THÊU HỌA TIẾT', 'Quần & Chân Váy', '1.899.000 VND', 'https://static.zara.net/assets/public/d215/b3eb/e8074bf4a149/f7cbfa484b39/01165108250-e1/01165108250-e1.jpg?ts=1774007949494&w=750', '', '2026-06-10 06:57:22'),
(26, 'ÁO PHÔNG THÊU HOA', 'Áo Sơ Mi & Blazer', '799.000 VND', 'https://static.zara.net/assets/public/d94e/6458/1d6d403d8fdc/6eb44676bfd6/01165037250-000-e1/01165037250-000-e1.jpg?ts=1771490274624&w=750', '', '2026-06-10 06:58:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
