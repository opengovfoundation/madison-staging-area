<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Http\Requests\Sponsor as Requests;
use Illuminate\Http\Request;
use Auth;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: implement?
    }

    /**
     * Display all sponsors available to the current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function manage(Requests\Manage $request)
    {
        $sponsors = Auth::user()->getValidSponsors();
        return view('sponsors.manage', compact('sponsors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\Edit $request, Sponsor $sponsor)
    {
        return view('sponsors.edit', compact('sponsor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Update $request, Sponsor $sponsor)
    {
        $sponsor->name = $request->input('name') ?: null;
        $sponsor->display_name = $request->input('display_name') ?: null;
        $sponsor->address1 = $request->input('address1') ?: null;
        $sponsor->address2 = $request->input('address2') ?: null;
        $sponsor->city = $request->input('city') ?: null;
        $sponsor->state = $request->input('state') ?: null;
        $sponsor->postal_code = $request->input('postal_code') ?: null;
        $sponsor->phone = $request->input('phone') ?: null;

        if ($sponsor->save()) {
            flash(trans('messages.sponsors.updated'));
            return redirect()->route('sponsors.edit', ['sponsor' => $sponsor->id]);
        } else {
            flash(trans('messages.sponsors.update_failed'));
            return redirect()->route('sponsors.edit', ['sponsor' => $sponsor->id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
