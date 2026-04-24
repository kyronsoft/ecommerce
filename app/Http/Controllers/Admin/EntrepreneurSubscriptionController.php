<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Support\AdminPanelScope;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EntrepreneurSubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        [, , $isSuperAdmin] = AdminPanelScope::fromRequest($request);

        abort_unless($isSuperAdmin, 403);

        $subscriptionsQuery = PaymentTransaction::query()
            ->with('order.customer')
            ->where('request_payload->flow', 'entrepreneur_plan');

        $subscriptions = (clone $subscriptionsQuery)
            ->latest()
            ->paginate(12)
            ->through(function (PaymentTransaction $subscription) {
                $subscription->effective_status = $this->resolveEffectiveStatus($subscription);

                return $subscription;
            });

        $allSubscriptions = (clone $subscriptionsQuery)->get();
        $approvedCount = $allSubscriptions->filter(fn (PaymentTransaction $subscription) => $this->resolveEffectiveStatus($subscription) === 'approved')->count();
        $pendingCount = $allSubscriptions->filter(fn (PaymentTransaction $subscription) => $this->resolveEffectiveStatus($subscription) === 'pending')->count();
        $approvedSales = $allSubscriptions
            ->filter(fn (PaymentTransaction $subscription) => $this->resolveEffectiveStatus($subscription) === 'approved')
            ->sum(fn (PaymentTransaction $subscription) => (float) $subscription->amount);

        return view('admin/entrepreneur-subscriptions/index', [
            'subscriptions' => $subscriptions,
            'stats' => [
                'total' => $allSubscriptions->count(),
                'approved' => $approvedCount,
                'pending' => $pendingCount,
                'sales' => (float) $approvedSales,
            ],
        ]);
    }

    protected function resolveEffectiveStatus(PaymentTransaction $subscription): string
    {
        if (in_array($subscription->status, ['approved', 'rejected', 'failed_validation'], true)) {
            return $subscription->status;
        }

        $payload = array_merge(
            $subscription->response_payload ?? [],
            $subscription->confirmation_payload ?? [],
        );

        $responseText = strtolower((string) ($payload['x_response'] ?? $payload['x_transaction_state'] ?? ''));
        $responseCode = (int) ($payload['x_cod_transaction_state'] ?? $payload['x_cod_response'] ?? 0);

        if ($responseCode === 1 || str_contains($responseText, 'acept') || str_contains($responseText, 'aprob')) {
            return 'approved';
        }

        if ($responseCode === 2 || str_contains($responseText, 'rechaz')) {
            return 'rejected';
        }

        return $subscription->status ?: 'pending';
    }
}
