<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\SlaReview;

class SlaReviewController extends Controller
{
    public function index()
    {
        plugin_page_name('SLA Reviews & Action Plans');
        $reviews = SlaReview::with('actionPlans')->orderBy('period_year', 'desc')->orderBy('period_quarter', 'desc')->paginate(10);
        return view('spbe-sla::admin.reviews.index', compact('reviews'));
    }
}
