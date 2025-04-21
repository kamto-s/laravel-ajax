<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductService
{
    public function create(array $data)
    {
        $data['uuid'] = Str::uuid();
        $data['slug'] = Str::slug($data['name']);
        return Product::create($data);
    }

    public function update(array $data, string $id)
    {
        // ubah uuid jika ubah data
        $data['uuid'] = Str::uuid();

        $data['slug'] = Str::slug($data['name']);
        $product = Product::where('uuid', $id)->firstOrFail();
        return $product->update($data);
    }

    public function getByUid(string $id)
    {
        return Product::where('uuid', $id)->firstOrFail();
    }

    //
    public function delete(string $id)
    {
        $product = Product::where('uuid', $id)->firstOrFail();
        return $product->delete();
    }

    //
    public function getDatatable()
    {
        $product = Product::latest()->get();

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
