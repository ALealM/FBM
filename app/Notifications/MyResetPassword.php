<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class MyResetPassword extends ResetPassword
{
  public function toMail($notifiable)
  {
    return (new MailMessage)
    ->subject('Recuperar contraseña')
    ->line('Has recibido este correo electrónico para reiniciar tu contraseña.')
    ->action('Reiniciar contraseña', route('password.reset', $this->token))
    ->line('Si no solicitaste esta acción comunicate con soporte.')
    ->salutation(config('app.name'));
  }
}
