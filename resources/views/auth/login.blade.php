<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventory</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="{{ asset('public/style.css') }}">
    <style>
        body {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #333;
            /* background-image: url("https://img.freepik.com/free-vector/realistic-neon-lights-background_23-2148907367.jpg?t=st=1730198482~exp=1730202082~hmac=7d3d661add6b214f715634983c4c84ac80ab6895ff19b17570a5bd120a470b53&w=996");
            background-image: cover; */
        }

        .container {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>

<body style="" >
    <div class="container">
        <div class="container-fluid">
            <div class="">
                <div class="card">
                    <div class="p-4">
                        <h4 style="text-align: center; font-size:2rem; margin-bottom:30px; color: #3C50E0">Sales
                            Inventory</h4>
                        <form method="POST" action="{{ route('login') }}" autocomplete="off">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="">Email </label>
                                <input name='email' type='email' value="admin@gmail.com"
                                    class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="">Password </label>
                                <input name='password' value="12345678" type='password' autocomplete="new-password"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button style="padding: 0.4rem 1.2rem; font-size:20px" class="btn btn-primary"
                                    type="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
