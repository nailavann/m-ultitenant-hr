@extends('central.layouts.app')

@section('title', 'Home Dashboard')

@section('content')

    <h1>Kiracılar</h1>
    <p>Hoş geldiniz! Bu sizin kiracı listeleme sayfanız.</p>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Ad</th>
                    <th scope="col">Domain</th>
                    <th scope="col">Detay</th> <!-- Yeni eklenen sütun -->
                </tr>
                </thead>
                <tbody>
                @foreach ($tenants as $tenant)
                    @foreach ($tenant->domains as $domain)
                        <tr>
                            <th scope="row">{{ $tenant->id }}</th>
                            <td>{{ $tenant->name }}</td>
                            <td>{{ $domain->domain }}</td>
                            <!-- Detay bağlantısı için -->
                            <td>
                                <a href="{{ route('central.detail.tenant', ['tenantId' => $tenant->id, 'domainId' => $domain->id]) }}">Detay</a>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    @if ($tenants->currentPage() > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $tenants->previousPageUrl() }}">Previous</a>
                        </li>
                    @endif

                    @if ($tenants->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $tenants->nextPageUrl() }}">Next</a>
                        </li>
                    @endif
                </ul>
            </nav>

        </div>
    </div>
@endsection
