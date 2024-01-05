<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoalRequest;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $soals = \App\Models\Soal::paginate(10);
        $soals = DB::table('soals')
            // $users = User::
            ->when($request->input('pertanyaan'), function ($query, $name) {
                return $query->where('pertanyaan', 'like', '%' . $name . '%');
            })
            ->where('deleted_at', null)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('pages.soals.index', compact('soals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.soals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSoalRequest $request)
    {
        $data = $request->all();
        \App\Models\Soal::create($data);
        return redirect()->route('soal.index')->with('success', 'Soal Created Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $soal = \App\Models\Soal::findOrFail($id);
        return view('pages.soals.edit', compact('soal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSoalRequest $request, Soal $soal)
    {
        $data = $request->all();
        $soal->update($data);
        return redirect()->route('soal.index')->with('success', 'Soal Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Soal $soal)
    {
        $soal->delete();
        return redirect()->route('soal.index')->with('success', 'Soal Deleted Successfully');
    }
}
