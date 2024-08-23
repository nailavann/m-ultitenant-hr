@extends('central.layouts.app')

@section('title', 'Kiracı Oluştur')

@section('content')

    <h4>Müşteri Oluştur</h4>
    <form action="{{ route('central.store.tenant') }}" method="POST">
        @csrf
        @if ($errors->any())
            <div class="alert">
                <ul style="list-style: none; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <label for="tenant-name" class="form-label">Müşteri Adı</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="domain-name" class="form-label">Domain Adı</label>
            <input type="text" class="form-control" id="domainName" name="domainName" required>
        </div>
        <button type="submit" class="btn btn-primary">Oluştur</button>
    </form>

    <style>
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #ffdddd;
            color: #a94442;
        }
    </style>
@endsection


