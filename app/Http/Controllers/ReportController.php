<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Bill;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfYear()->toDateString());
        $endDate = $request->input('end_date', now()->endOfYear()->toDateString());

        // Invoiced Income
        $totalInvoiced = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'partial', 'sent', 'viewed'])
            ->sum('total');

        // Billed Expenses
        $totalBilled = Bill::whereBetween('bill_date', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'partial', 'received'])
            ->sum('total');

        // Cash flow transactions
        $cashIncome = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $cashExpense = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        // Grouped expenses by category
        $expensesByCategory = Category::where('type', 'expense')
            ->withSum(['transactions' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            }], 'amount')
            ->get();

        $netProfit = $totalInvoiced - $totalBilled;
        $netCashFlow = $cashIncome - $cashExpense;

        return view('reports.profit_loss', compact(
            'startDate', 'endDate', 'totalInvoiced', 'totalBilled',
            'cashIncome', 'cashExpense', 'expensesByCategory', 'netProfit', 'netCashFlow'
        ));
    }

    public function incomeExpense(Request $request)
    {
        $year = $request->input('year', now()->year);

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = sprintf('%04d-%02d-01', $year, $m);
            $monthEnd = date('Y-m-t', strtotime($monthStart));

            $income = Transaction::where('type', 'income')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expense = Transaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $monthlyData[] = [
                'month' => date('M', strtotime($monthStart)),
                'income' => (float) $income,
                'expense' => (float) $expense,
                'profit' => (float) ($income - $expense),
            ];
        }

        return view('reports.income_expense', compact('year', 'monthlyData'));
    }

    public function taxSummary(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfYear()->toDateString());
        $endDate = $request->input('end_date', now()->endOfYear()->toDateString());

        $collectedTax = Invoice::whereBetween('invoice_date', [$startDate, $endDate])->sum('tax_total');
        $paidTax = Bill::whereBetween('bill_date', [$startDate, $endDate])->sum('tax_total');
        $netTaxDue = $collectedTax - $paidTax;

        $taxes = Tax::all();

        return view('reports.tax_summary', compact('startDate', 'endDate', 'collectedTax', 'paidTax', 'netTaxDue', 'taxes'));
    }
}
