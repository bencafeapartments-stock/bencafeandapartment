<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receiptData;
    public $pdfPath;

    public function __construct($receiptData, $pdfPath = null)
    {
        $this->receiptData = $receiptData;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        $email = $this->subject('Payment Receipt ' . $this->receiptData['receipt_number'])
            ->view('emails.receipt')
            ->with('receiptData', $this->receiptData);

        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $email->attach($this->pdfPath, [
                'as' => $this->receiptData['receipt_number'] . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}
