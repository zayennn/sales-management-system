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
        this.cartToggle.addEventListener('click', () => this.openCart());
        this.closeCart.addEventListener('click', () => this.closeCartSidebar());
        this.cartOverlay.addEventListener('click', () => this.closeCartSidebar());
        this.confirmOrder.addEventListener('click', () => this.confirmOrderAction());
        
        this.loadCartCount();
        this.loadCartItems();
    }
    
    openCart() {
        this.cartSidebar.classList.add('active');
        this.cartOverlay.classList.add('active');
        this.loadCartItems();
    }
    
    closeCartSidebar() {
        this.cartSidebar.classList.remove('active');
        this.cartOverlay.classList.remove('active');
    }
    
    async loadCartCount() {
        try {
            const response = await fetch('/cart/get');
            const cart = await response.json();
            this.cartCount.textContent = Object.keys(cart).length;
        } catch (error) {
            console.error('Error loading cart count:', error);
        }
    }
    
    async loadCartItems() {
        try {
            const response = await fetch('/cart/get');
            const cart = await response.json();
            
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
                        <button class="remove-item" data-product-id="${item.id}">Remove</button>
                    </div>
                `;
                this.cartItems.appendChild(cartItem);
            });
            
            // Add event listeners to remove buttons
            this.cartItems.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = e.target.dataset.productId;
                    this.removeFromCart(productId);
                });
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
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    }
    
    async confirmOrderAction() {
        try {
            // disable tombol / show loading kalau mau (optional)
            const response = await fetch('/cart/confirm', {
                method: 'POST',
                credentials: 'same-origin', // pastikan cookie session terkirim
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({}) // optional, tapi buat konsistensi
            });

            // 1) kalau server redirect (302) fetch.followed redirect -> response.redirected true
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }

            // 2) kalau status bukan OK, baca text dulu buat inspect (login page / error HTML)
            if (!response.ok) {
                const txt = await response.text();
                console.error('Confirm order non-OK response:', response.status, txt);
                alert('Error confirming order. Server returned status ' + response.status + '. Check console for details.');
                return;
            }

            // 3) cek content-type sebelum parse JSON
            const ct = response.headers.get('content-type') || '';
            if (ct.indexOf('application/json') === -1) {
                const txt = await response.text();
                console.error('Confirm order unexpected content-type:', ct, txt);
                alert('Error confirming order. Unexpected server response. Check console.');
                return;
            }

            // 4) normal JSON flow
            const data = await response.json();
            if (data.success) {
                // kalau controller return redirect_url, follow itu
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                    return;
                }
                alert(data.message || 'Order confirmed!');
                // optionally refresh cart count / UI
            } else {
                alert(data.message || 'Error confirming order. Please try again.');
            }
        } catch (err) {
            console.error('Confirm order fetch error:', err);
            alert('Error confirming order. Check console for details.');
        }
    }

    
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new CartManager();
    
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