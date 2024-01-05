<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Http\Requests\StoreUjianRequest;
use App\Http\Requests\UpdateUjianRequest;
use App\Http\Resources\SoalResource;
use App\Models\Soal;
use App\Models\UjianSoalList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUjianRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ujian $ujian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ujian $ujian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUjianRequest $request, Ujian $ujian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ujian $ujian)
    {
        //
    }

    //create ujian
    public function createUjian(Request $request)
    {
        //soal angka
        // DB::enableQueryLog();
        $soalAngka = Soal::where('kategori', 'Numeric')->inRandomOrder()->Limit(20)->get();
        //soal verbal
        $soalVerbal = Soal::where('kategori', 'Verbal')->inRandomOrder()->Limit(20)->get();
        //soal Logika
        $soalLogika = Soal::where('kategori', 'Logika')->inRandomOrder()->Limit(20)->get();

        //create ujian
        $ujian = Ujian::create([
            'users_id' => $request->user()->id,

        ]);
        //mengisi ujiansoalangka
        foreach ($soalAngka as $soal) {
            UjianSoalList::create([
                'ujians_id' => $ujian->id,
                'soals_id' => $soal->id
            ]);
        }
        //mengisi ujiansoalverbal
        foreach ($soalVerbal as $soal) {
            UjianSoalList::create([
                'ujians_id' => $ujian->id,
                'soals_id' => $soal->id
            ]);
        }
        //mengisi ujiansoallogika
        foreach ($soalLogika as $soal) {
            UjianSoalList::create([
                'ujians_id' => $ujian->id,
                'soals_id' => $soal->id
            ]);
        }

        return response()->json(
            [
                'message' => 'Ujian berhasil dibuat',
                'data' => $ujian
            ]
        );
    }
    public function getListSoalByKategori(Request $request)
    {
        $ujian = Ujian::where('users_id', $request->user()->id)->latest('id')->first();
        //if ujian is empty
        if (!$ujian) {
            return response()->json(
                [
                    'message' => 'Ujian tidak ditemukan',
                    "timer" => 0,
                    'data' => []
                ],
                200
            );
        }
        $ujianSoalList = UjianSoalList::where('ujians_id', $ujian->id)->get();
        $ujianSoalListId = [];
        foreach ($ujianSoalList as $soal) {
            array_push($ujianSoalListId, $soal->soals_id);
        }
        // print_r($query);
        // $soal = Soal::where('kategori', $kategori)->whereNotIn('id', $ujianSoalListId)->inRandomOrder()->first();
        $soal = Soal::where('kategori', $request->kategori)->whereIn('id', $ujianSoalListId)->get();

        //timer by kategori
        $timer = $ujian->timer_angka;
        if ($request->kategori == 'Verbal') {
            $timer = $ujian->timer_verbal;
        } elseif ($request->kategori == 'Logika') {
            $timer = $ujian->timer_logika;
        }
        return response()->json(
            [
                'message' => 'Berhasil mendapatkan soal',
                'timer' => $timer,
                'data' => SoalResource::collection($soal)
            ],
            200
        );
    }
    public function jawabSoal(Request $request)
    {

        $validatedData = $request->validate([
            'soal_id' => 'required',
            'jawaban' => 'required'
        ]);
        $ujian = Ujian::where('users_id', $request->user()->id)->latest('id')->first();
        if (!$ujian) {
            return response()->json(
                [
                    'message' => 'Ujian tidak ditemukan',
                    'data' => []
                ],
                200
            );
        }
        $ujianSoalList = UjianSoalList::where('ujians_id', $ujian->id)->where('soals_id', $validatedData['soal_id'])->first();
        $soal = Soal::where('id', $validatedData['soal_id'])->first();

        //cek jawab
        if ($soal->kunci == $validatedData['jawaban']) {
            $ujianSoalList->kebenaran = true;
            $ujianSoalList->save();
        } else {
            $ujianSoalList->kebenaran = false;
            $ujianSoalList->save();
        }

        return response()->json(
            [
                'message' => 'Berhasil simpan jawaban',
                'jawaban' => $ujianSoalList->kebenaran
            ],
            200
        );
    }

    public function hitungNilaiUjianByKategori(Request $request)
    {
        $kategori = $request->kategori;
        $ujian = Ujian::where('users_id', $request->user()->id)->latest('id')->first();
        if (!$ujian) {
            return response()->json(
                [
                    'message' => 'Ujian tidak ditemukan',
                    'data' => []
                ],
                200
            );
        }
        $ujianSoalList = UjianSoalList::where('ujians_id', $ujian->id)->get();
        // dd($ujianSoalList);
        $ujianSoalList = $ujianSoalList->filter(function ($value, $key) use ($kategori) {
            return  $value->soals->kategori == $kategori;
        });

        $totalBenar = $ujianSoalList->where('kebenaran', true)->count();
        echo $totalBenar;
        $totalSoal = $ujianSoalList->count();
        echo $totalSoal;
        $nilai = ($totalBenar / $totalSoal) * 100;

        $kategori_field = 'nilai_verbal';
        if ($kategori == 'Numeric') {
            $kategori_field = 'nilai_angka';
        } elseif ($kategori == 'Logika') {
            $kategori_field = 'nilai_logika';
        }
        $ujian->update([
            $kategori_field => $nilai
        ]);

        return response()->json(
            [
                'message' => 'Berhasil mendapatkan nilai',
                'nilai' => $nilai
            ],
            200
        );
    }
}
