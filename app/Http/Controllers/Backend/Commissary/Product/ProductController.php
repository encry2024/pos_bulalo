<?php

namespace App\Http\Controllers\Backend\Commissary\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commissary\Inventory\Inventory;
use App\Models\Commissary\Product\Product;
use App\Models\Commissary\History\History;
use App\Models\Category\Category;

class ProductController extends Controller
{
    public function index(){
        $histories = History::take(15)->orderBy('created_at', 'desc')->get();

    	return view('backend.commissary.product.index', compact('histories'));
    }

    public function show(Product $product){
        $product = Product::with(['ingredients' => function($q) {
                    $q->withTrashed();
                }])
                ->where('id', $product->id)
                ->firstOrFail();

        return view('backend.commissary.product.show', compact('product'));
    }

    public function create(){
        $selections  = [];
    	$ingredients = Inventory::with('other_inventory', 'drygood_inventory')->get();
        $categories  = Category::pluck('name', 'id');

    	for($i = 0; $i < count($ingredients); $i++)
        {
            $name = '';

            if($ingredients[$i]->supplier == 'Other')
            {
                $name = $ingredients[$i]->other_inventory->name;

                $selections[$ingredients[$i]->id] = $name;
            }
            else
            {
                $name = $ingredients[$i]->drygood_inventory->name;

                $selections[$ingredients[$i]->id] = $name;
            }
        }
        
    	return view('backend.commissary.product.create', compact('ingredients', 'selections', 'categories'));
    }

    public function store(Request $request){
    	$ingredients = json_decode($request->ingredients);

    	$product = new Product();
    	$product->name = $request->name;
    	$product->category_id = $request->category;
    	$product->save();


    	foreach ($ingredients as $item) {
    		$ingredient = Inventory::findOrFail($item->id);

    		$ingredient->products()->attach($product, ['quantity' => $item->quantity, 'unit_type' => $item->unit_type]);
    	}

    	return redirect()->route('admin.commissary.product.index');
    }

    public function destroy(Product $product){
        $product->delete();

        return redirect()->route('admin.commissary.product.index')->withFlashDanger('Product has been deleted!');
    }

    public function unit_type($id){
        $inventory = Inventory::findOrFail($id);

        return $inventory->physical_quantity;
    }
}
