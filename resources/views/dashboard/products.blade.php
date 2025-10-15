@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="products-page">
        <div class="page-header">
            <h2>Products Management</h2>
            @auth
                @if (Auth::user()->isAdmin())
                    <button class="btn-primary" onclick="openModal('addProductModal')">➕ Add Product</button>
                @endif
            @endauth
        </div>

        <div class="products-grid">
            @foreach ($products as $product)
                <div class="product-card">
                    <div class="product-image">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div class="no-image">🖼️ No Image</div>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        <div class="product-details">
                            <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="product-qty">Stock: {{ $product->qty }}</span>
                        </div>
                    </div>
                    <div class="product-actions">
                        @if ($product->qty > 0)
                            <div class="quantity-controls">
                                <button class="qty-btn"
                                    onclick="updateQuantity({{ $product->id }}, 'decrement')">-</button>
                                <span class="qty-display" id="qty-{{ $product->id }}">0</span>
                                <button class="qty-btn"
                                    onclick="updateQuantity({{ $product->id }}, 'increment')">+</button>
                            </div>
                            <button class="btn-secondary add-to-cart" data-product-id="{{ $product->id }}"
                                onclick="addToCart({{ $product->id }})">
                                🛒 Add to Cart
                            </button>
                        @else
                            <button class="btn-disabled" disabled>Out of Stock</button>
                        @endif

                        @auth
                            @if (Auth::user()->isAdmin())
                                <button class="btn-edit" onclick="openEditModal({{ $product }})">✏️ Edit</button>
                                <button class="btn-delete" onclick="deleteProduct({{ $product->id }})">🗑️ Delete</button>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modals tetap sama seperti sebelumnya -->
    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Product</h3>
                <span class="close" onclick="closeModal('addProductModal')">&times;</span>
            </div>
            <form action="{{ route('products.index') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small>Format: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                    </div>
                    <div class="form-group">
                        <label for="qty">Quantity</label>
                        <input type="number" id="qty" name="qty" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" step="0.01" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('addProductModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Product</h3>
                <span class="close" onclick="closeModal('editProductModal')">&times;</span>
            </div>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Product Name</label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_image">Product Image</label>
                        <input type="file" id="edit_image" name="image" accept="image/*">
                        <small>Format: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                        <div id="currentImage" class="current-image"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_qty">Quantity</label>
                        <input type="number" id="edit_qty" name="qty" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="edit_price">Price</label>
                        <input type="number" id="edit_price" name="price" step="0.01" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('editProductModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Quantity management
        let quantities = {};

        function updateQuantity(productId, action) {
            if (!quantities[productId]) {
                quantities[productId] = 0;
            }

            if (action === 'increment') {
                quantities[productId]++;
            } else if (action === 'decrement' && quantities[productId] > 0) {
                quantities[productId]--;
            }

            document.getElementById(`qty-${productId}`).textContent = quantities[productId];
        }

        function addToCart(productId) {
            const quantity = quantities[productId] || 1;

            if (quantity < 1) {
                alert('Please set quantity first!');
                return;
            }

            for (let i = 0; i < quantity; i++) {
                fetch(`/cart/add/${productId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateCartCount(data.cart_count);
                            if (i === quantity - 1) {
                                alert(`Added ${quantity} item(s) to cart!`);
                                quantities[productId] = 0;
                                document.getElementById(`qty-${productId}`).textContent = '0';
                            }
                        } else {
                            alert(data.message);
                        }
                    });
            }
        }

        function updateCartCount(count) {
            document.getElementById('cartCount').textContent = count;
        }

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openEditModal(product) {
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_qty').value = product.qty;
            document.getElementById('edit_price').value = product.price;

            // Show current image if exists
            const currentImageDiv = document.getElementById('currentImage');
            if (product.image) {
                currentImageDiv.innerHTML = `
            <p>Current Image:</p>
            <img src="/storage/${product.image}" alt="${product.name}" style="max-width: 100px; max-height: 100px; margin-top: 10px;">
        `;
            } else {
                currentImageDiv.innerHTML = '<p>No image uploaded</p>';
            }

            document.getElementById('editProductForm').action = `/products/${product.id}`;
            openModal('editProductModal');
        }

        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                fetch(`/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }
    </script>
@endsection
