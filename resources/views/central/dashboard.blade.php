@extends('central.layouts.app')

@section('title', 'Home Dashboard')

@section('content')
    <style>
        .header {
            display: flex;
            justify-content: end;
            margin-bottom: 20px;
        }
    </style>

    <div class="header">
        <a href="{{ route('central.logout') }}" class="btn btn-primary">
            Çıkış Yap
        </a>
    </div>
    <h1>Dashboard</h1>
    <p>Hoş geldiniz! Bu sizin yönetim paneliniz.</p>
@endsection
