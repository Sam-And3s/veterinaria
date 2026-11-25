document.addEventListener('DOMContentLoaded', async function () {
    const productsGrid = document.querySelector('.products-grid');
    const filterBtnsContainer = document.querySelector('.filters-buttons');
    const pageNumbers = document.querySelector('.page-numbers');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const cartFloat = document.querySelector('.cart-float');
    const cartPopup = document.querySelector('.cart-popup');
    const checkoutButton = document.querySelector('.checkout-button'); // Botón Finalizar Compra
    const cartItems = [];
    const ITEMS_PER_PAGE = 8;
    let currentPage = 1;
    let currentCategory = 'all';

    // Agregar botón minimizar al header del carrito
    const cartHeader = cartPopup ? cartPopup.querySelector('.cart-popup-header') : null;
    if (cartHeader) {
        const minimizeButton = document.createElement('button');
        minimizeButton.className = 'minimize-cart';
        minimizeButton.textContent = '-';
        minimizeButton.addEventListener('click', () => {
            cartPopup.classList.remove('active');
        });
        cartHeader.appendChild(minimizeButton);
    }

    // Toggle del carrito
    if (cartFloat && cartPopup) {
        cartFloat.addEventListener('click', () => {
            cartPopup.classList.add('active');
        });
    }

    // Función para obtener productos desde la API
    async function fetchProducts(category = 'all') {
        const response = await fetch(`/veterinaria/api.php?action=getProducts&category=${category}`);
        return await response.json();
    }

    // Función para obtener categorías desde la API
    async function fetchCategories() {
        const response = await fetch('/veterinaria/api.php?action=getCategories');
        return await response.json();
    }

    // Función para actualizar el carrito visualmente
    function updateCart() {
        const cartItemsContainer = document.querySelector('.cart-items');
        const cartCount = document.querySelector('.cart-count-bubble');
        const cartItemsCount = document.querySelector('.cart-items-count');
        const totalAmount = document.querySelector('.subtotal-amount');
        let total = 0;
        let itemCount = 0;

        if (!cartItemsContainer || !cartCount || !cartItemsCount || !totalAmount) {
            console.error('Elementos del carrito no encontrados en el DOM.');
            return;
        }

        cartItemsContainer.innerHTML = '';

        cartItems.forEach((item, index) => {
            total += item.price * item.quantity;
            itemCount += item.quantity;

            const itemElement = document.createElement('div');
            itemElement.className = 'cart-item';
            itemElement.innerHTML = `
                <div class="cart-item-info">
                    <span class="cart-item-name">${item.name}</span>
                    <span class="cart-item-price">$${item.price.toLocaleString()}</span>
                    <div class="quantity-controls">
                        <button onclick="updateQuantity(${index}, -1)">−</button>
                        <span>${item.quantity}</span>
                        <button onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                </div>
                <button onclick="removeFromCart(${index})" class="remove-item">×</button>
            `;

            cartItemsContainer.appendChild(itemElement);
        });

        cartCount.textContent = itemCount;
        cartItemsCount.textContent = `${itemCount} items`;
        totalAmount.textContent = `$${total.toLocaleString()}`;
    }

    // Función para agregar al carrito (debe ser accesible globalmente)
    window.addToCart = function (productId, name, price, image) {
        const numericPrice = parseFloat(price);
        const existingItem = cartItems.find(item => item.productId === productId);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cartItems.push({
                productId: productId,
                name,
                price: numericPrice,
                image,
                quantity: 1
            });
        }

        updateCart();
        if (cartPopup) {
            cartPopup.classList.add('active');
        }
    };

    // Función para mostrar productos en la cuadrícula
    async function displayProducts(page = 1, category = 'all') {
        const products = await fetchProducts(category);
        const startIndex = (page - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;

        const paginatedProducts = products.slice(startIndex, endIndex);

        productsGrid.innerHTML = '';

        paginatedProducts.forEach(product => {
            const productCard = `
                <div class="product-card" data-aos="fade-up">
                    <div class="product-image">
                        <img src="../${product.img_product}" alt="${product.nom_product}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.nom_product}</h3>
                        <p class="product-price">$${parseFloat(product.prec_product).toLocaleString()}</p>
                        <button class="add-to-cart" onclick="addToCart('${product.id_product}', '${product.nom_product}', '${parseFloat(product.prec_product).toFixed(2)}', '../${product.img_product}')">Agregar al carrito</button>
                    </div>
                </div>
            `;
            productsGrid.innerHTML += productCard;
        });

        updatePagination(products.length);
    }

    // Función para actualizar la paginación
    function updatePagination(totalItems) {
        const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
        pageNumbers.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement('button');
            button.className = `page-num ${i === currentPage ? 'active' : ''}`;
            button.textContent = i;
            button.addEventListener('click', () => {
                currentPage = i;
                displayProducts(currentPage, currentCategory);
            });
            pageNumbers.appendChild(button);
        }
    }

    // Función para inicializar los botones de filtro
    async function initializeFilters() {
        const categories = await fetchCategories();

        filterBtnsContainer.innerHTML = `
            <button class="filter-btn active" data-category="all">Todos</button>
        `;

        categories.forEach(category => {
            const button = document.createElement('button');
            button.className = 'filter-btn';
            button.textContent = category.nom_cat;
            button.dataset.category = category.nom_cat;

            button.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                currentCategory = category.nom_cat;
                currentPage = 1;
                displayProducts(currentPage, currentCategory);
            });

            filterBtnsContainer.appendChild(button);
        });
    }

    // Función para actualizar la cantidad de un item en el carrito (debe ser accesible globalmente)
    window.updateQuantity = function (index, change) {
        const newQuantity = cartItems[index].quantity + change;
        if (newQuantity > 0) {
            cartItems[index].quantity = newQuantity;
        } else {
            cartItems.splice(index, 1);
        }
        updateCart();
    };

    // Función para eliminar un item del carrito (debe ser accesible globalmente)
    window.removeFromCart = function (index) {
        cartItems.splice(index, 1);
        updateCart();
    };

    // Función para calcular el total del carrito
    function getTotalCartAmount() {
        let total = 0;
        cartItems.forEach(item => {
            total += item.price * item.quantity;
        });
        return total;
    }

    // Event listener para el botón "Finalizar Compra"
    if (checkoutButton) {
        checkoutButton.addEventListener('click', async () => {
            if (cartItems.length > 0) {
                const response = await fetch('/veterinaria/procesar_login.php');
                const sessionData = await response.json();

                console.log('Datos de sesión:', sessionData); // Debug

                if (sessionData.success) {
                    const cliente = sessionData.cliente;

                    let pedidoData = {
                        userId: cliente.id,
                        items: cartItems.map(item => ({
                            productId: item.productId,
                            quantity: item.quantity,
                            subtotal: item.price * item.quantity,
                        })),
                        total: getTotalCartAmount(),
                    };

                    console.log('Datos enviados al servidor:', pedidoData); // Debug

                    const pedidoResponse = await fetch('/veterinaria/procesar_pedido.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            cliente: cliente,
                            productos: cartItems.map(item => ({
                                productId: item.productId,
                                quantity: item.quantity,
                                subtotal: item.price * item.quantity, // Calcular subtotal aquí
                            })),
                            total: getTotalCartAmount(),
                        }),
                    });

                    pedidoData = await pedidoResponse.json();

                    if (pedidoData.success) {
                        // Generar el mensaje de WhatsApp
                        const clientName = cliente.nombre;
                        const clientAddress = cliente.direccion;
                        const clientPhone = cliente.telefono;

                        let whatsappMessage = "¡Hola, equipo de Puppyshop!\n\n";
                        whatsappMessage += `Mi nombre es ${clientName}, acabo de realizar un pedido a través de su tienda en línea y me encantaría confirmar mi compra.\n\n`;
                        whatsappMessage += "Productos:\n";
                        cartItems.forEach(item => {
                            whatsappMessage += `- ${item.name} x ${item.quantity} - $${(item.price * item.quantity).toLocaleString()}\n`;
                        });
                        whatsappMessage += `\nTotal: $${getTotalCartAmount().toLocaleString()}\n\n`;
                        whatsappMessage += `Dirección de entrega: ${clientAddress}\n`;
                        whatsappMessage += `Teléfono de contacto: ${clientPhone}\n`;
                        whatsappMessage += "Estoy atento(a) a su confirmación para continuar con el proceso de pago y entrega.\n¡Gracias por su atención y por cuidar de nuestras mascotas con tanto amor!";

                        // Redirigir al chat de WhatsApp
                        const whatsappUrl = `https://wa.me/3015902278?text=${encodeURIComponent(whatsappMessage)}`;
                        window.open(whatsappUrl, '_blank'); // Abrir en una nueva pestaña

                        // Vaciar el carrito después de finalizar la compra
                        cartItems.length = 0;
                        updateCart();
                        alert('Pedido realizado. Te contactaremos por WhatsApp.');
                    } else {
                        alert('No se pudo procesar el pedido.');
                    }
                } else {
                    alert('No se pudo obtener los datos del cliente.');
                }
            } else {
                alert('El carrito está vacío. Agrega productos antes de finalizar la compra.');
            }
        });
    }

    // Inicializar la aplicación
    await initializeFilters();
    await displayProducts(1, 'all');
});
