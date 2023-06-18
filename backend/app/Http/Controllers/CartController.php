<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;


class CartController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserCart()
    {
        $user = auth()->user();

        $carts = DB::table('carts as c')
            ->select(
                'c.id as cart_id',
                'c.quantity as order_quantity',
                'c.created_at as cart_created',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.quantity',
                'p.price',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.first_name',
                'u.id as user_id',
            )
            ->join('users as u', 'c.user', '=', 'u.id')
            ->join('products as p', 'c.product', '=', 'p.id')
            ->where('c.user', '=', $user->id)
            ->orderBy('c.created_at', 'desc')
            ->get();

        return $this->success(["carts" => $carts], "", 200);
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
        $user = auth()->user();

        $data = $request->validate([
            'product' => 'required',
            'quantity' => 'required',
        ]);

        /**Check if theres existoing cart */
        $checkCart = DB::table('carts')
            ->where('product', '=', $data["product"])
            ->where('user', '=', $user->id)
            ->get();

        if ($checkCart->isEmpty()) {

            /**Check if the product have stocks */
            $product = DB::table('products')
                ->where('id', '=', $data["product"])
                ->get();


            if ($product[0]->quantity == 0) {
                return $this->error('', 'Out of stocks', 400);
            }

            $id = DB::table('carts')->insertGetId([
                'product' => $data['product'],
                'quantity' => $data['quantity'],
                'user' => $user->id,
                'created_at' => now(),
            ]);
            if ($id != 0) {
                // $qty=$product[0]->quantity- $data["quantity"];
                // DB::table('products')
                // ->where('id', $product[0]->id)
                // ->update([
                //     'quantity'=>$qty,
                // ]);

                $carts = DB::table('carts as c')
                    ->select(
                        'c.id as cart_id',
                        'c.quantity as order_quantity',
                        'c.created_at as cart_created',
                        'p.id as product_id',
                        'p.name',
                        'p.type',
                        'p.image',
                        'p.quantity',
                        'p.price',
                        'p.description',
                        'p.product_by',
                        'u.first_name',
                        'u.first_name',
                        'u.id as user_id',
                    )
                    ->join('users as u', 'c.user', '=', 'u.id')
                    ->join('products as p', 'c.product', '=', 'p.id')
                    ->where('c.id', '=', $id)
                    ->get();

                return $this->success(["cart" => $carts[0], "test" => $id], "", 200);
            }
        } else {
            DB::table('carts')
                ->where('id', $checkCart[0]->id)
                ->update([
                    'quantity' => $data['quantity'] + $checkCart[0]->quantity,
                ]);

            $carts = DB::table('carts as c')
                ->select(
                    'c.id as cart_id',
                    'c.quantity as order_quantity',
                    'c.created_at as cart_created',
                    'p.id as product_id',
                    'p.name',
                    'p.type',
                    'p.image',
                    'p.quantity',
                    'p.price',
                    'p.description',
                    'p.product_by',
                    'u.first_name',
                    'u.first_name',
                    'u.id as user_id',
                )
                ->join('users as u', 'c.user', '=', 'u.id')
                ->join('products as p', 'c.product', '=', 'p.id')
                ->where('c.id', '=', $checkCart[0]->id)
                ->get();

            return $this->success(["cart" => $carts[0]], "", 200);

        }
        return $this->error('', 'Record not found', 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carts = DB::table('carts as c')
            ->select(
                'c.id as cart_id',
                'c.quantity as order_quantity',
                'c.created_at as cart_created',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.quantity',
                'p.price',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.first_name',
                'u.id as user_id',
            )
            ->join('users as u', 'c.user', '=', 'u.id')
            ->join('products as p', 'c.product', '=', 'p.id')
            ->where('c.id', '=', $id)
            ->get();

        return $this->success(["cart" => $carts[0]], "", 200);
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
        $user = auth()->user();
        $idHolder = $id;

        $cart = DB::table('cart')
            ->where('id', '=', $id)
            ->get();

        $data = $request->validate([
            'quantity' => 'required',
        ]);


        DB::table('carts')
            ->where('id', $idHolder)
            ->update([
                'quantity' => $data['quantity'],
            ]);

        if ($id != 0) {
            $carts = DB::table('carts as c')
                ->select(
                    'c.id as cart_id',
                    'c.quantity as order_quantity',
                    'c.created_at as cart_created',
                    'p.id as product_id',
                    'p.name',
                    'p.type',
                    'p.image',
                    'p.quantity',
                    'p.price',
                    'p.description',
                    'p.product_by',
                    'u.first_name',
                    'u.first_name',
                    'u.id as user_id',
                )
                ->join('users as u', 'c.user', '=', 'u.id')
                ->join('products as p', 'c.product', '=', 'p.id')
                ->where('c.id', '=', $id)
                ->get();

            return $this->success(["cart" => $carts[0]], "", 200);
        }

        return $this->error('', 'Record not found', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**Get data */
        $cart = DB::table('carts')
            ->where('id', '=', $id)
            ->get();

        DB::table('carts')->where('id', '=', $id)->delete();

        /**Return data */
        return $this->success([], "Deleted successfully1    ", 200);
    }
}