<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Order::paginate(12));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        return response()->json($request->user()->orders);
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
        $request->validate([
            'products.*.name' => ['required', 'string']
        ]);

        $order = $request->user()->orders()->create();


        foreach ($request->products as $product) {
            $order->basket()->create([
                'name' => $product['name'],
                'description' => isset($product['description']) ? $product['description'] : null,
            ]);
        }

        return response()->json($order->fresh());
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
    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:orders,id'],
            'products.*.name' => ['required', 'string']
        ]);

        $order = Order::find($request->id);

        $order->basket()->delete();

        foreach ($request->products as $product) {
            $order->basket()->create([
                'name' => $product['name'],
                'description' => isset($product['description']) ? $product['description'] : null,
            ]);
        }

        return response()->json($order->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();

        return response()->json();
    }
}
