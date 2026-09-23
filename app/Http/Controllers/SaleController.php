<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\StockLog;
use Illuminate\Http\Request;


class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('customer', 'items')->latest()->get();
        return view('backend.pages.sale.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::get();
        $products = Product::get();
        return view('backend.pages.sale.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subtotal = array_sum($request->subtotal);
        // Parse the single discount field: "%..." from your blade -> use the hidden fields it fills
        $discountType  = $request->overall_discount_type ?: 'fixed';   // 'fixed' | 'percentage'
        $discountValue = $request->overall_discount_value ?: 0;
        $discountAmount = $discountType === 'percentage'
            ? ($subtotal * $discountValue) / 100
            : $discountValue;
        // Never let discount exceed the subtotal
        $discountAmount = min($discountAmount, $subtotal);
        $grandTotal     = $subtotal - $discountAmount;
        $sale = Sale::create([
            'customer_id'     => $request->customer_id,
            'payment_type'    => "cash",
            'order_date'      => $request->order_date,
            'delivery_date'   => $request->delivery_date,
            'total'           => $subtotal,
            'discount_type'   => $discountType,
            'discount_value'  => $discountValue,
            'discount_amount' => $discountAmount,
            'grand_total'     => $grandTotal,
            'date'            => date('Y-m-d'),
        ]);
        foreach ($request->product_id as $index => $productId) {
            $totalDeduct = ($request->quantity[$index] ?? 0) + ($request->bonus[$index] ?? 0) + ($request->bonus_facility[$index] ?? 0);
            $sale->items()->create([
                'product_id'     => $productId,
                'does_form'      => $request->does_form[$index],
                'quantity'       => $request->quantity[$index],
                'bonus'          => $request->bonus[$index],
                'price'          => $request->price[$index],
                'discount'       => $request->discount[$index],
                'sub_total'      => $request->subtotal[$index],
                'bonus_facility' => $request->bonus_facility[$index],
            ]);
            $stock = Stock::firstOrCreate(
                ['product_id' => $productId],
                ['quantity' => 0]
            );
            $stock->quantity = $stock->quantity - $totalDeduct;
            $stock->save();
            StockLog::create([
                'customer_id'    => $request->customer_id,
                'sale_id'        => $sale->id,
                'product_id'     => $productId,
                'quantity'       => $request->quantity[$index],
                'bonus'          => $request->bonus[$index],
                'bonus_facility' => $request->bonus_facility[$index],
                'date'           => date('Y-m-d'),
                'type'           => 'out',
            ]);
        }
        PaymentTransaction::create([
            'customer_id'    => $request->customer_id,
            'sale_id'        => $sale->id,
            'paid'           => $request->paid_amount,
            'payment_date'   => $sale->date,
            'payment_status' => "Initial Payment",
            'note'           => $request->note,
        ]);

        return redirect()->route('sales.print', $sale->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::with('customer', 'items.product')->findOrFail($id);
        return view('backend.pages.sale.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customers = Customer::get();
        $products = Product::get();
        $sale = Sale::with('customer', 'items.product', 'payment_transactions')->findOrFail($id);
        return view('backend.pages.sale.edit', compact('customers', 'products', 'sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if(!isset($request->product_id) && !$request->filled('product_id')){
            return redirect()->back()->with('error','At last one item needed');
        }
        $sale = Sale::findOrFail($id);
        // Restore stock for existing items first
        foreach ($sale->items as $oldItem) {
            $totalOld = ($oldItem->quantity ?? 0) + ($oldItem->bonus ?? 0) + ($oldItem->bonus_facility ?? 0);
            $stock = Stock::firstOrCreate(
                ['product_id' => $oldItem->product_id],
                ['quantity' => 0]
            );
            $stock->quantity += $totalOld; // restore stock
            $stock->save();

            // Delete old stock logs
            StockLog::where('sale_id', $sale->id)
                ->where('product_id', $oldItem->product_id)
                ->delete();
        }

        // Calculate totals with overall discount
        $subtotal = array_sum($request->subtotal);

        $discountType  = $request->overall_discount_type ?: 'fixed';
        $discountValue = $request->overall_discount_value ?: 0;

        $discountAmount = $discountType === 'percentage'
            ? ($subtotal * $discountValue) / 100
            : $discountValue;

        // Never let discount exceed the subtotal
        $discountAmount = min($discountAmount, $subtotal);
        $grandTotal     = $subtotal - $discountAmount;

        // Update sale info
        $sale->update([
            'customer_id'     => $request->customer_id,
            'payment_type'    => "cash",
            'order_date'      => $request->order_date,
            'delivery_date'   => $request->delivery_date,
            'total'           => $subtotal,
            'discount_type'   => $discountType,
            'discount_value'  => $discountValue,
            'discount_amount' => $discountAmount,
            'grand_total'     => $grandTotal,
            'date'            => date('Y-m-d'),
        ]);

        // Remove old items
        $sale->items()->delete();

        // Create new items & update stock
        foreach ($request->product_id as $index => $productId) {

            $totalDeduct = ($request->quantity[$index] ?? 0) + ($request->bonus[$index] ?? 0) + ($request->bonus_facility[$index] ?? 0);

            $sale->items()->create([
                'product_id'     => $productId,
                'does_form'      => $request->does_form[$index],
                'quantity'       => $request->quantity[$index],
                'bonus'          => $request->bonus[$index],
                'price'          => $request->price[$index],
                'discount'       => $request->discount[$index],
                'sub_total'      => $request->subtotal[$index],
                'bonus_facility' => $request->bonus_facility[$index],
            ]);

            // Update stock
            $stock = Stock::firstOrCreate(
                ['product_id' => $productId],
                ['quantity' => 0]
            );
            $stock->quantity -= $totalDeduct;
            $stock->save();

            // Create stock log
            StockLog::create([
                'customer_id'    => $request->customer_id,
                'sale_id'        => $sale->id,
                'product_id'     => $productId,
                'quantity'       => $request->quantity[$index],
                'bonus'          => $request->bonus[$index],
                'bonus_facility' => $request->bonus_facility[$index],
                'date'           => date('Y-m-d'),
                'type'           => 'out',
            ]);
        }

        $payment = PaymentTransaction::where(['sale_id' => $sale->id, 'customer_id' => $sale->customer_id])->first();
        $payment->update([
            'customer_id'    => $request->customer_id,
            'paid'           => $request->paid_amount,
            'payment_date'   => $sale->date,
            'payment_status' => "Initial Payment",
            'note'           => $request->note,
        ]);

        return redirect()->route('sales.print', $sale->id);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();
        return redirect()->route('sales.index')->with('error', 'Sale deleted successfully');
    }

    public function print($id)
    {
        $sale = Sale::with('customer', 'items.product')->findOrFail($id);
        $amount = (float) $sale->grand_total;
        $words = $this->numberToWordsBD($amount);
        $paymentTransaction = PaymentTransaction::where(['customer_id' => $sale->customer_id, 'sale_id' => $sale->id])->first();
        return view('backend.pages.sale.print', compact('sale', 'words', 'paymentTransaction'));
    }

    public function reports(Request $request)
    {
        $fromDate = $request->query('from_date') ?? date('Y-m-d');
        $toDate = $request->query('to_date') ?? date('Y-m-d');
        $sales = Sale::with('customer', 'items')->whereBetween('date', [$fromDate, $toDate])->latest()->get();
        return view('backend.pages.sale.report', compact('sales', 'fromDate', 'toDate'));
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
