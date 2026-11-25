// Elementos del DOM
const listaPedidos = document.getElementById('lista-pedidos');
const filtroPedidos = document.getElementById('filtro-estado');
const filtroFecha = document.getElementById('filtro-fecha');
const modal = document.getElementById('modal-detalles');
const btnRefresh = document.querySelector('.btn-refresh');

// Estado global
let pedidosActuales = [];

// Cargar pedidos al iniciar
document.addEventListener('DOMContentLoaded', () => {
  cargarPedidos();
  inicializarEventos();
});

// Inicializar eventos
function inicializarEventos() {
    if (filtroPedidos) {
        filtroPedidos.addEventListener('change', filtrarPedidos); // Evento para el filtro de estado
    }
    if (filtroFecha) {
        filtroFecha.addEventListener('change', filtrarPedidos); // Evento para el filtro de fecha
    }
    if (btnRefresh) {
        btnRefresh.addEventListener('click', cargarPedidos); // Evento para el botón de recargar
    }
}

// Cargar pedidos desde la base de datos
async function cargarPedidos() {
    try {
        const response = await fetch('/veterinaria/obtener_pedidos.php');
        if (!response.ok) {
            throw new Error(`Error al cargar pedidos: ${response.status} ${response.statusText}`);
        }

        const contentType = response.headers.get('Content-Type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta no es JSON válida');
        }

        const data = await response.json();
        console.log('Pedidos cargados:', data); // Debug
        pedidosActuales = data;
        actualizarEstadisticas();
        mostrarPedidos(data);
    } catch (error) {
        console.error('Error en cargarPedidos:', error);
        mostrarNotificacion(error.message, 'error');
    }
}

// Mostrar pedidos en la tabla
function mostrarPedidos(pedidos) {
    // Limpiar la tabla antes de agregar nuevos pedidos
    listaPedidos.innerHTML = '';

    if (pedidos.length === 0) {
        listaPedidos.innerHTML = '<tr><td colspan="6">No se encontraron pedidos.</td></tr>';
        return;
    }

    pedidos.forEach(pedido => {
        const row = document.createElement('tr');
        row.classList.add('animate__animated', 'animate__fadeIn');
        row.innerHTML = `
            <td>#${pedido.id_pedido}</td>
            <td>${formatearFecha(pedido.fecha_pedido)}</td>
            <td>${pedido.nombre_cliente}</td>
            <td>$${pedido.total}</td>
            <td><span class="estado-${pedido.estado}">${pedido.estado}</span></td>
            <td>
                ${
                    pedido.estado.toLowerCase() === 'pendiente' // Usar texto literal para el estado
                        ? `
                            <button class="action-btn confirm" data-id-pedido="${pedido.id_pedido}">
                                <i class="fas fa-check"></i> Confirmar
                            </button>
                            <button class="action-btn cancel" data-id-pedido="${pedido.id_pedido}">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        `
                        : ''
                }
                <button class="action-btn whatsapp" data-numero="${pedido.telefono_cliente}">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </button>
            </td>
        `;
        listaPedidos.appendChild(row);

        // Adjuntar event listeners a los botones recién creados
        if (pedido.estado.toLowerCase() === 'pendiente') { // Usar texto literal para el estado
            const confirmarBtn = row.querySelector('.action-btn.confirm');
            const cancelarBtn = row.querySelector('.action-btn.cancel');

            confirmarBtn.addEventListener('click', function () {
                const idPedido = this.getAttribute('data-id-pedido');
                actualizarEstado(idPedido, 'confirmado');
            });

            cancelarBtn.addEventListener('click', function () {
                const idPedido = this.getAttribute('data-id-pedido');
                actualizarEstado(idPedido, 'cancelado');
            });
        }

        const whatsappBtn = row.querySelector('.action-btn.whatsapp');
        whatsappBtn.addEventListener('click', function () {
            const numero = this.getAttribute('data-numero');
            window.open(`https://wa.me/${numero}`, '_blank');
        });
    });
}

// Ver detalles del pedido
async function verDetalles(idPedido) {
  try {
    const response = await fetch(`/veterinaria/obtener_detalle_pedido.php?id=${idPedido}`);
    if (!response.ok) {
      throw new Error(`Error al obtener detalles del pedido: ${response.status} ${response.statusText}`);
    }
    const detalle = await response.json();

    // Mostrar el modal antes de acceder a los elementos
    modal.style.display = 'block';
    modal.querySelector('.modal-content').classList.add('animate__animated', 'animate__fadeInDown');

    const pedidoIdElement = document.getElementById('pedido-id');
    const clienteNombreElement = document.getElementById('cliente-nombre');
    const clienteTelefonoElement = document.getElementById('cliente-telefono');
    const detalleProductosElement = document.getElementById('detalle-productos');

    if (pedidoIdElement && clienteNombreElement && clienteTelefonoElement && detalleProductosElement) {
      pedidoIdElement.textContent = idPedido;
      clienteNombreElement.textContent = detalle.cliente.nombre;
      clienteTelefonoElement.textContent = detalle.cliente.telefono;

      const productosHTML = detalle.productos.map(prod => `
                <tr>
                    <td>${prod.nombre}</td>
                    <td>${prod.cantidad}</td>
                    <td>$${prod.precio}</td>
                    <td>$${prod.subtotal}</td>
                </tr>
            `).join('');

      detalleProductosElement.innerHTML = productosHTML;
    } else {
      console.error("No se pudieron encontrar todos los elementos del modal");
      mostrarNotificacion('No se pudieron mostrar los detalles del pedido', 'error');
      return; // Importante: salir de la función si no se encuentran los elementos
    }


  } catch (error) {
    mostrarNotificacion(error.message, 'error');
  }
}

// Actualizar estado del pedido
async function actualizarEstado(idPedido, nuevoEstado) {
    console.log("actualizarEstado llamado con:", idPedido, nuevoEstado); // Debug
    try {
        const response = await fetch('/veterinaria/actualizar_estado_pedido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_pedido=${idPedido}&estado=${nuevoEstado}`,
        });

        if (!response.ok) {
            throw new Error(`Error al actualizar el estado del pedido: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        console.log("Respuesta del servidor:", data); // Debug

        if (data.success) {
            mostrarNotificacion(`Pedido ${nuevoEstado} exitosamente`, 'success');
            if (nuevoEstado === 'confirmado') {
                // Reducir el stock de los productos relacionados con la venta
                await reducirStock(idPedido);
            }
            cargarPedidos(); // Recargar la lista de pedidos
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    } catch (error) {
        mostrarNotificacion(error.message, 'error');
    }
}

// Función para reducir el stock de los productos relacionados con la venta
async function reducirStock(idPedido) {
    try {
        const response = await fetch(`/veterinaria/reducir_stock.php?id_pedido=${idPedido}`);
        if (!response.ok) {
            throw new Error(`Error al reducir el stock: ${response.status} ${response.statusText}`);
        }
        const data = await response.json();
        console.log("Stock reducido:", data);
    } catch (error) {
        console.error("Error al reducir el stock:", error);
    }
}

// Funciones auxiliares
function formatearFecha(fecha) {
  return new Date(fecha).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

function mostrarNotificacion(mensaje, tipo) {
  // Implementar sistema de notificaciones (puedes usar una librería como Toastify)
  console.log(`${tipo}: ${mensaje}`);
  alert(`${tipo}: ${mensaje}`); // Para que se vea la notificación
}

function filtrarPedidos() {
    const estadoFiltro = filtroPedidos.value; // Obtener el valor seleccionado en el filtro de estado
    const fechaFiltro = filtroFecha.value; // Obtener el valor del filtro de fecha

    console.log('Estado seleccionado:', estadoFiltro); // Debug
    console.log('Fecha seleccionada:', fechaFiltro); // Debug

    // Filtrar los pedidos según el estado y la fecha
    const pedidosFiltrados = pedidosActuales.filter(pedido => {
        const estadoCoincide = estadoFiltro === 'todos' || pedido.estado.toLowerCase() === estadoFiltro.toLowerCase();

        // Convertir la fecha del pedido al formato YYYY-MM-DD para compararla
        const fechaPedido = new Date(pedido.fecha_pedido).toISOString().split('T')[0];
        const fechaCoincide = !fechaFiltro || fechaPedido === fechaFiltro;

        return estadoCoincide && fechaCoincide;
    });

    console.log('Pedidos filtrados:', pedidosFiltrados); // Debug

    // Mostrar los pedidos filtrados en la tabla
    mostrarPedidos(pedidosFiltrados);
}

function actualizarEstadisticas() {
    const ventasHoy = pedidosActuales.filter(p =>
        new Date(p.fecha_pedido).toDateString() === new Date().toDateString() &&
        p.estado === 'confirmado'
    ).length;

    const pendientes = pedidosActuales.filter(p => p.estado === 'pendiente').length;
    const completadas = pedidosActuales.filter(p => p.estado === 'confirmado').length;

    document.getElementById('ventas-dia').textContent = ventasHoy;
    document.getElementById('pedidos-pendientes').textContent = pendientes;
    document.getElementById('ventas-completadas').textContent = completadas;
}

// Cerrar modal
if (modal) {
  document.querySelector('.btn-cerrar').addEventListener('click', () => {
    modal.querySelector('.modal-content').classList.add('animate__animated', 'animate__fadeOutUp');
    setTimeout(() => {
      modal.style.display = 'none';
      modal.querySelector('.modal-content').classList.remove('animate__animated', 'animate__fadeOutUp');
    }, 500);
  });
}

document.querySelectorAll('.action-btn.confirm').forEach(button => {
    button.addEventListener('click', function () {
        const idPedido = this.dataset.idPedido;
        console.log('Confirmando pedido con ID:', idPedido);

        const confirmAction = confirm('¿Estás seguro de que deseas confirmar este pedido?');
        if (confirmAction) {
            confirmOrder(idPedido);
        }
    });
});

function confirmOrder(idPedido) {
    console.log('Enviando solicitud para confirmar pedido con ID:', idPedido);

    fetch('/veterinaria/citas_api.php?action=confirmOrder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_pedido: idPedido }),
    })
        .then(response => {
            console.log('Estado de la respuesta:', response.status);
            if (!response.ok) {
                throw new Error(`Error en la respuesta del servidor: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta del servidor:', data);
            if (data.success) {
                alert('Pedido confirmado correctamente y stock actualizado.');
                // Actualizar la interfaz si es necesario
              cargarPedidos(); // Recargar pedidos para actualizar la lista
            } else {
                alert('Error al confirmar el pedido: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al confirmar el pedido:', error);
            alert('Error al confirmar el pedido.');
        });
}

