<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use App\Mail\ContactConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Obtener IP del cliente para identificación única
        $clientIp = $request->ip();
        
        // Crear clave de caché única basada en IP
        $cacheKey = 'contact_form:' . md5($clientIp);
        
        // Comprobar si hay un bloqueo por IP
        if (Cache::has($cacheKey)) {
            $timeLeft = Cache::get($cacheKey);
            return response("Por favor espera $timeLeft segundos antes de enviar otro mensaje.", 429);
        }
        
        // Evitar envíos múltiples verificando la marca de tiempo del último envío
        $lastContactSent = session('last_contact_form_sent');
        $now = now()->timestamp;
        
        if ($lastContactSent && ($now - $lastContactSent < 30)) {
            $timeLeft = 30 - ($now - $lastContactSent);
            
            // Guardar el bloqueo en caché para que sea efectivo incluso si la sesión cambia
            Cache::put($cacheKey, $timeLeft, $timeLeft);
            
            return response("Por favor espera $timeLeft segundos antes de enviar otro mensaje.", 429);
        }
        
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ];

        // Si el usuario no está autenticado, validar el reCAPTCHA
        if (!Auth::check()) {
            $validationRules['g-recaptcha-response'] = 'required';

            // Validar las reglas básicas primero
            $validated = $request->validate($validationRules);

            // Validar el reCAPTCHA con Google
            $recaptcha = $request->input('g-recaptcha-response');
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $recaptcha,
                'remoteip' => $request->ip()
            ]);

            $result = $response->json();

            if (!$result['success']) {
                return response('Error: Por favor, verifica que no eres un robot.', 400);
            }
        } else {
            // Si el usuario está autenticado, solo validar las reglas básicas
            $validated = $request->validate($validationRules);
        }

        // Enviar el correo a la administración
        Mail::to(env('MAIL_FROM_ADDRESS', 'misdulces@gmail.com'))
            ->send(new ContactFormMail($validated));

        // Enviar correo de confirmación al usuario
        Mail::to($validated['email'])
            ->send(new ContactConfirmationMail($validated['name'], $validated['email']));
            
        // Guardar marca de tiempo del último envío
        session(['last_contact_form_sent' => now()->timestamp]);
        
        // Establecer bloqueo en caché para evitar spam
        Cache::put($cacheKey, 30, 30);

        // Responder con éxito para el script de AJAX
        return response('OK');
    }
}
