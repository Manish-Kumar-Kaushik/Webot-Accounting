<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Bill;
use App\Models\Category;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $company = Company::first() ?? new Company(['name' => 'WebotApp Accounting', 'currency_symbol' => '₹', 'currency_code' => 'INR']);

        // KPI 1: Bank & Cash Balances
        $totalCash = BankAccount::sum('current_balance');
        $bankAccounts = BankAccount::orderByDesc('is_default')->get();

        // KPI 2: Total Receivables (Unpaid & Partial Invoices)
        $totalReceivables = Invoice::whereIn('status', ['draft', 'sent', 'partial', 'overdue'])->sum('due_amount');

        // KPI 3: Total Payables (Unpaid & Partial Bills)
        $totalPayables = Bill::whereIn('status', ['draft', 'received', 'partial', 'overdue'])->sum('due_amount');

        // KPI 4: Net Profit (All-time or YTD)
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        // Chart 1: Cash Flow (Last 6 Months Income vs Expense)
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthKey = $monthDate->format('M Y');
            $year = $monthDate->year;
            $month = $monthDate->month;

            $months[] = $monthKey;

            $inc = Transaction::where('type', 'income')
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $exp = Transaction::where('type', 'expense')
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $incomeData[] = (float) $inc;
            $expenseData[] = (float) $exp;
        }

        // Chart 2: Expenses by Category (Donut)
        $expensesByCategory = Transaction::where('type', 'expense')
            ->whereNotNull('category_id')
            ->select('category_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $categoryLabels = [];
        $categorySeries = [];
        $categoryColors = [];

        foreach ($expensesByCategory as $item) {
            $categoryLabels[] = $item->category->name ?? 'Uncategorized';
            $categorySeries[] = (float) $item->total_amount;
            $categoryColors[] = $item->category->color ?? '#10b981';
        }

        // Recent Invoices
        $recentInvoices = Invoice::with('customer')->latest()->take(5)->get();

        // Recent Transactions
        $recentTransactions = Transaction::with(['bankAccount', 'customer', 'vendor', 'category'])
            ->latest('transaction_date')
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'company',
            'totalCash',
            'bankAccounts',
            'totalReceivables',
            'totalPayables',
            'totalIncome',
            'totalExpense',
            'netProfit',
            'months',
            'incomeData',
            'expenseData',
            'categoryLabels',
            'categorySeries',
            'categoryColors',
            'recentInvoices',
            'recentTransactions'
        ));
    }
}
