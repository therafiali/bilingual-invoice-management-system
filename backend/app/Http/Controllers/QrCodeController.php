<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function generateQr()
    {
        $qrCode = QrCode::size(300)->generate('Hello World');
        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }
}
