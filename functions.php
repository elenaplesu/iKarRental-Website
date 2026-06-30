<?php
function loadCarsFromJson($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    $jsonData = file_get_contents($filePath);
    return json_decode($jsonData, true);
}

function getCarDetails($cars, $id) {
    foreach ($cars as $car) {
        if ($car['id'] == $id) {
            return $car;
        }
    }
    return null;
}

function loadBookingsFromJson($filename) {
    
    if (!file_exists($filename)) {
        return [];
    }
    
    
    $jsonData = file_get_contents($filename);
    
    
    return json_decode($jsonData, true);
}

function filterCars($cars, $filters) {
    $bookings = loadBookingsFromJson('bookings.json');
    
    $filteredCars = $cars;
    if (!empty($filters['passengers'])) {
        $filteredCars = array_filter($filteredCars, function($car) use ($filters) {
            return $car['passengers'] >= $filters['passengers'];
        });
    }

    
    if (!empty($filters['transmission'])) {
        $filteredCars = array_filter($filteredCars, function($car) use ($filters) {
            return $car['transmission'] === $filters['transmission'];
        });
    }

    
    if (!empty($filters['daily_price_huf'])) {
        $filteredCars = array_filter($filteredCars, function($car) use ($filters) {
            return $car['daily_price_huf'] <= $filters['daily_price_huf'];
        });
    }

    
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $startDate = strtotime($filters['start_date']);
        $endDate = strtotime($filters['end_date']);
        
        $filteredCars = array_filter($filteredCars, function($car) use ($startDate, $endDate, $bookings) {
           
            foreach ($bookings as $booking) {
                if ($booking['car_id'] == $car['id']) {
                    $bookingStartDate = strtotime($booking['start_date']);
                    $bookingEndDate = strtotime($booking['end_date']);
                    
                    
                    if (($startDate < $bookingEndDate) && ($endDate > $bookingStartDate)) {
                        return false; 
                    }
                }
            }
            return true; 
        });
    }

    return $filteredCars;
}

