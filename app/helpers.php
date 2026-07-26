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

