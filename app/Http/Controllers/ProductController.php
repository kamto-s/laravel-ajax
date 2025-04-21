<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->productService->create($data);

        return response()->json([
            'title' => 'Success',
            'text' => 'Product created successfully',
            'icon' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json([
                // 'data' => Product::find($id)
                'data' => $this->productService->getByUid($id),
            ]);
        } catch (Exception $error) {
            //throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $this->productService->update($data, $id);

            return response()->json([
                'title' => 'Success',
                'text' => 'Product updated successfully',
                'icon' => 'success'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'title' => 'Error',
                'text' => $error->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->productService->delete($id);

        return response()->json([
            'title' => 'Success',
            'text' => 'Product deleted successfully',
            'icon' => 'success'
        ]);
    }

    public function serversideTable(): JsonResponse
    {
        return $this->productService->getDatatable();
    }
}
