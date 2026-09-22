@extends('layouts.app')

@section('title', 'Tax Summary Report')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Tax Liability Summary</h1>
            <p class="text-xs text-slate-400">Sales tax collected on customer invoices vs input tax paid on vendor bills</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.profit_loss') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white border border-slate-700">Profit & Loss</a>
            <a href="{{ route('reports.income_expense') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white border border-slate-700">Monthly Cashflow</a>
            <a href="{{ route('reports.tax_summary') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Tax Summary</a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <form method="GET" action="{{ route('reports.tax_summary') }}" class="glass-card p-4 rounded-2xl border border-slate-800 flex flex-wrap items-center gap-4 text-xs">
        <span class="text-slate-400 font-semibold">Period:</span>
        <input type="date" name="start_date" value="{{ $startDate }}" class="bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-emerald-500">
        <span class="text-slate-500">to</span>
        <input type="date" name="end_date" value="{{ $endDate }}" class="bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-emerald-500">
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-semibold shadow transition">Calculate Tax</button>
    </form>

    <!-- Tax Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="glass-card p-5 rounded-2xl border border-slate-800">
            <span class="text-xs text-slate-400 uppercase font-semibold">Output Tax Collected (Sales)</span>
            <h3 class="text-2xl font-bold text-emerald-400 mt-2">{{ $currencySymbol }}{{ number_format($collectedTax, 2) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">From issued customer invoices</p>
        </div>
        <div class="glass-card p-5 rounded-2xl border border-slate-800">
            <span class="text-xs text-slate-400 uppercase font-semibold">Input Tax Paid (Purchases)</span>
            <h3 class="text-2xl font-bold text-red-400 mt-2">{{ $currencySymbol }}{{ number_format($paidTax, 2) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">From vendor purchase bills</p>
        </div>
        <div class="glass-card p-5 rounded-2xl border border-slate-800">
            <span class="text-xs text-slate-400 uppercase font-semibold">Net Tax Liability Due</span>
            <h3 class="text-2xl font-bold text-white mt-2">{{ $currencySymbol }}{{ number_format($netTaxDue, 2) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Estimated remittance to authorities</p>
        </div>
    </div>
</div>
@endsection
