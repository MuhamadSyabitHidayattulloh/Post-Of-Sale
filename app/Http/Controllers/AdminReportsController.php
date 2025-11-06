<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportsController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'today');
        [$start, $end] = $this->resolveRange($range, $request);

        // Sales summary
        $salesQuery = Transaction::whereBetween('created_at', [$start, $end])->where('status', 'paid');
        $totalSales = (float) $salesQuery->sum('total');
        $totalTransactions = (int) $salesQuery->count();
        $avgTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        $productsSold = (int) TransactionItem::whereHas('transaction', function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end])->where('status', 'paid');
        })->sum('quantity');

        // Products report
        $productRows = TransactionItem::selectRaw('product_id, SUM(quantity) as qty, SUM(total) as revenue')
            ->whereHas('transaction', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])->where('status', 'paid');
            })
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->with('product')
            ->limit(10)
            ->get();

        // Transactions table
        $transactions = $salesQuery->with(['cashier', 'member'])->orderByDesc('created_at')->limit(50)->get();

        // Members summary
        $totalMembers = (int) User::where('role', 'member')->count();
        $memberSales = (float) Transaction::whereBetween('created_at', [$start, $end])
            ->whereNotNull('member_id')->where('status', 'paid')->sum('total');
        $avgPerMember = $totalMembers > 0 ? $memberSales / $totalMembers : 0;

        // Chart series (daily totals within range)
        $chartLabels = [];
        $chartData = [];
        $cursor = $start->copy()->startOfDay();
        while ($cursor <= $end) {
            $chartLabels[] = $cursor->format('d M');
            $chartData[] = (float) Transaction::whereBetween('created_at', [$cursor->copy()->startOfDay(), $cursor->copy()->endOfDay()])
                ->where('status', 'paid')
                ->sum('total');
            $cursor->addDay();
        }

        // Top members by spend in range
        $memberRows = Transaction::selectRaw('member_id, COUNT(*) as trx_count, SUM(total) as spend, MAX(created_at) as last_at')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('member_id')
            ->where('status', 'paid')
            ->groupBy('member_id')
            ->orderByDesc('spend')
            ->with('member')
            ->limit(10)
            ->get();

        return view('admin.reports', compact(
            'range', 'start', 'end',
            'totalSales', 'totalTransactions', 'avgTransaction', 'productsSold',
            'productRows', 'transactions', 'totalMembers', 'memberSales', 'avgPerMember',
            'chartLabels', 'chartData', 'memberRows'
        ));
    }

    

    private function resolveRange(string $range, Request $request): array
    {
        $now = Carbon::now();
        return match ($range) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            'week' => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
            'month' => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
            'custom' => [
                Carbon::parse($request->get('start', $now->copy()->startOfDay())),
                Carbon::parse($request->get('end', $now->copy()->endOfDay()))
            ],
            default => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
        };
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $type = $request->get('type', 'transactions'); // sales|products|transactions|members
        $range = $request->get('range', 'today');
        [$start, $end] = $this->resolveRange($range, $request);

        $filename = sprintf('laporan-%s-%s.csv', $type, now()->format('Ymd_His'));

        $response = new StreamedResponse(function () use ($type, $start, $end) {
            $out = fopen('php://output', 'w');
            // Optional: UTF-8 BOM for Excel
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($type === 'products') {
                fputcsv($out, ['Produk', 'SKU', 'Terjual', 'Total Penjualan', 'Stok Tersisa']);
                $rows = TransactionItem::selectRaw('product_id, SUM(quantity) as qty, SUM(total) as revenue')
                    ->whereHas('transaction', function ($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end])->where('status', 'paid');
                    })
                    ->groupBy('product_id')
                    ->orderByDesc('qty')
                    ->with('product')
                    ->cursor();
                foreach ($rows as $row) {
                    $p = $row->product;
                    fputcsv($out, [
                        $p->name ?? '-',
                        $p->sku ?? '-',
                        (int) $row->qty,
                        (float) $row->revenue,
                        (int) ($p->stock ?? 0),
                    ]);
                }
            } elseif ($type === 'sales') {
                fputcsv($out, ['Tanggal', 'Total Penjualan']);
                $cursor = $start->copy()->startOfDay();
                while ($cursor <= $end) {
                    $total = (float) Transaction::whereBetween('created_at', [$cursor->copy()->startOfDay(), $cursor->copy()->endOfDay()])
                        ->where('status', 'paid')
                        ->sum('total');
                    fputcsv($out, [$cursor->format('Y-m-d'), $total]);
                    $cursor->addDay();
                }
            } elseif ($type === 'members') {
                fputcsv($out, ['Member', 'Tier', 'Total Transaksi', 'Total Belanja', 'Terakhir Belanja']);
                $rows = Transaction::selectRaw('member_id, COUNT(*) as trx_count, SUM(total) as spend, MAX(created_at) as last_at')
                    ->whereBetween('created_at', [$start, $end])
                    ->whereNotNull('member_id')
                    ->where('status', 'paid')
                    ->groupBy('member_id')
                    ->orderByDesc('spend')
                    ->with(['member.tier'])
                    ->cursor();
                foreach ($rows as $row) {
                    $m = $row->member;
                    fputcsv($out, [
                        $m->name ?? '-',
                        optional($m->tier)->name ?? '-',
                        (int) $row->trx_count,
                        (float) $row->spend,
                        Carbon::parse($row->last_at)->format('Y-m-d H:i:s'),
                    ]);
                }
            } else { // transactions (default)
                fputcsv($out, ['Kode', 'Tanggal', 'Kasir', 'Member', 'Total', 'Metode', 'Status']);
                $rows = Transaction::whereBetween('created_at', [$start, $end])->where('status', 'paid')
                    ->with(['cashier', 'member'])
                    ->orderBy('created_at')
                    ->cursor();
                foreach ($rows as $trx) {
                    fputcsv($out, [
                        $trx->code,
                        optional($trx->created_at)->format('Y-m-d H:i:s'),
                        $trx->cashier->name ?? '-',
                        $trx->member->name ?? '-',
                        (float) $trx->total,
                        $trx->payment_method,
                        $trx->status,
                    ]);
                }
            }

            fclose($out);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
