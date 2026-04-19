<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Category::whereRaw('LOWER(name) = ?', [strtolower($value)])->exists()) {
                        $fail('Nama kategori sudah digunakan, coba nama lain.');
                    }
                },
            ]
        ], [
            'category_name.required' => 'Nama kategori wajib diisi.',
        ]);

        try {
            Category::create([
            'name' => $request->category_name,
        ]);

        return redirect()->route('admin.category.index')->with('sukses', 'Kategori baru berhasil ditambahkan.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menambahkan kategori.')->withInput();
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
        $category = Category::findOrFail($id);
        return view('pages.admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'category_name' => [
                'required',
                function ($attribute, $value, $fail) use($id) {
                    if (Category::whereRaw('LOWER(name) = ?', [strtolower($value)])->where('id', '!=', $id)->exists()) {
                        $fail('Nama kategori sudah digunakan, coba nama lain.');
                    }
                },
            ]
        ], [
            'category_name.required' => 'Nama kategori wajib diisi.',
        ]);

        try {
            Category::where('id', $id)->update([
            'name' => $request->category_name,
        ]);

        return redirect()->route('admin.category.index')->with('sukses', 'Kategori baru berhasil diupdate.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mengupdate kategori.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            if($category->products()->exists()) {
                return back()->with('warning', 'Kategori sedang digunakan dan hanya dapat dinonaktifkan.');
            }
            $category->delete();
            return redirect()->route('admin.category.index')->with('sukses', 'Kategori berhasil dihapus.');
        } catch (Exception $e) {
            return back()->with('error', 'Kategori gagal dihapus.');
        }
    }
}
