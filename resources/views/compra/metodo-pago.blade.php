<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finalizar Compra - Mi Sueño Dulce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff7070;
            --bg-color: #fef6e6;
            --text-color: #555555;
            --border-color: #f0e8da;
            --card-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            --card-bg: white;
            --header-color: #333;
            --info-bg: rgba(255, 255, 255, 0.7);
            --alert-bg: white;
            --footer-bg: white;
            --footer-text: #777;
            --footer-border: #f0e8da;
            --method-bg: white;
            --method-hover-border: #ff7070;
            --method-selected-bg: rgba(255, 112, 112, 0.03);
            --method-text: #555555;
            --form-bg: rgba(255, 255, 255, 0.5);
            --form-label: #444;
            --form-border: #f0e8da;
            --summary-border: #f0e8da;
        }

        /* Variables para modo oscuro */
        [data-theme="dark"] {
            --bg-color: #1a1a1a;
            --text-color: #e0e0e0;
            --border-color: #444;
            --card-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            --card-bg: #2d2d2d;
            --header-color: #ffffff;
            --info-bg: rgba(45, 45, 45, 0.7);
            --alert-bg: #2d2d2d;
            --footer-bg: #1c1c1c;
            --footer-text: #999;
            --footer-border: #333;
            --method-bg: #2d2d2d;
            --method-hover-border: #ff7070;
            --method-selected-bg: rgba(255, 112, 112, 0.1);
            --method-text: #e0e0e0;
            --form-bg: rgba(45, 45, 45, 0.7);
            --form-label: #c0c0c0;
            --form-border: #444;
            --summary-border: #444;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            padding-bottom: 60px;
            position: relative;
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        .page-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 15px;
        }

        .header {
            text-align: center;
            padding: 15px 0;
        }

        .logo-text {
            font-family: 'Dancing Script', cursive;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--header-color);
            margin: 0;
            display: inline-block;
            transition: color 0.5s ease;
        }
        
        .logo-text .dulce {
            color: var(--primary-color);
        }
        
        .logo-image {
            height: 40px;
            margin-left: 8px;
            vertical-align: middle;
            margin-top: -14px;
        }

        .section-title {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            color: var(--header-color);
            margin-bottom: 20px;
            text-align: center;
            transition: color 0.5s ease;
        }

        .btn-volver {
            display: inline-flex;
            align-items: center;
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 15px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .btn-volver:hover {
            color: #e85f5f;
            transform: translateX(-3px);
        }

        .btn-volver i {
            margin-right: 6px;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            border: none;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 20px;
            transition: background-color 0.5s ease, box-shadow 0.5s ease;
        }

        .card-header {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 18px 20px;
            transition: background-color 0.5s ease;
        }

        .card-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--header-color);
            margin: 0;
            transition: color 0.5s ease;
        }

        .card-body {
            padding: 20px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px;
            margin-bottom: 25px;
        }

        .payment-method {
            background-color: var(--method-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-method:hover {
            border-color: var(--method-hover-border);
            box-shadow: 0 3px 8px rgba(255, 112, 112, 0.1);
        }

        .payment-method.selected {
            background-color: var(--method-selected-bg);
            border: 1px solid var(--primary-color);
            box-shadow: 0 3px 10px rgba(255, 112, 112, 0.1);
        }

        .payment-method-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
            color: var(--primary-color);
        }

        .payment-method-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--method-text);
        }

        .payment-form {
            display: none;
            background-color: var(--form-bg);
            border-radius: 8px;
            padding: 18px;
            margin-top: 15px;
            margin-bottom: 20px;
            border: 1px solid var(--form-border);
            transition: background-color 0.5s ease, border-color 0.5s ease;
        }

        .form-label {
            font-weight: 500;
            color: var(--form-label);
            margin-bottom: 6px;
            font-size: 0.9rem;
            transition: color 0.5s ease;
        }

        .form-control {
            border: 1px solid var(--form-border);
            border-radius: 6px;
            padding: 10px 12px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            background-color: var(--card-bg);
            color: var(--text-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 112, 112, 0.15);
        }

        .btn-finalizar {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.2s ease;
            width: 100%;
            margin-top: 15px;
        }

        .btn-finalizar:hover, .btn-finalizar:focus {
            background-color: #e85f5f;
            color: white;
        }

        .btn-finalizar:disabled {
            background-color: #ffb2b2;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .summary-total {
            border-top: 1px solid var(--summary-border);
            padding-top: 10px;
            margin-top: 12px;
            font-weight: 600;
            color: var(--header-color);
            font-size: 1.05rem;
            transition: color 0.5s ease, border-color 0.5s ease;
        }

        .badge-tamano {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .payment-info {
            margin-bottom: 15px;
        }

        .payment-info-title {
            color: var(--form-label);
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            transition: color 0.5s ease;
        }

        .payment-info-title i {
            color: var(--primary-color);
            margin-right: 8px;
        }

        .payment-info-content {
            padding: 12px;
            background-color: var(--info-bg);
            border-radius: 6px;
            margin-bottom: 12px;
            border: 1px solid var(--form-border);
            font-size: 0.9rem;
            transition: background-color 0.5s ease, border-color 0.5s ease;
        }

        .security-feature {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .security-feature i {
            color: var(--primary-color);
            margin-right: 8px;
            font-size: 1rem;
        }

        .alert-custom {
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            font-size: 0.9rem;
            background-color: var(--alert-bg);
            transition: background-color 0.5s ease;
        }

        .alert-custom-icon {
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .alert-success-custom {
            background-color: rgba(46, 204, 113, 0.1);
            border-left: 3px solid #2ecc71;
            color: #27ae60;
        }

        [data-theme="dark"] .alert-success-custom {
            background-color: rgba(46, 204, 113, 0.05);
            color: #2ecc71;
        }

        .alert-danger-custom {
            background-color: rgba(231, 76, 60, 0.1);
            border-left: 3px solid #e74c3c;
            color: #c0392b;
        }

        [data-theme="dark"] .alert-danger-custom {
            background-color: rgba(231, 76, 60, 0.05);
            color: #e74c3c;
        }

        footer {
            text-align: center;
            background-color: var(--footer-bg);
            color: var(--footer-text);
            padding: 15px 0;
            width: 100%;
            position: absolute;
            bottom: 0;
            border-top: 1px solid var(--footer-border);
            font-size: 0.9rem;
            transition: background-color 0.5s ease, color 0.5s ease, border-color 0.5s ease;
        }

        /* Botón de cambio de tema */
        .theme-toggle-btn {
            position: fixed;
            bottom: 30px;
            left: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            color: #ff7070;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            border: none;
            transition: all 0.3s ease;
        }

        [data-theme="dark"] .theme-toggle-btn {
            background-color: #333;
            color: #ff9494;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
        }

        .theme-toggle-btn:hover {
            transform: scale(1.1);
        }

        .theme-toggle-btn i {
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .section-title {
                font-size: 1.7rem;
            }
            
            .card-header h3 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="header">
            <h1 class="logo-text">Mi Sueño <span class="dulce">Dulce</span></h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo Mi Sueño Dulce" class="logo-image">
        </div>
        
        <!-- Botón para cambiar tema -->
        <button id="theme-toggle" class="theme-toggle-btn">
            <i class="bi bi-moon-fill" id="theme-icon"></i>
        </button>
        
        <a href="{{ url()->previous() }}" class="btn-volver">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

        <h2 class="section-title">Finalizar tu compra</h2>

        @if(session('success'))
            <div class="alert-custom alert-success-custom">
                <div class="alert-custom-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-custom alert-danger-custom">
                <div class="alert-custom-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Método de pago</h3>
                    </div>
                    <div class="card-body">
                        <form id="payment-form" action="{{ route('payment.complete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="metodo_pago" id="metodo_pago" value="">
                            <input type="hidden" name="debug_info" value="Browser: {{ request()->header('User-Agent') }}">

                            <div class="payment-methods">
                                <div class="payment-method" data-payment="tarjeta">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-credit-card"></i>
                                    </div>
                                    <div class="payment-method-name">Tarjeta</div>
                                </div>
                                <div class="payment-method" data-payment="paypal">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-paypal"></i>
                                    </div>
                                    <div class="payment-method-name">PayPal</div>
                                </div>
                                <div class="payment-method" data-payment="transferencia">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-bank"></i>
                                    </div>
                                    <div class="payment-method-name">Transferencia</div>
                                </div>
                                <div class="payment-method" data-payment="bizum">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-phone"></i>
                                    </div>
                                    <div class="payment-method-name">Bizum</div>
                                </div>
                            </div>

                            <div id="tarjeta-form" class="payment-form">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="card_number" class="form-label">Número de Tarjeta</label>
                                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label for="expiry_date" class="form-label">Fecha de Expiración</label>
                                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/AA">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="card_holder" class="form-label">Titular de la Tarjeta</label>
                                        <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="NOMBRE APELLIDOS">
                                    </div>
                                </div>
                            </div>

                            <div id="paypal-form" class="payment-form">
                                <div class="payment-info">
                                    <div class="payment-info-title">
                                        <i class="bi bi-info-circle-fill"></i>
                                        Información de pago con PayPal
                                    </div>  
                                    <p class="mb-3">Serás redirigido a PayPal para completar tu compra de forma segura.</p>
                                    <div class="d-flex justify-content-center my-3">
                                        <img src="{{ asset('img/image.png') }}" alt="PayPal" style="max-height: 40px;">
                                    </div>
                                    <p class="mb-0 text-center" style="font-size: 0.9rem;">Tras confirmar el pago en PayPal, volverás automáticamente a nuestra tienda.</p>
                                </div>
                            </div>

                            <div id="bizum-form" class="payment-form">
                                <div class="payment-info">
                                    <div class="payment-info-title">
                                        <i class="bi bi-info-circle-fill"></i>
                                        Información de pago con Bizum
                                    </div>
                                    <p class="mb-3">Para realizar tu pago mediante Bizum:</p>
                                    
                                    <div class="payment-info-content">
                                        <p class="mb-1"><strong>Número:</strong> 600123456</p>
                                        <p class="mb-1"><strong>Beneficiario:</strong> Mi Sueño Dulce</p>
                                        <p class="mb-0"><strong>Concepto:</strong> Pedido #{{ time() }} - {{ Auth::user()->name }}</p>
                                    </div>
                                    
                                    <ol class="ps-3 mb-3" style="font-size: 0.9rem;">
                                        <li>Abre la app de tu banco</li>
                                        <li>Selecciona la opción de Bizum</li>
                                        <li>Introduce el número y concepto indicados</li>
                                        <li>Confirma el pago en tu app</li>
                                    </ol>
                                </div>
                            </div>

                            <div id="transferencia-form" class="payment-form">
                                <div class="payment-info">
                                    <div class="payment-info-title">
                                        <i class="bi bi-info-circle-fill"></i>
                                        Información para transferencia bancaria
                                    </div>
                                    <p class="mb-3">Para realizar tu pago mediante transferencia bancaria:</p>
                                    
                                    <div class="payment-info-content">
                                        <p class="mb-1"><strong>Banco:</strong> Banco Ejemplo</p>
                                        <p class="mb-1"><strong>IBAN:</strong> ES00 0000 0000 0000 0000 0000</p>
                                        <p class="mb-1"><strong>Beneficiario:</strong> Mi Sueño Dulce</p>
                                        <p class="mb-0"><strong>Concepto:</strong> Pedido #{{ time() }} - {{ Auth::user()->name }}</p>
                                    </div>
                                    
                                    <p class="mt-3 mb-0" style="font-size: 0.9rem;">Recibirás un correo de confirmación cuando verifiquemos tu pago.</p>
                                </div>
                            </div>

                            <button type="submit" id="submit-button" class="btn-finalizar" disabled>
                                Finalizar Compra
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Resumen del Pedido</h3>
                    </div>
                    <div class="card-body">
                        @foreach($carrito as $item)
                        <div class="summary-item">
                            <div>
                                <div>{{ $item->producto->nombre }} <span class="text-muted">x{{ $item->cantidad }}</span></div>
                                @if(isset($item->tamano))
                                    <div class="mt-1">
                                        <span class="badge-tamano">
                                            @if($item->tamano == 'grande')
                                                Grande
                                            @elseif($item->tamano == 'muygrande')
                                                Muy Grande
                                            @else
                                                Normal
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div>€{{ number_format($item->producto->precio * $item->cantidad, 2) }}</div>
                        </div>
                        @endforeach

                        <div class="summary-total">
                            <span>Total</span>
                            <span>€{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="security-feature">
                            <i class="bi bi-shield-check"></i>
                            <span>Pago 100% seguro</span>
                        </div>
                        <div class="security-feature">
                            <i class="bi bi-truck"></i>
                            <span>Entrega entre 24-48 horas</span>
                        </div>
                        <div class="security-feature mb-0">
                            <i class="bi bi-heart"></i>
                            <span>Hecho con amor</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethods = document.querySelectorAll('.payment-method');
            const paymentForms = document.querySelectorAll('.payment-form');
            const submitButton = document.getElementById('submit-button');
            const methodInput = document.getElementById('metodo_pago');
            const paymentForm = document.getElementById('payment-form');
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            // Comprobar el tema actual del sistema o el guardado previamente
            const currentTheme = localStorage.getItem('theme') || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            
            if (currentTheme === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
                themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
            }
            
            // Función para cambiar el tema
            themeToggleBtn.addEventListener('click', function() {
                if (document.body.getAttribute('data-theme') === 'dark') {
                    document.body.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
                } else {
                    document.body.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
                }
            });

            // Formatear tarjeta de crédito
            function formatCardNumber(input) {
                let value = input.value.replace(/\D/g, '');
                if (value.length > 16) {
                    value = value.slice(0, 16);
                }
                
                const formatted = [];
                for (let i = 0; i < value.length; i += 4) {
                    formatted.push(value.slice(i, i + 4));
                }
                
                input.value = formatted.join(' ');
            }

            // Formatear fecha de expiración
            function formatExpiryDate(input) {
                let value = input.value.replace(/\D/g, '');
                if (value.length > 4) {
                    value = value.slice(0, 4);
                }
                
                if (value.length > 2) {
                    input.value = value.slice(0, 2) + '/' + value.slice(2);
                } else {
                    input.value = value;
                }
            }

            // Formatear CVV
            function formatCVV(input) {
                let value = input.value.replace(/\D/g, '');
                if (value.length > 4) {
                    value = value.slice(0, 4);
                }
                input.value = value;
            }

            // Aplicar formateo a los campos
            const cardNumberInput = document.getElementById('card_number');
            const expiryDateInput = document.getElementById('expiry_date');
            const cvvInput = document.getElementById('cvv');

            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function() {
                    formatCardNumber(this);
                });
            }

            if (expiryDateInput) {
                expiryDateInput.addEventListener('input', function() {
                    formatExpiryDate(this);
                });
            }

            if (cvvInput) {
                cvvInput.addEventListener('input', function() {
                    formatCVV(this);
                });
            }

            // Manejar selección de método de pago
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    // Quitar selección previa
                    paymentMethods.forEach(opt => opt.classList.remove('selected'));
                    
                    // Ocultar formularios
                    paymentForms.forEach(form => form.style.display = 'none');
                    
                    // Seleccionar opción actual
                    this.classList.add('selected');
                    
                    // Mostrar formulario correspondiente
                    const paymentMethod = this.getAttribute('data-payment');
                    document.getElementById(paymentMethod + '-form').style.display = 'block';
                    
                    // Activar botón de envío
                    submitButton.disabled = false;
                    
                    // Actualizar método en input oculto
                    methodInput.value = paymentMethod;
                });
            });

            // Validar envío del formulario
            paymentForm.addEventListener('submit', function(e) {
                // Verificar método seleccionado
                if (!methodInput.value) {
                    e.preventDefault();
                    alert('Por favor, selecciona un método de pago');
                    return false;
                }

                // Validar campos según método
                if (methodInput.value === 'tarjeta') {
                    const cardNumber = document.getElementById('card_number').value;
                    const expiryDate = document.getElementById('expiry_date').value;
                    const cvv = document.getElementById('cvv').value;
                    const cardHolder = document.getElementById('card_holder').value;
                    
                    if (!cardNumber || !expiryDate || !cvv || !cardHolder) {
                        e.preventDefault();
                        alert('Por favor, completa todos los campos de la tarjeta');
                        return false;
                    }
                }

                // Cambiar apariencia del botón
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando...';
                submitButton.disabled = true;

                // Añadir timestamp
                const timestamp = document.createElement('input');
                timestamp.type = 'hidden';
                timestamp.name = 'timestamp';
                timestamp.value = new Date().getTime();
                this.appendChild(timestamp);
                
                return true;
            });
        });
    </script>
</body>
</html>
