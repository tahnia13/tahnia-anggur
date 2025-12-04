<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $fiterableColumns = ['gender'];

        $searchableColumns = ['first_name', 'last_name', 'email', 'phone'];

        $data['dataPelanggan'] = Pelanggan::filter($request, $fiterableColumns)
                                    ->search($request, $searchableColumns)
                                    ->paginate(10)->withQueryString();

		return view('admin.pelanggan.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $data['first_name'] = $request->first_name;
		$data['last_name'] = $request->last_name;
		$data['birthday'] = $request->birthday;
		$data['gender'] = $request->gender;
		$data['email'] = $request->email;
		$data['phone'] = $request->phone;

		Pelanggan::create($data);

		return redirect()->route('pelanggan.index')->with('succes','Penambahan Data Berhasil!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|email|unique:pelanggan,email,' . $id . ',pelanggan_id',
            'phone' => 'required|string|max:20',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        $data = $request->only(['first_name', 'last_name', 'birthday', 'gender', 'email', 'phone']);

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            $uploadedFiles = [];

            // Simpan file yang sudah ada sebelumnya
            $existingFiles = $pelanggan->files ? json_decode($pelanggan->files, true) : [];

            foreach ($request->file('files') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/pelanggan-files', $filename);
                $uploadedFiles[] = $filename;
            }

            // Gabungkan file lama dan baru
            $allFiles = array_merge($existingFiles, $uploadedFiles);
            $data['files'] = json_encode($allFiles);
        }

        $pelanggan->update($data);

        return redirect()->route('pelanggan.index')->with('update', 'Pelanggan <strong>' . $pelanggan->first_name . ' ' . $pelanggan->last_name . '</strong> berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyFile($id, $filename)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $files = $pelanggan->files ? json_decode($pelanggan->files, true) : [];

        // Hapus file dari storage
        if (Storage::exists('public/pelanggan-files/' . $filename)) {
            Storage::delete('public/pelanggan-files/' . $filename);
        }

        // Hapus dari array files
        $updatedFiles = array_filter($files, function($file) use ($filename) {
            return $file !== $filename;
        });

        $pelanggan->update(['files' => json_encode(array_values($updatedFiles))]);

        return back()->with('succes', 'File berhasil dihapus!');
    }
}
