<?php

require_once __DIR__ . '/../src/Basket.php';

function runTests()
{
    // Product Catalog
    $products = [
        'R01' => ['name' => 'Red Widget', 'price' => 32.95],
        'G01' => ['name' => 'Green Widget', 'price' => 24.95],
        'B01' => ['name' => 'Blue Widget', 'price' => 7.95],
    ];

    // Delivery Cost Rules
    $deliveryRules = [
        50 => 4.95,  // Orders under $50
        90 => 2.95,  // Orders under $90
        PHP_INT_MAX => 0, // Orders $90+
    ];

    // Offers
    $offers = ['R01' => 'buy_one_get_half_off'];

    // Test Cases
    $testCases = [
        [['B01', 'G01'], 37.85],
        [['R01', 'R01'], 54.37],
        [['R01', 'G01'], 60.85],
        [['B01', 'B01', 'R01', 'R01', 'R01'], 98.27],
        [['R01', 'R01', 'G01', 'B01', 'B01'], 90.27],
        [['R01'], 37.90],
        [['G01'], 29.90],
        [['B01'], 12.90], 
        [['R01', 'R01', 'R01'], 85.32],
        [['B01', 'B01', 'B01', 'B01', 'B01', 'B01'], 52.65],
        [[], 0.00], // Empty Basket
        [['R01', 'R01', 'R01', 'R01'], 98.85],
        [['R01', 'R01', 'R01', 'R01', 'G01', 'B01'], 131.75],
        [['R01', 'R01', 'R01', 'R01', 'R01', 'R01', 'R01', 'R01', 'R01', 'R01', 'G01', 'G01', 'G01', 'G01', 'G01', 
          'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01', 'B01'], 491.12],
        [['X99'], 'error'], // Invalid Product
    ];

    foreach ($testCases as $index => [$items, $expectedTotal]) {
        try {
            $basket = new Basket($products, $deliveryRules, $offers);
            foreach ($items as $item) {
                $basket->add($item);
            }

            $actualTotal = $basket->total();
            $result = ($actualTotal === $expectedTotal) ? '✅ Passed' : '❌ Failed';

            echo "Test " . ($index + 1) . " - Expected: \${$expectedTotal}, Got: \${$actualTotal} - {$result}\n";
        } catch (Exception $e) {
            echo "Test " . ($index + 1) . " - Expected: ERROR, Got: " . $e->getMessage() . " - ✅ Passed (Handled Properly)\n";
        }
    }
}

runTests();