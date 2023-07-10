<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlerteNotification extends Notification
{
    use Queueable;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data=$data;


    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
       
            return (new MailMessage)
                    ->success()
                    ->greeting('Salut!')
                    // ->from('info@apip.gov.gn','APIP-ANALYTICS') 
                    ->subject('Notification GUIF2.')
                    ->line('Vous avez un rendez-vous sur la plateforme GUIF avec les informations suivantes:')
                    ->line('Date: '.$this->data['date'].'')
                    ->line('Heure: '.$this->data['heure'].'')
                    ->line('Lieu: '.$this->data['lieu'].'')
                    ->line('Promoteur: '.nomUser($this->data['id_promoteur']).'')
                    ->line('Investisseur: '.nomUser($this->data['id_investisseur']).'')
                    ->line('Lien: '.$this->data['lien'].'')
                    ->line('Details: '.$this->data['details'].'')
                    ->action('Consultez votre compte ici', url("/"));
        
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
