// Admin Functions
let currentAdminTab = 'products';
let editId = null;

function loadAdminPage() {
    const content = document.getElementById('app-content');
    content.innerHTML = `
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 fade-in">
            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-5 mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-red-500">
                            <i class="fas fa-shield-alt mr-2"></i>HỆ THỐNG QUẢN TRỊ
                        </h2>
                        <p class="text-xs text-gray-400 mt-1">Quản lý dữ liệu AVG Store</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <button onclick="switchAdminTab('products')" id="tab-products" class="px-4 py-2 text-sm font-bold rounded-lg bg-red-400 text-white transition-all">
                            <i class="fas fa-box mr-1"></i> Sản Phẩm
                        </button>
                        <button onclick="switchAdminTab('employees')" id="tab-employees" class="px-4 py-2 text-sm font-bold rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-users mr-1"></i> Nhân Viên
                        </button>
                        <button onclick="switchAdminTab('customers')" id="tab-customers" class="px-4 py-2 text-sm font-bold rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-user-friends mr-1"></i> Khách Hàng
                        </button>
                        <button onclick="switchAdminTab('orders')" id="tab-orders" class="px-4 py-2 text-sm font-bold rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-shopping-cart mr-1"></i> Đơn Hàng
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <div id="admin-form" class="bg-gray-50 rounded-2xl p-5 mb-6 hidden">
                    <h3 id="form-title" class="text-sm font-bold text-gray-700 mb-4">➕ Thêm mới</h3>
                    <div id="form-inputs" class="grid grid-cols-1 md:grid-cols-3 gap-4"></div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button onclick="clearForm()" class="px-4 py-2 bg-gray-200 rounded-lg text-sm hover:bg-gray-300">Hủy</button>
                        <button onclick="saveData()" id="save-btn" class="px-4 py-2 bg-red-400 text-white rounded-lg text-sm hover:bg-red-500">Lưu</button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-xl border">
                    <table class="admin-table">
                        <thead id="table-head" class="bg-gray-100"></thead>
                        <tbody id="table-body" class="divide-y divide-gray-200 bg-white"></tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    switchAdminTab('products');
}

function switchAdminTab(tab) {
    currentAdminTab = tab;
    editId = null;
    
    // Update tab buttons
    ['products', 'employees', 'customers', 'orders'].forEach(t => {
        const btn = document.getElementById(`tab-${t}`);
        if (btn) {
            if (t === tab) {
                btn.className = "px-4 py-2 text-sm font-bold rounded-lg bg-red-400 text-white transition-all";
            } else {
                btn.className = "px-4 py-2 text-sm font-bold rounded-lg text-gray-600 hover:bg-gray-100";
            }
        }
    });
    
    renderAdminForm();
    renderAdminTable();
}

function renderAdminForm() {
    const container = document.getElementById('form-inputs');
    const formDiv = document.getElementById('admin-form');
    const title = document.getElementById('form-title');
    
    if (currentAdminTab === 'orders') {
        formDiv.classList.add('hidden');
        return;
    }
    
    formDiv.classList.remove('hidden');
    title.innerText = editId ? `✏️ Cập nhật (ID: ${editId})` : "➕ Thêm mới";
    
    let html = '';
    if (currentAdminTab === 'products') {
        html = `
            <input type="text" id="prod-name" placeholder="Tên sản phẩm *" class="p-2 border rounded-lg">
            <input type="number" id="prod-price" placeholder="Giá *" class="p-2 border rounded-lg">
            <input type="number" id="prod-stock" placeholder="Tồn kho" class="p-2 border rounded-lg">
            <input type="text" id="prod-category" placeholder="Danh mục" class="p-2 border rounded-lg">
            <textarea id="prod-desc" placeholder="Mô tả" class="p-2 border rounded-lg col-span-2" rows="2"></textarea>
            <select id="prod-hot" class="p-2 border rounded-lg">
                <option value="false">Sản phẩm thường</option>
                <option value="true">Sản phẩm hot</option>
            </select>
        `;
    } else if (currentAdminTab === 'employees') {
        html = `
            <input type="text" id="emp-name" placeholder="Họ tên *" class="p-2 border rounded-lg">
            <input type="email" id="emp-email" placeholder="Email *" class="p-2 border rounded-lg">
            <input type="text" id="emp-phone" placeholder="SĐT" class="p-2 border rounded-lg">
            <input type="password" id="emp-password" placeholder="Mật khẩu" class="p-2 border rounded-lg">
            <select id="emp-role" class="p-2 border rounded-lg">
                <option value="employee">Nhân viên</option>
                <option value="admin">Quản trị viên</option>
            </select>
        `;
    } else if (currentAdminTab === 'customers') {
        html = `
            <input type="text" id="cus-name" placeholder="Họ tên *" class="p-2 border rounded-lg">
            <input type="email" id="cus-email" placeholder="Email *" class="p-2 border rounded-lg">
            <input type="text" id="cus-phone" placeholder="SĐT" class="p-2 border rounded-lg">
        `;
    }
    container.innerHTML = html;
}

function renderAdminTable() {
    const thead = document.getElementById('table-head');
    const tbody = document.getElementById('table-body');
    
    let headers = [];
    let data = [];
    
    if (currentAdminTab === 'products') {
        headers = ['ID', 'Tên sản phẩm', 'Giá', 'Tồn kho', 'Danh mục', 'Hot', 'Hành động'];
        data = DB.products;
    } else if (currentAdminTab === 'employees') {
        headers = ['ID', 'Họ tên', 'Email', 'SĐT', 'Vai trò', 'Hành động'];
        data = DB.users.filter(u => u.role === 'employee' || u.role === 'admin');
    } else if (currentAdminTab === 'customers') {
        headers = ['ID', 'Họ tên', 'Email', 'SĐT', 'Hành động'];
        data = DB.users.filter(u => u.role === 'user');
    } else if (currentAdminTab === 'orders') {
        headers = ['ID', 'Khách hàng', 'Ngày đặt', 'Tổng tiền', 'Trạng thái', 'Chi tiết'];
        data = DB.orders;
    }
    
    thead.innerHTML = `<tr>${headers.map(h => `<th class="p-3 text-left">${h}</th>`).join('')}</tr>`;
    
    if (currentAdminTab === 'orders') {
        tbody.innerHTML = data.map(order => `
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">#${order.id}</td>
                <td class="p-3">${order.userName}</td>
                <td class="p-3">${new Date(order.date).toLocaleDateString('vi-VN')}</td>
                <td class="p-3 font-semibold text-[#C3AED6]">${formatPrice(order.total)}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs ${order.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'}">
                        ${order.status === 'pending' ? 'Chờ xử lý' : 'Đã giao'}
                    </span>
                </td>
                <td class="p-3">
                    <button onclick="viewOrderDetail(${order.id})" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-eye"></i> Xem
                    </button>
                 </td>
            </td>
        `).join('');
    } else {
        tbody.innerHTML = data.map(item => {
            if (currentAdminTab === 'products') {
                return `
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">${item.id}</td>
                        <td class="p-3 font-semibold">${item.name}</td>
                        <td class="p-3 text-[#C3AED6]">${formatPrice(item.price)}</td>
                        <td class="p-3">${item.stock}</td>
                        <td class="p-3"><span class="px-2 py-1 bg-gray-100 rounded-full text-xs">${item.category}</span></td>
                        <td class="p-3">${item.hot ? '🔥 Hot' : ''}</td>
                        <td class="p-3">
                            <button onclick="editItem(${item.id})" class="text-blue-500 mr-2 hover:text-blue-700"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteItem(${item.id})" class="text-red-500 hover:text-red-700"><i class="fas fa-trash-alt"></i></button>
                         </td>
                    </table>
                `;
            } else if (currentAdminTab === 'employees' || currentAdminTab === 'customers') {
                return `
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">${item.id}</td>
                        <td class="p-3">${item.name}</td>
                        <td class="p-3">${item.email}</td>
                        <td class="p-3">${item.phone || '---'}</td>
                        ${currentAdminTab === 'employees' ? `<td class="p-3"><span class="px-2 py-1 ${item.role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'} rounded-full text-xs">${item.role === 'admin' ? 'Quản trị' : 'Nhân viên'}</span></td>` : ''}
                        <td class="p-3">
                            <button onclick="editItem(${item.id})" class="text-blue-500 mr-2"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteItem(${item.id})" class="text-red-500"><i class="fas fa-trash-alt"></i></button>
                         </td>
                    </table>
                `;
            }
            return '';
        }).join('');
    }
}

function editItem(id) {
    editId = id;
    renderAdminForm();
    
    if (currentAdminTab === 'products') {
        const product = DB.products.find(p => p.id === id);
        if (product) {
            document.getElementById('prod-name').value = product.name;
            document.getElementById('prod-price').value = product.price;
            document.getElementById('prod-stock').value = product.stock;
            document.getElementById('prod-category').value = product.category;
            document.getElementById('prod-desc').value = product.description;
            document.getElementById('prod-hot').value = product.hot;
        }
    } else if (currentAdminTab === 'employees') {
        const user = DB.users.find(u => u.id === id);
        if (user) {
            document.getElementById('emp-name').value = user.name;
            document.getElementById('emp-email').value = user.email;
            document.getElementById('emp-phone').value = user.phone || '';
            if (document.getElementById('emp-role')) 
                document.getElementById('emp-role').value = user.role;
        }
    } else if (currentAdminTab === 'customers') {
        const user = DB.users.find(u => u.id === id);
        if (user) {
            document.getElementById('cus-name').value = user.name;
            document.getElementById('cus-email').value = user.email;
            document.getElementById('cus-phone').value = user.phone || '';
        }
    }
}

function deleteItem(id) {
    if (confirm('Bạn có chắc muốn xóa không? Hành động này không thể hoàn tác!')) {
        if (currentAdminTab === 'products') {
            DB.products = DB.products.filter(p => p.id !== id);
        } else if (currentAdminTab === 'employees' || currentAdminTab === 'customers') {
            DB.users = DB.users.filter(u => u.id !== id);
        }
        renderAdminTable();
        showMessage('Xóa thành công!');
    }
}

function saveData() {
    if (currentAdminTab === 'products') {
        const name = document.getElementById('prod-name')?.value;
        const price = parseInt(document.getElementById('prod-price')?.value);
        const stock = parseInt(document.getElementById('prod-stock')?.value) || 0;
        const category = document.getElementById('prod-category')?.value || 'Khác';
        const description = document.getElementById('prod-desc')?.value || '';
        const hot = document.getElementById('prod-hot')?.value === 'true';
        
        if (!name || !price) {
            showMessage('Vui lòng nhập đầy đủ thông tin!', true);
            return;
        }
        
        if (editId) {
            const index = DB.products.findIndex(p => p.id === editId);
            if (index !== -1) {
                DB.products[index] = { ...DB.products[index], name, price, stock, category, description, hot };
            }
        } else {
            const newProduct = {
                id: DB.currentId.products++,
                name, price, stock, category, description, hot,
                image: '👗', sizes: ['S', 'M', 'L'], colors: ['Trắng', 'Đen']
            };
            DB.products.push(newProduct);
        }
    } else if (currentAdminTab === 'employees') {
        const name = document.getElementById('emp-name')?.value;
        const email = document.getElementById('emp-email')?.value;
        const phone = document.getElementById('emp-phone')?.value;
        const role = document.getElementById('emp-role')?.value;
        const password = document.getElementById('emp-password')?.value;
        
        if (!name || !email) {
            showMessage('Vui lòng nhập đầy đủ thông tin!', true);
            return;
        }
        
        if (editId) {
            const index = DB.users.findIndex(u => u.id === editId);
            if (index !== -1) {
                DB.users[index] = { ...DB.users[index], name, email, phone, role };
                if (password) DB.users[index].password = password;
            }
        } else {
            const newUser = {
                id: DB.currentId.users++,
                name, email, phone, role: role || 'employee',
                password: password || '123456',
                avatar: '👩‍💼'
            };
            DB.users.push(newUser);
        }
    } else if (currentAdminTab === 'customers') {
        const name = document.getElementById('cus-name')?.value;
        const email = document.getElementById('cus-email')?.value;
        const phone = document.getElementById('cus-phone')?.value;
        
        if (!name || !email) {
            showMessage('Vui lòng nhập đầy đủ thông tin!', true);
            return;
        }
        
        if (editId) {
            const index = DB.users.findIndex(u => u.id === editId);
            if (index !== -1 && DB.users[index].role === 'user') {
                DB.users[index] = { ...DB.users[index], name, email, phone };
            }
        }
    }
    
    clearForm();
    renderAdminTable();
    showMessage(editId ? 'Cập nhật thành công!' : 'Thêm mới thành công!');
}

function clearForm() {
    editId = null;
    renderAdminForm();
    const inputs = document.querySelectorAll('#form-inputs input, #form-inputs textarea, #form-inputs select');
    inputs.forEach(input => input.value = '');
}

function viewOrderDetail(orderId) {
    const order = DB.orders.find(o => o.id === orderId);
    if (!order) return;
    
    let itemsHtml = order.items.map(item => `
        <div class="flex justify-between items-center py-2 border-b">
            <span>${item.name} x ${item.quantity}</span>
            <span class="font-semibold">${formatPrice(item.price * item.quantity)}</span>
        </div>
    `).join('');
    
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content p-6 max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Chi tiết đơn hàng #${order.id}</h2>
                <button onclick="this.closest('.modal').remove()" class="text-gray-500 text-2xl">&times;</button>
            </div>
            <div class="mb-4">
                <p><strong>Khách hàng:</strong> ${order.userName}</p>
                <p><strong>Ngày đặt:</strong> ${new Date(order.date).toLocaleString('vi-VN')}</p>
                <p><strong>Trạng thái:</strong> ${order.status === 'pending' ? 'Chờ xử lý' : 'Đã giao'}</p>
            </div>
            <div class="mb-4">
                <h3 class="font-semibold mb-2">Sản phẩm:</h3>
                ${itemsHtml}
            </div>
            <div class="border-t pt-4">
                <div class="flex justify-between font-bold text-lg">
                    <span>Tổng cộng:</span>
                    <span class="text-[#C3AED6]">${formatPrice(order.total)}</span>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
}