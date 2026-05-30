<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status');

        $statusMeta = $this->statusMeta();
        $allowedStatuses = array_keys($statusMeta);

        $query = Pelanggan::query()
            ->withCount(['transaksi', 'feedback'])
            ->withSum('transaksi as total_belanja', 'total_harga')
            ->withAvg('feedback as rata_rating', 'rating');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nomor_hp', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        }

        $pelanggan = $query
            ->orderByDesc('tanggal_daftar')
            ->paginate(12)
            ->withQueryString();

        $totalCustomers = Pelanggan::count();

        $newThisMonth = Pelanggan::whereBetween('tanggal_daftar', [
            now()->copy()->startOfMonth(),
            now()->copy()->endOfMonth(),
        ])->count();

        $activeCustomers = Pelanggan::whereIn('status', [
            'aktif',
            'potensial_loyal',
            'loyal',
        ])->count();

        $loyalCustomers = Pelanggan::where('status', 'loyal')->count();

        $inactiveCustomers = Pelanggan::where('status', 'tidak_aktif')->count();

        $statusRaw = Pelanggan::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusData = collect($statusMeta)
            ->map(function ($meta, $key) use ($statusRaw, $totalCustomers) {
                $count = (int) $statusRaw->get($key, 0);

                return [
                    'key' => $key,
                    'label' => $meta['label'],
                    'count' => $count,
                    'percent' => $totalCustomers > 0 ? round(($count / $totalCustomers) * 100, 1) : 0,
                    'icon' => $meta['icon'],
                    'tone' => $meta['tone'],
                    'bg' => $meta['bg'],
                    'border' => $meta['border'],
                    'desc' => $meta['desc'],
                ];
            })
            ->values();

        $stats = [
            'total_customers' => $totalCustomers,
            'new_this_month' => $newThisMonth,
            'active_customers' => $activeCustomers,
            'loyal_customers' => $loyalCustomers,
            'inactive_customers' => $inactiveCustomers,
        ];

        return view('pelanggan.index', compact(
            'pelanggan',
            'search',
            'status',
            'statusMeta',
            'statusData',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Pelanggan::create($data);

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $data = $this->validateData($request, $pelanggan);

        $pelanggan->update($data);

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        // Proteksi: Hanya admin yang boleh menghapus pelanggan
        if (session('staff.type') !== 'admin') {
            abort(403, 'Operator tidak memiliki akses untuk menghapus data pelanggan.');
        }

        if ($pelanggan->transaksi()->exists()) {
            return back()->with('error', 'Pelanggan tidak dapat dihapus karena memiliki transaksi.');
        }

        if ($pelanggan->feedback()->exists()) {
            return back()->with('error', 'Pelanggan tidak dapat dihapus karena memiliki feedback.');
        }

        $pelanggan->delete();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    private function validateData(Request $request, ?Pelanggan $pelanggan = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('pelanggan', 'email')->ignore($pelanggan?->id_pelanggan, 'id_pelanggan'),
            ],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
        ]);
    }

    private function statusMeta(): array
    {
        return [
            'baru' => [
                'label' => 'Baru',
                'icon' => 'user-plus',
                'tone' => '#5B8EA8',
                'bg' => 'rgba(91, 142, 168, .12)',
                'border' => 'rgba(91, 142, 168, .25)',
                'desc' => 'Pelanggan baru atau belum memiliki pola transaksi kuat.',
            ],
            'aktif' => [
                'label' => 'Aktif',
                'icon' => 'activity',
                'tone' => '#3F7D58',
                'bg' => 'rgba(63, 125, 88, .12)',
                'border' => 'rgba(63, 125, 88, .25)',
                'desc' => 'Pelanggan yang sudah bertransaksi dan masih aktif.',
            ],
            'potensial_loyal' => [
                'label' => 'Potensial Loyal',
                'icon' => 'trending-up',
                'tone' => '#C7955B',
                'bg' => 'rgba(199, 149, 91, .14)',
                'border' => 'rgba(199, 149, 91, .28)',
                'desc' => 'Pelanggan yang mulai menunjukkan kecenderungan loyal.',
            ],
            'loyal' => [
                'label' => 'Loyal',
                'icon' => 'heart-handshake',
                'tone' => '#D4A853',
                'bg' => 'rgba(212, 168, 83, .15)',
                'border' => 'rgba(212, 168, 83, .30)',
                'desc' => 'Pelanggan setia dengan repeat purchase dan rating baik.',
            ],
            'tidak_aktif' => [
                'label' => 'Tidak Aktif',
                'icon' => 'user-x',
                'tone' => '#8A8077',
                'bg' => 'rgba(138, 128, 119, .13)',
                'border' => 'rgba(138, 128, 119, .25)',
                'desc' => 'Pelanggan yang perlu pendekatan reaktivasi.',
            ],
        ];
    }
}
