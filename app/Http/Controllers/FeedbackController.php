<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Services\CustomerJourneyService;
use App\Services\PelangganSegmentasiService;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __construct(
        private CustomerJourneyService $journeyService,
        private PelangganSegmentasiService $segmentasi
    ) {}

    public function index()
    {
        $feedbacks = Feedback::with(['pelanggan', 'produk'])
            ->orderByDesc('tanggal_feedback')
            ->paginate(15);

        return view('feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::orderBy('nama')->get();
        $produk = Produk::orderBy('nama_produk')->get();

        return view('feedback.create', compact('pelanggan', 'produk'));
    }

    public function store(Request $request)
    {
        $data = $this->validateFeedback($request);
        $feedback = Feedback::create($data);
        $this->journeyService->syncFromFeedback($feedback);
        $this->segmentasi->updateStatus($feedback->pelanggan);

        return redirect()->route('feedback.index')->with('success', 'Feedback berhasil disimpan.');
    }

    public function update(Request $request, Feedback $feedback)
    {
        $data = $this->validateFeedback($request, false);
        $feedback->update($data);
        $this->journeyService->syncFromFeedback($feedback);
        $this->segmentasi->updateStatus($feedback->pelanggan);

        return redirect()->route('feedback.index')->with('success', 'Feedback berhasil diperbarui.');
    }

    public function destroy(Feedback $feedback)
    {
        $pelanggan = $feedback->pelanggan;
        $feedback->delete();
        $this->journeyService->syncFromFeedback();
        $this->segmentasi->updateStatus($pelanggan);

        return redirect()->route('feedback.index')->with('success', 'Feedback berhasil dihapus.');
    }

    private function validateFeedback(Request $request, bool $requireProduk = true): array
    {
        return $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'id_produk' => ($requireProduk ? 'required' : 'nullable').'|exists:produk,id_produk',
            'kategori' => 'required|string|max:100',
            'tahap_journey' => 'required|in:awareness,consideration,purchase,experience,retention,loyalty',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string',
            'status' => 'nullable|in:baru,ditinjau,selesai',
        ]);
    }
}
