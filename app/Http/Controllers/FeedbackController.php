<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Services\CustomerJourneyService;
use App\Services\PelangganSegmentasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    protected CustomerJourneyService $journeyService;
    protected PelangganSegmentasiService $segmentasi;

    public function __construct(
        CustomerJourneyService $journeyService,
        PelangganSegmentasiService $segmentasi
    ) {
        $this->journeyService = $journeyService;
        $this->segmentasi = $segmentasi;
    }

    public function index(Request $request)
    {
        if (session('staff.type') !== 'admin') {
            return redirect()
                ->route('feedback.create')
                ->with('error', 'Operator hanya dapat mencatat feedback baru, tidak dapat melihat data feedback pelanggan.');
        }

        $dbKategori = Feedback::query()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori')
            ->toArray();

        $kategoriOptions = collect([
            'Rasa Produk',
            'Layanan',
            'Harga',
            'Tempat/Suasana',
        ])->merge($dbKategori)->unique()->sort()->values();

        $feedbackQuery = Feedback::with(['pelanggan', 'produk']);

        if ($request->filled('q')) {
            $keyword = trim($request->q);

            $feedbackQuery->where(function ($query) use ($keyword) {
                $query->where('komentar', 'like', "%{$keyword}%")
                    ->orWhere('kategori', 'like', "%{$keyword}%")
                    ->orWhereHas('pelanggan', function ($q) use ($keyword) {
                        $q->where('nama', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('nomor_hp', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('produk', function ($q) use ($keyword) {
                        $q->where('nama_produk', 'like', "%{$keyword}%")
                            ->orWhere('kategori', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('kategori')) {
            $feedbackQuery->where('kategori', $request->kategori);
        }

        if ($request->filled('tahap')) {
            $feedbackQuery->where('tahap_journey', $request->tahap);
        }

        if ($request->filled('status')) {
            $feedbackQuery->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $feedbackQuery->where('rating', (int) $request->rating);
        }

        $feedbacks = $feedbackQuery
            ->orderByDesc('tanggal_feedback')
            ->paginate(12)
            ->withQueryString();

        $pelanggan = Pelanggan::orderBy('nama')->get();
        $produk = Produk::orderBy('nama_produk')->get();

        $totalFeedback = Feedback::count();
        $monthlyFeedback = Feedback::whereBetween('tanggal_feedback', [
            now()->copy()->startOfMonth(),
            now()->copy()->endOfMonth(),
        ])->count();

        $avgRating = (float) Feedback::avg('rating');
        $painPoints = Feedback::where('rating', '<=', 2)->count();

        $positiveCount = Feedback::where('rating', '>=', 4)->count();
        $neutralCount = Feedback::where('rating', 3)->count();
        $negativeCount = Feedback::where('rating', '<=', 2)->count();

        $sentimentData = [
            ['name' => 'Positif', 'count' => (int) $positiveCount, 'color' => '#3F7D58'],
            ['name' => 'Netral',  'count' => (int) $neutralCount,  'color' => '#C7955B'],
            ['name' => 'Negatif', 'count' => (int) $negativeCount, 'color' => '#B4533C'],
        ];

        $journeyRaw = Feedback::query()
            ->select(
                'tahap_journey',
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(rating) as avg_rating')
            )
            ->groupBy('tahap_journey')
            ->get()
            ->keyBy('tahap_journey');

        $journeyData = collect(Feedback::TAHAPAN)
            ->map(function ($label, $key) use ($journeyRaw) {
                $row = $journeyRaw->get($key);

                return [
                    'key'        => $key,
                    'label'      => $label,
                    'count'      => (int) data_get($row, 'count', 0),
                    'avg_rating' => (float) data_get($row, 'avg_rating', 0),
                ];
            })
            ->values();

        $ratingRaw = Feedback::query()
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating');

        $ratingData = collect(range(1, 5))
            ->map(fn ($rating) => [
                'rating' => $rating,
                'label'  => $rating . ' Bintang',
                'count'  => (int) $ratingRaw->get($rating, 0),
            ])
            ->values();

        $stats = [
            'total_feedback'   => $totalFeedback,
            'monthly_feedback' => $monthlyFeedback,
            'avg_rating'       => $avgRating,
            'pain_points'      => $painPoints,
        ];

        return view('feedback.index', compact(
            'feedbacks',
            'pelanggan',
            'produk',
            'kategoriOptions',
            'sentimentData',
            'journeyData',
            'ratingData',
            'stats'
        ));
    }

    public function create(Request $request)
    {
        $pelanggan = Pelanggan::orderBy('nama')->get();
        $produk = Produk::orderBy('nama_produk')->get();

        $dbKategori = Feedback::query()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori')
            ->toArray();

        $kategoriOptions = collect([
            'Rasa Produk',
            'Layanan',
            'Harga',
            'Tempat/Suasana',
        ])->merge($dbKategori)->unique()->sort()->values();

        return view('feedback.create', compact('pelanggan', 'produk', 'kategoriOptions'));
    }

    public function store(Request $request)
    {
        $data = $this->validateFeedback($request);
        $data['status'] = $data['status'] ?? 'baru';

        $feedback = Feedback::create($data);

        $this->journeyService->syncFromFeedback($feedback);

        if ($feedback->pelanggan) {
            $this->segmentasi->updateStatus($feedback->pelanggan);
        }

        if (session('staff.type') === 'admin') {
            return redirect()
                ->route('feedback.index')
                ->with('success', 'Feedback berhasil disimpan.');
        }

        return redirect()
            ->route('feedback.create')
            ->with('success', 'Feedback berhasil disimpan. Operator dapat mencatat feedback berikutnya.');
    }

    public function update(Request $request, Feedback $feedback)
    {
        if (session('staff.type') !== 'admin') {
            abort(403, 'Operator tidak memiliki akses untuk mengubah feedback.');
        }

        $oldPelanggan = $feedback->pelanggan;

        $data = $this->validateFeedback($request);
        $data['status'] = $data['status'] ?? $feedback->status ?? 'baru';

        $feedback->update($data);
        $feedback->refresh();

        $this->journeyService->syncFromFeedback($feedback);

        if ($oldPelanggan) {
            $this->segmentasi->updateStatus($oldPelanggan);
        }

        if ($feedback->pelanggan) {
            $this->segmentasi->updateStatus($feedback->pelanggan);
        }

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Feedback berhasil diperbarui.');
    }

    public function destroy(Feedback $feedback)
    {
        if (session('staff.type') !== 'admin') {
            abort(403, 'Operator tidak memiliki akses untuk menghapus feedback.');
        }

        $pelanggan = $feedback->pelanggan;

        $feedback->delete();

        $this->journeyService->syncFromFeedback();

        if ($pelanggan) {
            $this->segmentasi->updateStatus($pelanggan);
        }

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Feedback berhasil dihapus.');
    }

    private function validateFeedback(Request $request): array
    {
        return $request->validate([
            'id_pelanggan'  => 'required|exists:pelanggan,id_pelanggan',
            'id_produk'     => 'required|exists:produk,id_produk',
            'kategori'      => 'required|string|max:100',
            'tahap_journey' => 'required|in:awareness,consideration,purchase,experience,retention,loyalty',
            'rating'        => 'required|integer|min:1|max:5',
            'komentar'      => 'required|string|max:2000',
            'status'        => 'nullable|in:baru,ditinjau,selesai',
        ]);
    }
}
