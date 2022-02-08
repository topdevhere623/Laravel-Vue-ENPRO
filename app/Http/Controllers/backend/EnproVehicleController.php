<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnproVehicleRequest;
use App\Http\Resources\EnproVehicleResource;
use App\Models\EnproVehicle;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnproVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return EnproVehicleResource::collection(EnproVehicle::paginate(10));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store(StoreEnproVehicleRequest $request)
    {
        $validated = EnproVehicle::create($request->validated());
        return (new EnproVehicleResource($validated))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return EnproVehicleResource
     */
    public function show($id)
    {
        return new EnproVehicleResource(EnproVehicle::findOrFail($id));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function update(StoreEnproVehicleRequest $request, $id)
    {
        $data = EnproVehicle::findOrFail($id);
        $data->update($request->validated());

        return (new EnproVehicleResource($data))
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
        EnproVehicle::findOrFail($id)->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }
}
