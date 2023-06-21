<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendReceipt;

class OrderController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserOrders(Request $request)
    {
        $user = auth()->user();

        $limit = $request->input('limit');

        $type = $request->input('type');

        $order_by = $request->order_by;

        $search = $request->search;

        $startDate = $request->startDate;

        $endDate = $request->endDate;

        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.place',
                'o.time',
                'o.status',
                'o.payment_method',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            // ->when($search, function ($query) use ($search) {
            //     return $query
            //             ->whereOr('p.name', 'like','%' . $search .'%')
            //             ->whereOr('p.description', 'like','%' . $search .'%');
            //     })
            ->when($type, function ($query) use ($type) {
                return $query->where('o.status', '=', $type);
            })
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('o.created_at', '>=', Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay());
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->where('o.created_at', '<=', Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay());
            })
            ->when($limit, function ($query) use ($limit) {
                return $query->take($limit);
            })
            ->where('p.product_by', '=', $user->id);
        // ->orderBy('o.created_at', 'desc')
        // ->when($order_by, function ($query) use ($order_by) {
        //     return $query->orderBy('u.last_name', $order_by);
        // });


        if ($search != "") {
            $orders = $orders->where(function ($query) use ($search) {
                $query
                    ->where('p.name', 'like', "%$search%")
                    ->orWhere('p.description', 'like', "%$search%");

            });
        }

        if ($order_by != "" || $order_by != null) {

            $orders = $orders->orderBy('u.last_name', $order_by);
        } else {
            $orders = $orders->orderBy('o.created_at', 'desc');
        }

        $orders = $orders->get();
        return $this->success(["orders" => $orders], "", 200);
    }

    public function getSalesToday(Request $request)
    {
        $user = auth()->user();

        $today = Carbon::today();
        $count = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.place',
                'o.time',
                'o.status',
                'o.payment_method',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('o.status', '=', "Done")
            ->where('p.product_by', '=', $user->id)
            ->whereDate('o.created_at', $today)
            ->orderBy('o.created_at', 'desc')
            ->count();

        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.place',
                'o.time',
                'o.status',
                'o.payment_method',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('o.status', '=', "Done")
            ->where('p.product_by', '=', $user->id)
            ->whereDate('o.created_at', $today)
            ->orderBy('o.created_at', 'desc')
            ->get();

        $total = 0;
        foreach ($orders as $order) {
            $total += $order->payment;
        }

        return $this->success(["count" => $count, "total" => $total], "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserPurchase(Request $request)
    {
        $user = auth()->user();

        $limit = $request->input('limit');

        $type = $request->input('type');

        $search = $request->search;

        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.place',
                'o.time',
                'o.status',
                'o.payment_method',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->when($search, function ($query) use ($search) {
                return $query
                    ->whereOr('p.name', 'like', '%' . $search . '%')
                    ->whereOr('p.description', 'like', '%' . $search . '%');
            })
            ->when($type, function ($query) use ($type) {
                return $query->where('o.status', '=', $type);
            })
            ->when($limit, function ($query) use ($limit) {
                return $query->take($limit);
            })
            ->where('o.user', '=', $user->id)
            ->orderBy('o.created_at', 'desc')
            ->get();

        return $this->success(["orders" => $orders], "", 200);
    }

    public function getAllAvailableDelivery(Request $request)
    {
        $user = auth()->user();
        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.place',
                'o.time',
                'o.status',
                'o.payment_method',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('o.status', '=', "For Delivery")
            ->where('o.rider', '=', null)
            ->orderBy('o.created_at', 'desc')
            ->get();

        return $this->success(["orders" => $orders], "", 200);
    }

    public function getRidersDelivery(Request $request)
    {
        $user = auth()->user();
        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.place',
                'o.time',
                'o.status',
                'o.payment_method',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            // ->where('o.status', '=', "For Delivery")
            ->where('o.rider', '=', $user->id)
            ->orderBy('o.created_at', 'desc')
            ->get();

        return $this->success(["orders" => $orders], "", 200);
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
            'quantity' => 'required',
            'payment' => 'required',
            // 'time' => 'required', // remove this
            'status' => 'required',
            'type' => 'required',
            'payment_method' => 'required',
        ]);

        $cart = DB::table('carts as c')
            ->select(
                'c.id as cart_id',
                'c.quantity as order_quantity',
                'c.created_at as cart_created',
                'p.id as product_id',
                'p.quantity',
                'p.price',
            )
            ->join('products as p', 'c.product', '=', 'p.id')
            ->where('c.id', '=', $request->cart_id)
            ->get();

        if ($cart[0]->quantity == 0 || $cart[0]->order_quantity > $cart[0]->quantity) {
            return $this->error('', 'Out of stocks', 400);
        }

        $id = DB::table('orders')->insertGetId([
            'product' => $cart[0]->product_id,
            'quantity' => $data['quantity'],
            'payment' => $data['payment'],
            'note' => $request->note,
            'place' => $request->place,
            'status' => $data['status'],
            // 'time' => $data['time'],
            'type' => $data['type'],
            'payment_method' => $data['payment_method'],
            'user' => $user->id,
            'created_at' => now(),
        ]);

        if ($id != 0) {

            $qty = $cart[0]->quantity - $data["quantity"];
            DB::table('products')
                ->where('id', $cart[0]->product_id)
                ->update([
                    'quantity' => $qty,
                ]);

            DB::table('carts')->where('id', '=', $request->cart_id)->delete();

            $orders = DB::table('orders as o')
                ->select(
                    'o.id as order_id',
                    'o.payment',
                    'o.note',
                    'o.place',
                    'o.time',
                    'o.status',
                    'o.payment_method',
                    'o.quantity as order_quantity',
                    'p.id as product_id',
                    'p.name',
                    'p.type',
                    'p.image',
                    'p.quantity',
                    'p.description',
                    'p.product_by',
                    'u.first_name',
                    'u.last_name',
                    'u.contact_number',
                    'u.id as user_id',
                    'st.store_name',
                    'st.contact_number as store_gcash',
                )
                ->join('users as u', 'o.user', '=', 'u.id')
                ->join('products as p', 'o.product', '=', 'p.id')
                ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
                ->where('o.id', '=', $id)
                ->get();

            return $this->success(["order" => $orders[0]], "", 200);
        }

        return $this->error('', 'Record not found', 400);
    }
    public function walkInOrder(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'quantity' => 'required',
            'payment' => 'required',

            'status' => 'required',
            'type' => 'required',
            'payment_method' => 'required',
        ]);

        $product = DB::table('products')
            ->where('id', '=', $request->product)
            ->get();

        if ($product[0]->quantity == 0 || $product[0]->quantity < $request->quantity) {
            return $this->error('', 'Out of stocks', 400);
        }

        $id = DB::table('orders')->insertGetId([
            'product' => $request->product,
            'quantity' => $data['quantity'],
            'payment' => $data['payment'],
            'status' => $data['status'],
            'type' => $data['type'],
            'payment_method' => $data['payment_method'],
            'user' => $user->id,
            'created_at' => now(),
        ]);

        if ($id != 0) {

            $qty = $product[0]->quantity - $data["quantity"];
            DB::table('products')
                ->where('id', $product[0]->id)
                ->update([
                    'quantity' => $qty,
                ]);

            $orders = DB::table('orders as o')
                ->select(
                    'o.id as order_id',
                    'o.payment',
                    'o.note',
                    'o.place',
                    'o.time',
                    'o.status',
                    'o.payment_method',
                    'o.quantity as order_quantity',
                    'p.id as product_id',
                    'p.name',
                    'p.type',
                    'p.image',
                    'p.quantity',
                    'p.description',
                    'p.product_by',
                    'u.first_name',
                    'u.last_name',
                    'u.contact_number',
                    'u.id as user_id',
                    'st.store_name',
                    'st.contact_number as store_gcash',
                )
                ->join('users as u', 'o.user', '=', 'u.id')
                ->join('products as p', 'o.product', '=', 'p.id')
                ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
                ->where('o.id', '=', $id)
                ->get();

            return $this->success(["order" => $orders[0]], "", 200);
        }

        return $this->error('', 'Record not found', 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addRating(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'rating' => 'required',
            'order' => 'required'
        ]);



        $id = DB::table('ratings')->insertGetId([
            'order' => $data['order'],
            'rating' => $data['rating'],
            'comment' => $request->comment,
            'created_at' => now(),
        ]);

        if ($id != 0) {

            $ratings = DB::table('ratings as co')
                ->select(
                    'co.id as rating_id',
                    'co.comment',
                    'co.rating',
                    'u.first_name',
                    'u.last_name',
                    'u.contact_number',
                    'u.id as user_id',

                )
                ->join('orders as o', 'co.order', '=', 'o.id')
                ->join('users as u', 'o.user', '=', 'u.id')
                ->where('co.id', '=', $id)
                ->get();

            return $this->success(["rating" => $ratings[0]], "", 200);
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
        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.type as order_type',
                'o.place',
                'o.time',
                'o.payment_method',
                'o.status',
                'o.rider',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'p.size',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
                'r.first_name as rider_first_name',
                'r.last_name as rider_last_name',
                'r.contact_number as rider_contact_number',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->leftJoin('users as r', 'o.rider', '=', 'r.id')
            ->where('o.id', '=', $id)
            ->get();

        $ratings = DB::table('ratings as co')
            ->select(
                'co.id as rating_id',
                'co.comment',
                'co.rating',
                'u.first_name',
                'u.last_name',
                'u.id as user_id',
            )
            ->join('orders as o', 'co.order', '=', 'o.id')
            ->join('users as u', 'o.user', '=', 'u.id')

            ->where('co.order', '=', $id)
            ->get();

        return $this->success(["order" => $orders[0], "ratings" => $ratings], "", 200);
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
        $idHolder = $id;

        $product = DB::table('orders')
            ->where('id', '=', $id)
            ->get();

        $data = $request->validate([
            'quantity' => 'required',
            'payment' => 'required',
            'note' => 'required',
            'place' => 'required',
            'time' => 'required',
            'status' => 'required',
            'payment_method' => 'required',
        ]);

        DB::table('orders')
            ->where('id', $idHolder)
            ->update([

                'quantity' => $data['quantity'],
                'payment' => $data['payment'],
                'note' => $data['note'],
                'place' => $data['place'],
                'time' => $data['time'],
                'status' => $data['status'],
                'payment_method' => $data['payment_method'],
            ]);

        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.place',
                'o.payment_method',
                'o.time',
                'o.status',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->join('users as u', 'p.user', '=', 'u.id')
            ->join('products as p', 'p.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('p.id', '=', $id)
            ->get();

        return $this->success(["order" => $orders[0]], "", 200);

    }

    public function acceptDelivery(Request $request, $id)
    {
        $idHolder = $id;
        $user = auth()->user();
        $order = DB::table('orders')
            ->where('id', '=', $id)
            ->get();

        if ($order[0]->rider != null) {
            return $this->error('', 'Record not found', 400);
        }


        DB::table('orders')
            ->where('id', $idHolder)
            ->update([
                'rider' => $user->id
            ]);

        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.type as order_type',
                'o.place',
                'o.time',
                'o.payment_method',
                'o.status',
                'o.rider',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'p.size',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('o.id', '=', $id)
            ->get();


        return $this->success(["order" => $orders[0]], "", 200);

    }

    public function completeDelivery(Request $request, $id)
    {
        $idHolder = $id;
        $user = auth()->user();



        DB::table('orders')
            ->where('id', $idHolder)
            ->update([
                'status' => "Done"
            ]);

        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.type as order_type',
                'o.place',
                'o.time',
                'o.payment_method',
                'o.status',
                'o.rider',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'p.size',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('o.id', '=', $id)
            ->get();


        return $this->success(["order" => $orders[0]], "", 200);

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $idHolder = $id;

        $product = DB::table('orders')
            ->where('id', '=', $id)
            ->get();

        $data = $request->validate([
            'status' => 'required',
        ]);

        DB::table('orders')
            ->where('id', $idHolder)
            ->update([
                'status' => $data['status'],
            ]);

        $orders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'o.payment',
                'o.note',
                'o.type as order_type',
                'o.place',
                'o.payment_method',
                'o.time',
                'o.status',
                'o.created_at as order_created',
                'o.quantity as order_quantity',
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                'st.store_name',
                'st.contact_number as store_gcash',
            )
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('o.id', '=', $idHolder)
            ->get();

        return $this->success(["order" => $orders[0]], "", 200);

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
        $product = DB::table('orders')
            ->where('id', '=', $id)
            ->get();

        DB::table('orders')->where('id', '=', $id)->delete();


        /**Return data */
        return $this->success([], "Deleted successfully", 200);
    }

    public function sendReceipt(Request $request)
    {

        $request->validate([
            'data' => 'required',
            'total' => 'required',
        ]);

        $user = auth()->user();

        $jsonData = $request->input('data');
        $data = json_decode($jsonData);
        // $total = 1000;
        // $data = [
        //     ['name' => 'Calamares', 'order_quantity' => 10, 'price' => 100, 'payment' => 1000],

        // ];

        Mail::to($user->email)
            ->send(new SendReceipt($user->first_name, $user->email, $request->total, $data));


        return $this->success([], "We have e-mailed your password reset link!", 200);
    }
}