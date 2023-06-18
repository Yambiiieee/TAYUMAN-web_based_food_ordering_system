<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;


class RefundController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $userType="";

        if($user->type=='store'){
            $userType="p.product_by";
        }else{
            $userType="o.user";

        }

        $limit = $request->input('limit');

        $type = $request->input('type');

        $search=$request->search;

        $refundOrders = DB::table('refunds as r' )
            ->select(
                'r.id as refund_id',
                'r.status as refund_status',
                'r.reason',
                'r.created_at as refund_date',
                'o.id as order_id',
                'o.payment', 
                'o.note', 
                'o.place', 
                'o.time', 
                'o.status', 
                'o.quantity as order_quantity', 
                'r.created_at as order_date',
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
                )
            ->leftJoin('orders as o', 'r.order', '=', 'o.id')
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->when($search, function ($query) use ($search) {
                return $query
                        ->whereOr('p.name', 'like','%' . $search .'%')
                        ->whereOr('p.description', 'like','%' . $search .'%');
                })
            ->when($type, function ($query) use ($type) {
                    return $query->where('r.status', '=',$type);
            })
            ->when($limit, function ($query) use ($limit) {
                return $query->take($limit);
            })
        ->where($userType, '=',$user->id)
        ->orderBy('r.created_at', 'desc')
        ->get();



        return $this->success(["refund_orders" =>   $refundOrders], "", 200);
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
        $idHolder=$id;

        $product = DB::table('refunds')
        ->where('id', '=',$id)
        ->get();

        $data = $request->validate([
            'status' => 'required',
        ]);

        DB::table('refunds')
        ->where('id', $idHolder)
        ->update([
            'status'=>$data['status'],
        ]);

        $refundOrders = DB::table('refunds as r' )
            ->select(
                'r.id as refund_id',
                'r.status as refund_status',
                'r.reason',
                'r.created_at as refund_date',
                'o.id as order_id',
                'o.payment', 
                'o.note', 
                'o.place', 
                'o.time', 
                'o.status', 
                'o.quantity as order_quantity', 
                'r.created_at as order_date',
                'p.id as product_id', 
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'st.store_name',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                )
            ->leftJoin('orders as o', 'r.order', '=', 'o.id')
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('r.id', '=',$id)
            ->get();
            
            return $this->success(["refund_order" =>  $refundOrders[0]], "", 200);
        

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
            'order' => 'required',
            'reason' => 'required',
            'status' => 'required',
        ]);

        $checkExist= DB::table('refunds')
        ->where('order', '=',$data['order'])
        // ->get()
        ->count();
 
        if($checkExist > 0){
            return $this->error($checkExist, 'Refund request for this order already sent', 400);
        }

        $id =  DB::table('refunds')->insertGetId([
            'order' => $data['order'],
            'reason'=>$data['reason'],
            'status'=>$data['status'],
            'created_at'=>now(),
        ]);
    
        if($id!=0){
            
                
            $refundOrders = DB::table('refunds as r' )
            ->select(
                'r.id as refund_id',
                'r.status as refund_status',
                'r.reason',
                'r.created_at as refund_date',
                'o.id as order_id', 
                'o.payment', 
                'o.note', 
                'o.place', 
                'o.time', 
                'o.status', 
                'o.quantity as order_quantity', 
                'r.created_at as order_date',
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
                )
            ->leftJoin('orders as o', 'r.order', '=', 'o.id')
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->where('r.id', '=',$id)
            ->get();
            
            return $this->success(["refund_order" =>  $refundOrders[0]], "", 200);
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
        if($id!=0){
            
                
            $refundOrders = DB::table('refunds as r' )
            ->select(
                'r.id as refund_id',
                'r.status as refund_status',
                'r.reason',
                'r.created_at as refund_date',
                'o.id as order_id',
                'o.payment', 
                'o.note', 
                'o.place', 
                'o.time', 
                'o.status', 
                'o.quantity as order_quantity', 
                'r.created_at as order_date',
                'p.id as product_id', 
                'p.name',
                'p.type',
                'p.image',
                'p.price',
                'st.store_name',
                'p.quantity',
                'p.description',
                'p.product_by',
                'u.first_name',
                'u.last_name',
                'u.contact_number',
                'u.id as user_id',
                )
            ->leftJoin('orders as o', 'r.order', '=', 'o.id')
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->leftJoin('users as st', 'p.product_by', '=', 'st.id')
            ->where('r.id', '=',$id)
            ->get();
            
            return $this->success(["refund_order" =>  $refundOrders[0]], "", 200);
        }
    
            return $this->error('', 'Record not found', 400);
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

        $data = $request->validate([
            'order' => 'required',
            'reason' => 'required',
            'status' => 'required',
        ]);

        
           DB::table('orders')
        ->where('id', $idHolder)
        ->update([
            'order' => $data['order'],
            'reason'=>$data['reason'],
            'status'=>$data['status'],
            'updated_at'=>now(),
        ]);
        
    
        if($id!=0){
            
                
            $refundOrders = DB::table('refund as r' )
            ->select(
                'r.id as refund_id',
                'r.status as refund_status',
                'r.reason',
                'r.created_at as refund_date',
                'o.id as order_id',
                'o.payment', 
                'o.note', 
                'o.place', 
                'o.time', 
                'o.status', 
                'o.quantity as order_quantity', 
                'r.created_at as order_date',
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
                )
            ->leftJoin('orders as o', 'r.order', '=', 'o.id')
            ->leftJoin('users as u', 'o.user', '=', 'u.id')
            ->leftJoin('products as p', 'o.product', '=', 'p.id')
            ->where('r.id', '=',$id)
            ->get();
            
            return $this->success(["refund_order" =>  $refundOrders[0]], "", 200);
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
         $refund = DB::table('refunds')
         ->where('id', '=',$id)
         ->get();
 
         DB::table('refunds')->where('id', '=',$id)->delete();
       
 
         /**Return data */
         return $this->success([], "Deleted successfully", 200);
    }
}
