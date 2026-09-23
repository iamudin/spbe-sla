<?php
namespace App\Models\Plugins\SpbeSla;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class UnitKerja extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'spbe_sla_unit_kerjas';

    protected $fillable = [
        'name',
        'code',
        'description',
        'user_id',
    ];

    public function digitalServices()
    {
        return $this->hasMany(DigitalService::class, 'unit_kerja_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\Plugins\SpbeSla\User::class, 'user_id');
    }


}
