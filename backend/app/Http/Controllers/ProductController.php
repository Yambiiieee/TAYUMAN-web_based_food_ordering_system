<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $limit = $request->input('limit');

        $type = $request->input('type');

        $search = $request->search;

        $products = DB::table('products as p')
            ->select(
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.quantity',
                'p.description',
                'p.product_by',
                'p.price',
                'p.size',
                'u.store_name',
            )
            ->join('users as u', 'p.product_by', '=', 'u.id')
            ->when($search, function ($query) use ($search) {
                return $query
                    ->where('p.name', 'like', '%' . $search . '%')
                    ->where('p.description', 'like', '%' . $search . '%');
            })
            ->when($type, function ($query) use ($type) {
                return $query->where('p.type', '=', $type);
            })
            ->when($limit, function ($query) use ($limit) {
                return $query->take($limit);
            })
            // ->where('p.id', '=',$idHolder)
            ->get();

        return $this->success(["products" => $products], "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStoreProducts(Request $request, $id)
    {
        $limit = $request->input('limit');

        $type = $request->input('type');

        $min = $request->input('min_price');

        $max = $request->input('max_price');

        $search = $request->search;

        $products = DB::table('products as p')
            ->select(
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.quantity',
                'p.description',
                'p.product_by',
                'p.price',
                'p.size',
                'u.store_name',
            )
            ->join('users as u', 'p.product_by', '=', 'u.id')
            // ->when($search, function ($query) use ($search) {
            //     return $query
            //             ->orWhere('p.name', 'like','%' . $search .'%')
            //             ->orWhere('p.description', 'like','%' . $search .'%');
            //     })
            ->when($type, function ($query) use ($type) {
                return $query->where('p.type', '=', $type);
            })
            ->when($min, function ($query) use ($min) {
                return $query->where('p.price', '>=', $min);
            })
            ->when($max, function ($query) use ($max) {
                return $query->where('p.price', '<=', $max);
            })
            ->when($limit, function ($query) use ($limit) {
                return $query->take($limit);
            })
            ->where('p.product_by', '=', $id)
            ->orderBy('p.created_at', 'desc');
        // ->get();

        if ($search !== "") {
            $products = $products->where(function ($query) use ($search) {
                $query
                    ->where('p.name', 'like', "%$search%")
                    ->orWhere('p.description', 'like', "%$search%");

            });
        }

        $products = $products->get();

        return $this->success(["products" => $products], "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllProducts(Request $request)
    {

        $limit = $request->input('limit');

        $type = $request->input('type');

        $min = $request->input('min_price');

        $max = $request->input('max_price');

        $search = $request->search;

        $products = DB::table('products as p')
            ->select(
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.quantity',
                'p.description',
                'p.product_by',
                'p.price',
                'p.size',
                'u.store_name',
            )
            ->join('users as u', 'p.product_by', '=', 'u.id')
            // ->when($search, function ($query) use ($search) {
            //     return $query
            //             ->orWhere('p.name', 'like','%' . $search .'%')
            //             ->orWhere('p.description', 'like','%' . $search .'%');
            //     })
            ->when($type, function ($query) use ($type) {
                return $query->where('p.type', '=', $type);
            })
            ->when($min, function ($query) use ($min) {
                return $query->where('p.price', '>=', $min);
            })
            ->when($max, function ($query) use ($max) {
                return $query->where('p.price', '<=', $max);
            })
            ->when($limit, function ($query) use ($limit) {
                return $query->take($limit);
            })
            ->orderBy('p.created_at', 'desc');
        // ->where('p.product_by', '=',$id);
        // ->get();

        if ($search !== "") {
            $products = $products->where(function ($query) use ($search) {
                $query
                    ->where('p.name', 'like', "%$search%")
                    ->orWhere('p.description', 'like', "%$search%");

            });
        }

        $products = $products->get();

        return $this->success(["products" => $products], "", 200);
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
            'name' => 'required|string',
            'type' => 'required|string',
            'price' => 'required',
            'quantity' => 'required',
            'description' => 'required|string',
            'size' => 'required|string',

        ]);


        if (!$request->hasFile('image')) {
            return $this->error('', 'Please upload file', 404);
        }

        $file = $request->file('image')->store('products', 'public_uploads');

        $data['image'] = asset('uploads/' . $file);


        $id = DB::table('products')->insertGetId([
            'name' => $data['name'],
            'type' => $data['type'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'size' => $data['size'],
            'image' => $data['image'],
            'description' => $data['description'],
            'product_by' => $user->id,
            'created_at' => now(),
        ]);

        if ($id != 0) {
            $products = DB::table('products as p')
                ->select(
                    'p.id as product_id',
                    'p.name',
                    'p.type',
                    'p.image',
                    'p.quantity',
                    'p.description',
                    'p.product_by',
                    'p.size',
                    'p.price',
                    'u.store_name',
                )
                ->join('users as u', 'p.product_by', '=', 'u.id')
                ->where('p.id', '=', $id)
                ->get();

            return $this->success(["product" => $products[0]], "", 200);
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
        $products = DB::table('products as p')
            ->select(
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.quantity',
                'p.description',
                'p.product_by',
                'p.price',
                'p.size',
                'u.store_name',
            )
            ->join('users as u', 'p.product_by', '=', 'u.id')
            ->where('p.id', '=', $id)
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
            ->where('o.product', '=', $id)
            ->get();

        return $this->success(["product" => $products[0], "ratings" => $ratings], "", 200);
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

        $product = DB::table('products')
            ->where('id', '=', $id)
            ->get();

        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'price' => 'required',
            'quantity' => 'required',
            'description' => 'required|string',
            'size' => 'required|string',


        ]);


        if ($request->hasFile('image')) {
            $file = $request->file('image')->store('products', 'public_uploads');

            $data['image'] = asset('uploads/' . $file);


            $id = DB::table('products')
                ->where('id', $idHolder)
                ->update([
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'price' => $data['price'],
                    'quantity' => $data['quantity'],
                    'size' => $data['size'],
                    'image' => $data['image'],
                    'description' => $data['description'],
                ]);

        } else {
            $id = DB::table('products')
                ->where('id', $idHolder)
                ->update([
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'price' => $data['price'],
                    'size' => $data['size'],
                    'quantity' => $data['quantity'],
                    'description' => $data['description'],
                ]);
        }

        $products = DB::table('products as p')
            ->select(
                'p.id as product_id',
                'p.name',
                'p.type',
                'p.image',
                'p.quantity',
                'p.description',
                'p.product_by',
                'p.price',
                'p.size',   
                'u.store_name',
            )
            ->join('users as u', 'p.product_by', '=', 'u.id')
            ->where('p.id', '=', $idHolder)
            ->get();

        return $this->success(["product" => $products[0]], "", 200);

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
        $product = DB::table('products')
            ->where('id', '=', $id)
            ->get();


        DB::table('products')->where('id', '=', $product[0]->id)->delete();

        /**Remove file in the directory */
        Storage::disk('public_uploads')->delete($product[0]->image);

        /**Return data */
        return $this->success([], "Deleted successfully", 200);
    }
}