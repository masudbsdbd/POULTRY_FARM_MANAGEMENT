<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class PrintController extends Controller
{
    public function print(Request $request)
    {
        try {
            // 🖨️ Replace with your printer's IP address
            $connector = new NetworkPrintConnector("192.168.0.100", 9100);
            $printer = new Printer($connector);

            // 🧾 Example receipt content
            $printer->setEmphasis(true);
            $printer->text("MY SHOP NAME\n");
            $printer->setEmphasis(false);
            $printer->text("Date: " . date('Y-m-d H:i:s') . "\n");
            $printer->text("----------------------------\n");
            $printer->text("Item 1       1 x 100  = 100\n");
            $printer->text("Item 2       2 x 50   = 100\n");
            $printer->text("----------------------------\n");
            $printer->text("Total                = 200\n");
            $printer->feed(2);
            $printer->cut();
            $printer->close();

            return redirect()->back()->with('success', 'Receipt printed!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Print failed: ' . $e->getMessage());
        }
    }
}


