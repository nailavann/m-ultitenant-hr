@extends('tenant.layouts.app')

@section('title', 'Tenant Dashboard')

@section('content')
    <style>
        .header {
            display: flex;
            justify-content: end;
            margin-bottom: 20px;
        }
    </style>

    <div class="header">
        <a href="{{ route('tenant.logout') }}" class="btn btn-primary">
            Çıkış Yap
        </a>
    </div>
    <h1>Dashboard</h1>
    <p>Hoş geldiniz!</p>
    <p>{{'Kayıtlı kullanıcı sayısı: ' . $users->count()}}</p>
@endsection
