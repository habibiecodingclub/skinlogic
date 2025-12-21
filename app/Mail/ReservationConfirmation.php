<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation; // Pastikan model di-import

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    // Property public agar bisa diakses langsung di Blade view
    public $reservation;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Subject Email yang menarik
            subject: 'Konfirmasi Booking SkinLogic - #' . str_pad($this->reservation->id, 6, '0', STR_PAD_LEFT),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // Arahkan ke file blade yang akan kita buat
            view: 'landing.emails.reservation_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}