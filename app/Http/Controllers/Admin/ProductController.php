<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('pages.admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (Product::whereRaw('LOWER(name) = ?', [strtolower($value)])->exists()) {
                        $fail('Nama produk sudah digunakan, coba nama lain.');
                    }
                },
            ],
            'product_category' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'product_name.required' => 'Nama produk wajib diisi.',
            'product_name.string' => 'Nama produk harus berupa teks.',
            'product_name.max' => 'Nama produk maksimal 255 karakter.',

            'product_category.required' => 'Kategori produk wajib dipilih.',
            'product_category.exists' => 'Kategori yang dipilih tidak valid.',

            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',

            'image.required' => 'Gambar produk wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus PNG, JPG, JPEG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $image = $request->file('image');
            $imageName = time() . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            Product::create([
                'name' => $request->product_name,
                'category_id' => $request->product_category,
                'price' => $request->price,
                'stock' => 0,
                'image' => $imagePath
            ]);

            return redirect()->route('admin.product.index')->with('sukses', 'Produk berhasil ditambahkan.');
        } catch (Exception $e) {
            return back()->with('error', 'Produk gagal ditambahkan.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('pages.admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($product) {
                    if (Product::whereRaw('LOWER(name) = ?', [strtolower($value)])->where('id', '!=', $product->id)->exists()) {
                        $fail('Nama produk sudah digunakan, coba nama lain.');
                    }
                },
            ],
            'product_category' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'product_name.required' => 'Nama produk wajib diisi.',
            'product_name.string' => 'Nama produk harus berupa teks.',
            'product_name.max' => 'Nama produk maksimal 255 karakter.',

            'product_category.required' => 'Kategori produk wajib dipilih.',
            'product_category.exists' => 'Kategori yang dipilih tidak valid.',

            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',

            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus PNG, JPG, JPEG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $dataToUpdate = [
                'name' => $request->product_name,
                'category_id' => $request->product_category,
                'price' => $request->price,
            ];

            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $image = $request->file('image');
                $imageName = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $dataToUpdate['image'] = $imagePath;
            }

            $product->update($dataToUpdate);

            return redirect()->route('admin.product.index')->with('sukses', 'Produk berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', 'Produk gagal diperbarui.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
