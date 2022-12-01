<?php

namespace App\Http\Controllers;


use App\Models\UserCollection;
use App\Http\Requests\StoreUserCollectionRequest;
use App\Http\Requests\UpdateUserCollectionRequest;
use Illuminate\Support\Facades\Auth;

class UserCollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:sanctum"])->only(["owner"]);
    }


    public function OwnerToCollection()
    {
        dd("you are owner");

    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserCollectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserCollectionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserCollection  $userCollection
     * @return \Illuminate\Http\Response
     */
    public function show(UserCollection $userCollection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserCollection  $userCollection
     * @return \Illuminate\Http\Response
     */
    public function edit(UserCollection $userCollection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserCollectionRequest  $request
     * @param  \App\Models\UserCollection  $userCollection
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserCollectionRequest $request, UserCollection $userCollection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserCollection  $userCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserCollection $userCollection)
    {
        //
    }
}
