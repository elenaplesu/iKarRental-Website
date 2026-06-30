<?php
require_once 'storage.php';


$filename = 'cars.json';
$valid_transmissions = ['Automatic', 'Manual', 'Semi-Automatic'];


try {
    $storage = new Storage(new JsonIO($filename));
} catch (Exception $e) {
    die("Error initializing storage: " . $e->getMessage());
}

$errors = [];
$success = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'] ?? null;
    $model = $_POST['model'] ?? null;
    $year = $_POST['year'] ?? null;
    $transmission = $_POST['transmission'] ?? null;
    $fuel_type = $_POST['fuel_type'] ?? null;
    $passengers = $_POST['passengers'] ?? null;
    $daily_price_huf = $_POST['daily_price_huf'] ?? null;
    $image = $_POST['image'] ?? null;

    
    if (empty($brand)) $errors[] = "Brand is required.";
    if (empty($model)) $errors[] = "Model is required.";
    if (empty($year) || !filter_var($year, FILTER_VALIDATE_INT)) $errors[] = "Valid year is required.";
    if (empty($transmission)) $errors[] = "Transmission is required.";
    if (empty($fuel_type)) $errors[] = "Fuel type is required.";
    if (empty($passengers) || !filter_var($passengers, FILTER_VALIDATE_INT)) $errors[] = "Valid number of passengers is required.";
    if (empty($daily_price_huf) || !filter_var($daily_price_huf, FILTER_VALIDATE_FLOAT)) $errors[] = "Valid daily price is required.";
    if (empty($image) || !filter_var($image, FILTER_VALIDATE_URL)) $errors[] = "Valid image URL is required.";

    
    if (empty($errors)) {
        $new_car = [
            'brand' => $brand,
            'model' => $model,
            'year' => (int)$year,
            'transmission' => $transmission,
            'fuel_type' => $fuel_type,
            'passengers' => (int)$passengers,
            'daily_price_huf' => (float)$daily_price_huf,
            'image' => $image,
        ];

        $storage->add($new_car);
        $success = "Car added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Car</h1>
        
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        
        <form method="POST">
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input 
                    type="text" 
                    name="brand" 
                    id="brand" 
                    class="form-control" 
                    value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>" 
                    >
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input 
                    type="text" 
                    name="model" 
                    id="model" 
                    class="form-control" 
                    value="<?= htmlspecialchars($_POST['model'] ?? '') ?>" 
                    >
                </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input 
                    type="number" 
                    name="year" 
                    id="year" 
                    class="form-control" 
                    value="<?= htmlspecialchars($_POST['year'] ?? '') ?>" 
                    >
                </div>
                <div class="mb-3">
                <label for="transmission" class="form-label">Transmission</label>
                <select 
                    name="transmission" 
                    id="transmission" 
                    class="form-select" 
                    >
                    <option value="" disabled selected>Select transmission</option>
                    <?php foreach ($valid_transmissions as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= (isset($_POST['transmission']) && $_POST['transmission'] === $type) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fuel_type" class="form-label">Fuel Type</label>
                <input 
                    type="text" 
                    name="fuel_type" 
                    id="fuel_type" 
                    class="form-control" 
                    value="<?= htmlspecialchars($_POST['fuel_type'] ?? '') ?>" 
                    >
                </div>
            <div class="mb-3">
                <label for="passengers" class="form-label">Passengers</label>
                <input 
                    type="number" 
                    name="passengers" 
                    id="passengers" 
                    class="form-control" 
                    value="<?= htmlspecialchars($_POST['passengers'] ?? '') ?>" 
                    >
                </div>
            <div class="mb-3">
                <label for="daily_price_huf" class="form-label">Daily Price (HUF)</label>
                <input 
                    type="number" 
                    step="1000" 
                    name="daily_price_huf" 
                    id="daily_price_huf" 
                    class="form-control" 
                    value="<?= htmlspecialchars($_POST['daily_price_huf'] ?? '') ?>" 
                    >
                </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image URL</label>
                <input 
                    type="url" 
                    name="image" 
                    id="image" 
                    class="form-control" 
                    value="<?= htmlspecialchars($_POST['image'] ?? '') ?>" 
                    >
                </div>
            <button type="submit" class="btn btn-success">Add Car</button>
        </form>

        <!-- Back to Main Page Button -->
        <a href="index.php" class="btn btn-secondary mt-3">Back to Main Page</a>
        <br>
        <br>
    </div>
</body>
</html>
