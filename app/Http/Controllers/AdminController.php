<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PajakTagihan;

class AdminController extends Controller
{
    public function index()
    {
        $totalData = PajakTagihan::count();
        $totalDitagih = PajakTagihan::where('is_ditagih', true)->count();
        $totalBelumDitagih = PajakTagihan::where('is_ditagih', false)->count();

        return view('admin.dashboard', compact('totalData', 'totalDitagih', 'totalBelumDitagih'));
    }
}
