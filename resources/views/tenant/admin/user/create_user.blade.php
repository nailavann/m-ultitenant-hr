@extends('tenant.layouts.app')

@section('title', 'Kullanıcı Oluştur')

@section('content')

    <h4>Kullanıcı Oluştur</h4>
    <form id="create-user-form" action="{{ route('tenant.store.user') }}" method="POST">
        @csrf
        <div>
            <h3>Adım 1</h3>
            <div class="mb-3">
                <label for="name" class="form-label">Ad</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
        </div>
        <div>
            <h3>Adım 2</h3>
            <div class="mb-3">
                <label for="surname" class="form-label">Soyad</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>
        </div>
        <div>
            <h3>Adım 3</h3>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Oluştur</button>
    </form>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#create-user-form").steps({
                headerTag: "h3",
                bodyTag: "div",
                transitionEffect: "fade",
                autoFocus: true,
                labels: {
                    next: 'İleri',
                    previous: 'Geri',
                    finish: 'Tamamla'
                }
            });
        });
    </script>
@endsection
