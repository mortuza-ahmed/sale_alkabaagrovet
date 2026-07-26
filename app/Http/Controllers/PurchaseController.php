<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\StockLog;
use App\Models\Supplier;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with('supplier','items')->latest()->get();
        return view('backend.pages.purchase.index',compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::get();
        $products = Product::get();
        return view('backend.pages.purchase.create', compact('suppliers','products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'total_amount' => array_sum($request->subtotal),
        ]);
        foreach ($request->product_id as $index => $productId) {
            $purchase->items()->create([
                'product_id'   => $productId,
                'quantity'     => $request->quantity[$index],
                'buying_price' => $request->buying_price[$index],
                'subtotal'     => $request->quantity[$index] * $request->buying_price[$index],
            ]);
            $stock = Stock::firstOrCreate(
                ['product_id' => $productId],
                ['quantity' => 0]
            );
            $stock->quantity += $request->quantity[$index];
            $stock->save();
            $stockLog = StockLog::create([
                'supplier_id' => $request->supplier_id,
                'purchase_id' => $purchase->id,
                'product_id' => $productId,
                'quantity' => $request->quantity[$index],
                'date' => date('Y-m-d'),
                'type' => 'in',
            ]);
        }
        return redirect()->route('purchases.print', $purchase->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::with('supplier','items.product')->findOrFail($id);
        return view('backend.pages.purchase.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $suppliers = Supplier::get();
        $products = Product::get();
        $purchase = Purchase::with('supplier','items.product')->findOrFail($id);
        return view('backend.pages.purchase.edit', compact('suppliers','products','purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchase = Purchase::findOrFail($id);
        foreach ($purchase->items as $item) {
            Stock::where('product_id', $item->product_id)->decrement('quantity', $item->quantity);
        }
        $purchase->items()->delete();
        StockLog::where('purchase_id', $purchase->id)->delete();
        $purchase->update([
            'supplier_id'  => $request->supplier_id,
            'purchase_date'=> $request->purchase_date,
            'total_amount' => array_sum($request->subtotal),
        ]);
        foreach ($request->product_id as $i => $productId) {
            $qty   = $request->quantity[$i];
            $price = $request->buying_price[$i];
            $purchase->items()->create([
                'product_id'   => $productId,
                'quantity'     => $qty,
                'buying_price' => $price,
                'subtotal'     => $qty * $price,
            ]);
            Stock::firstOrCreate(
                ['product_id' => $productId],
                ['quantity' => 0]
            )->increment('quantity', $qty);
            StockLog::create([
                'supplier_id' => $request->supplier_id,
                'purchase_id' => $purchase->id,
                'product_id'  => $productId,
                'quantity'    => $qty,
                'date'        => now()->toDateString(),
                'type'        => 'in',
            ]);
        }
        return redirect()->route('purchases.print', $purchase->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();
        return redirect()->route('purchases.index')->with('error','Purchase deleted successfully');
    }

    public function print($id){
        $purchase = Purchase::with('supplier','items.product')->findOrFail($id);
        $amount = (float) $purchase->total_amount;
        $words = $this->numberToWordsBD($amount);
        return view('backend.pages.purchase.print', compact('purchase','words'));
    }

    public function reports(Request $request)
    {
        $fromDate = $request->query('from_date') ?? date('Y-m-d');
        $toDate = $request->query('to_date') ?? date('Y-m-d');
        $purchases = Purchase::with('supplier', 'items')->whereBetween('purchase_date', [$fromDate, $toDate])->get();
        return view('backend.pages.purchase.report', compact('purchases', 'fromDate', 'toDate'));
    }
    public function numberToWordsBD($amount)
    {
        $ones = [
            0 => '',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen'
        ];

        $tens = [
            2 => 'twenty',
            3 => 'thirty',
            4 => 'forty',
            5 => 'fifty',
            6 => 'sixty',
            7 => 'seventy',
            8 => 'eighty',
            9 => 'ninety'
        ];

        $num = floor($amount);
        $paisa = round(($amount - $num) * 100);

        $convert = function ($num) use (&$convert, $ones, $tens) {
            $str = '';
            if ($num >= 10000000) {
                $str .= $convert(intval($num / 10000000)) . ' crore ';
                $num %= 10000000;
            }
            if ($num >= 100000) {
                $str .= $convert(intval($num / 100000)) . ' lakh ';
                $num %= 100000;
            }
            if ($num >= 1000) {
                $str .= $convert(intval($num / 1000)) . ' thousand ';
                $num %= 1000;
            }
            if ($num >= 100) {
                $str .= $convert(intval($num / 100)) . ' hundred ';
                $num %= 100;
            }
            if ($num > 0) {
                if ($num < 20) {
                    $str .= $ones[$num];
                } else {
                    $str .= $tens[intval($num / 10)];
                    if ($num % 10) {
                        $str .= ' ' . $ones[$num % 10];
                    }
                }
            }
            return trim($str);
        };
        $words = $convert($num);
        if ($paisa > 0) {
            $words .= ' and ' . $convert($paisa) . ' paisa';
        }
        return trim($words);
    }

}
