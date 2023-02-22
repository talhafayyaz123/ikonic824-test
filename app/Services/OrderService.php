<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {
        $this->affiliateService = $affiliateService;
    }

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method
        $customer_email = $data['customer_email'];
        $count = Affiliate::whereHas('orders', function ($query) use ($customer_email) {
            $query->where('customer_email', $customer_email)->count();
        });

        $order = Order::with('merchant')->where('id', $data['order_id'])->first();
        if ($count == 0) {
            $this->affiliateService->register($order->merchant, $customer_email, $data['customer_name'], 0);
        }
    }
}
