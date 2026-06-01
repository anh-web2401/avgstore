// Database
const DB = {
    users: [
        { id: 1, name: 'Admin', email: 'admin@avgstore.com', password: 'admin123', role: 'admin', phone: '0900123456', avatar: '👩‍💼' },
        { id: 2, name: 'Nguyễn Thị A', email: 'user@avgstore.com', password: 'user123', role: 'user', phone: '0900123457', avatar: '👩' }
    ],
    products: [
        { 
            id: 1, 
            name: 'Đầm xòe', 
            price: 1490000  , 
            category: 'Đầm', 
            stock: 45, 
            description: 'Đầm chất liệu voan mềm mại, tạo cảm giác nhẹ nhàng và nữ tính. Phù hợp cho các buổi dạo phố hay tiệc nhẹ.',
            image: 'https://picsum.photos/id/20/400/500', // URL hình ảnh thật
            thumbnail: 'https://picsum.photos/id/20/200/250',
            images: [ // Thêm nhiều góc nhìn
                'https://picsum.photos/id/20/400/500',
                'https://picsum.photos/id/30/400/500',
                'https://picsum.photos/id/26/400/500'
            ],
            hot: true, 
            sizes: ['S', 'M', 'L'], 
            colors: ['Tím', 'Hồng', 'Trắng'] 
        },
        { 
            id: 2, 
            name: 'Áo Blazer Màu Be', 
            price: 420000, 
            category: 'Áo khoác', 
            stock: 12, 
            description: 'Blazer phong cách Hàn Quốc, trẻ trung và thanh lịch. Chất liệu cao cấp, form dáng chuẩn, thích hợp cho môi trường công sở.',
            image: 'https://picsum.photos/id/21/400/500',
            thumbnail: 'https://picsum.photos/id/21/200/250',
            images: [
                'https://picsum.photos/id/21/400/500',
                'https://picsum.photos/id/31/400/500'
            ],
            hot: true, 
            sizes: ['S', 'M', 'L', 'XL'], 
            colors: ['Be', 'Đen', 'Trắng'] 
        },
        { 
            id: 3, 
            name: 'Sơ Mi Lụa Cổ Đức', 
            price: 280000, 
            category: 'Áo sơ mi', 
            stock: 30, 
            description: 'Sơ mi lụa cao cấp với cổ đức cách điệu, mát mẻ và thoải mái. Dễ dàng phối đồ với quần tây hay chân váy.',
            image: 'https://picsum.photos/id/22/400/500',
            thumbnail: 'https://picsum.photos/id/22/200/250',
            images: [
                'https://picsum.photos/id/22/400/500'
            ],
            hot: false, 
            sizes: ['S', 'M', 'L'], 
            colors: ['Trắng', 'Hồng', 'Xanh'] 
        },
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