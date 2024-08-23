<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.steps@1.1.1/dist/jquery-steps.min.css">



    <style>
        body {
            margin: 0;
            background-color: #f1f1f1;
        }

        .col-2 {
            background-color: #9b59b6;
            color: white;
            height: 100vh;
            padding: 20px;
        }

        .col-2 .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 0;
            display: block;
        }

        .col-2 .nav-link:hover {
            background-color: #495057;
            padding-left: 10px;
            transition: all 0.3s;
        }

        .col-10 {
            padding: 20px;
        }

    </style>
</head>
<body>
<div class="row">
    <div class="col-2">
        <a href="{{ route('tenant.dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('tenant.create.user') }}" class="nav-link">Kullanıcı Oluştur</a>
    </div>
    <div class="col-10">
        @yield('content')
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
