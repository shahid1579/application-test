<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class TimeTrackingSummary extends Mailable
{
    use Queueable, SerializesModels;
    protected $timeEntries;
    protected $user;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        
    }

    public function build()
{
    return $this->markdown('emails.time_tracking_summary', [
        'user' => $this->user,
        'timeEntries' => $this->timeEntries,
    ]);
}


 
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Time Tracking Summary',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
