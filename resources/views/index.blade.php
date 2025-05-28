@extends('layouts.layout')

@section('title', 'Mis dulces pastelitos')

@section('content')

<div class="card" id="alert-card">
  <div class="card-wrapper">
    <div class="card-icon">
      <div class="icon-cart-box">
        <svg viewBox="0 0 576 512" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" fill="#009688"></path>
        </svg>
      </div>
    </div>

    <div class="card-content">
      <div class="card-title-wrapper">
        <span class="card-title">¡Añadido al carrito!</span>
        <span class="card-action" id="close-btn">
          <svg viewBox="0 0 384 512" width="15" height="15" xmlns="http://www.w3.org/2000/svg">
            <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"></path>
          </svg>
        </span>
      </div>
      <div class="product-name"></div>
      <div class="product-price"></div>
      <button class="btn-view-cart" type="button" id="viewCartBtn" data-route="{{ route('compra') }}">Ir al carrito</button>
    </div>
  </div>
</div>


<main class="main">

  <!-- Hero Section -->
  <section id="hero" class="hero-section">
    <div class="hero-overlay">
      <div class="hero-content text-center">
        <h1 data-aos="fade-up" style="font-size: 4rem;">Mi sueño dulce</h1>
        <p data-aos="fade-up" data-aos-delay="100">Deliciosos postres hechos con amor, ingredientes frescos y la calidad que te mereces.</p>
        <div data-aos="fade-up" data-aos-delay="200">
          <a href="{{ url('#menu') }}" class="btn-proceso">Haz tu Pedido</a>
        </div>
      </div>
    </div>
  </section><!-- /Hero Section -->

  <section id="about" class="about section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Nuestra Historia<br></h2>
      <p><span>Descubre</span> <span class="description-title">quiénes somos</span></p>
    </div><!-- End Section Title -->

    <div class="container">
      <div class="row align-items-center">
        <!-- Imagen principal -->
        <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-up">
          <div class="about-image-container">
            <img src="{{ asset('img/acerca-de.jpg') }}" class="img-fluid shadow-sm rounded" alt="Mi Sueño Dulce - Repostería artesanal">
            <div class="experience-badge">
              <span style="color: black;">15</span>
              <small>años de<br>experiencia</small>
            </div>
          </div>
        </div>
        
        <!-- Contenido principal -->
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <div class="about-content">
            <h3 class="about-heading mb-4">El arte de endulzar momentos</h3>
            
            <p class="about-text">
              Desde 2010 convertimos ingredientes en emociones. En Mi Sueño Dulce no solo horneamos pasteles; creamos recuerdos que perduran en el paladar y en el corazón.
            </p>
            
            <p class="about-text mb-4">
              Cada creación que sale de nuestro horno lleva consigo la dedicación de manos expertas que transforman lo cotidiano en extraordinario. Porque para nosotros, la repostería no es solo un oficio, es nuestra forma de vida.
            </p>
            
            <!-- Valores en 3 tarjetas limpias -->
            <div class="row g-4 mt-3">
              <div class="col-md-4 text-center">
                <div class="value-card">
                  <i class="bi bi-heart"></i>
                  <h5>Pasión</h5>
                </div>
              </div>
              
              <div class="col-md-4 text-center">
                <div class="value-card">
                  <i class="bi bi-gem"></i>
                  <h5>Calidad</h5>
                </div>
              </div>
              
              <div class="col-md-4 text-center">
                <div class="value-card">
                  <i class="bi bi-stars"></i>
                  <h5>Creatividad</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section><!-- /About Section -->


  <!-- Stats Section -->
  <section id="stats" class="stats section dark-background">

    <img src="{{ asset('img/stats.jpg') }}" alt="" data-aos="fade-in">

    <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">

      <div class="row gy-4">

        <div class="col-lg-3 col-md-6">
          <div class="stats-item text-center w-100 h-100">
            <span data-purecounter-start="0" data-purecounter-end="88" data-purecounter-duration="1" class="purecounter"></span>
            <p>Clientes</p>
          </div>
        </div><!-- End Stats Item -->

        <div class="col-lg-3 col-md-6">
          <div class="stats-item text-center w-100 h-100">
            <span data-purecounter-start="0" data-purecounter-end="5" data-purecounter-duration="1" class="purecounter"></span>
            <p>Projectos</p>
          </div>
        </div><!-- End Stats Item -->

        <div class="col-lg-3 col-md-6">
          <div class="stats-item text-center w-100 h-100">
            <span data-purecounter-start="0" data-purecounter-end="120" data-purecounter-duration="1" class="purecounter"></span>
            <p>Horas de soporte</p>
          </div>
        </div><!-- End Stats Item -->

        <div class="col-lg-3 col-md-6">
          <div class="stats-item text-center w-100 h-100">
            <span data-purecounter-start="0" data-purecounter-end="7" data-purecounter-duration="1" class="purecounter"></span>
            <p>Trabajadores</p>
          </div>
        </div><!-- End Stats Item -->

      </div>

    </div>

  </section><!-- /Stats Section -->

  <!-- Menu Section -->
  <section id="menu" class="menu section">
    <div class="container section-title" data-aos="fade-up">
      <h2>¡Descubre Nuestro Menú Irresistible!</h2>
      <p><span>Disfruta de nuestras delicias</span> <span class="description-title">y sabores únicos</span></p>
    </div>

    <div class="container">
      <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
        <!-- Las pestañas se cargarán dinámicamente -->
      </ul>

      <div class="tab-content" data-aos="fade-up" data-aos-delay="200">
        <!-- El contenido de las pestañas se cargará dinámicamente -->
      </div>
    </div>
  </section>

  <!-- Nuestro Proceso Section -->
  <section id="proceso" class="proceso section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Nuestro Proceso</h2>
      <p><span>Descubre</span> <span class="description-title">cómo creamos la magia</span></p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-12 text-center mb-4">
          <p class="proceso-intro">En Mi Sueño Dulce, cada creación es elaborada con pasión y dedicación, siguiendo un meticuloso proceso que garantiza la calidad y el sabor que nos caracteriza.</p>
        </div>
      </div>

      <div class="row proceso-steps">
        <div class="col-md-3 proceso-step" data-aos="fade-up" data-aos-delay="200">
          <div class="proceso-card">
            <div class="proceso-icon-container">
              <i class="bi bi-basket"></i>
              <div class="proceso-number">1</div>
            </div>
            <h3>Selección de ingredientes</h3>
            <p>Escogemos cuidadosamente los ingredientes más frescos y de la mejor calidad para nuestras creaciones.</p>
          </div>
        </div>

        <div class="col-md-3 proceso-step" data-aos="fade-up" data-aos-delay="300">
          <div class="proceso-card">
            <div class="proceso-icon-container">
              <i class="bi bi-rulers"></i>
              <div class="proceso-number">2</div>
            </div>
            <h3>Preparación artesanal</h3>
            <p>Mezclamos los ingredientes siguiendo recetas tradicionales con un toque de innovación que nos distingue.</p>
          </div>
        </div>

        <div class="col-md-3 proceso-step" data-aos="fade-up" data-aos-delay="400">
          <div class="proceso-card">
            <div class="proceso-icon-container">
              <i class="bi bi-fire"></i>
              <div class="proceso-number">3</div>
            </div>
            <h3>Horneado perfecto</h3>
            <p>Cada producto es horneado con precisión para lograr la textura y sabor ideal que caracteriza a nuestros dulces.</p>
          </div>
        </div>

        <div class="col-md-3 proceso-step" data-aos="fade-up" data-aos-delay="500">
          <div class="proceso-card">
            <div class="proceso-icon-container">
              <i class="bi bi-palette"></i>
              <div class="proceso-number">4</div>
            </div>
            <h3>Decoración creativa</h3>
            <p>El toque final es una decoración detallada que convierte cada postre en una auténtica obra de arte.</p>
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-12 text-center">
          <a href="#menu" class="btn-proceso">Descubre Nuestros Productos</a>
        </div>
      </div>
    </div>

  </section><!-- /Nuestro Proceso Section -->

  <!-- Contact Section -->
  <section id="contact" class="contact section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>¡Póngase en Contacto con Nosotros!</h2>
      <p><span>¿Tienes alguna duda?</span> <span class="description-title">¡Estamos para ayudarte!</span></p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="mb-5">
        <iframe style="width: 100%; height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5811.600676563896!2d-2.9308912244612677!3d43.2556061711238!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4e4fd288cfd097%3A0xc541df1944a9eba2!2sC.%20Cantalojas%2C%201%2C%20Ibaiondo%2C%2048003%20Bilbao%2C%20Vizcaya!5e0!3m2!1ses!2ses!4v1742210188664!5m2!1ses!2ses" frameborder="0" allowfullscreen=""></iframe>
      </div><!-- End Google Maps -->

      <div class="row gy-4">

        <div class="col-md-6">
          <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="100">
            <i class="icon bi bi-geo-alt flex-shrink-0" id="icon-geo-alt"></i>
            <div>
              <h3>Dirección</h3>
              <p>Calle Cantalojas, 1, Ibaiondo, 48003 Bilbao, Vizcaya</p>
            </div>
          </div>
        </div><!-- End Info Item -->

        <div class="col-md-6">
          <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
            <i class="icon bi bi-telephone flex-shrink-0" id="icon-telephone"></i>
            <div>
              <h3>¡Llámanos!</h3>
              <p>+34 631 30 48 68</p>
            </div>
          </div>
        </div><!-- End Info Item -->

        <div class="col-md-6">
          <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="300">
            <i class="icon bi bi-envelope flex-shrink-0" id="icon-envelope"></i>
            <div>
              <h3>Escríbenos</h3>
              <p>misuenodulceoficial@gmail.com</p>
            </div>
          </div>
        </div><!-- End Info Item -->

        <div class="col-md-6">
          <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="400">
            <i class="icon bi bi-clock flex-shrink-0" id="icon-clock"></i>
            <div>
              <h3>Horarios de Apertura</h3>
              <p><strong>De Lunes a Sábado:</strong> 08:00 AM - 21:00 PM, <strong> Domingo:</strong> Cerrado</p>
            </div>
          </div>
        </div><!-- End Info Item -->

      </div>

      <form action="{{ route('contact.store') }}" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
        @csrf
        <div class="row gy-4">

          <div class="col-md-6">
            <input type="text" name="name" class="form-control" placeholder="Tu Nombre" required="" value="{{ Auth::check() ? Auth::user()->name : '' }}" {{ Auth::check() ? 'readonly' : '' }}>
          </div>

          <div class="col-md-6">
            <input type="email" class="form-control" name="email" placeholder="Tu Correo" required="" value="{{ Auth::check() ? Auth::user()->email : '' }}" {{ Auth::check() ? 'readonly' : '' }}>
          </div>

          <div class="col-md-12">
            <input type="text" class="form-control" name="subject" placeholder="Asunto" required="">
          </div>

          <div class="col-md-12">
            <textarea class="form-control" name="message" rows="6" placeholder="Mensaje" required=""></textarea>
          </div>

          @if(!Auth::check())
          <div class="col-md-12 mt-3">
            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
            <div class="recaptcha-error text-danger mt-2" style="display: none;">Por favor, verifica que no eres un robot.</div>
          </div>
          @endif

          <div class="col-md-12 text-center">
            <div class="error-message"></div>
            <button type="submit">Enviar Mensaje</button>
          </div>

        </div>
      </form><!-- End Contact Form -->

    </div>

  </section><!-- /Contact Section -->

</main>
@endsection

@section('scripts')
<script>
  // Inicialización de PureCounter para la sección de estadísticas
  document.addEventListener('DOMContentLoaded', function() {
    // Comprobamos si PureCounter está disponible
    if (typeof PureCounter === 'function') {
      new PureCounter({
        selector: '.purecounter',
        once: true,
        observer: true
      });
    } else {
      console.error('PureCounter no está cargado. Asegúrate de incluir la biblioteca.');
    }
    
    // Animación para el contador en la sección Sobre Nosotros
    const counterElement = document.querySelector('.counter');
    if (counterElement) {
      const targetNumber = 15; // El número final que queremos mostrar
      let currentNumber = 0;
      
      // Función para verificar si el elemento es visible en el viewport
      function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
          rect.top >= 0 &&
          rect.left >= 0 &&
          rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
          rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
      }
      
      // Función para animar el contador
      function animateCounter() {
        if (isElementInViewport(counterElement) && currentNumber < targetNumber) {
          counterElement.classList.add('animate-count');
          const duration = 2000; // 2 segundos para completar la animación
          const interval = duration / targetNumber;
          
          const timer = setInterval(function() {
            currentNumber++;
            counterElement.textContent = currentNumber;
            
            if (currentNumber >= targetNumber) {
              clearInterval(timer);
            }
          }, interval);
          
          // Eliminar el event listener una vez que la animación ha comenzado
          window.removeEventListener('scroll', animateCounter);
        }
      }
      
      // Iniciar la animación si el elemento es visible al cargar la página
      animateCounter();
      
      // O iniciarla cuando el usuario haga scroll hasta el elemento
      window.addEventListener('scroll', animateCounter);
    }
  });
</script>
@endsection
