<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $password;
    public $apartmentInfo;

    /**
     * Create a new message instance.
     */
    public function __construct($tenant, $password, $apartmentInfo = null)
    {
        $this->tenant = $tenant;
        $this->password = $password;
        $this->apartmentInfo = $apartmentInfo;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to Ben Cafe Apartments - Your Account Details')
            ->view('emails.tenant-welcome')
            ->with([
                'tenantName' => $this->tenant->name,
                'email' => $this->tenant->email,
                'password' => $this->password,
                'loginUrl' => route('login'),
                'apartmentInfo' => $this->apartmentInfo,
            ]);
    }
}