<?php

    namespace App\Mail;
    
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Mail\Mailable;
    use Illuminate\Mail\Mailables\Content;
    use Illuminate\Mail\Mailables\Envelope;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Mail\Mailables\Address;
    
    class SendOtp extends Mailable
    {
        use Queueable, SerializesModels;
    
    
        
        /**
         * Create a new message instance.
         */
        public function __construct(private $full_name,
        private $message,
        private $email,
        private $phone_number)
        {
            $this->full_name = $full_name;
            $this->message = $message;
            $this->email = $email;
            $this->phone_number = $phone_number;
            //
        }
    
        /**
         * Get the message envelope.
         */
        public function envelope(): Envelope
        {
            return new Envelope(
                subject: 'Code de confirmation LeBaobab',
                from: new Address('accounts@unetah.net', 'Message de LeBaobab'),
            );
        }
    
        /**
         * Get the message content definition.
         */
        public function content(): Content
        {
            return new Content(
                view: 'emails.SendMessage',
                with: [
                    'full_name' => $this->full_name,
                    'message' => $this->message,
                    'email' => $this->email,
                    'phone_number' => $this->phone_number,
                ]
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
    
  