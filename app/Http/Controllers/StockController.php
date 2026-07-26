<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockLog;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(){
        $stocks = Stock::with('product')->latest()->get();
        return view('backend.pages.stock.index',compact('stocks'));
    }
    public function log(){
        $stockLogs = StockLog::with('supplier','customer','purchase','sale','product')->latest()->get();
        return view('backend.pages.stock.log',compact('stockLogs'));
    }

}
