<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
</head>
<body>
<div class="container"
     style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f0f0;">
    <form action="{{ route('login') }}" method="post"
          style="width: 400px; background-color: #fff; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        @csrf
        <h1>{{tenant('name')}}</h1>
        <h2 style="text-align: center; margin-bottom: 20px;">Giriş Yap</h2>

        @if ($errors->any())
            <div class="alert alert-danger"
                 style="padding: 10px; margin-bottom: 20px; border-radius: 5px; background-color: #ffdddd;">
                <ul style="list-style: none; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li style="color: #a94442; margin-bottom: 5px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="email" style="display: block; margin-bottom: 5px;">E-posta Adresi:</label>
            <input type="email" class="form-control" id="email" name="email" required autofocus
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="password" style="display: block; margin-bottom: 5px;">Şifre:</label>
            <input type="password" class="form-control" id="password" name="password" required
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>


        <button type="submit" class="btn btn-primary"
                style="display: block; width: 100%; padding: 10px; background-color: #307bfe; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
            Giriş Yap
        </button>
    </form>
</div>

</body>
</html>
