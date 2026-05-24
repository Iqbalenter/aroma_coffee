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
            // Definisikan pain points dari sentimen negatif, bukan cuma rating <= 2 (Kualitatif)
            $painPoints = $stageFeedbacks->where('sentimen', 'negatif');
            
            // Dapatkan distribusi tema
            $temaCount = $stageFeedbacks->whereNotNull('kategori_tema')->countBy('kategori_tema')->sortDesc()->take(2);

            $stageData[$key] = [
                'meta' => $meta,
                'count' => $stageFeedbacks->count(),
                'pain_points' => $painPoints->count(),
                'recent_comments' => $stageFeedbacks->whereNotNull('komentar')->take(3), // ambil object-nya untuk emosi
                'journey' => $journeys->firstWhere('tahapan', $key),
                'top_themes' => $temaCount
            ];
        }

        return view('cjm.index', compact('stageData'));
    }
}
