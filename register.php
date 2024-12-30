<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <form method="post" action="register_action.php">
            <div class="input">
                <input type="text" class="element-input" name="nama_pengguna" placeholder="Username"
                    autocomplete="username" required>
            </div>
            <div class="input">
                <input type="password" class="element-input" name="password_pengguna" placeholder="Password"
                    autocomplete="new-password" required>
            </div>
            <div class="input">
                <input type="email" class="element-input" name="email_pengguna" placeholder="Email" autocomplete="email"
                    required>
            </div>
            <div class="input">
                <button type="submit" name="register" class="button-login">Register</button>
            </div>
        </form>
    </div>
</body>

</html>