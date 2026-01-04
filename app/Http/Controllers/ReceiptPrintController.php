<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Illuminate\Support\Facades\Http;

class ReceiptPrintController extends Controller
{
    public function print(Request $request)
    {
        try {
            Http::post('http://localhost:4000/print');
            return redirect()->back()->with('success', 'Receipt printed!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Print failed: ' . $e->getMessage());
        }
    }
}
