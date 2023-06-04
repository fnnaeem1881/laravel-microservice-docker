<!-- dashboard.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @if(session('access_token'))
                <!-- Show logout menu item if the user is logged in -->
                <li class="nav-item">
                   <a href="{{route('logout.custom')}}">Logout</a>
                </li>
                @endif
            </ul>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Welcome to the Dashboard!</h5>
                        <p class="card-text">User ID: {{ $userId }}</p>
                        <p class="card-text">Email: {{ $email }}</p>
                        <p class="card-text">Name: {{ $name }}</p>
                        <!-- Include any other user information as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS (jQuery and Popper.js required) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
