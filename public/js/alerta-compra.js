// Obtener el botón de mostrar alerta y el contenedor de la alerta
const showAlertBtn = document.getElementById("showAlertBtn");
const alertCard = document.getElementById("alert-card");
const closeBtn = document.getElementById("close-btn");

// Función para mostrar la alerta
function showAlert() {
    if (alertCard) {
        alertCard.style.display = "block"; // Mostrar la alerta
        setTimeout(function () {
            alertCard.style.display = "none"; // Ocultar la alerta después de 5 segundos
        }, 5000); // 5000ms = 5 segundos
    }
}

// Función para cerrar la alerta cuando se hace clic en la "X"
if (closeBtn) {
    closeBtn.addEventListener("click", function () {
        if (alertCard) {
            alertCard.style.display = "none"; // Ocultar la alerta
        }
    });
}

// Evento para mostrar la alerta cuando el botón es presionado
if (showAlertBtn) {
    showAlertBtn.addEventListener("click", function () {
        showAlert(); // Llama la función para mostrar la alerta
    });
}

const viewCartBtn = document.getElementById('viewCartBtn');
if (viewCartBtn) {
    viewCartBtn.addEventListener('click', function () {
        var route = this.getAttribute('data-route');
        window.location.href = route + "#book-a-table";
    });
}
