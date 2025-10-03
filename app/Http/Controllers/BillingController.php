<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\InvoiceMail;
use App\Mail\ReceiptMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Billing;

class BillingController extends Controller
{
    public function sendInvoiceEmail($billId)
    {
        try {
            $bill = Billing::findOrFail($billId);
            $invoiceData = $this->getInvoiceData($billId); // Use your existing method

            // Optionally generate PDF
            // $pdfPath = $this->generateInvoicePDF($billId);

            Mail::to($bill->tenant->email)->send(new InvoiceMail($invoiceData));

            return response()->json([
                'success' => true,
                'message' => 'Invoice email sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendReceiptEmail($billId)
    {
        try {
            $bill = Billing::findOrFail($billId);

            if ($bill->status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bill is not marked as paid'
                ], 400);
            }

            $receiptData = $this->getReceiptData($billId); // Use your existing method

            // Optionally generate PDF
            // $pdfPath = $this->generateReceiptPDF($billId);

            Mail::to($bill->tenant->email)->send(new ReceiptMail($receiptData));

            return response()->json([
                'success' => true,
                'message' => 'Receipt email sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getReceiptData($billId)
    {
        $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
            ->findOrFail($billId);

        if ($bill->status !== 'paid') {
            return response()->json(['error' => 'Receipt only available for paid bills'], 400);
        }

        $receiptNumber = 'RCP-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);
        $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);

        // Handle null paid_at - use current time or updated_at as fallback
        $paymentDate = $bill->paid_at
            ? $bill->paid_at->format('F d, Y h:i A')
            : ($bill->updated_at ? $bill->updated_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A'));

        $receiptData = [
            'receipt_number' => $receiptNumber,
            'invoice_number' => $invoiceNumber,
            'payment_date' => $paymentDate,
            'bill_id' => $bill->id,
            'tenant' => [
                'name' => $bill->tenant->name,
                'email' => $bill->tenant->email,
                'contact' => $bill->tenant->contact_number ?? '',
                'apartment' => $bill->rent && $bill->rent->apartment
                    ? $bill->rent->apartment->apartment_number
                    : 'N/A'
            ],
            'items' => $this->getInvoiceItems($bill),
            'subtotal' => $bill->amount,
            'tax' => 0,
            'total' => $bill->amount,
            'amount_paid' => $bill->amount,
            'payment_method' => $bill->payment_method ?? 'Cash',
            'received_by' => auth()->user()->name,
            'notes' => 'Thank you for your payment!',
        ];

        return response()->json($receiptData);
    }
    private function getInvoiceItems($bill)
    {
        $items = [];

        if ($bill->billingItems && $bill->billingItems->count() > 0) {
            foreach ($bill->billingItems as $item) {
                $items[] = [
                    'description' => ucfirst(str_replace('_', ' ', $item->type)),
                    'details' => $item->description ?? '',
                    'quantity' => 1,
                    'unit_price' => $item->amount,
                    'total' => $item->amount
                ];
            }
        } else {
            $billingTypes = explode(',', $bill->billing_type);
            $description = count($billingTypes) > 1
                ? 'Consolidated Bill - ' . implode(', ', array_map('ucfirst', $billingTypes))
                : ucfirst(str_replace('_', ' ', $bill->billing_type));

            $items[] = [
                'description' => $description,
                'details' => $bill->description ?? '',
                'quantity' => 1,
                'unit_price' => $bill->amount,
                'total' => $bill->amount
            ];
        }

        return $items;
    }


    public function downloadInvoicePDF($billId)
    {
        $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
            ->findOrFail($billId);

        $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);

        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $bill->created_at->format('F d, Y'),
            'due_date' => $bill->due_date->format('F d, Y'),
            'tenant' => [
                'name' => $bill->tenant->name,
                'email' => $bill->tenant->email,
                'contact' => $bill->tenant->contact_number ?? '',
                'apartment' => $bill->rent && $bill->rent->apartment
                    ? $bill->rent->apartment->apartment_number
                    : 'N/A'
            ],
            'items' => $this->getInvoiceItems($bill),
            'subtotal' => $bill->amount,
            'tax' => 0,
            'total' => $bill->amount,
            'status' => $bill->status,
            'payment_terms' => 'Payment due within 7 days of invoice date',
            'notes' => $bill->description ?? '',
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('staff.billing.invoice-pdf', compact('invoiceData'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($invoiceNumber . '.pdf');
    }


    public function getInvoiceData($billId)
    {
        $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
            ->findOrFail($billId);

        $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);

        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $bill->created_at->format('F d, Y'),
            'due_date' => $bill->due_date->format('F d, Y'),
            'bill_id' => $bill->id,
            'tenant' => [
                'name' => $bill->tenant->name,
                'email' => $bill->tenant->email,
                'contact' => $bill->tenant->contact_number ?? '',
                'apartment' => $bill->rent && $bill->rent->apartment
                    ? $bill->rent->apartment->apartment_number
                    : 'N/A'
            ],
            'items' => $this->getInvoiceItems($bill),
            'subtotal' => $bill->amount,
            'tax' => 0,
            'total' => $bill->amount,
            'status' => $bill->status,
            'payment_terms' => 'Payment due within 7 days of invoice date',
            'notes' => $bill->description ?? '',
        ];

        return response()->json($invoiceData);
    }

}
