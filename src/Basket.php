<?php

class Basket
{
    private array $products;
    private array $basket = [];
    private array $deliveryRules;
    private array $offers;

    /**
     * Initialize the basket with product catalog, delivery rules, and offers.
     */
    public function __construct(array $products, array $deliveryRules, array $offers)
    {
        $this->products = $products;
        $this->deliveryRules = $deliveryRules;
        $this->offers = $offers;
    }

    /**
     * Add a product to the basket.
     */
    public function add(string $productCode): void
    {
        if (!isset($this->products[$productCode])) {
            throw new InvalidArgumentException("Product code {$productCode} does not exist.");
        }
        $this->basket[] = $productCode;
    }

    /**
     * Calculate the total cost including offers and delivery charges.
     */
    public function total(): float
    {
        $subtotal = $this->calculateSubtotal();
        $discount = $this->applyOffers();
        $finalTotal = round($subtotal - $discount, 2); // Ensure rounding before applying delivery
        $delivery = $this->calculateDelivery($finalTotal);

        return round($finalTotal + $delivery, 2);
    }

    /**
     * Calculate the subtotal before any discounts.
     */
    private function calculateSubtotal(): float
    {
        $total = 0;
        foreach ($this->basket as $productCode) {
            $total = round($total + $this->products[$productCode]['price'], 2);
        }
        return $total;
    }

    /**
     * Apply applicable offers and return the discount amount.
     */
    private function applyOffers(): float
    {
        $discount = 0;

        // Get item counts
        $itemCounts = array_count_values($this->basket);

        // Offer: Buy one Red Widget (R01), get the second one at half price
        if (isset($this->offers['R01']) && isset($itemCounts['R01'])) {
            $redWidgets = $itemCounts['R01'];

            // Apply discount for every second Red Widget
            $discountedPairs = intdiv($redWidgets, 2);
            $discount = round($discountedPairs * ($this->products['R01']['price'] / 2), 2);
        }

        return round($discount, 2);
    }

    /**
     * Calculate delivery charges based on the final basket total after discounts.
     */
    private function calculateDelivery(float $finalTotal): float
    {
        if ($finalTotal <= 0) {
            return 0; // No delivery charge for an empty or zero-priced basket
        }

        foreach ($this->deliveryRules as $threshold => $charge) {
            if ($finalTotal < $threshold) {
                return round($charge, 2);
            }
        }

        return 0; // Free delivery for orders above the highest threshold 90
    }
}