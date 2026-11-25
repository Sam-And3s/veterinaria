// Barra de búsqueda para filtrar productos
const searchInput = document.querySelector('.search-bar input');
if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const productRows = document.querySelectorAll('.table-body .product-row');

        productRows.forEach(row => {
            const nameCell = row.querySelector('.name-cell');
            if (!nameCell) return;

            const productName = nameCell.textContent.toLowerCase();
            const shouldShow = productName.includes(searchTerm);
            row.style.display = shouldShow ? 'flex' : 'none';
        });
    });
}

// Asegura que los modales estén ocultos al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    const editProductModal = document.getElementById('edit-product-modal');
    const newProductModal = document.getElementById('products-modal');

    if (editProductModal) {
        editProductModal.style.display = 'none'; // Oculta el modal de edición
    }
    if (newProductModal) {
        newProductModal.style.display = 'none'; // Oculta el modal de nuevo producto
    }

    // Event delegation para los botones de acción de productos
    const productsTable = document.querySelector('.products-table');
    if (productsTable) {
        productsTable.addEventListener('click', function (e) {
            const target = e.target.closest('.action-btn');
            if (!target) return;

            const productId = target.closest('.product-row').getAttribute('data-id');
            if (!productId) {
                console.error('No se encontró el ID del producto.');
                return;
            }

            if (target.classList.contains('edit')) {
                editarProducto(productId); // Abre el modal de edición
            } else if (target.classList.contains('delete')) {
                eliminarProducto(productId); // Elimina el producto
            }
        });
    }

    // Manejo del modal para nuevo producto
    const newProductBtn = document.querySelector('.new-product-btn');
    const productModal = document.getElementById('products-modal');

    if (newProductBtn && productModal) {
        newProductBtn.addEventListener('click', () => {
            productModal.style.display = 'block'; // Muestra el modal
        });

        // Cierra el modal al hacer clic en el botón de cerrar
        const closeModalBtn = productModal.querySelector('.close');
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => {
                productModal.style.display = 'none'; // Oculta el modal
            });
        }

        // Cierra el modal al hacer clic fuera del contenido del modal
        window.addEventListener('click', (e) => {
            if (e.target === productModal) {
                productModal.style.display = 'none'; // Oculta el modal
            }
        });
    }
});

// Función para abrir el modal de edición con los datos del producto
function editarProducto(productId) {
    const productModal = document.getElementById('edit-product-modal');
    const form = document.getElementById('edit-product-form');

    // Realiza una solicitud al backend para obtener los datos del producto
    fetch(`obtener_producto.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            // Rellena los campos del formulario con los datos del producto
            form.querySelector('#edit-id-product').value = data.id_product;
            form.querySelector('#edit-nom-product').value = data.nom_product;
            form.querySelector('#edit-id-cat').value = data.id_cat;
            form.querySelector('#edit-prec-product').value = data.prec_product;
            form.querySelector('#edit-stock-product').value = data.stock_product;

            // Muestra el modal
            productModal.style.display = 'block';
        })
        .catch(error => {
            console.error('Error al obtener los datos del producto:', error);
            alert('No se pudieron cargar los datos del producto.');
        });
}

// Función para guardar los cambios del producto
function guardarCambiosProducto() {
    const form = document.getElementById('edit-product-form');
    const formData = new FormData(form);

    // Envía los datos al backend para actualizar el producto
    fetch('actualizar_producto.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Producto actualizado correctamente');
                location.reload(); // Recarga la página para reflejar los cambios
            } else {
                alert('Error al actualizar el producto: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al actualizar el producto:', error);
            alert('Error al actualizar el producto.');
        });
}

// Cierra el modal al hacer clic en el botón de cancelar o fuera del modal
document.querySelectorAll('.btn-cancel, .close').forEach(button => {
    button.addEventListener('click', () => {
        const productModal = document.getElementById('edit-product-modal');
        if (productModal) {
            productModal.style.display = 'none'; // Oculta el modal
        }
    });
});

// Cierra el modal al hacer clic fuera del contenido del modal
window.addEventListener('click', (e) => {
    const productModal = document.getElementById('edit-product-modal');
    if (e.target === productModal) {
        productModal.style.display = 'none'; // Oculta el modal
    }
});

// Función para eliminar un producto
function eliminarProducto(productId) {
    if (!confirm('¿Está seguro de eliminar este producto?')) return;

    fetch('eliminar_producto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${productId}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Producto eliminado correctamente');
                location.reload();
            } else {
                throw new Error(data.message || 'Error al eliminar');
            }
        })
        .catch(error => {
            console.error('Error al eliminar el producto:', error);
            alert('Error al eliminar el producto: ' + error.message);
        });
}