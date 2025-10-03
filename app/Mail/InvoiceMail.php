<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceData;
    public $pdfPath;

    public function __construct($invoiceData, $pdfPath = null)
    {
        $this->invoiceData = $invoiceData;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        $email = $this->subject('Invoice ' . $this->invoiceData['invoice_number'])
            ->view('emails.invoice')
            ->with('invoiceData', $this->invoiceData);

        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $email->attach($this->pdfPath, [
                'as' => $this->invoiceData['invoice_number'] . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}
