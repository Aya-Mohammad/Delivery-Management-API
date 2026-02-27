<?php

// namespace App\Mail;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Mail\Mailable;
// use Illuminate\Mail\Mailables\Content;
// use Illuminate\Mail\Mailables\Envelope;
// use Illuminate\Queue\SerializesModels;

// class Email extends Mailable
// {
//     use Queueable, SerializesModels;


//     public function __construct($content)
//     {
//         $this->content = $content;
//     }


//     public function envelope(): Envelope
//     {
//         return new Envelope(
//             subject: 'Email',
//         );
//     }


//     public function content(): Content
//     {
//         return new Content(
//             text: 'your verification code is 123456',
//         );
//     }


//     public function attachments(): array
//     {
//         return [];
//     }
// }
