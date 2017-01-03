<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sponsor as Requests;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\Index $request)
    {
        $orderField = $request->input('order', 'updated_at');
        $orderDir = $request->input('order_dir', 'DESC');
        $limit = $request->input('limit', 10);
        $requestedStatuses = $request->input('statuses', null);

        $sponsorsQuery = Sponsor
            ::query();

        // TODO: this should probably support something similar to the
        // document query, where if you can see the status of any sponsor you
        // are a part of, even if it still pending
        $statuses = [];
        if ($requestedStatuses) {
            if (in_array(Sponsor::STATUS_PENDING, $requestedStatuses)
                && !($request->user() && $request->user()->isAdmin())
            ) {
                $statuses = [Sponsor::STATUS_ACTIVE];
            } else {
                $statuses = $requestedStatuses;
            }
        } elseif ($request->user() && $request->user()->isAdmin()) {
            $statuses = Sponsor::getStatuses();
        } else {
            $statuses = [Sponsor::STATUS_ACTIVE];
        }

        $sponsorsQuery->whereIn('status', $statuses);

        if ($request->has('name')) {
            $name = $request->get('name');
            $sponsorsQuery->where('name', 'LIKE', "%$name%");
        }

        $sponsors = $sponsorsQuery
            ->orderby($orderField, $orderDir)
            ->paginate($limit);

        $sponsorsCapabilities = [];
        $baseSponsorCapabilities = [
            'viewDocs' => true,
            'open' => true,
            'edit' => false,
            'viewStatus' => false,
        ];
        $canSeeAtLeastOneStatus = false;

        foreach ($sponsors as $sponsor) {
            $caps = $baseSponsorCapabilities;
            if ($request->user()) {
                if ($request->user()->isAdmin()) {
                    $caps = array_map(function ($item) { return true; }, $caps);
                    $canSeeAtLeastOneStatus = true;
                } elseif ($sponsor->isValidUserForGroup($request->user())) {
                    $caps = array_map(function ($item) { return true; }, $caps);
                    $canSeeAtLeastOneStatus = true;
                }
            }
            $sponsorsCapabilities[$sponsor->id] = $caps;
        }

        $validStatuses = Sponsor::getStatuses();

        return view('sponsors.list', compact([
            'sponsors',
            'sponsorsCapabilities',
            'canSeeAtLeastOneStatus',
            'validStatuses',
        ]));
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
