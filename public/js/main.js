/**
* Template Name: Yummy
* Template URL: https://bootstrapmade.com/yummy-bootstrap-restaurant-website-template/
* Updated: Aug 07 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  mobileNavToggleBtn.addEventListener('click', mobileNavToogle);

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Correct scrolling position upon page load for URLs containing hash links.
   */
  window.addEventListener('load', function(e) {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        setTimeout(() => {
          let section = document.querySelector(window.location.hash);
          let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: 'smooth'
          });
        }, 100);
      }
    }
  });

  /**
   * Navmenu Scrollspy
   */
  let navmenulinks = document.querySelectorAll('.navmenu a');

  function navmenuScrollspy() {
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);

  /**
   * Funcionalidad para el botón de carrito
   * Garantiza que el estilo y funcionalidad del botón de carrito 
   * sea consistente independientemente del estado de inicio de sesión
   */
  document.addEventListener('DOMContentLoaded', function() {
    // Verificamos que el contador del carrito sea visible en el botón sweet-cart-btn
    const actualizarContadorVisual = function() {
      const cartBtn = document.querySelector('.sweet-cart-btn');
      
      if (cartBtn) {
        // Asegurarse de que todos los elementos están presentes
        const cartContainer = cartBtn.querySelector('.cart-container');
        if (!cartContainer) {
          console.log('Restaurando estructura del botón del carrito');
          cartBtn.innerHTML = `
            <div class="cart-container">
              <div class="cart-icon-wrapper">
                <i class="bi bi-cart3"></i>
                <div class="cart-badge">
                  <span class="cart-count">0</span>
                </div>
              </div>
              <div class="cart-text">Mi carrito</div>
            </div>
          `;
        }
        
        // Actualizar contador si la API de carrito está disponible
        fetch('/carrito/count')
          .then(response => response.json())
          .then(data => {
            const contadores = document.querySelectorAll('.cart-count');
            if (contadores.length > 0) {
              contadores.forEach(contador => {
                contador.textContent = data.count;
              });
            }
          })
          .catch(error => console.error('Error al actualizar contador del carrito:', error));
      }
    };
    
    // Ejecutar al cargar la página y después de cualquier cambio en la sesión
    actualizarContadorVisual();
    
    // También actualizar después de que la página esté completamente cargada
    window.addEventListener('load', actualizarContadorVisual);
  });

  /**
   * Deshabilitar confirmaciones adicionales en formularios de eliminación
   * Evita que aparezcan confirmaciones duplicadas
   */
  document.addEventListener('DOMContentLoaded', function() {
    // Desactivar permanentemente el método confirm() del navegador
    // Guardar referencia original para restaurarla luego
    const originalConfirm = window.confirm;
    
    // Reemplazar confirm() solo para formularios de eliminación
    window.confirm = function(message) {
      console.log("Intento de confirmación interceptado:", message);
      
      // Si el mensaje está relacionado con eliminación, evitar la confirmación del navegador
      if (message && (
        message.includes('eliminar') || 
        message.includes('borrar') || 
        message.includes('quitar') ||
        message.includes('remove') || 
        message.includes('delete')
      )) {
        console.log("Confirmación evitada para mensaje de eliminación");
        return true; // Siempre confirmar
      }
      
      // Para otros tipos de mensajes, usar la confirmación original
      return originalConfirm.call(this, message);
    };
    
    // Limpiar atributos onsubmit de formularios que contengan acciones de eliminación
    document.querySelectorAll('form[action*="destroy"], form[action*="delete"], form[action*="eliminar"]').forEach(form => {
      // Eliminar cualquier atributo onsubmit que pueda tener
      form.removeAttribute('onsubmit');
      
      // Eliminar cualquier listener de eventos que pueda tener
      const oldForm = form.cloneNode(true);
      form.parentNode.replaceChild(oldForm, form);
      
      console.log('Formulario protegido contra confirmaciones duplicadas:', oldForm.action);
    });
  });

  /**
   * Sistema de filtrado de reseñas por estrellas
   */
  document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const reviewCards = document.querySelectorAll('.review-card');

    if (filterButtons.length > 0 && reviewCards.length > 0) {
      filterButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Remover clase active de todos los botones
          filterButtons.forEach(btn => btn.classList.remove('active'));
          // Agregar clase active al botón clickeado
          this.classList.add('active');

          const selectedRating = this.getAttribute('data-rating');

          // Filtrar las reseñas
          reviewCards.forEach(card => {
            const cardRating = card.getAttribute('data-rating');
            
            if (selectedRating === 'all' || selectedRating === cardRating) {
              card.style.display = 'block';
              // Agregar animación de fade in
              card.style.opacity = '0';
              setTimeout(() => {
                card.style.opacity = '1';
              }, 50);
            } else {
              card.style.display = 'none';
            }
          });

          // Actualizar contador de reseñas visibles
          const visibleReviews = document.querySelectorAll('.review-card[style="display: block;"]');
          const noReviewsState = document.querySelector('.no-reviews-state');
          
          if (visibleReviews.length === 0 && noReviewsState) {
            noReviewsState.style.display = 'flex';
          } else if (noReviewsState) {
            noReviewsState.style.display = 'none';
          }
        });
      });
    }
  });

})();