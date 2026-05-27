<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $whatsappFormat = Setting::getValue('whatsapp_format', "Selamat pagi/siang Bapak/Ibu {nama},\n\nMengingatkan kewajiban Pajak Kendaraan Bermotor (PKB) untuk Kendaraan Anda:\n\n• Nopol: {nopol}\n• Kendaraan: {merek} {type} ({th})\n• PKB: Rp {pkb}\n• Opsen: Rp {opsen}\n---------------------------\n• Total Tagihan: Rp {nominal}\n• Masa Laku s.d: {masa_laku}\n\nMohon untuk segera melakukan pembayaran sebelum jatuh tempo untuk menghindari sanksi administratif.\n\nPembayaran dapat dilakukan di Loket Samsat, Indomaret, Alfamart, atau platform online resmi.\n\nLink keabsahan tagihan:\nhttps://namainstansi.go.id/pajak/tagihan/{nopol}");
        
        return view('admin.settings', compact('whatsappFormat'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_format' => 'required|string',
        ]);

        Setting::updateOrCreate(
            ['key' => 'whatsapp_format'],
            ['value' => $request->whatsapp_format]
        );

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
