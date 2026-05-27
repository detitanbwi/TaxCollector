<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PajakTagihan;

class PenagihController extends Controller
{
    public function index()
    {
        return view('penagih.dashboard');
    }

    public function search(Request $request)
    {
        $request->validate([
            'nopol' => 'required|string',
        ]);

        $nopol = strtoupper($request->nopol);
        $pajak = PajakTagihan::where('nopol', $nopol)->first();

        if (!$pajak) {
            return back()->with('error', 'Data Pajak dengan Nopol tersebut tidak ditemukan.');
        }

        $whatsappFormat = \App\Models\Setting::getValue('whatsapp_format', "Selamat pagi/siang Bapak/Ibu {nama},\n\nMengingatkan kewajiban Pajak Kendaraan Bermotor (PKB) untuk Kendaraan Anda:\n\n• Nopol: {nopol}\n• Kendaraan: {merek} {type} ({th})\n• PKB: Rp {pkb}\n• Opsen: Rp {opsen}\n---------------------------\n• Total Tagihan: Rp {nominal}\n• Masa Laku s.d: {masa_laku}\n\nMohon untuk segera melakukan pembayaran sebelum jatuh tempo untuk menghindari sanksi administratif.\n\nPembayaran dapat dilakukan di Loket Samsat, Indomaret, Alfamart, atau platform online resmi.\n\nLink keabsahan tagihan:\nhttps://namainstansi.go.id/pajak/tagihan/{nopol}");

        return view('penagih.dashboard', compact('pajak', 'nopol', 'whatsappFormat'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pajak = PajakTagihan::findOrFail($id);
        $pajak->update(['is_ditagih' => true]);

        return response()->json(['success' => true]);
    }
}
