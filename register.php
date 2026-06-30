<?php
session_start();

$filename = 'users.json';
$errors = [];


$users = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirmPassword = $_POST['confirm_password'] ?? null;

   
    if (empty($fullName) || str_word_count($fullName) < 2) $errors[] = "Full Name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (empty($password)) $errors[] = "Password is required.";
    elseif (strlen($password) < 5) $errors[] = "Password must be at least 5 characters.";
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match.";

    
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $errors[] = "Email is already registered.";
            break;
        }
    }

    
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $users[] = [
            'full_name' => $fullName,
            'email' => $email,
            'password' => $hashedPassword,
            'is_admin' => false
        ];
        file_put_contents($filename, json_encode($users, JSON_PRETTY_PRINT));
        $_SESSION['email'] = $email;

        
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Register</h1>

        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <form method="POST">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" >
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" >
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" >
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" >
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <div class="mt-3">
            <a href="login.php" class="btn btn-secondary">Login</a>
            <a href="index.php" class="btn btn-outline-secondary">Back to Main Page</a>
        </div>
    </div>
</body>
</html>
