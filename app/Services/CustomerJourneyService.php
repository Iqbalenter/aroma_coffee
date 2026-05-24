<?php

namespace App\Services;

use App\Models\CustomerJourney;
use App\Models\Feedback;

class CustomerJourneyService
{
    public function syncFromFeedback(?Feedback $feedback = null): void
    {
        // Jika ada feedback baru, lakukan text text analysis kualitatif
        if ($feedback && empty($feedback->sentimen)) {
            $this->analyzeFeedbackText($feedback);
        }

        foreach (array_keys(CustomerJourney::TAHAPAN) as $tahapan) {
            $query = Feedback::where('tahap_journey', $tahapan);
            $count = $query->count();
            $avg = $count > 0 ? round($query->avg('rating'), 2) : 0;

            // Cari sentimen dominan
            $sentimenDominan = $query->select('sentimen')
                  ->whereNotNull('sentimen')
                  ->selectRaw('count(sentimen) as total')
                  ->groupBy('sentimen')
                  ->orderByDesc('total')
                  ->value('sentimen');

            CustomerJourney::updateOrCreate(
                ['tahapan' => $tahapan],
                [
                    'jumlah_feedback' => $count, 
                    'rata_rating' => $avg,
                    'sentimen_dominan' => $sentimenDominan
                ]
            );
        }
    }

    /**
     * Prototipe Lexicon-Based Sentiment & Thematic Analysis
     * Sangat cocok untuk ditunjukkan sebagai fitur Kualitatif Skripsi
     */
    private function analyzeFeedbackText(Feedback $feedback): void
    {
        if (empty($feedback->komentar)) {
            return;
        }

        $text = strtolower($feedback->komentar);

        // 1. Analisis Sentimen Sederhana
        $kataPositif = ['enak', 'mantap', 'bagus', 'ramah', 'cepat', 'nyaman', 'suka', 'keren', 'terbaik', 'puas'];
        $kataNegatif = ['pahit', 'asam', 'lambat', 'kotor', 'mahal', 'jelek', 'kurang', 'kecewa', 'lama', 'buruk', 'kasar'];

        $skorPositif = 0;
        $skorNegatif = 0;

        foreach ($kataPositif as $kata) {
            $skorPositif += substr_count($text, $kata);
        }
        foreach ($kataNegatif as $kata) {
            $skorNegatif += substr_count($text, $kata);
        }

        $sentimen = 'netral';
        // Gabungkan rating juga sebagai faktor penunjanng analisis kualitatif
        if ($skorPositif > $skorNegatif || $feedback->rating >= 4) {
            $sentimen = 'positif';
        } elseif ($skorNegatif > $skorPositif || $feedback->rating <= 2) {
            $sentimen = 'negatif';
        }
        $feedback->sentimen = $sentimen;

        // 2. Analisis Tema (Thematic Coding)
        $tema = 'Lainnya';
        if (str_contains($text, 'rasa') || str_contains($text, 'kopi') || str_contains($text, 'manis') || str_contains($text, 'pahit') || str_contains($text, 'menu')) {
            $tema = 'Kualitas Produk';
        } elseif (str_contains($text, 'barista') || str_contains($text, 'pelayanan') || str_contains($text, 'kasir') || str_contains($text, 'senyum')) {
            $tema = 'Pelayanan';
        } elseif (str_contains($text, 'tempat') || str_contains($text, 'meja') || str_contains($text, 'kotor') || str_contains($text, 'nyaman') || str_contains($text, 'toilet') || str_contains($text, 'wifi')) {
            $tema = 'Suasana & Fasilitas';
        } elseif (str_contains($text, 'harga') || str_contains($text, 'mahal') || str_contains($text, 'murah') || str_contains($text, 'promo')) {
            $tema = 'Harga & Promo';
        }
        $feedback->kategori_tema = $tema;

        // Save back bypassing events if not needed, or just save
        $feedback->save();
    }
}
