<?php

namespace App\Models\Plugins\SpbeSla;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AppDevelopment extends Model
{
    use SoftDeletes;

    protected $table = 'spbe_sla_app_developments';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'digital_service_id',
        'ticket_id',
        'specification',
        'progress_percentage',
        'it_staff',
        'status',
        'modules_data',
    ];

    protected $casts = [
        'modules_data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
        
        static::saving(function ($model) {
            if (is_array($model->modules_data)) {
                $totalFeatures = 0;
                $completedFeatures = 0;
                
                foreach ($model->modules_data as $module) {
                    if (isset($module['features']) && is_array($module['features'])) {
                        foreach ($module['features'] as $feature) {
                            $totalFeatures++;
                            if (isset($feature['is_completed']) && filter_var($feature['is_completed'], FILTER_VALIDATE_BOOLEAN)) {
                                $completedFeatures++;
                            }
                        }
                    }
                }
                
                if ($totalFeatures > 0) {
                    $model->progress_percentage = (int) round(($completedFeatures / $totalFeatures) * 100);
                } else {
                    $model->progress_percentage = 0;
                }
            } else {
                if (empty($model->progress_percentage)) {
                    $model->progress_percentage = 0;
                }
            }
        });
    }

    public function digitalService()
    {
        return $this->belongsTo(DigitalService::class, 'digital_service_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
