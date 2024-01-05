<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\content;
use App\Http\Requests\StorecontentRequest;
use App\Http\Requests\UpdatecontentRequest;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $content = \App\Models\Content::when($request->id, function ($query, $id) {
            return $query->where('id', $id);
        })->get();

        return response()->json([
            'status' => 'success',
            'data' => $content
        ]);
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
    public function store(StorecontentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(content $content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecontentRequest $request, content $content)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(content $content)
    {
        //
    }
}
