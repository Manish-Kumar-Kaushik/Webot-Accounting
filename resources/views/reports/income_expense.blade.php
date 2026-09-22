@extends('layouts.app')

@section('title', 'Monthly Cash Flow Report')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Monthly Cash Flow Breakdown</h1>
            <p class="text-xs text-slate-400">12-month income vs expense performance</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.profit_loss') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white border border-slate-700">Profit & Loss</a>
            <a href="{{ route('reports.income_expense') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Monthly Cashflow</a>
            <a href="{{ route('reports.tax_summary') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white border border-slate-700">Tax Summary</a>
        </div>
    </div>

    <!-- Annual Table -->
    <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Month</th>
                        <th class="py-3.5 px-4 text-right">Income Cash In</th>
                        <th class="py-3.5 px-4 text-right">Expense Cash Out</th>
                        <th class="py-3.5 px-4 text-right">Net Flow</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @foreach($monthlyData as $row)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-4 font-bold text-white">{{ $row['month'] }} {{ $year }}</td>
                            <td class="py-3.5 px-4 text-right font-semibold text-emerald-400">{{ $currencySymbol }}{{ number_format($row['income'], 2) }}</td>
                            <td class="py-3.5 px-4 text-right font-semibold text-red-400">{{ $currencySymbol }}{{ number_format($row['expense'], 2) }}</td>
                            <td class="py-3.5 px-4 text-right font-bold {{ $row['profit'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $currencySymbol }}{{ number_format($row['profit'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
