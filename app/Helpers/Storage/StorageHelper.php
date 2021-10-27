<?php

namespace App\Helpers\Storage;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

/**
 * Storage helper class
 */
class StorageHelper
{
    /**
     * Formatting product data for saving to storage.
     *
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

    /**
     * Merging data about goods for the warehouse from which goods are transferred
     *
     * @param Collection $products
     * @param array $data
     * @return array
     */
    public static function mergingDataProductsFrom(Collection $products, array $data): array
    {
        $output = [];
        $found = false;
        foreach ($products as $product) {
            foreach ($data as $datum) {
                if ($product->id === $datum['id']) {
                    $found = true;
                    $result = $product->pivot->count - $datum['count'];
                    if ($result > 0) {
                        $output[$product->id] = [
                            'count' => $result
                        ];
                    }
                }
            }
            if (!$found) {
                $output[$product->id] = [
                    'count' => $product->pivot->count,
                ];
            }
            $found = false;
        }
        return $output;
    }

    /**
     * Get data about the goods for the warehouse to which the goods are transferred
     *
     * @param array $data
     * @return array
     */
    public static function getDataProductsTo(array $data): array
    {
        $output = [];
        foreach ($data as $datum) {
            $output[$datum['id']] = [
                'count' => $datum['count'],
            ];
        }
        return $output;
    }
}
