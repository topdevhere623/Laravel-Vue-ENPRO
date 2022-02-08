<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnproToolRequest;
use App\Http\Resources\EnproToolResource;
use App\Models\EnproTool;
use App\Models\EnproVehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class EnproToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return EnproToolResource::collection(EnproTool::paginate(10));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store(StoreEnproToolRequest $request)
    {
        $validated = EnproTool::create($request->validated());
        return (new EnproToolResource($validated))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return EnproToolResource
     */
    public function show($id)
    {
        return new EnproToolResource(EnproTool::findOrFail($id));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function update(StoreEnproToolRequest $request, $id)
    {
        $data = EnproTool::findOrFail($id);
        $data->update($request->validated());

        return (new EnproToolResource($data))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EnproTool::findOrFail($id)->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }
}
