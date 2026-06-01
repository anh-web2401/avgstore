// Cart Functions
let cart = [];

function addToCart(productId, quantity = 1) {
    if (!currentUser) {
        showMessage('Vui lòng đăng nhập để mua hàng!');
        navigateTo('auth');
        return;
    }
    
    const product = DB.products.find(p => p.id === productId);
    if (!product) return;
    
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({ ...product, quantity });
    }
    
    updateCartCount();
    showMessage(`Đã thêm ${product.name} vào giỏ hàng! 🛒`);
}

function updateCartCount() {
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.innerText = count;
    }
}

function showCart() {
    const modal = document.getElementById('cart-modal');
    const cartItemsDiv = document.getElementById('cart-items');
    
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-shopping-cart text-4xl mb-3 opacity-50"></i>
                <p>Giỏ hàng trống</p>
            </div>
        `;
        document.getElementById('cart-total').innerText = '0đ';
    } else {
        cartItemsDiv.innerHTML = cart.map(item => `
            <div class="flex justify-between items-center border-b pb-3">
                <div class="flex-1">
                    <h4 class="font-semibold">${item.name}</h4>
                    <p class="text-sm text-gray-500">${formatPrice(item.price)}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" class="w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300 transition-colors">-</button>
                    <span class="w-8 text-center">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" class="w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300 transition-colors">+</button>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 ml-2 hover:text-red-700">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `).join('');
        
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        document.getElementById('cart-total').innerHTML = formatPrice(total);
    }
    
    modal.classList.add('active');
}

function updateQuantity(productId, newQuantity) {
    if (newQuantity <= 0) {
        removeFromCart(productId);
    } else {
        const item = cart.find(i => i.id === productId);
        if (item) item.quantity = newQuantity;
        showCart();
        updateCartCount();
    }
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    showCart();
    updateCartCount();
}

function closeCart() {
    document.getElementById('cart-modal').classList.remove('active');
}

function checkout() {
    if (cart.length === 0) {
        showMessage('Giỏ hàng trống!');
        return;
    }
    
    const order = {
        id: DB.currentId.orders++,
        userId: currentUser.id,
        userName: currentUser.name,
        items: [...cart],
        total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
        date: new Date().toISOString(),
        status: 'pending'
    };
    
    DB.orders.push(order);
    cart = [];
    updateCartCount();
    closeCart();
    showMessage('Đặt hàng thành công! Cảm ơn bạn đã mua sắm tại AVG Store 💖');
    
    // Send order confirmation (simulated)
    console.log('Order placed:', order);
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function loadCart() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCartCount();
    }
}

// Auto-save cart
setInterval(saveCart, 30000);
window.addEventListener('beforeunload', saveCart);
loadCart();