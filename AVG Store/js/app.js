// Main Application
let currentUser = null;
let currentPage = 'home';
let currentProductDetail = null;

// Navigation
function navigateTo(page, productId = null) {
    currentPage = page;
    const content = document.getElementById('app-content');
    
    if (page === 'home') {
        loadHomePage();
    } else if (page === 'products') {
        loadProductsPage();
    } else if (page === 'auth') {
        loadAuthPage();
    } else if (page === 'admin') {
        if (currentUser && currentUser.role === 'admin') {
            loadAdminPage();
        } else {
            showMessage('Bạn cần đăng nhập với tài khoản admin!', true);
            navigateTo('auth');
        }
    } else if (page === 'product-detail' && productId) {
        loadProductDetail(productId);
    }
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Load Home Page
function loadHomePage() {
    const content = document.getElementById('app-content');
    const featuredProducts = DB.products.filter(p => p.hot).slice(0, 4);
    const newProducts = DB.products.slice(0, 4);
    
    content.innerHTML = `
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 fade-in">
            <!-- Hero Section -->
            <div class="hero-section bg-gradient-to-r from-[#E8DFF5] to-[#F5EBE6] rounded-3xl p-8 md:p-12 mb-12 flex flex-col md:flex-row items-center justify-between shadow-sm">
                <div class="mb-6 md:mb-0 md:max-w-md">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-500">New Collection 2026</span>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mt-2 mb-4">Nhẹ nhàng & <br>Tinh tế cùng AVG</h1>
                    <p class="text-gray-600 mb-6">Khám phá những mẫu thiết kế tone màu pastel ngọt ngào, tôn vinh vẻ đẹp của bạn.</p>
                    <button onclick="navigateTo('products')" class="bg-white text-gray-700 px-6 py-3 rounded-xl font-semibold shadow-sm hover:shadow-md transition-all">
                        Khám Phá Ngay <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
                <div class="w-48 h-48 md:w-64 md:h-64 bg-white rounded-full flex items-center justify-center shadow-inner overflow-hidden border-4 border-[#E8DFF5] animate-float">
                    <div class="text-7xl md:text-8xl">👗</div>
                </div>
            </div>

            <!-- Categories -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">DANH MỤC NỔI BẬT</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="category-card bg-white rounded-xl p-4 text-center cursor-pointer hover-scale" onclick="filterByCategory('Đầm')">
                        <div class="text-4xl mb-2">👗</div>
                        <p class="font-semibold">Đầm</p>
                    </div>
                    <div class="category-card bg-white rounded-xl p-4 text-center cursor-pointer hover-scale" onclick="filterByCategory('Áo khoác')">
                        <div class="text-4xl mb-2">🧥</div>
                        <p class="font-semibold">Áo khoác</p>
                    </div>
                    <div class="category-card bg-white rounded-xl p-4 text-center cursor-pointer hover-scale" onclick="filterByCategory('Áo sơ mi')">
                        <div class="text-4xl mb-2">👔</div>
                        <p class="font-semibold">Sơ mi</p>
                    </div>
                    <div class="category-card bg-white rounded-xl p-4 text-center cursor-pointer hover-scale" onclick="filterByCategory('Quần')">
                        <div class="text-4xl mb-2">👖</div>
                        <p class="font-semibold">Quần</p>
                    </div>
                </div>
            </div>

            <!-- Featured Products -->
            <div class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">SẢN PHẨM NỔI BẬT</h2>
                    <button onclick="navigateTo('products')" class="text-[#C3AED6] hover:underline">Xem tất cả →</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    ${renderProductCards(featuredProducts)}
                </div>
            </div>

            <!-- New Products -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">SẢN PHẨM MỚI</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    ${renderProductCards(newProducts)}
                </div>
            </div>
        </div>
    `;
}

// Load Products Page
function loadProductsPage(category = null) {
    const content = document.getElementById('app-content');
    let products = DB.products;
    
    if (category) {
        products = products.filter(p => p.category === category);
    }
    
    content.innerHTML = `
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 fade-in">
            <div class="mb-8">
                <button onclick="navigateTo('home')" class="text-gray-500 hover:text-[#C3AED6] mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </button>
                <h2 class="text-3xl font-bold text-gray-800">TẤT CẢ SẢN PHẨM</h2>
                <p class="text-sm text-gray-500 mt-2">Khám phá bộ sưu tập đa dạng của chúng tôi</p>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap gap-3 mb-8 pb-4 border-b">
                <button onclick="loadProductsPage()" class="filter-btn px-4 py-2 rounded-full ${!category ? 'bg-[#C3AED6] text-white' : 'bg-gray-200'}">Tất cả</button>
                ${Array.from(new Set(products.map(p => p.category))).map(cat => `
                    <button onclick="loadProductsPage('${cat}')" class="filter-btn px-4 py-2 rounded-full ${category === cat ? 'bg-[#C3AED6] text-white' : 'bg-gray-200'}">${cat}</button>
                `).join('')}
            </div>
            
            <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                ${renderProductCards(products)}
            </div>
        </div>
    `;
}

// Render Product Cards
function renderProductCards(products) {
    if (!products || products.length === 0) {
        return '<div class="col-span-full text-center py-12 text-gray-500">Không có sản phẩm nào</div>';
    }
    
    return products.map(product => `
        <div class="product-card hover-scale cursor-pointer" onclick="navigateTo('product-detail', ${product.id})">
            <div class="product-image">
                ${product.hot ? '<span class="product-badge">🔥 Hot</span>' : ''}
                <div class="product-icon">${product.image || '👗'}</div>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-lg text-gray-800 mb-1 line-clamp-1">${product.name}</h3>
                <p class="text-sm text-gray-500 mb-2 line-clamp-2">${product.description.substring(0, 60)}...</p>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-xl font-bold text-[#C3AED6]">${formatPrice(product.price)}</span>
                    <button onclick="event.stopPropagation(); addToCart(${product.id})" class="bg-[#F5EBE6] hover:bg-[#E8DFF5] px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-shopping-cart"></i> Thêm
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Load Product Detail
function loadProductDetail(productId) {
    const product = DB.products.find(p => p.id === productId);
    if (!product) {
        navigateTo('home');
        return;
    }
    
    const relatedProducts = DB.products.filter(p => p.category === product.category && p.id !== product.id).slice(0, 4);
    
    const content = document.getElementById('app-content');
    content.innerHTML = `
        <div class="detail-container fade-in">
            <button onclick="navigateTo('products')" class="text-gray-500 hover:text-[#C3AED6] mb-6 inline-block">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại sản phẩm
            </button>
            
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="grid md:grid-cols-2 gap-8 p-6 md:p-8">
                    <!-- Product Image -->
                    <div class="detail-image">
                        <div class="product-icon">${product.image || '👗'}</div>
                    </div>
                    
                    <!-- Product Info -->
                    <div>
                        ${product.hot ? '<span class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm mb-3">🔥 Sản phẩm hot</span>' : ''}
                        <h1 class="text-3xl font-bold text-gray-800 mb-3">${product.name}</h1>
                        <div class="text-3xl font-bold text-[#C3AED6] mb-4">${formatPrice(product.price)}</div>
                        
                        <div class="mb-4">
                            <p class="text-gray-600 leading-relaxed">${product.description}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Danh mục: <span class="font-semibold text-gray-700">${product.category}</span></p>
                            <p class="text-sm text-gray-500">Tình trạng: <span class="font-semibold ${product.stock > 0 ? 'text-green-600' : 'text-red-600'}">${product.stock > 0 ? 'Còn hàng' : 'Hết hàng'}</span></p>
                            <p class="text-sm text-gray-500">Số lượng tồn: <span class="font-semibold">${product.stock}</span></p>
                        </div>
                        
                        ${product.sizes ? `
                        <div class="mb-4">
                            <p class="font-semibold mb-2">Kích thước:</p>
                            <div class="flex gap-2">
                                ${product.sizes.map(size => `<button class="size-btn w-10 h-10 border rounded-full hover:border-[#C3AED6] hover:bg-[#C3AED6] hover:text-white transition-all">${size}</button>`).join('')}
                            </div>
                        </div>
                        ` : ''}
                        
                        ${product.colors ? `
                        <div class="mb-6">
                            <p class="font-semibold mb-2">Màu sắc:</p>
                            <div class="flex gap-2">
                                ${product.colors.map(color => `<button class="color-btn w-8 h-8 rounded-full border-2 hover:border-[#C3AED6] transition-all" style="background: ${getColorCode(color)}" title="${color}"></button>`).join('')}
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="flex gap-4">
                            <button onclick="addToCart(${product.id})" class="flex-1 bg-[#C3AED6] text-white py-3 rounded-xl font-bold hover:bg-[#b09bc4] transition-colors">
                                <i class="fas fa-shopping-cart mr-2"></i> Thêm vào giỏ
                            </button>
                            <button onclick="buyNow(${product.id})" class="flex-1 border-2 border-[#C3AED6] text-[#C3AED6] py-3 rounded-xl font-bold hover:bg-[#C3AED6] hover:text-white transition-colors">
                                Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Products -->
            ${relatedProducts.length > 0 ? `
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">SẢN PHẨM LIÊN QUAN</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    ${renderProductCards(relatedProducts)}
                </div>
            </div>
            ` : ''}
        </div>
    `;
}

function getColorCode(color) {
    const colors = {
        'Trắng': '#FFFFFF',
        'Đen': '#000000',
        'Hồng': '#FFB6C1',
        'Xanh': '#87CEEB',
        'Đỏ': '#FF4444',
        'Vàng': '#FFD700',
        'Be': '#F5F5DC',
        'Tím': '#C3AED6',
        'Xám': '#808080'
    };
    return colors[color] || '#CCCCCC';
}

function filterByCategory(category) {
    loadProductsPage(category);
}

function buyNow(productId) {
    if (!currentUser) {
        showMessage('Vui lòng đăng nhập để mua hàng!');
        navigateTo('auth');
        return;
    }
    addToCart(productId);
    showCart();
}

// Initialize app
function init() {
    const savedUser = localStorage.getItem('currentUser');
    if (savedUser) {
        currentUser = JSON.parse(savedUser);
        updateAuthButton();
    }
    loadHomePage();
    updateCartCount();
}

init();