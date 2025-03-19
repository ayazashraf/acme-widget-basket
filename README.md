# Acme Widget Co - Basket Implementation

## Overview

This project implements a **shopping basket system** for Acme Widget Co using PHP. It includes:
- **Product management** (adding/removing items).
- **Pricing rules** (standard item pricing).
- **Discount offers** (Buy One Red Widget, Get One at Half Price).
- **Delivery fee calculations** (based on order value).

The basket system is built using a **well-structured, object-oriented approach**, making it **easy to read, maintain, and modify**.

---

## Features

- **Add products** to the basket.
- **Calculate total cost** dynamically with:
  - **Discounts** (Buy one Red Widget, get the second at half price).
  - **Delivery charges** based on order total.
- **Handles invalid product codes gracefully**.
- **Well-structured object-oriented PHP implementation** for easy expansion.

## Setup & Usage

### 1️⃣ Clone the Repository
Run the following command in your terminal:
    ```sh
    git clone https://github.com/ayazashraf/acme-widget-basket.git
    cd acme-widget-basket

### Run the test cases:
   ```sh
   php tests/BasketTest.php
   ```

If all tests pass, you will see output similar to:

Test 1 - Expected: $37.85, Got: $37.85 - ✅ Passed
Test 2 - Expected: $54.37, Got: $54.37 - ✅ Passed
...
Test 15 - Expected: ERROR, Got: Product code X99 does not exist. - ✅ Passed (Handled Properly)

## How the Code Works

### Basket Initialization

The Basket class is initialized with:

- A product catalog (list of available items and prices).
- Delivery cost rules (order-based shipping charges).
- Special offers (discount rules like "Buy One Get One Half Price").

### Adding Products to Basket

- $basket->add('R01');

### Calculating Subtotal

The subtotal is calculated by summing up the price of all items before applying any offer discounts.

### Applying Discounts

private function applyOffers(): float

- For every second R01 in the basket, a 50% discount is applied.
- No discount is applied to other products.

### Delivery Charges

- Orders below $50 → $4.95 shipping fee.
- Orders between $50 - $89.99 → $2.95 shipping fee.
- Orders of $90+ → Free shipping.

### Final Calculation

public function total(): float

- Applies discounts first.
- delivery fee last
- Ensures rounding consistency.

### Assumptions

The following assumptions have been made in the implementation:

- Discounts are applied before delivery charges.
- Only one discount rule exists (buy one red widge, get the second half price).
- All calculations are rounded to two decimal places.
- Invalid product codes trigger an error.
- Pricing, discounts, and delivery rules are configurable and can be modified in arrays.

## Test Cases & Results

The following test cases verify that the basket system calculates totals correctly.

| Test # | Products in Basket                     | Expected Total ($) | Actual Total ($) | Status  |
|--------|----------------------------------------|--------------------|------------------|---------|
| 1      | B01, G01                               | 37.85             | 37.85            | ✅ Passed  |
| 2      | R01, R01                               | 54.37             | 54.37            | ✅ Passed  |
| 3      | R01, G01                               | 60.85             | 60.85            | ✅ Passed  |
| 4      | B01, B01, R01, R01, R01                | 98.27             | 98.27            | ✅ Passed  |
| 5      | R01, R01, G01, B01, B01                | 90.27             | 90.27            | ✅ Passed  |
| 6      | R01                                    | 37.90             | 37.90            | ✅ Passed  |
| 7      | G01                                    | 29.90             | 29.90            | ✅ Passed  |
| 8      | B01                                    | 12.90             | 12.90            | ✅ Passed  |
| 9      | R01, R01, R01                          | 85.32             | 85.32            | ✅ Passed  |
| 10     | B01, B01, B01, B01, B01, B01           | 52.65             | 52.65            | ✅ Passed  |
| 11     | Empty Basket                           | 0.00              | 0.00             | ✅ Passed  |
| 12     | R01, R01, R01, R01                     | 98.85             | 98.85            | ✅ Passed  |
| 13     | R01, R01, R01, R01, G01, B01           | 131.75            | 131.75           | ✅ Passed  |
| 14     | Large Order (10x R01, 5x G01, 20x B01) | 491.12            | 491.12           | ✅ Passed  |
| 15     | Invalid Product Code (X99)             | Error Expected    | Handled Properly | ✅ Passed  |

### Notes:

- All test cases pass successfully.
- Edge cases like an empty basket and invalid products are handled.
- Discounts and delivery fees are correctly applied.

To manually run these tests, execute the following command:

    ```sh
    php tests/BasketTest.php
    ---

### Example Usage

Below is an example of how the basket system can be used in a real scenario:

    require_once 'src/Basket.php';

    // Define Products
    $products = [
        'R01' => ['name' => 'Red Widget', 'price' => 32.95],
        'G01' => ['name' => 'Green Widget', 'price' => 24.95],
        'B01' => ['name' => 'Blue Widget', 'price' => 7.95],
    ];

    // Define Delivery Rules
    $deliveryRules = [
        50 => 4.95,  
        90 => 2.95,  
        PHP_INT_MAX => 0, 
    ];

    // Define Offers
    $offers = ['R01' => 'buy_one_get_half_off'];

    // Create a new Basket
    $basket = new Basket($products, $deliveryRules, $offers);

    // Add Products
    $basket->add('R01');
    $basket->add('G01');
    $basket->add('R01');

    // Get Total
    $total = $basket->total();
    echo "Final Total: $" . number_format($total, 2);

Expected Output:

- Final Total: $60.85

## Author

Developed by Ayaz.
For questions, feel free to reach out!
