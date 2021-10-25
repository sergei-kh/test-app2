<?php

namespace App\Helpers\Storage;

use Illuminate\Http\Request;

/**
 * Storage helper class
 */
class StorageHelper
{
    /**
     * Formatting product data for saving to storage
     * @param Request $request
     * @return array
     */
    public static function getDataProducts(Request $request): array
    {
        $output = [];
        $products = json_decode($request->products, true);
        foreach ($products as $product) {
            if ($product['count'] > 0) {
                $output[$product['id']] = ['count' => $product['count']];
            }
        }
        return $output;
    }
}
