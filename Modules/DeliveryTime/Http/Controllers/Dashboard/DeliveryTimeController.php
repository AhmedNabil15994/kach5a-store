<?php

namespace Modules\DeliveryTime\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\DeliveryTime\Repositories\DeliveryTimeRepository;

class DeliveryTimeController extends Controller
{

    protected $delivery_times;

    function __construct(DeliveryTimeRepository $delivery_times)
    {
        $this->delivery_times = $delivery_times;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $delivery_times = $this->delivery_times->getAll();
        return view('deliverytime::dashboard.delivery_times.index')->with('delivery_times', $delivery_times);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('deliverytime::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('deliverytime::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('deliverytime::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        try {
            $create = $this->delivery_times->createOrUpdate($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
