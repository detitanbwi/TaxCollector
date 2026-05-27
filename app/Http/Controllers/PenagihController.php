<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PajakTagihan;
use App\Models\Setting;

class PenagihController extends Controller
{
    public function index(Request $request)
    {
        $query = PajakTagihan::query();

        // Kolom Pencarian (Nopol / Nama / Alamat - nama_pemilik atau merek_nama)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nopol', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%")
                  ->orWhere('merek_nama', 'like', "%{$search}%")
                  ->orWhere('merek_type', 'like', "%{$search}%");
            });
        }

        // Sorting & Filter Status
        $sort = $request->input('sort', 'nama_pemilik'); // default sort by nama
        $order = $request->input('order', 'asc'); // default order ascending (A-Z)

        if ($sort === 'nominal') {
            $query->orderBy('nominal', $order);
        } elseif ($sort === 'nama_pemilik') {
            $query->orderBy('nama_pemilik', $order);
        } else {
            $query->orderBy('id', 'desc'); // Terbaru
        }

        // Ambil data dengan pagination agar tidak meload seluruh data sekaligus (10 data per halaman)
        $pajakList = $query->paginate(10)->withQueryString();

        $whatsappFormat = Setting::getValue('whatsapp_format', "Selamat pagi/siang Bapak/Ibu {nama},\n\nMengingatkan kewajiban Pajak Kendaraan Bermotor (PKB) untuk Kendaraan Anda:\n\n• Nopol: {nopol}\n• Kendaraan: {merek} {type} ({th})\n• PKB: Rp {pkb}\n• Opsen: Rp {opsen}\n---------------------------\n• Total Tagihan: Rp {nominal}\n• Masa Laku s.d: {masa_laku}\n\nMohon untuk segera melakukan pembayaran sebelum jatuh tempo untuk menghindari sanksi administratif.\n\nPembayaran dapat dilakukan di Loket Samsat, Indomaret, Alfamart, atau platform online resmi.\n\nLink keabsahan tagihan:\nhttps://namainstansi.go.id/pajak/tagihan/{nopol}");

        return view('penagih.dashboard', compact('pajakList', 'whatsappFormat'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pajak = PajakTagihan::findOrFail($id);
        $pajak->update(['is_ditagih' => true]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $pajak = PajakTagihan::findOrFail($id);
        $whatsappFormat = Setting::getValue('whatsapp_format', "Selamat pagi/siang Bapak/Ibu {nama},\n\nMengingatkan kewajiban Pajak Kendaraan Bermotor (PKB) untuk Kendaraan Anda:\n\n• Nopol: {nopol}\n• Kendaraan: {merek} {type} ({th})\n• PKB: Rp {pkb}\n• Opsen: Rp {opsen}\n---------------------------\n• Total Tagihan: Rp {nominal}\n• Masa Laku s.d: {masa_laku}\n\nMohon untuk segera melakukan pembayaran sebelum jatuh tempo untuk menghindari sanksi administratif.\n\nPembayaran dapat dilakukan di Loket Samsat, Indomaret, Alfamart, atau platform online resmi.\n\nLink keabsahan tagihan:\nhttps://namainstansi.go.id/pajak/tagihan/{nopol}");

        return view('penagih.show', compact('pajak', 'whatsappFormat'));
    }
}
