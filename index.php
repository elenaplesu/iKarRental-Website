<?php
require_once 'functions.php';
session_start();


$cars = loadCarsFromJson('cars.json');


if ($_GET) {
    $cars = filterCars($cars, $_GET);
}


$fullName = null;
$isAdmin = false;

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

   
    $usersFilename = 'users.json';
    $users = file_exists($usersFilename) ? json_decode(file_get_contents($usersFilename), true) : [];

  
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $fullName = $user['full_name'];
            $isAdmin = isset($user['is_admin']) && $user['is_admin'] === true;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental - Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
       
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>iKarRental</h1>
            <div>
                <?php if (isset($_SESSION['email'])): ?>
                    
                    <div class="mb-3">
                    <span>Welcome, <?= htmlspecialchars($fullName) ?>!</span>
                    </div>
        
                    <div class="d-flex justify-content-end">
                    <a href="profile.php" class="btn btn-primary me-2">My Profile</a>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
                <?php else: ?>
                    
                    <a href="login.php" class="btn btn-primary me-2">Login</a>
                    <a href="register.php" class="btn btn-secondary">Register</a>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if ($isAdmin): ?>
            <div class="d-flex justify-content-end mb-4">
                <a href="add_car.php" class="btn btn-success">Add Car</a>
            </div>
        <?php endif; ?>

        <h2 class="text-center mb-4">Available Cars</h2>

       

<form method="GET" class="mb-4">
    <div class="row gy-3">
       
        <div class="col-12 col-md-2">
            <input type="number" name="passengers" class="form-control" placeholder="Min Passengers" value="<?= htmlspecialchars($_GET['passengers'] ?? '') ?>">
        </div>
        <div class="col-12 col-md-2">
            <select name="transmission" class="form-select">
                <option value="">Transmission</option>
                <option value="Automatic" <?= (isset($_GET['transmission']) && $_GET['transmission'] === 'Automatic') ? 'selected' : '' ?>>Automatic</option>
                <option value="Manual" <?= (isset($_GET['transmission']) && $_GET['transmission'] === 'Manual') ? 'selected' : '' ?>>Manual</option>
            </select>
        </div>
        <div class="col-12 col-md-2">
            <input type="number" name="daily_price_huf" class="form-control" placeholder="Max Price" value="<?= htmlspecialchars($_GET['daily_price_huf'] ?? '') ?>" step=1000>
        </div>
        <div class="col-12 col-md-2">
            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
        </div>
        <div class="col-12 col-md-2">
            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
        </div>
        
        <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>
<br>


        
        <div class="row">
            <?php foreach ($cars as $car): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($car['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                            <p class="card-text">Year: <?= htmlspecialchars($car['year']) ?></p>
                            <p class="card-text">Passengers: <?= htmlspecialchars($car['passengers']) ?></p>
                            <p class="card-text">Price: <?= htmlspecialchars($car['daily_price_huf']) ?> HUF/day</p>
                            <a href="car_details.php?id=<?= htmlspecialchars($car['id']) ?>" class="btn btn-info">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
