<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Storage;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Helpers\Storage\StorageHelper;
use App\Helpers\Story\StoryHelper;
use App\Contracts\StoryManager;

class StorageController extends Controller
{
    /**
     * Service for working with warehouse history
     *
     * @var StoryManager
     */
    protected $storyManager;

    public function __construct(StoryManager $storyManager)
    {
        $this->storyManager = $storyManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $storages = Storage::with('products')->get();
        return response()->json([
            'status' => true,
            'storages' => $storages,
        ]);
    }

    /**
     * Display warehouse list and product list
     *
     * @return JsonResponse
     */
    public function indexProducts(): JsonResponse
    {
        $storages = Storage::with('products')->get();
        $products = Product::all();
        return response()->json([
            'status' => true,
            'storages' => $storages,
            'products' => $products,
        ]);
    }

    /**
     * The method is triggered when we transfer goods from one warehouse to another
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function transfer(Request $request): JsonResponse
    {
        $storages = Storage::with('products')->get();
        $storageFrom = $storages->firstWhere('id', $request->from_storage);
        $storageTo = $storages->firstWhere('id', $request->to_storage);
        $dataFrom = json_decode($request->data_from, true);
        $dataTo = json_decode($request->data_to, true);
        $productsDataFrom = StorageHelper::mergingDataProductsFrom($storageFrom->products, $dataFrom);
        $infoProductsFrom = StoryHelper::getInfoProductsFrom($storageFrom->products, $productsDataFrom, $dataFrom);
        $productsDataTo = StorageHelper::getDataProductsTo($dataTo);
        $infoProductsTo = StoryHelper::getInfoProductsTo($storageTo->products, $productsDataTo);
        $storageFrom->products()->sync($productsDataFrom);
        $storageTo->products()->sync($productsDataTo);
        $this->storyManager->create($storageFrom, $infoProductsFrom, true, $storageTo->id);
        $this->storyManager->create($storageTo, $infoProductsTo, false, $storageFrom->id);
        return response()->json([
            'status' => true,
            'storages_updated' => Storage::with('products')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = StorageHelper::getDataProducts($request);
        $storage = Storage::find($id);
        $status = true;
        if ($storage !== null) {
            $storage->products()->sync($data);
        } else {
            $status = false;
        }
        return response()->json([
            'status' => $status,
            'id' => $storage->id,
            'products' => $storage->products()->get(),
        ]);
    }
}
