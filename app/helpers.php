<?php

use App\Models\PaymentTransaction;
use App\Models\Sale;

function totalPaid($saleId){
    return PaymentTransaction::where('sale_id',$saleId)->sum('paid');
}
function paymentHistoryTotal($customerId){
    return $query = Sale::where('customer_id',$customerId)->sum('grand_total');
}
function paymentHistoryPaid($customerId){
    return $query = PaymentTransaction::where('customer_id',$customerId)->sum('paid');
}
function paymentHistoryDue($customerId){
    return paymentHistoryTotal($customerId) - paymentHistoryPaid($customerId);
}
function customerTotalSale($customerId, $fromDate, $toDate){
    return Sale::where('customer_id', $customerId)->whereBetween('date', [$fromDate, $toDate])->sum('total');
}
function customerTotalSaleDiscounted($customerId, $fromDate, $toDate){
    return Sale::where('customer_id', $customerId)->whereBetween('date', [$fromDate, $toDate])->sum('discount_amount');
}
function customerGrandTotalSale($customerId, $fromDate, $toDate){
    return Sale::where('customer_id', $customerId)->whereBetween('date', [$fromDate, $toDate])->sum('grand_total');
}
function customerTotalCollection($customerId, $fromDate, $toDate){
    return PaymentTransaction::where('customer_id', $customerId)->whereBetween('payment_date', [$fromDate, $toDate])->sum('paid');
}
function customerTotalDue($customerId, $fromDate, $toDate){
    return customerGrandTotalSale($customerId, $fromDate, $toDate) - customerTotalCollection($customerId, $fromDate, $toDate);
}
