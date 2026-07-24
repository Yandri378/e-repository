@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Dashboard Dosen</p>
    <h1>Upload PKM dan penelitian dosen.</h1>
</section>

@include('dashboards.partials.role-workspace', [
    'categories' => ['pkm' => 'PKM', 'penelitian' => 'Penelitian'],
])

@include('dashboards.partials.cards', [
    'cardKeys' => ['pkm' => 'PKM', 'penelitian' => 'Penelitian'],
])
@include('dashboards.partials.approval-documents')
@include('dashboards.partials.documents')
@endsection
