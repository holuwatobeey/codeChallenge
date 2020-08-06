<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Product;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        $products = $this->user->products()->get(['name','price','quantity'])->toArray();

        return $products;
    }
    public function show($id)
    {
        $product = $this->user->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product does not exist.'
            ], 400);
        }

        return $product;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->quantity = $request->quantity;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            $product->image = $image;
        }
        if ($this->user->products()->save($product))
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be added.'
            ], 500);
    }
    public function update(Request $request, $id)
    {
        $product = $this->user->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product does not exist.'
            ], 400);
        }

        $updated = $product->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be updated.'
            ], 500);
        }
    }
    public function destroy($id)
    {
        $product = $this->user->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product does not exist.'
            ], 400);
        }

        if ($product->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be deleted.'
            ], 500);
        }
    }
    public function exportExcel() 
    {
        return Excel::download(new ProductExport, 'product.xlsx');
    }
    public function exportCsv() 
    {
        return Excel::download(new ProductExport, 'product.csv');
    }
    public function getFactorial()
    {
        $num = 13;  
        $factorial = 1;  
        for ($x=$num; $x>=1; $x--)   
        {  
        $factorial = $factorial * $x;  
        }  
        echo "Factorial of $num is $factorial";  

    }
}
