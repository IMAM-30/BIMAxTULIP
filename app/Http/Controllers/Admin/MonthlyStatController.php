<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MonthlyStatRequest;
use App\Models\MonthlyStat;
use Illuminate\Http\Request;

class MonthlyStatController extends Controller
{
    public function index()
    {
        $items = MonthlyStat::orderBy('year','desc')->orderBy('month')->paginate(20);
        return view('Admin.monthly_stats.index', compact('items'));
    }

    public function create()
    {
        return view('Admin.monthly_stats.create');
    }

    public function store(MonthlyStatRequest $request)
    {
        MonthlyStat::create($request->validated());
        return redirect()->route('admin.monthly-stats.index')->with('success','Data berhasil ditambahkan.');
    }

    public function edit(MonthlyStat $monthlyStat)
    {
        return view('Admin.monthly_stats.edit', ['item' => $monthlyStat]);
    }

    public function update(MonthlyStatRequest $request, MonthlyStat $monthlyStat)
    {
        $monthlyStat->update($request->validated());
        return redirect()->route('admin.monthly-stats.index')->with('success','Data berhasil diperbarui.');
    }

    public function destroy(MonthlyStat $monthlyStat)
    {
        $monthlyStat->delete();
        return redirect()->route('admin.monthly-stats.index')->with('success','Data berhasil dihapus.');
    }
}
