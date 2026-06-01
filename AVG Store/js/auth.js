// Authentication Functions
function loadAuthPage() {
    const content = document.getElementById('app-content');
    content.innerHTML = `
        <div class="max-w-md mx-auto my-16 px-4 fade-in">
            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="text-center mb-8">
                    <div class="text-5xl mb-3">👋</div>
                    <h2 class="text-2xl font-bold text-gray-800">Chào mừng đến với AVG Store</h2>
                    <p class="text-gray-500 text-sm mt-1">Đăng nhập để trải nghiệm mua sắm tốt nhất</p>
                </div>
                
                <div class="flex justify-center space-x-6 mb-8 border-b pb-4">
                    <button id="show-login" class="text-[#C3AED6] border-b-2 border-[#C3AED6] pb-2 font-medium">Đăng Nhập</button>
                    <button id="show-register" class="text-gray-400 pb-2 font-medium">Đăng Ký</button>
                </div>

                <!-- Login Form -->
                <form id="login-form" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                        <input type="email" id="login-email" required class="w-full px-4 py-3 bg-[#F5EBE6] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C3AED6]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Mật khẩu</label>
                        <input type="password" id="login-password" required class="w-full px-4 py-3 bg-[#F5EBE6] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C3AED6]">
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#C3AED6] text-white rounded-xl font-bold hover:bg-[#b09bc4] transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i> Đăng Nhập
                    </button>
                </form>

                <!-- Register Form -->
                <form id="register-form" class="space-y-5 hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Họ và Tên</label>
                        <input type="text" id="register-name" required class="w-full px-4 py-3 bg-[#F5EBE6] rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                        <input type="email" id="register-email" required class="w-full px-4 py-3 bg-[#F5EBE6] rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Số điện thoại</label>
                        <input type="tel" id="register-phone" class="w-full px-4 py-3 bg-[#F5EBE6] rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Mật khẩu</label>
                        <input type="password" id="register-password" required class="w-full px-4 py-3 bg-[#F5EBE6] rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Xác nhận mật khẩu</label>
                        <input type="password" id="register-confirm-password" required class="w-full px-4 py-3 bg-[#F5EBE6] rounded-xl">
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#C3AED6] text-white rounded-xl font-bold hover:bg-[#b09bc4] transition-colors">
                        <i class="fas fa-user-plus mr-2"></i> Tạo Tài Khoản
                    </button>
                </form>
                
                <div class="mt-6 text-center text-xs text-gray-400">
                    <p>Admin: admin@avgstore.com | admin123</p>
                    <p>User: user@avgstore.com | user123</p>
                </div>
            </div>
        </div>
    `;
    
    // Bind events
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const showLoginBtn = document.getElementById('show-login');
    const showRegisterBtn = document.getElementById('show-register');
    
    loginForm.onsubmit = (e) => {
        e.preventDefault();
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        
        const user = DB.users.find(u => u.email === email && u.password === password);
        if (user) {
            currentUser = user;
            localStorage.setItem('currentUser', JSON.stringify(user));
            updateAuthButton();
            showMessage(`Chào mừng ${user.name} trở lại! 💖`);
            navigateTo('home');
        } else {
            showMessage('Email hoặc mật khẩu không đúng!', true);
        }
    };
    
    registerForm.onsubmit = (e) => {
        e.preventDefault();
        const name = document.getElementById('register-name').value;
        const email = document.getElementById('register-email').value;
        const phone = document.getElementById('register-phone').value;
        const password = document.getElementById('register-password').value;
        const confirmPassword = document.getElementById('register-confirm-password').value;
        
        if (password !== confirmPassword) {
            showMessage('Mật khẩu xác nhận không khớp!', true);
            return;
        }
        
        if (DB.users.find(u => u.email === email)) {
            showMessage('Email đã tồn tại!', true);
            return;
        }
        
        const newUser = {
            id: DB.currentId.users++,
            name,
            email,
            phone,
            password,
            role: 'user',
            avatar: '👩'
        };
        
        DB.users.push(newUser);
        currentUser = newUser;
        localStorage.setItem('currentUser', JSON.stringify(newUser));
        updateAuthButton();
        showMessage('Đăng ký thành công! 🎉');
        navigateTo('home');
    };
    
    showLoginBtn.onclick = () => {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        showLoginBtn.className = "text-[#C3AED6] border-b-2 border-[#C3AED6] pb-2 font-medium";
        showRegisterBtn.className = "text-gray-400 pb-2 font-medium";
    };
    
    showRegisterBtn.onclick = () => {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        showRegisterBtn.className = "text-[#C3AED6] border-b-2 border-[#C3AED6] pb-2 font-medium";
        showLoginBtn.className = "text-gray-400 pb-2 font-medium";
    };
}

function logout() {
    currentUser = null;
    localStorage.removeItem('currentUser');
    updateAuthButton();
    showMessage('Đã đăng xuất! 👋');
    navigateTo('home');
}

function updateAuthButton() {
    const authBtn = document.getElementById('auth-btn');
    if (authBtn) {
        if (currentUser) {
            authBtn.innerHTML = `<i class="fas fa-user-circle mr-2"></i>${currentUser.name} <i class="fas fa-chevron-down ml-2 text-xs"></i>`;
            // Add dropdown menu
            if (!document.getElementById('user-dropdown')) {
                const dropdown = document.createElement('div');
                dropdown.id = 'user-dropdown';
                dropdown.className = 'absolute right-4 top-16 bg-white rounded-xl shadow-lg hidden z-50';
                dropdown.innerHTML = `
                    <div class="py-2">
                        <div class="px-4 py-2 border-b">
                            <p class="font-semibold">${currentUser.name}</p>
                            <p class="text-xs text-gray-500">${currentUser.email}</p>
                        </div>
                        ${currentUser.role === 'admin' ? '<a href="#" onclick="navigateTo(\'admin\')" class="block px-4 py-2 hover:bg-gray-100"><i class="fas fa-cog mr-2"></i>Quản trị</a>' : ''}
                        <a href="#" onclick="logout()" class="block px-4 py-2 hover:bg-gray-100 text-red-500"><i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất</a>
                    </div>
                `;
                authBtn.parentElement.appendChild(dropdown);
                
                authBtn.onclick = (e) => {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                };
                
                document.addEventListener('click', () => {
                    dropdown.classList.add('hidden');
                });
            }
        } else {
            authBtn.innerHTML = '<i class="fas fa-user mr-2"></i>Đăng Nhập';
            authBtn.onclick = () => navigateTo('auth');
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown) dropdown.remove();
        }
    }
}