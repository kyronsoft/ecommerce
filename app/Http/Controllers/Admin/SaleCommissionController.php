<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SaleCommission;
use App\Services\SaleCommissionService;
use App\Support\AdminPanelScope;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SaleCommissionController extends Controller
{
    public function __construct(private readonly SaleCommissionService $saleCommissions)
    {
    }

    public function index(Request $request): View
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest($request);

        $this->saleCommissions->syncForPaidOrders();

        $query = AdminPanelScope::scopeSaleCommissions(SaleCommission::query(), $adminStore, $isSuperAdmin)
            ->with(['order.customer', 'orderItem', 'product', 'store', 'paymentTransaction'])
            ->latest();

        $sales = (clone $query)->paginate(15);
        $allSales = (clone $query)->get();

        return view('admin.sale-commissions.index', [
            'sales' => $sales,
            'stats' => [
                'total' => $allSales->count(),
                'approved' => $allSales->where('payment_status', 'approved')->count(),
                'sales_amount' => (float) $allSales->sum(fn (SaleCommission $sale) => (float) $sale->sale_amount),
                'deductions_amount' => (float) $allSales->sum(fn (SaleCommission $sale) => (float) $sale->total_deduction_amount),
                'net_amount' => (float) $allSales->sum(fn (SaleCommission $sale) => (float) $sale->entrepreneur_net_amount),
            ],
            'isSuperAdmin' => $isSuperAdmin,
            'epaycoRateLabel' => number_format($this->saleCommissions->epaycoPercentageRate() * 100, 2, ',', '.').'%',
            'epaycoFixedLabel' => '$'.number_format($this->saleCommissions->epaycoFixedFee(), 0, ',', '.'),
        ]);
    }
}
