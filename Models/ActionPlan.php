<?php
namespace App\Models\Plugins\SpbeSla;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActionPlan extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'spbe_sla_action_plans';

    protected $fillable = [
        'sla_review_id',
        'finding_aspect',
        'action_taken',
        'progress_percentage',
        'status',
    ];

    protected $casts = [
        'progress_percentage' => 'integer',
    ];

    public function slaReview()
    {
        return $this->belongsTo(SlaReview::class, 'sla_review_id');
    }
}
