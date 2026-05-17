<?php

namespace App\Http\Controllers;

use App\Models\CustomerJourney;
use App\Models\Feedback;

class CjmController extends Controller
{
    public function index()
    {
        $journeys = CustomerJourney::orderByRaw("FIELD(tahapan, 'awareness','consideration','purchase','experience','retention','loyalty')")->get();
        $feedbacks = Feedback::with('pelanggan')->latest('tanggal_feedback')->get();

        $stageData = [];
        foreach (CustomerJourney::TAHAPAN as $key => $meta) {
            $stageFeedbacks = $feedbacks->where('tahap_journey', $key);
            $painPoints = $stageFeedbacks->where('rating', '<=', 2);
            $stageData[$key] = [
                'meta' => $meta,
                'count' => $stageFeedbacks->count(),
                'pain_points' => $painPoints->count(),
                'recent_comments' => $stageFeedbacks->take(2)->pluck('komentar'),
                'journey' => $journeys->firstWhere('tahapan', $key),
            ];
        }

        return view('cjm.index', compact('stageData'));
    }
}
