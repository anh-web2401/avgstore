// Header and Footer Components
function loadHeaderFooter() {
    // Header
    document.getElementById('header-container').innerHTML = `
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <div class="flex items-center space-x-2 cursor-pointer" onclick="navigateTo('home')">
                    <div class="text-3xl"></div>
                    <div class="text-2xl font-extrabold tracking-wider text-[#C3AED6]">
                        AVG <span class="text-sm font-light text-gray-400">STORE</span>
                    </div>
                </div>

                <nav class="hidden md:flex space-x-8 font-medium">
                    <a href="#" onclick="navigateTo('home')" class="hover:text-[#C3AED6] transition-colors">Trang Chủ</a>
                    <a href="#" onclick="navigateTo('products')" class="hover:text-[#C3AED6] transition-colors">Sản Phẩm</a>
                    <a href="#" onclick="showCart()" class="hover:text-[#C3AED6] transition-colors relative">
                        <i class="fas fa-shopping-cart"></i> Giỏ Hàng 
                        <span id="cart-count" class="absolute -top-2 -right-4 bg-red-500 text-white rounded-full px-2 py-0.5 text-xs">0</span>
                    </a>
                    ${currentUser && currentUser.role === 'admin' ? `
                        <a href="#" onclick="navigateTo('admin')" class="text-red-400 hover:text-red-600 transition-colors">
                            <i class="fas fa-cog"></i> Quản Trị
                        </a>
                    ` : ''}
                </nav>

                <div class="flex items-center space-x-4">
                    <button id="auth-btn" class="bg-[#E8DFF5] hover:bg-[#C3AED6] text-gray-700 px-5 py-2 rounded-full font-medium transition-all duration-300 shadow-sm">
                        <i class="fas fa-user mr-2"></i>Đăng Nhập
                    </button>
                </div>
            </div>
        </header>
    `;
    
    // Footer
    document.getElementById('footer-container').innerHTML = `
        <footer class="bg-white border-t border-gray-200 mt-20 text-gray-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="text-2xl"></div>
                            <div class="text-2xl font-extrabold text-[#C3AED6]">AVG <span class="text-xs text-gray-400">STORE</span></div>
                        </div>
                        <p class="text-sm text-gray-500">Thương hiệu thời trang nữ phong cách pastel nhẹ nhàng, tinh tế.</p>
                        <div class="flex space-x-4 mt-4">
                            <a href="#" class="text-gray-400 hover:text-[#C3AED6]"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-gray-400 hover:text-[#C3AED6]"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-gray-400 hover:text-[#C3AED6]"><i class="fab fa-tiktok"></i></a>
                            <a href="#" class="text-gray-400 hover:text-[#C3AED6]"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold uppercase text-gray-700 mb-3">Thông Tin</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="#" class="hover:text-[#C3AED6]">Giới thiệu</a></li>
                            <li><a href="#" class="hover:text-[#C3AED6]">Tuyển dụng</a></li>
                            <li><a href="#" class="hover:text-[#C3AED6]">Chính sách bảo mật</a></li>
                            <li><a href="#" class="hover:text-[#C3AED6]">Điều khoản sử dụng</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold uppercase text-gray-700 mb-3">Hỗ Trợ</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="#" class="hover:text-[#C3AED6]">Hướng dẫn mua hàng</a></li>
                            <li><a href="#" class="hover:text-[#C3AED6]">Chính sách đổi trả</a></li>
                            <li><a href="#" class="hover:text-[#C3AED6]">Phương thức thanh toán</a></li>
                            <li><a href="#" class="hover:text-[#C3AED6]">FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold uppercase text-gray-700 mb-3">Liên Hệ</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><i class="fas fa-map-marker-alt mr-2 text-[#C3AED6]"></i> TP. Hồ Chí Minh, Vietnam</li>
                            <li><i class="fas fa-phone mr-2 text-[#C3AED6]"></i> 0123.456.789</li>
                            <li><i class="fas fa-envelope mr-2 text-[#C3AED6]"></i> contact@avgstore.com</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t mt-8 pt-6 text-center text-xs text-gray-400">
                    <p>&copy; 2026 AVG Store. All rights reserved.</p>
                </div>
            </div>
        </footer>
    `;
    
    // Update auth button after loading header
    updateAuthButton();
    
    // Update cart count display
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCountElement.innerText = count;
    }
}

// Call this after DOM is loaded
document.addEventListener('DOMContentLoaded', loadHeaderFooter);