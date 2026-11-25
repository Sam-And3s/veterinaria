document.addEventListener('DOMContentLoaded', async function () {
    const productsGrid = document.querySelector('.products-grid');
    const filterBtnsContainer = document.querySelector('.filters-buttons');
    const pageNumbers = document.querySelector('.page-numbers');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    const ITEMS_PER_PAGE = 8;
    let currentPage = 1;
    let currentCategory = 'all';

    // Función para obtener productos desde la API
    async function fetchProducts(category = 'all') {
        const response = await fetch(`api.php?action=getProducts&category=${category}`);
        return await response.json();
    }

    // Función para obtener categorías desde la API
    async function fetchCategories() {
        const response = await fetch('api.php?action=getCategories');
        return await response.json();
    }

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
                        <img src="${product.img_product}" alt="${product.nom_product}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.nom_product}</h3>
                        <p class="product-price">$${parseFloat(product.prec_product).toLocaleString()}</p>
                        <a href="sesion.php"><button class="add-to-cart">Agregar al carrito</button></a>
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

    // Inicializar la aplicación
    await initializeFilters();
    await displayProducts(1, 'all');

    // Event listeners para los botones de paginación
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            displayProducts(currentPage, currentCategory);
        }
    });

    nextBtn.addEventListener('click', async () => {
        const products = await fetchProducts(currentCategory);
        const totalPages = Math.ceil(products.length / ITEMS_PER_PAGE);

        if (currentPage < totalPages) {
            currentPage++;
            displayProducts(currentPage, currentCategory);
        }
    });

    // Inicialización de AOS
    AOS.init({
        duration: 800,
        offset: 100,
        once: true
    });
});