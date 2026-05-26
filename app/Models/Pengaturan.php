<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table    = 'pengaturan';
    protected $fillable = ['kunci', 'nilai'];

    public static function get(string $kunci, string $default = ''): string
    {
        $row = static::where('kunci', $kunci)->first();
        return $row ? ($row->nilai ?? $default) : $default;
    }

    public static function set(string $kunci, ?string $nilai): void
    {
        static::updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
    }
}
