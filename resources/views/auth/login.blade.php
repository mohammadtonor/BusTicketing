<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 ">
                <div class="card">
                    <div class="card-header text-center">Admin Login</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.login.submit') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" required
                                    autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                            <!-- Link to Register Page -->
                            <div class="form-group mt-3">
                                <p class="mb-0">
                                    Don't have an account? <a href="{{ route('user.register') }}">Register here</a>.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
