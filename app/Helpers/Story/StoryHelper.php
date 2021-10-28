<?php

namespace App\Helpers\Story;

use App\Models\Product;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Collection;

/**
 * Story helper class
 */
class StoryHelper
{
    /**
     * Get information about the goods that are in the warehouse from which the goods are transferred
     *
     * @param Collection $oldProducts
     * @param array $newProducts
     * @param array $selectProducts
     * @return array
     */
    public static function getInfoProductsFrom(Collection $oldProducts, array $newProducts, array $selectProducts): array
    {
        $output = [];
        $arrayId = [];
        foreach ($selectProducts as $product) {
            $arrayId[] = $product['id'];
        }
        foreach ($oldProducts as $item) {
            if (array_key_exists($item->id, $newProducts) && array_search($item->id, $arrayId) !== false) {
                $result = $item->pivot->count - $newProducts[$item->id]['count'];
                $output[$item->id] = [
                    'count' => $result,
                    'remainder' => $newProducts[$item->id]['count'],
                    'is_decreased' => true,
                    'is_increased' => false,
                    'is_deleted' => false,
                ];
            } elseif (!array_key_exists($item->id, $newProducts) && array_search($item->id, $arrayId) !== false) {
                $output[$item->id] = [
                    'count' => $item->pivot->count,
                    'remainder' => 0,
                    'is_decreased' => false,
                    'is_increased' => false,
                    'is_deleted' => true,
                ];
            }
        }
        return $output;
    }

    /**
     * Get information about the goods that are in the warehouse to which the goods are transferred
     *
     * @param Collection $oldProducts
     * @param array $newProducts
     * @return array
     */
    public static function getInfoProductsTo(Collection $oldProducts, array $newProducts): array
    {
        $output = [];
        foreach ($newProducts as $id => $product) {
            $oldProduct = $oldProducts->firstWhere('id', $id);
            if ($oldProduct !== null) {
                $result = $product['count'] - $oldProduct->pivot->count;
                if ($result > 0) {
                    $output[$id] = [
                        'count' => $result,
                        'remainder' => $product['count'],
                        'is_decreased' => false,
                        'is_increased' => true,
                        'is_deleted' => false,
                        'is_created' => false,
                    ];
                }
            } else {
                $output[$id] = [
                    'count' => 0,
                    'remainder' => $product['count'],
                    'is_decreased' => false,
                    'is_increased' => false,
                    'is_deleted' => false,
                    'is_created' => true,
                ];
            }
        }
        return $output;
    }

    /**
     * Gets a formatted array of data
     *
     * @param Collection $stories
     * @return array
     */
    public static function getFormatData(Collection $stories): array
    {
        $output = [];
        $storages = Storage::all();
        foreach ($stories as $story) {
            $storageTarget = $storages->firstWhere('id', $story->target_id);
            $output[] = [
                'id' => $storageTarget->id,
                'to' => $storageTarget->name,
                'is_from' => $story->is_from,
                'date' => $story->created_at->format('d.m.Y H:i:s'),
                'products' => $story->products,
            ];
        }
        return $output;
    }

    /**
     * Returns formatted product history
     *
     * @param Collection $stories
     * @return array
     */
    public static function getFormatProduct(Collection $stories): array
    {
        $output = [];
        $storages = Storage::all();
        foreach ($stories as $story) {
            $storage = $storages->firstWhere('id', $story->storage_id);
            $storageTo = $storages->firstWhere('id', $story->target_id);
            $output[] = [
                'storage' => $storage->name,
                'to' => $storageTo->name,
                'is_from' => $story->is_from,
                'date' => $story->created_at->format('d.m.Y H:i:s'),
                'count' => $story->pivot->count,
                'remainder' => $story->pivot->remainder,
                'is_decreased' => $story->pivot->is_decreased,
                'is_increased' => $story->pivot->is_increased,
                'is_deleted' => $story->pivot->is_deleted,
                'is_created' => $story->pivot->is_created,
            ];
        }
        return $output;
    }
}
