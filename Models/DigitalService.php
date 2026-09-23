<?php
namespace App\Models\Plugins\SpbeSla;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitalService extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'spbe_sla_digital_services';

    protected $fillable = [
        'name',
        'code',
        'description',
        'url',
        'unit_kerja_id',
        'status',
    ];

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function slaPolicies()
    {
        return $this->hasMany(SlaPolicy::class, 'digital_service_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'digital_service_id');
    }

    public function appDevelopment()
    {
        return $this->hasOne(AppDevelopment::class, 'digital_service_id');
    }
}
