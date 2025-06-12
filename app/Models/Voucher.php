<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Voucher extends Model
{
    protected $fillable = ['name','serie','number'];

    public static function serie($document_type){
        return static::where('id', $document_type)->value('serie');
    }

    public static function nextCorrelativeById(int $voucherId): int
    {
        // Carga sólo fields necesarios
        $voucher = static::findOrFail($voucherId, ['serie', 'number']);

        $last = Document::where('serie', $voucher->serie)
            ->max('correlative');
        Log::info("last correlative: " . $last);
        return $last
            ? $last + 1
            : (int) $voucher->number + 1;
    }



}
