<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;


class MerchantController extends Controller
{
    public function __construct(
        MerchantService $merchantService
    ) {
    }

    /**
     * Useful order statistics for the merchant API.
     *
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        // TODO: Complete this method
        $from = $request->from;
        $to = $request->to;
        $order = Order::whereBetween('created_at', [$from, $to])->select(DB::raw('count(*) as count, commission_owed,sum(subtotal) as revenue '));
        return response()->json(['count', $order->count, 'commission_owed', $order->commission_owed, 'revenue', $order->revenue]);
    }
}
