<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function generateInvoice(Request $request)
    {


        $request->validate([
            'invoice_number' => 'required|string',
            'client_name' => 'required|string',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0.01',
            // ... other necessary invoice fields
        ]);

        // Mock invoice data
        $invoiceData = [
            'number' => $request->input('invoice_number'),
            'client' => $request->input('client_name'),
            'date' => now()->format('Y-m-d'),
            'items' => $request->input('items'),
            'total' => collect($request->input('items'))->sum(fn($item) => $item['quantity'] * $item['price']),
            // You might save this data to your database here...
        ];

        // Define file path structure
        $folder = 'invoices';
        $filename = 'invoice_' . Str::slug($invoiceData['number']) . '_' . time() . '.pdf';
        $path = $folder . '/' . $filename;

        $pdfPublicUrl = Storage::disk('public')->url($path);

        // We use 'data:image/svg+xml;base64,...' in the view to embed the QR code
        $qrCodeSvg = QrCode::size(100)->generate($pdfPublicUrl);
        $qrCodeBase64 = base64_encode($qrCodeSvg);
        $qrCodeDataUrl = 'data:image/svg+xml;base64,' . $qrCodeBase64;


        $pdf = Pdf::loadView('invoices.template', [
            'invoice' => $invoiceData,
            'qrCodeDataUrl' => $qrCodeDataUrl,
            // Include your company logo, address, etc.
        ]);

        Storage::disk('public')->put($path, $pdf->output());

        return response()->json([
            'success' => true,
            'message' => 'Invoice generated successfully.',
            'data' => [
                'file_url' => asset('storage/' . $path),
                'qr_code_url' => $qrCodeDataUrl, // The Base64 image data for the QR code
                'qr_code_points_to' => $pdfPublicUrl,
            ]
        ], 201);
    }
}
