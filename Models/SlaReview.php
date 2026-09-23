<?php
namespace App\Models\Plugins\SpbeSla;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlaReview extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'spbe_sla_reviews';

    protected $fillable = [
        'title',
        'period_year',
        'period_quarter',
        'compliance_rate',
        'findings',
        'recommendations',
        'report_file',
    ];

    protected $casts = [
        'period_year' => 'integer',
        'period_quarter' => 'integer',
        'compliance_rate' => 'float',
    ];

    public function actionPlans()
    {
        return $this->hasMany(ActionPlan::class, 'sla_review_id');
    }
}
