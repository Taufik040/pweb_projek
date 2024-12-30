<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .card {
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            transition: transform 0.3s, background 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            transform: scale(1.05);
        }

        .show-password {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 73%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: #6c757d;
            transition: color 0.3s;
        }

        .show-password:hover {
            color: #343a40;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4 text-dark">Welcome Back</h3>
            <?php if (isset($_GET["login_gagal"])): ?>
                <div class="alert alert-danger" role="alert">
                    Login gagal! <br> Username atau Password salah
                </div>
            <?php endif; ?>
            <form method="post" action="validasi.php">
                <div class="mb-3">
                    <label for="nama_admin" class="form-label text-dark">Username</label>
                    <input type="text" class="form-control" id="nama_admin" name="nama_admin"
                        placeholder="Enter your username" required>
                </div>
                <div class="mb-3 position-relative">
                    <label for="password_admin" class="form-label text-dark">Password</label>
                    <input type="password" class="form-control" id="password_admin" name="password_admin"
                        placeholder="Enter your password" required>
                    <span class="show-password" onclick="togglePassword()">👁️</span>
                </div>
                <div class="d-grid">
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password_admin');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        }
    </script>
</body>

</html>