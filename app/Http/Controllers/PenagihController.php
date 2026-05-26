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

        return view('penagih.dashboard', compact('pajak', 'nopol'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pajak = PajakTagihan::findOrFail($id);
        $pajak->update(['is_ditagih' => true]);

        return response()->json(['success' => true]);
    }
}
