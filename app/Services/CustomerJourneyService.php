<?php

namespace App\Services;

use App\Models\CustomerJourney;
use App\Models\Feedback;

class CustomerJourneyService
{
    public function syncFromFeedback(?Feedback $feedback = null): void
    {
        foreach (array_keys(CustomerJourney::TAHAPAN) as $tahapan) {
            $query = Feedback::where('tahap_journey', $tahapan);
            $count = $query->count();
            $avg = $count > 0 ? round($query->avg('rating'), 2) : 0;

            CustomerJourney::updateOrCreate(
                ['tahapan' => $tahapan],
                ['jumlah_feedback' => $count, 'rata_rating' => $avg]
            );
        }
    }
}
