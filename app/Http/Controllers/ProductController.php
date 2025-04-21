<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\ImageService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService, private ImageService $imageService) {}

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
        try {
            $data = $request->validated();

            $uploadImg = $this->imageService->uploadImage($data);
            $data['image'] = $uploadImg;

            $this->productService->create($data);

            return response()->json([
                'title' => 'Success',
                'text' => 'Product created successfully',
                'icon' => 'success',
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
    public function update(UpdateProductRequest $request, string $id)
    {
        $data = $request->validated();
        $getImage = $this->productService->getByUid($id);

        try {
            if ($request->hasFile('image')) {
                $uploadImg = $this->imageService->uploadImage($data, $getImage->image);
                $data['image'] = $uploadImg;
            }

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
