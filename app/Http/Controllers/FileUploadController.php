<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048', // Limit na veľkosť obrázku 2 MB
        ]);


        $existingFileId = $request->input('existing_file_id');

        // Vyhľadanie starého obrázka
        if ($existingFileId) {
            $existingFile = File::find($existingFileId);

            if ($existingFile && Storage::disk('public')->exists($existingFile->filepath)) {
                // Odstránenie starého súboru
                Storage::disk('public')->delete($existingFile->filepath);
                $existingFile->delete(); // Odstránenie záznamu z databázy
            }
        }

        // Uloženie súboru do 'public/uploads'
        $file = $request->file('file');
        $filename = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filepath = $file->storeAs('public/uploads', $filename);

        // Uloženie do databázy
        $fileRecord = File::create([
            'filename' => $filename,
            'filepath' => Storage::url($filepath),
            'mime_type' => $file->getClientMimeType(),
        ]);

        // Vrátenie odpovede s ID a cestou k obrázku
        return response()->json([
            'id' => $fileRecord->id,
            'filepath' => $fileRecord->filepath,
        ]);
    }

    // Získa galériu obrázkov
    public function getGallery()
    {
        //todo potom upravit
        if(auth()->id() === 2) {
            $files = File::all();
        } else {
            $files = File::where('user_id', auth()->id())->where('filename', '!=', 'default-product.jpg')->get();
        }

        return response()->json($files);
    }
}
