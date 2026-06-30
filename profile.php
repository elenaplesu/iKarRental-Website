<?php
session_start();


if (!isset($_SESSION['email'])) {
    
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email']; 

$usersFilename = 'users.json';
$users = file_exists($usersFilename) ? json_decode(file_get_contents($usersFilename), true) : [];


$user = null;
foreach ($users as $registeredUser) {
    if ($registeredUser['email'] === $email) {
        $user = $registeredUser;
        break;
    }
}

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$fullName = $user['full_name'];
$isAdmin = isset($user['is_admin']) && $user['is_admin'] === true;


$bookingsFilename = 'bookings.json';
$bookings = file_exists($bookingsFilename) ? json_decode(file_get_contents($bookingsFilename), true) : [];

if ($isAdmin) {
    $userBookings = $bookings; 
} else {
    $userBookings = array_filter($bookings, function($booking) use ($email) {
        return $booking['email'] === $email;
    });
}


if ($isAdmin && isset($_GET['delete_booking'])) {
    $carBrand = $_GET['car_brand'];
    $carModel = $_GET['car_model'];
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    $bookings = array_filter($bookings, function($booking) use ($carBrand, $carModel, $startDate, $endDate) {
        return !(
            $booking['car_brand'] === $carBrand &&
            $booking['car_model'] === $carModel &&
            $booking['start_date'] === $startDate &&
            $booking['end_date'] === $endDate
        );
    });
    
    
    $bookings = array_values($bookings);
    
    
    file_put_contents($bookingsFilename, json_encode($bookings, JSON_PRETTY_PRINT));
    
    header('Location: profile.php'); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1>Welcome, <?= htmlspecialchars($fullName) ?>!</h1>

        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Email: <?= htmlspecialchars($email) ?></h5>
                <p class="card-text">You are logged in.</p>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        
        <h3>Bookings</h3>
        <?php if (empty($userBookings)): ?>
            <p>No bookings yet.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <?php if ($isAdmin): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userBookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']) ?></td>
                            <td><?= htmlspecialchars($booking['start_date']) ?></td>
                            <td><?= htmlspecialchars($booking['end_date']) ?></td>
                            <?php if ($isAdmin): ?>
                                <td>
                                    <a href="profile.php?delete_booking=1&car_brand=<?= urlencode($booking['car_brand']) ?>&car_model=<?= urlencode($booking['car_model']) ?>&start_date=<?= urlencode($booking['start_date']) ?>&end_date=<?= urlencode($booking['end_date']) ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
</body>
</html>
