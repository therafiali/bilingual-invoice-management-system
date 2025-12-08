<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    use ApiResponse;

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp,pdf,svg|max:5120',
            'folder' => 'nullable|string'
        ]);

        $folder = $request->input('folder', 'uploads');

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folder, $filename, 'public');

            $uploadedFiles[] = asset('storage/' . $path);
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedFiles) . ' file(s) uploaded',
            'data' => [
                'urls' => $uploadedFiles,
                'count' => count($uploadedFiles)
            ]
        ], 200);
    }

    public function deleteFile(Request $request)
    {
        $url = $request->query('url');
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'];
        $path = str_replace('/storage/', '', $path);

        Storage::disk('public')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ], 200);
    }
}
