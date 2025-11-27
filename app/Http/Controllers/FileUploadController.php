<?php

namespace App\Http\Controllers;

use App\Models\MultipleUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:2048',
            'ref_table' => 'required|string',
            'ref_id' => 'required|integer'
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $filename = time() . '_' . uniqid() . '_' . $originalName;
                $filePath = $file->storeAs('public/uploads', $filename);

                $upload = MultipleUpload::create([
                    'ref_table' => $request->ref_table,
                    'ref_id' => $request->ref_id,
                    'filename' => $filename,
                    'original_name' => $originalName,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension()
                ]);

                $uploadedFiles[] = $upload;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'File berhasil diupload',
            'files' => $uploadedFiles
        ]);
    }

    public function destroy($id)
    {
        try {
            $file = MultipleUpload::findOrFail($id);
            
            // Hapus file dari storage
            if (Storage::exists($file->file_path)) {
                Storage::delete($file->file_path);
            }
            
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFiles($refTable, $refId)
    {
        $files = MultipleUpload::where('ref_table', $refTable)
                              ->where('ref_id', $refId)
                              ->get();

        return response()->json([
            'success' => true,
            'files' => $files
        ]);
    }
}