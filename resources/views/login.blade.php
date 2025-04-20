<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #6f42c1;
            --light-purple: #e9ecef;
        }
        body {
            background: linear-gradient(135deg, var(--primary-purple) 0%, #8e44ad 100%);
            min-height: 100vh;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
        }
        .btn-primary:hover {
            background-color: #563d7c;
            border-color: #563d7c;
        }
        .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
        }
        .img-side {
            background-image: url('https://media.philstar.com/photos/2025/02/01/14_2025-02-01_21-04-26.jpg');
            background-size: cover;
            background-position: center;
            border-radius: 15px 0 0 15px;
            min-height: 600px;
        }
        .password-toggle {
            position: relative;
        }
        .password-toggle input {
            padding-right: 40px;
        }
        .password-toggle .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        .password-toggle .toggle-password:hover {
            color: var(--primary-purple);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-6 img-side"></div>
                        <div class="col-md-6">
                            <div class="card-body p-4">
                                <h3 class="text-center mb-4">Login</h3>
                                
                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('login.submit') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="password-toggle">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <i class="toggle-password bi bi-eye-slash" data-target="password"></i>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('bi-eye-slash');
                        this.classList.add('bi-eye');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('bi-eye');
                        this.classList.add('bi-eye-slash');
                    }
                });
            });
        });
    </script>
</body>
</html>
