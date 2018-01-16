<?php

namespace App\Http\Controllers\Backend\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product\Product;
use App\Models\Inventory\Inventory;
use App\Models\ProductSize\ProductSize;
use App\Http\Requests\Backend\Product\ManageRequest;
use Illuminate\Support\Facades\Input;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

class ProductController extends Controller
{
    public function index(){
    	return view('backend.product.index');
    }

    public function create(){
        $selections  = [];
    	$ingredients = Inventory::with(
                            [
                                'commissary_inventory.other_inventory' => function($q) 
                                {
                                    $q->withTrashed();
                                }, 
                                'commissary_inventory.drygood_inventory' => function($q)
                                {
                                    $q->withTrashed();
                                }
                            ]
                        )
                        ->withTrashed()
                        ->get();        
        
        for($i = 0; $i < count($ingredients); $i++)
        {
            $name = '';

            if($ingredients[$i]->supplier == 'Other')
            {
                $name = $ingredients[$i]->other->name;

                $selections[$ingredients[$i]->id] = $name;
            }
            elseif($ingredients[$i]->supplier == 'Commissary Product')
            {
                if(!empty($ingredients[$i]->commissary_product))
                {
                    $name = $ingredients[$i]->commissary_product->name;

                    $selections[$ingredients[$i]->id] = $name;
                }
                
            }
            elseif($ingredients[$i]->supplier == 'DryGoods Material')
            {
                if(!empty($ingredients[$i]->dry_good_inventory))
                {
                    $name = $ingredients[$i]->dry_good_inventory->name;

                    $selections[$ingredients[$i]->id] = $name;
                }
            }
            else
            {
                if(!empty($ingredients[$i]->commissary_inventory))
                {
                    if($ingredients[$i]->commissary_inventory->supplier == 'Other')
                    {
                        if(!empty($ingredients[$i]->commissary_inventory->other_inventory))
                            $name = $ingredients[$i]->commissary_inventory->other_inventory->name;
                    }
                    else
                    {
                        if(!empty($ingredients[$i]->commissary_inventory->drygood_inventory))
                            $name = $ingredients[$i]->commissary_inventory->drygood_inventory->name;
                    }

                    $selections[$ingredients[$i]->id] = $name;
                }
                
            }
        }

    	return view('backend.product.create', compact('ingredients', 'selections'));
    }

    public function store(ManageRequest $request){
    	$filename    = 'no_image.png';
        $products    = json_decode($request->product_ingredients);

    	if($request->hasFile('image')){
    		$file 		= $request->file('image');
    		$filename 	= $file->getClientOriginalName();
    		$file->move(public_path().'/img/product/', $filename);
    	}

    	$product           = new Product();
    	$product->name 	   = $request->name;
        $product->code     = $request->code;
    	$product->image    = $filename;
        $product->category = $request->category;
    	$product->save();


        foreach ($products as $prod) 
        {
            $cost = 0;

            foreach ($prod->ingredient as $item) 
            {
                $price      = 0;
                $qty_left   = 0;
                $last_stock = 0;
                $total      = 0;

                $ingredient = Inventory::findOrFail($item->id);

                if(count($ingredient->stocks))
                {
                    $price      = $ingredient->stocks->last()->price;
                    $last_stock = $ingredient->stocks->last()->quantity;
                }

                if($ingredient->physical_quantity == 'Mass')
                {
                    if($ingredient->unit_type == $item->unit_type)
                    {
                        $qty_left = $item->quantity;
                    }
                    else
                    {
                        $stock_qty = new Mass(1, $ingredient->unit_type);

                        $req_qty   = new Mass($item->quantity, $item->unit_type);

                        $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
                    }
                }
                elseif($ingredient->physical_quantity == 'Volume')
                {
                    if($ingredient->unit_type == $item->unit_type)
                    {
                        $qty_left = $item->quantity;
                    }
                    else
                    {
                        $stock_qty = new Volume(1, $ingredient->unit_type);

                        $req_qty   = new Volume($item->quantity, $item->unit_type);

                        $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
                    }
                }
                else
                {
                    $qty_left = $item->quantity;
                }
                
                if($price != 0 && $last_stock != 0)
                {
                    if($qty_left < 0 || $qty_left == 0)
                        $qty_left = $item->quantity;                        

                    $total = ($price / $last_stock) * $qty_left;
                }
                else
                    $total = 0;

                $cost = $cost + $total;
            }

            $prod_size              = new ProductSize();
            $prod_size->size        = $prod->size;
            $prod_size->price       = $prod->price;
            $prod_size->product_id  = $product->id;
            $prod_size->cost        = $cost;
            $prod_size->save();

            //attach product size ingredients
            foreach ($prod->ingredient as $item) {
               $ingredient = Inventory::findOrFail($item->id);

               $ingredient->product_size()->attach($prod_size, ['quantity' => $item->quantity, 'unit_type' => $item->unit_type]);
            }
        }

    	return redirect()->route('admin.product.index'); 
    }

    public function show(Product $product){
        $product = Product::with(
                    [
                        'product_size', 
                        'product_size.ingredients' => function($q) {
                            $q->withTrashed();
                        }
                    ])
                    ->where('id', $product->id)
                    ->first();

        return view('backend.product.show', compact('product'));
    }

    public function edit(Product $product){
        $ingredients = Inventory::all();
        $selections  = [];

        for($i = 0; $i < count($ingredients); $i++)
        {
            $name = '';

            if($ingredients[$i]->supplier == 'Other')
            {
                $name = $ingredients[$i]->other->name;

                $selections[$ingredients[$i]->id] = $name;
            }
            elseif($ingredients[$i]->supplier == 'Commissary Product')
            {
                $name = $ingredients[$i]->commissary_product->name;

                $selections[$ingredients[$i]->id] = $name;
            }
            elseif($ingredients[$i]->supplier == 'DryGoods Material')
            {
                $name = $ingredients[$i]->dry_good_inventory->name;

                $selections[$ingredients[$i]->id] = $name;
            }
            else
            {
                if($ingredients[$i]->commissary_inventory->supplier == 'Other')
                    $name = $ingredients[$i]->commissary_inventory->other_inventory->name;
                else
                    $name = $ingredients[$i]->commissary_inventory->drygood_inventory->name;

                $selections[$ingredients[$i]->id] = $name;
            }
        }

    	return view('backend.product.edit', compact('product', 'ingredients', 'selections'));
    }

    public function update(Product $product, ManageRequest $request){
    	$filename    = $product->image;
		$products    = json_decode($request->product_ingredients);
        $size_update = [];

    	if($request->hasFile('image')){
    		$file 		= $request->file('image');
    		$filename 	= $file->getClientOriginalName();
    		$file->move(public_path().'/img/product/', $filename);
    	}

    	$product->name     = $request->name;
        $product->code     = $request->code;
        $product->image    = $filename;
        $product->category = $request->category;
        $product->save();

        //
        //get available size
        //
        for($i = 0; $i < count($products); $i++)
        {
            $size_update[$i] = $products[$i]->size;
        }

        //
        // remove product size
        //
        $remove_prods = ProductSize::where('product_id', $product->id)->whereNotIn('size', $size_update)->get();
        foreach ($remove_prods as $prod) 
        {
            $prod->delete();
        }

    	foreach ($products as $prod) 
        {
            $cost                   = 0;
            $exist_ingredient       = [];
            $not_exist_ingredient   = [];

            $prod_size = ProductSize::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'size'       => $prod->size
                    ],
                    [
                        'price'      => $prod->price,
                        'cost'       => $cost
                    ]
                );

            for($i = 0; $i < count($prod->ingredient); $i++)
            {
                $exist_ingredient[$i] = (int)$prod->ingredient[$i]->id;
            }

            //
            // get existing product ingredients
            //
            $ingredients = $prod_size->ingredients->whereIn('id', $exist_ingredient);

            foreach ($ingredients as $ingredient) 
            {
                for($i = 0; $i < count($prod->ingredient); $i++)
                {

                    if($prod->ingredient[$i]->id == $ingredient->id)
                    {
                        $price      = 0;
                        $last_stock = 0;
                        $qty_left   = 0;

                        //update quantity to use
                        $ingredient->pivot->quantity = $prod->ingredient[$i]->quantity;

                        if(count($ingredient->stocks))
                        {
                            $price      = $ingredient->stocks->last()->price;
                            $last_stock = $ingredient->stocks->last()->quantity;
                        }

                        if($ingredient->physical_quantity == 'Mass')
                        {
                            if($ingredient->unit_type == $prod->ingredient[$i]->unit_type)
                            {
                                $qty_left = $prod->ingredient[$i]->quantity;
                            }
                            else
                            {
                                $stock_qty = new Mass(1, $ingredient->unit_type);
                                $req_qty   = new Mass($prod->ingredient[$i]->quantity, $prod->ingredient[$i]->unit_type);
                                $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
                            }
                            
                        }
                        elseif($ingredient->physical_quantity == 'Volume')
                        {
                            if($ingredient->unit_type == $prod->ingredient[$i]->unit_type)
                            {
                                $qty_left = $prod->ingredient[$i]->quantity;
                            }
                            else
                            {
                                $stock_qty = new Volume(1, $ingredient->unit_type);
                                $req_qty   = new Volume($prod->ingredient[$i]->quantity, $prod->ingredient[$i]->unit_type);
                                $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
                            }
                        }
                        else
                        {
                            $qty_left = $prod->ingredient[$i]->quantity;
                        }
                        
                        if($price != 0 && $last_stock != 0)
                        {
                            if($qty_left < 0 || $qty_left == 0)
                                $qty_left = $ingredient->pivot->quantity;                        

                            $total = ($price / $last_stock) * $qty_left;
                        }
                        else
                            $total = 0;

                        $cost = $cost + $total;
                        $ingredient->save();
                    }
                }
            }


            $remove_ingredients = $prod_size->ingredients->whereNotIn('id', $exist_ingredient);
            
            foreach ($remove_ingredients as $ingredient) {
                $ingredient->product_size()->detach($prod_size);
            }

            $ingredient_ids = $ingredients->pluck('id');

            $not_exist_ingredient = array_diff_assoc($exist_ingredient, $ingredient_ids->toArray());
            
            foreach ($not_exist_ingredient as $id) {
                $ingredient = Inventory::findOrFail($id);

                foreach ($prod->ingredient as $item) 
                {
                    if($id == $item->id)
                    {
                        $ingredient->product_size()->attach($prod_size, ['quantity' => $item->quantity, 'unit_type' => $item->unit_type]);

                        if(count($ingredient->stocks))
                        {
                            $price      = $ingredient->stocks->last()->price;
                            $last_stock = $ingredient->stocks->last()->quantity;
                        }

                        if($ingredient->physical_quantity == 'Mass')
                        {
                            if($ingredient->unit_type == $item->unit_type)
                            {
                                $qty_left = $item->quantity;
                            }
                            else
                            {
                                $stock_qty = new Mass(1, $ingredient->unit_type);
                                $req_qty   = new Mass($item->quantity, $item->unit_type);
                                $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
                            }
                        }
                        elseif($ingredient->physical_quantity == 'Volume')
                        {
                            if($ingredient->unit_type == $item->unit_type)
                            {
                                $qty_left = $item->quantity;
                            }
                            else
                            {
                                $stock_qty = new Volume(1, $ingredient->unit_type);
                                $req_qty   = new Volume($item->quantity, $item->unit_type);
                                $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
                            }
                        }
                        else
                        {
                            $qty_left = $item->quantity;
                        }

                        if($price != 0 && $last_stock != 0)
                        {
                            if($qty_left < 0 || $qty_left == 0)
                            {
                                $_ingredients = ProductSize::where('product_id', $product->id)
                                        ->where('size', $prod_size->size)
                                        ->first()->ingredients;
                                
                                foreach($ingredients as $_ing)
                                {
                                    if($_ing->id = $ingredient->id)
                                        $qty_left = $_ing->pivot->quantity;    
                                }                   
                            }

                            $total = ($price / $last_stock) * $qty_left;
                        }
                        else
                            $total = 0;

                        $cost = $cost + $total;
                    }
                }
            }

            $prod_size->cost = $cost;
            $prod_size->save();
        }

    	return redirect()->route('admin.product.index')->withFlashSuccess('Product has been updated!'); 
    }

    public function destroy(Product $product){
    	$product->delete();

    	return redirect()->route('admin.product.index')->withFlashSuccess('Product has been deleted!');
    }

    public function unit_type($id){
        $inventory = Inventory::where('id', $id)->withTrashed()->first();
        return $inventory->physical_quantity;
    }
}
