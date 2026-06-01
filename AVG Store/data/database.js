// Database
const DB = {
    users: [
        { id: 1, name: 'Admin', email: 'admin@avgstore.com', password: 'admin123', role: 'admin', phone: '0900123456', avatar: '👩‍💼' },
        { id: 2, name: 'Nguyễn Thị A', email: 'user@avgstore.com', password: 'user123', role: 'user', phone: '0900123457', avatar: '👩' }
    ],
    products: [
        { id: 1, name: 'Váy Voan Tím Pastel', price: 350000, category: 'Đầm', stock: 45, description: 'Đầm dáng xòe tiểu thư, chất liệu voan mềm mại, tạo cảm giác nhẹ nhàng và nữ tính. Phù hợp cho các buổi dạo phố hay tiệc nhẹ.', image: '👗', hot: true, sizes: ['S', 'M', 'L'], colors: ['Tím', 'Hồng', 'Trắng'] },
        { id: 2, name: 'Áo Blazer Màu Be', price: 420000, category: 'Áo khoác', stock: 12, description: 'Blazer phong cách Hàn Quốc, trẻ trung và thanh lịch. Chất liệu cao cấp, form dáng chuẩn, thích hợp cho môi trường công sở.', image: '🧥', hot: true, sizes: ['S', 'M', 'L', 'XL'], colors: ['Be', 'Đen', 'Trắng'] },
        { id: 3, name: 'Sơ Mi Lụa Cổ Đức', price: 280000, category: 'Áo sơ mi', stock: 30, description: 'Sơ mi lụa cao cấp với cổ đức cách điệu, mát mẻ và thoải mái. Dễ dàng phối đồ với quần tây hay chân váy.', image: '👔', hot: false, sizes: ['S', 'M', 'L'], colors: ['Trắng', 'Hồng', 'Xanh'] },
        { id: 4, name: 'Chân Váy A Tone Be', price: 250000, category: 'Chân váy', stock: 20, description: 'Chân váy xếp ly dáng A, tôn dáng và che khuyết điểm hiệu quả. Chất vải dày dặn, không nhăn, dễ phối đồ.', image: '👗', hot: false, sizes: ['S', 'M', 'L'], colors: ['Be', 'Đen', 'Xám'] },
        { id: 5, name: 'Đầm Suông Hoa Nhí', price: 390000, category: 'Đầm', stock: 25, description: 'Đầm suông dễ thương với họa tiết hoa nhí, chất vải thoáng mát. Rất phù hợp cho những ngày hè năng động.', image: '👗', hot: true, sizes: ['S', 'M', 'L'], colors: ['Hồng', 'Xanh', 'Vàng'] },
        { id: 6, name: 'Quần Tây Ống Rộng', price: 320000, category: 'Quần', stock: 18, description: 'Quần tây ống rộng phong cách Hàn Quốc, tạo sự thoải mái và sang trọng. Phù hợp cho công sở và các buổi gặp mặt.', image: '👖', hot: false, sizes: ['S', 'M', 'L', 'XL'], colors: ['Đen', 'Trắng', 'Be'] },
        { id: 7, name: 'Áo Thun Basic Cổ Tròn', price: 150000, category: 'Áo thun', stock: 50, description: 'Áo thun basic cổ tròn, chất cotton 100% thoáng mát. Dễ dàng phối đồ với mọi loại quần.', image: '👕', hot: true, sizes: ['S', 'M', 'L', 'XL'], colors: ['Trắng', 'Đen', 'Hồng', 'Xanh'] },
        { id: 8, name: 'Váy Maxi Dự Tiệc', price: 550000, category: 'Đầm', stock: 10, description: 'Váy maxi dự tiệc sang trọng, thiết kế cầu kỳ với đính kết tinh tế. Phù hợp cho các buổi tiệc quan trọng.', image: '👗', hot: true, sizes: ['S', 'M', 'L'], colors: ['Đỏ', 'Đen', 'Vàng'] }
    ],
    orders: [],
    employees: [],
    currentId: { users: 3, products: 9, orders: 1 }
};

// Helper functions
function showLoading(show) {
    const loading = document.getElementById('loading');
    if (loading) {
        loading.style.display = show ? 'flex' : 'none';
    }
}

function showMessage(msg, isError = false) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white z-50 animate-fade-in ${isError ? 'bg-red-500' : 'bg-green-500'}`;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
}