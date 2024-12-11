<?php

namespace App\Http\Controllers;

use App\Http\Resources\Outline as OutlineResource;
use App\Models\Outline;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class OutlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(#[CurrentUser] User $user): Response|JsonResource
    {
        return OutlineResource::collection(
            $user->outlines->sortByDesc('id')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, #[CurrentUser] User $user): Response|JsonResource
    {
        return OutlineResource::make(
            $user->outlines()->create()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Outline $outline): Response|JsonResource
    {
        return OutlineResource::make($outline);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outline $outline): Response|JsonResource
    {
        return OutlineResource::make(
            $outline
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outline $outline): Response|JsonResource
    {
        return response()->noContent();
    }
}
