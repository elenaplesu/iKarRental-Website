<?php
session_start();
require_once 'functions.php';


$carId = $_GET['id'] ?? null;
$filename = 'cars.json';
$bookingsFilename = 'bookings.json';
$errors = [];


$cars = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];


$car = null;
foreach ($cars as $c) {
    if ($c['id'] == $carId) {
        $car = $c;
        break;
    }
}

if (!$car) {
    header('Location: index.php');
    exit;
}


$bookings = file_exists($bookingsFilename) ? json_decode(file_get_contents($bookingsFilename), true) : [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['email'])) {
        
        header('Location: login.php');
        exit;
    }

    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    
    if (empty($startDate) || empty($endDate)) {
        $errors[] = "Both start date and end date are required.";
    } elseif (strtotime($startDate) >= strtotime($endDate)) {
        $errors[] = "End date must be later than start date.";
    }

    if (empty($errors)) {
        
        foreach ($bookings as $booking) {
            if ($booking['car_id'] == $car['id'] &&
                (strtotime($startDate) < strtotime($booking['end_date']) && strtotime($endDate) > strtotime($booking['start_date']))) {
                $errors[] = "The car is already booked for these dates.";
                break;
            }
        }

        if (empty($errors)) {
            $booking = [
                'email' => $_SESSION['email'],
                'car_id' => $car['id'],
                'car_brand' => $car['brand'],
                'car_model' => $car['model'],
                'start_date' => $startDate,
                'end_date' => $endDate
            ];

            $bookings[] = $booking;
            file_put_contents($bookingsFilename, json_encode($bookings, JSON_PRETTY_PRINT));

            
            $_SESSION['last_booking'] = $booking;

            
            header('Location: booking_success.php');
            exit;
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Car Details</h1>
            <div>
                <?php if (isset($_SESSION['email'])): ?>
                    <span class="me-3">You are logged in</span>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary me-2">Login</a>
                    <a href="register.php" class="btn btn-secondary">Register</a>
                <?php endif; ?>
            </div>
        </div>
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            
            <div class="card mx-auto" style="border: 1px solid #ddd; border-radius: 10px;">
                
                <div class="card-img-top" style="border-bottom: 1px solid #ddd;">
                    <img src="<?= htmlspecialchars($car['image']) ?>" 
                         class="img-fluid mx-auto d-block" 
                         alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>" 
                         style="border-radius: 10px 10px 0 0; width: 100%;">
                </div>
                
                <div class="card-body" style="text-align: left; border-radius: 0 0 10px 10px; padding-top: 20px;">
                    <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                    <p class="card-text"><strong>Year:</strong> <?= htmlspecialchars($car['year']) ?></p>
                    <p class="card-text"><strong>Transmission:</strong> <?= htmlspecialchars($car['transmission']) ?></p>
                    <p class="card-text"><strong>Fuel Type:</strong> <?= htmlspecialchars($car['fuel_type']) ?></p>
                    <p class="card-text"><strong>Passengers:</strong> <?= htmlspecialchars($car['passengers']) ?></p>
                    <p class="card-text"><strong>Price per Day:</strong> <?= htmlspecialchars($car['daily_price_huf']) ?> HUF</p>
                </div>
            </div>
            <br>

           
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

           
            <?php if (isset($_SESSION['email'])): ?>
                <h3>Book this Car</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Book Now</button>
                </form>
            <?php else: ?>
                <p class="alert alert-info">Please <a href="login.php">log in</a> to book this car.</p>
            <?php endif; ?>

            <a href="index.php" class="btn btn-secondary mt-3">Back to Cars</a>
        </div>
    </div>
</div>




        
    </div>
</body>
</html>
