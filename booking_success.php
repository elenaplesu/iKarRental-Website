<?php
session_start();


if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}


$lastBooking = $_SESSION['last_booking'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1>Booking Successful!</h1>
        <p>Your booking has been confirmed. You will receive a confirmation email shortly.</p>

        <?php if ($lastBooking): ?>
            <div class="card my-4">
                <div class="card-body">
                    <h5 class="card-title">Car Details</h5>
                    <p><strong>Brand:</strong> <?= htmlspecialchars($lastBooking['car_brand']) ?></p>
                    <p><strong>Model:</strong> <?= htmlspecialchars($lastBooking['car_model']) ?></p>
                    <p><strong>Start Date:</strong> <?= htmlspecialchars($lastBooking['start_date']) ?></p>
                    <p><strong>End Date:</strong> <?= htmlspecialchars($lastBooking['end_date']) ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>No booking details available.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-primary">Back to Homepage</a>
    </div>
</body>
</html>
