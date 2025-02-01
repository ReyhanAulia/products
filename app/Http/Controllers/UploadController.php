<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/uploads', $filename);

        // Simpan URL gambar ke database jika diperlukan
        $image_url = asset('storage/uploads/' . $filename);

        // Mengembalikan URL gambar
        return redirect()->back()->with([
            'success' => 'Image uploaded successfully!',
            'image' => $image_url
        ]);
    }
}
