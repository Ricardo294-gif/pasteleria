// Función para cargar los productos desde la API
async function cargarProductos() {
    try {
        const response = await fetch('/api/productos');
        const data = await response.json();
        return data.categorias;
    } catch (error) {
        console.error('Error al cargar los productos:', error);
        return null;
    }
}

// Función para inicializar las pestañas
function inicializarTabs() {
    const tabContent = document.querySelector('.tab-content');
    const navTabs = document.querySelector('.nav-tabs');

    // Verificar si los elementos existen
    if (!tabContent || !navTabs) {
        console.log('No se encontraron los elementos tab-content o nav-tabs');
        return;
    }

    // Limpiar contenido existente
    tabContent.innerHTML = '';
    navTabs.innerHTML = '';
}

// Función para mostrar los productos en una categoría
function mostrarProductos(categoria, productos, esTodos = false) {
    const tabContent = document.querySelector('.tab-content');
    if (!tabContent) {
        console.log('No se encontró el elemento tab-content');
        return;
    }

    const tabPane = document.createElement('div');
    tabPane.className = 'tab-pane fade';
    tabPane.id = `menu-${categoria.toLowerCase()}`;
    if (esTodos) {
        tabPane.classList.add('show', 'active');
    }

    const header = document.createElement('div');
    header.className = 'tab-header text-center';
    header.innerHTML = `
        <h3>${categoria}</h3>
    `;

    const row = document.createElement('div');
    row.className = 'row gy-5';

    productos.forEach(producto => {
        const menuItem = document.createElement('div');
        menuItem.className = 'col-lg-4 menu-item';

        menuItem.innerHTML = `
            <a href="/producto/${producto.id}" class="product-link">
                <img src="/img/productos/${producto.imagen}" class="menu-img img-fluid" alt="${producto.nombre}">
            </a>
            <h4>${producto.nombre}</h4>
            <p class="ingredients">${producto.descripcion}</p>
            <div class="card-footer">
                <div class="card-price price">€${producto.precio.toFixed(2)}</div>
            </div>
        `;
        row.appendChild(menuItem);
    });

    tabPane.appendChild(header);
    tabPane.appendChild(row);
    tabContent.appendChild(tabPane);
    
    // Asegurarse de que las secciones posteriores sean visibles
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.style.overflow = 'visible';
        pane.style.height = 'auto';
    });
    
    // Inicializar eventos para las pestañas
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Forzar que el contenedor sea visible después del cambio de pestaña
            setTimeout(() => {
                const activePane = document.querySelector('.tab-pane.active');
                if (activePane) {
                    activePane.style.overflow = 'visible';
                    activePane.style.height = 'auto';
                    
                    // Asegurar que las secciones siguientes sean visibles
                    const menuSection = document.getElementById('menu');
                    if (menuSection) {
                        const nextSections = [];
                        let nextElement = menuSection.nextElementSibling;
                        
                        while (nextElement) {
                            nextSections.push(nextElement);
                            nextElement = nextElement.nextElementSibling;
                        }
                        
                        nextSections.forEach(section => {
                            section.style.display = 'block';
                        });
                    }
                }
            }, 100);
        });
    });
}

// Función para agregar productos al carrito
function agregarAlCarrito(id, nombre, precio) {
    // Mostrar la alerta
    const alertCard = document.getElementById('alert-card');
    const productName = alertCard.querySelector('.product-name');
    const productPrice = alertCard.querySelector('.product-price');

    productName.textContent = nombre;
    productPrice.textContent = `€${precio.toFixed(2)}`;
    alertCard.style.display = 'block';

    // Ocultar la alerta después de 3 segundos
    setTimeout(() => {
        alertCard.style.display = 'none';
    }, 3000);
}

// Función para obtener todos los productos
function obtenerTodosLosProductos(categorias) {
    let todosLosProductos = [];
    Object.values(categorias).forEach(categoria => {
        todosLosProductos = todosLosProductos.concat(categoria.productos);
    });
    return todosLosProductos;
}

// Función para inicializar el menú
async function inicializarMenu() {
    // Verificar si estamos en la página correcta que contiene estos elementos
    const navTabs = document.querySelector('.nav-tabs');
    const tabContent = document.querySelector('.tab-content');

    if (!navTabs || !tabContent) {
        console.log('No estamos en la página de categorías de productos');
        return; // Salir de la función si no estamos en la página correcta
    }

    const categorias = await cargarProductos();
    if (!categorias) return;

    inicializarTabs();

    // Crear la pestaña "Todos" primero
    const navItemTodos = document.createElement('li');
    navItemTodos.className = 'nav-item';
    navItemTodos.innerHTML = `
        <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#menu-todos">
            <h4>Todos</h4>
        </a>
    `;
    navTabs.appendChild(navItemTodos);

    // Mostrar todos los productos
    const todosLosProductos = obtenerTodosLosProductos(categorias);
    mostrarProductos('Todos', todosLosProductos, true);

    // Crear las pestañas de categorías
    Object.keys(categorias).forEach((categoria, index) => {
        const navItem = document.createElement('li');
        navItem.className = 'nav-item';
        navItem.innerHTML = `
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-${categoria.toLowerCase()}">
                <h4>${categoria}</h4>
            </a>
        `;
        navTabs.appendChild(navItem);

        // Mostrar los productos de la categoría
        mostrarProductos(categoria, categorias[categoria].productos);
    });

    // Inicializar el botón de ir al carrito
    const viewCartBtn = document.getElementById('viewCartBtn');
    if (viewCartBtn) {
        viewCartBtn.addEventListener('click', function() {
            const route = this.getAttribute('data-route');
            if (route) {
                window.location.href = route;
            }
        });
    }
    
    // Añadir manejadores de eventos para todas las pestañas
    document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function() {
            // Esperar a que Bootstrap haga la transición de pestañas
            setTimeout(function() {
                // Reinicializar AOS para las secciones después del menú
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
                
                // Asegurar que todas las secciones posteriores al menú sean visibles
                const menuSection = document.getElementById('menu');
                if (menuSection) {
                    let nextSection = menuSection.nextElementSibling;
                    while (nextSection) {
                        // Remover cualquier estilo que pueda ocultar la sección
                        nextSection.style.display = '';
                        nextSection.style.visibility = '';
                        nextSection.style.opacity = '';
                        
                        // Forzar que la sección sea visible
                        nextSection.classList.remove('aos-animate');
                        nextSection.classList.add('aos-animate');
                        
                        // Continuar con la siguiente sección
                        nextSection = nextSection.nextElementSibling;
                    }
                }
                
                // Desplazarse un poco para activar las animaciones
                window.scrollBy(0, 1);
                window.scrollBy(0, -1);
            }, 300);
        });
    });
}

// Inicializar el menú cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en la página de productos (categorías)
    // y no en la página de un producto individual
    const isProductsPage = document.querySelector('.tab-content') &&
                          document.querySelector('.nav-tabs') &&
                          !document.querySelector('.single-product-details');

    if (isProductsPage) {
        console.log('Inicializando sistema de categorías de productos');
        inicializarMenu();
        
        // Asegurar que las secciones posteriores sean visibles después de cargar la página
        window.addEventListener('load', function() {
            setTimeout(() => {
                // Asegurar que todas las tab-pane sean visibles
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.style.overflow = 'visible';
                    pane.style.height = 'auto';
                });
                
                // Asegurar que el tab-content sea visible
                const tabContent = document.querySelector('.tab-content');
                if (tabContent) {
                    tabContent.style.overflow = 'visible';
                    tabContent.style.height = 'auto';
                    tabContent.style.position = 'relative';
                }
                
                // Asegurar que las secciones posteriores al menú estén visibles
                const menuSection = document.getElementById('menu');
                if (menuSection) {
                    let nextSection = menuSection.nextElementSibling;
                    while (nextSection) {
                        nextSection.style.display = 'block';
                        nextSection.style.visibility = 'visible';
                        nextSection.style.opacity = '1';
                        nextSection = nextSection.nextElementSibling;
                    }
                }
            }, 500);
        });
    } else {
        console.log('No estamos en la página de categorías de productos, saltando inicialización');
    }
});

// Inicializar el menú cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en la página de productos (categorías)
    // y no en la página de un producto individual
    const isProductsPage = document.querySelector('.tab-content') &&
                          document.querySelector('.nav-tabs') &&
                          !document.querySelector('.single-product-details');

    if (isProductsPage) {
        console.log('Inicializando sistema de categorías de productos');
        inicializarMenu();
        
        // Sobrescribir el comportamiento por defecto de las pestañas de Bootstrap
        const tabLinks = document.querySelectorAll('.nav-tabs .nav-link');
        tabLinks.forEach(tabLink => {
            tabLink.addEventListener('shown.bs.tab', function(event) {
                console.log('Cambio de pestaña detectado:', event.target.getAttribute('data-bs-target'));
                
                // Forzar visibilidad de todas las secciones después del menú
                const menuSection = document.getElementById('menu');
                if (menuSection) {
                    // Remover cualquier estilo que pueda causar problemas en el tab-content
                    const tabContent = document.querySelector('.tab-content');
                    if (tabContent) {
                        tabContent.style.cssText = 'overflow: visible !important; height: auto !important; position: relative !important;';
                    }
                    
                    // Hacer visibles todas las secciones posteriores
                    let nextSection = menuSection.nextElementSibling;
                    while (nextSection) {
                        // Eliminar cualquier estilo inline que pueda ocultar la sección
                        nextSection.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
                        
                        // Forzar que los elementos con animación sean visibles
                        nextSection.querySelectorAll('[data-aos]').forEach(el => {
                            el.classList.add('aos-animate');
                        });
                        
                        nextSection = nextSection.nextElementSibling;
                    }
                    
                    // Reinicializar AOS para detectar nuevos elementos visibles
                    if (typeof AOS !== 'undefined') {
                        setTimeout(() => {
                            AOS.refresh();
                        }, 100);
                    }
                    
                    // Pequeño desplazamiento para forzar la actualización del scroll
                    setTimeout(() => {
                        window.scrollBy(0, 1);
                        window.scrollBy(0, -1);
                    }, 200);
                }
            });
        });
        
        // También asegurarse en el evento de carga de la ventana
        window.addEventListener('load', function() {
            fixMenuVisibility();
        });
        
        // Y cada vez que el usuario hace scroll
        window.addEventListener('scroll', function() {
            fixMenuVisibility();
        });
    } else {
        console.log('No estamos en la página de categorías de productos, saltando inicialización');
    }
});

// Función auxiliar para garantizar la visibilidad de todas las secciones
function fixMenuVisibility() {
    // Asegurar que todas las tab-pane tengan los estilos correctos
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.style.cssText = 'overflow: visible !important; height: auto !important;';
    });
    
    // Asegurar que el tab-content tenga los estilos correctos
    const tabContent = document.querySelector('.tab-content');
    if (tabContent) {
        tabContent.style.cssText = 'overflow: visible !important; height: auto !important; position: relative !important;';
    }
    
    // Asegurar que las secciones posteriores al menú estén visibles
    const menuSection = document.getElementById('menu');
    if (menuSection) {
        let nextSection = menuSection.nextElementSibling;
        while (nextSection) {
            // Aplicar estilos para forzar visibilidad
            nextSection.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
            nextSection = nextSection.nextElementSibling;
        }
    }
}
