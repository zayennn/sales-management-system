// Cart functionality
class CartManager {
    constructor() {
        this.cartToggle = document.getElementById('cartToggle');
        this.cartSidebar = document.getElementById('cartSidebar');
        this.cartOverlay = document.getElementById('cartOverlay');
        this.closeCart = document.getElementById('closeCart');
        this.cartItems = document.getElementById('cartItems');
        this.confirmOrder = document.getElementById('confirmOrder');
        this.cartCount = document.getElementById('cartCount');
        
        this.init();
    }
    
    init() {
        if (this.cartToggle) {
            this.cartToggle.addEventListener('click', () => this.openCart());
        }
        
        if (this.closeCart) {
            this.closeCart.addEventListener('click', () => this.closeCartSidebar());
        }
        
        if (this.cartOverlay) {
            this.cartOverlay.addEventListener('click', () => this.closeCartSidebar());
        }
        
        if (this.confirmOrder) {
            this.confirmOrder.addEventListener('click', () => this.confirmOrderAction());
        }
        
        this.loadCartCount();
    }
    
    openCart() {
        console.log('Opening cart...');
        this.cartSidebar.classList.add('active');
        this.cartOverlay.classList.add('active');
        this.loadCartItems();
    }
    
    closeCartSidebar() {
        console.log('Closing cart...');
        this.cartSidebar.classList.remove('active');
        this.cartOverlay.classList.remove('active');
    }
    
    async loadCartCount() {
        try {
            const response = await fetch('/cart/get');
            const cart = await response.json();
            if (this.cartCount) {
                this.cartCount.textContent = Object.keys(cart).length;
            }
        } catch (error) {
            console.error('Error loading cart count:', error);
        }
    }
    
    async loadCartItems() {
        try {
            const response = await fetch('/cart/get');
            const cart = await response.json();
            
            if (!this.cartItems) return;
            
            this.cartItems.innerHTML = '';
            
            if (Object.keys(cart).length === 0) {
                this.cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
                return;
            }
            
            Object.values(cart).forEach(item => {
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        <p>Rp ${this.formatPrice(item.price)} x ${item.quantity}</p>
                        <p>Total: Rp ${this.formatPrice(item.price * item.quantity)}</p>
                    </div>
                    <div class="cart-item-actions">
                        <button class="remove-item" onclick="cartManager.removeFromCart(${item.id})">Remove</button>
                    </div>
                `;
                this.cartItems.appendChild(cartItem);
            });
            
        } catch (error) {
            console.error('Error loading cart items:', error);
        }
    }
    
    async removeFromCart(productId) {
        try {
            const response = await fetch(`/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.loadCartCount();
                this.loadCartItems();
                alert('Item removed from cart!');
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
            alert('Error removing item from cart!');
        }
    }
    
    async confirmOrderAction() {
        const customerName = document.getElementById('customerName');
        
        if (!customerName) {
            alert('Customer name input not found!');
            return;
        }
        
        const customerNameValue = customerName.value.trim();
        const errorElement = document.querySelector('.customer-name-error');

        if (!customerNameValue) {
            if (errorElement) {
                errorElement.style.display = 'block';
                errorElement.textContent = 'Please enter customer name';
            }
            return;
        }
        
        if (errorElement) {
            errorElement.style.display = 'none';
        }

        try {
            const formData = new FormData();
            formData.append('customer_name', customerNameValue);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            const response = await fetch('/cart/confirm', {
                method: 'POST',
                body: formData
            });
            
            if (response.ok) {
                this.closeCartSidebar();
                this.loadCartCount();
                window.location.href = '/sales/data';
            } else {
                const result = await response.json();
                alert(result.message || 'Error confirming order');
            }
        } catch (error) {
            console.error('Error confirming order:', error);
            alert('Error confirming order. Please try again.');
        }
    }
    
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
}

let cartManager;
document.addEventListener('DOMContentLoaded', function() {
    cartManager = new CartManager();
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
});

// Global modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Global function untuk update cart count (dipanggil dari products page)
function updateCartCount(count) {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        cartCount.textContent = count;
    }
}