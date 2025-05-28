/**
* PHP Email Form Validation - v3.10
* URL: https://bootstrapmade.com/php-email-form/
* Author: BootstrapMade.com
*/
(function () {
  "use strict";

  let forms = document.querySelectorAll('.php-email-form');

  forms.forEach( function(e) {
    e.addEventListener('submit', function(event) {
      event.preventDefault();

      let thisForm = this;

      let action = thisForm.getAttribute('action');
      let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');

      if( ! action ) {
        displayError(thisForm, 'The form action property is not set!');
        return;
      }
      thisForm.querySelector('.loading').classList.add('d-block');
      thisForm.querySelector('.error-message').classList.remove('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block');

      let formData = new FormData( thisForm );

      if ( recaptcha ) {
        if(typeof grecaptcha !== "undefined" ) {
          grecaptcha.ready(function() {
            try {
              grecaptcha.execute(recaptcha, {action: 'php_email_form_submit'})
              .then(token => {
                formData.set('recaptcha-response', token);
                php_email_form_submit(thisForm, action, formData);
              })
            } catch(error) {
              displayError(thisForm, error);
            }
          });
        } else {
          displayError(thisForm, 'The reCaptcha javascript API url is not loaded!')
        }
      } else {
        php_email_form_submit(thisForm, action, formData);
      }
    });
  });

  function php_email_form_submit(thisForm, action, formData) {
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(response => {
      if( response.ok ) {
        return response.text();
      } else {
        throw new Error(`${response.status} ${response.statusText} ${response.url}`);
      }
    })
    .then(data => {
      thisForm.querySelector('.loading').classList.remove('d-block');
      if (data.trim() == 'OK') {
        const sentMessage = thisForm.querySelector('.sent-message');
        sentMessage.classList.add('d-block');
        thisForm.reset();

        // Configurar desaparición gradual después de 4 segundos
        setTimeout(function() {
          // Añadir clase para fade out
          sentMessage.style.opacity = '1';
          sentMessage.style.transition = 'opacity 1.5s ease';

          // Iniciar fade out
          sentMessage.style.opacity = '0';

          // Remover clase d-block después de completar la transición
          setTimeout(function() {
            sentMessage.classList.remove('d-block');
            // Resetear opacidad para futuras apariciones
            sentMessage.style.opacity = '';
          }, 1500); // Este tiempo debe coincidir con la duración de la transición
        }, 4000); // Tiempo en milisegundos antes de comenzar a desaparecer

      } else {
        throw new Error(data ? data : 'Form submission failed and no error message returned from: ' + action);
      }
    })
    .catch((error) => {
      displayError(thisForm, error);
    });
  }

  function displayError(thisForm, error) {
    thisForm.querySelector('.loading').classList.remove('d-block');
    thisForm.querySelector('.error-message').innerHTML = error;
    thisForm.querySelector('.error-message').classList.add('d-block');

    // También hacer que el mensaje de error desaparezca después de un tiempo
    setTimeout(function() {
      const errorMessage = thisForm.querySelector('.error-message');
      errorMessage.style.opacity = '1';
      errorMessage.style.transition = 'opacity 1.5s ease';
      errorMessage.style.opacity = '0';

      setTimeout(function() {
        errorMessage.classList.remove('d-block');
        errorMessage.style.opacity = '';
      }, 1500);
    }, 4000);
  }

})();
