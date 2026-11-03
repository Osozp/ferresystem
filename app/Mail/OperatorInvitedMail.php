<?php

namespace App\Mail;

use App\Models\Commerce;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OperatorInvitedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $commerce;
    public $temporaryPassword;

    public function __construct(User $user, Commerce $commerce, string $temporaryPassword)
    {
        $this->user = $user;
        $this->commerce = $commerce;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Invitación para colaborar en {$this->commerce->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.operator_invited',
        );
    }
}
