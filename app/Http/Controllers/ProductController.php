<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Requests\ProductRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['uuid'] = Str::uuid();
        $data['slug'] = Str::slug($data['name']);
        Product::create($data);

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
                'data' => Product::where('uuid', $id)->firstOrFail()
            ]);
        } catch (Exception $error) {
            //throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            // ubah uuid jika ubah data
            $data['uuid'] = Str::uuid();

            $data['slug'] = Str::slug($data['name']);
            $product = Product::where('uuid', $id)->firstOrFail();
            $product->update($data);

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
        // Product::destroy($id);
        $product = Product::where('uuid', $id)->firstOrFail();
        $product->delete();

        return response()->json([
            'title' => 'Success',
            'text' => 'Product deleted successfully',
            'icon' => 'success'
        ]);
    }

    public function serversideTable(Request $request)
    {
        $product = Product::get();

        return DataTables::of($product)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<div>
                            <button class="btn btn-warning btn-sm edit" onclick="editModal(this)" data-id="' . $row->uuid . '"> <i class="fa-solid fa-pencil"></i></button>
                            <button class="btn btn-danger btn-sm hapus" onclick="deleteModal(this)" data-id="' . $row->uuid . '"> <i class="fa-solid fa-trash-can"></i></button>
                        </div>';
            })
            ->make();
    }
}
