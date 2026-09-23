<?php
namespace App\Models\Plugins\SpbeSla;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlaPolicy extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'spbe_sla_policies';

    protected $fillable = [
        'digital_service_id',
        'version',
        'effective_date',
        'uptime_target',
        'response_time_target_minutes',
        'resolution_time_target_hours',
        'rpo_minutes',
        'document_path',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'uptime_target' => 'float',
        'response_time_target_minutes' => 'integer',
        'resolution_time_target_hours' => 'float',
        'rpo_minutes' => 'integer',
    ];

    public function digitalService()
    {
        return $this->belongsTo(DigitalService::class, 'digital_service_id');
    }
}
