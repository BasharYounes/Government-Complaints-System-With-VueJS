<?php

namespace App\Http\Controllers;

use App\Models\GovernmentEntities;
use App\Repositories\GovernementEntities\GovernmentEntityRepository;
use App\Http\Resources\GovernmentEntityResource;
use Illuminate\Http\Request;

class GovernmentEntitiesController extends Controller
{
    public function __construct(protected GovernmentEntityRepository $governmentEntityRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $entities = $this->governmentEntityRepository->getAllEntities();

            return $this->success(
                'Government entities retrieved successfully',
                GovernmentEntityResource::collection($entities),
                200
            );
        } catch (\Throwable $e) {
            // Return server error message temporarily to help debugging client 400
            return $this->error($e->getMessage(), null, 400);
        }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GovernmentEntities $government_entities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GovernmentEntities $government_entities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GovernmentEntities $government_entities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GovernmentEntities $government_entities)
    {
        //
    }
}
