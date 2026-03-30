@extends('layouts.pos-app')

@section('title', $title . ' — ' . config('app.name'))
@section('page-title', $title)

@section('page-actions')
    <button type="button" class="pos-btn-ghost">New {{ $title }}</button>
@endsection

@section('page-content')
    <section class="pos-stat-card">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $title }}</h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            This page uses the same POS layout style. Add your {{ strtolower($title) }} features here.
        </p>
    </section>
@endsection
