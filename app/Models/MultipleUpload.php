<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleUpload extends Model
{
    use HasFactory;

    protected $table = 'multipleuploads';
    
    protected $fillable = [
        'ref_table',
        'ref_id',
        'filename',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'extension'
    ];

    /**
     * Scope query untuk file berdasarkan referensi
     */
    public function scopeByReference($query, $refTable, $refId)
    {
        return $query->where('ref_table', $refTable)
                    ->where('ref_id', $refId);
    }

    /**
     * Relationship dynamic berdasarkan ref_table
     */
    public function reference()
    {
        switch ($this->ref_table) {
            case 'pelanggan':
                return $this->belongsTo(Pelanggan::class, 'ref_id', 'pelanggan_id');
            // Tambahkan case lain untuk tabel lainnya
            default:
                return null;
        }
    }
}