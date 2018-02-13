<?php

namespace App\Http\Controllers\Backend\Commissary\Produce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commissary\Produce\Produce;
use App\Models\Commissary\Product\Product;
use App\Models\Commissary\Inventory\Inventory;
use App\Models\Commissary\History\History;
use App\Models\Notification\Notification;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;
use DB;

class ProduceController extends Controller
{
    public function index()
    {
    	return view('backend.commissary.produce.index');
    }

    public function create()
    {
    	$products = Product::pluck('name', 'id');

    	return view('backend.commissary.produce.create', compact('products'));
    }

    /**
     * @param Request $request
     *
     * @param Request $request Product ID
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $canProduce     = 0;
        $cost           = 0;
        $product        = Product::findOrFail($request->product_id);
        $ingredients    = $product->ingredients;

        #  Check Product ID
        // dd($request->all());
        # Fetch product's ingredients
        foreach ($ingredients as $ingredient) {
            $quantity       = 0;
            $total_stocks   = 0;

            /**
             * Get the number of items that can be produced on the requested quantity
             * and remaining stocks for the selected item.
             */
            if ($ingredient->physical_quantity == 'Mass') {
                # Ingredients total stocks in commissary_inventories table. (A)
                $stock_quantity     =   new Mass($ingredient->stock, $ingredient->unit_type);
                // dd($stock_quantity);
                # Required quantity of ingredients to produce the product
                $required_quantity  =   new Mass(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);
                # Total quantity of (A) after the ingredients was used. (B)
                $quantity_left      =   $stock_quantity->subtract($required_quantity);
                // dd($quantity_left);
                # Convert (B) to a string data.
                $total_stocks       =   $quantity_left->toUnit($ingredient->unit_type);
                // dd($total_stocks);
            } elseif ($ingredient->physical_quantity == 'Volume') {
                $stock_quantity     =   new Volume($ingredient->stock, $ingredient->unit_type);
                $required_quantity  =   new Volume(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);
                $quantity_left      =   $stock_quantity->subtract($required_quantity);
                $total_stocks       =   $quantity_left->toUnit($ingredient->unit_type);
            } else {
                $total_stocks       = $ingredient->stock - $request->quantity;
            }

            if ($total_stocks >= 0) {
                $canProduce++;
            }
        }

        if (count($ingredients) == $canProduce) {
            foreach ($ingredients as $ingredient) {
                $quantity_left  = 0;
                $total_stocks   = 0;

                # Stocks Left
                if ($ingredient->physical_quantity == 'Mass') {
                    # Ingredients total stocks in commissary_inventories table. (A)
                    $stock_quantity     =   new Mass($ingredient->stock, $ingredient->unit_type);

                    # Required quantity of ingredients to produce the product
                    $required_quantity  =   new Mass(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);

                    # Total quantity of (A) after the ingredients was used. (B)
                    $quantity_left      =   $stock_quantity->subtract($required_quantity);

                    # Convert (B) to a string data.
                    $total_stocks       =   $quantity_left->toUnit($ingredient->unit_type);
                } elseif ($ingredient->physical_quantity == 'Volume') {
                    $stock_quantity     =   new Volume($ingredient->stock, $ingredient->unit_type);
                    $required_quantity  =   new Volume(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);
                    $quantity_left      =   $stock_quantity->subtract($required_quantity);
                    $total_stocks       =   $quantity_left->toUnit($ingredient->unit_type);
                } else {
                    $quantity_left      = $ingredient->stock - $request->quantity;
                    $total_stocks       = $quantity_left;
                }

                $ingredient->stock = $quantity_left;

                if (count($ingredient->stock)) {
                    if ($ingredient->physical_quantity == 'Mass') {
                        $stock_quantity         = new Mass(1, $ingredient->unit_type);
                        $required_quantity      = new Mass($ingredient->pivot->quantity, $ingredient->pivot->unit_type);
                        $quantity_left          = $stock_quantity->subtract($required_quantity);
                        $actual_quantity        = $required_quantity->toUnit($ingredient->unit_type);
                        // dd($quantity_left);
                    } elseif ($ingredient->physical_quantity == 'Volume') {
                        $stock_quantity     =   new Volume(1, $ingredient->unit_type);
                        $required_quantity  =   new Volume(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);
                        $quantity_left      =   $stock_quantity->subtract($required_quantity);
                        $actual_quantity       =   $quantity_left->toUnit($ingredient->unit_type);
                    } else {
                        $total_stocks       = $ingredient->stock - $request->quantity;
                    }

                    $total      = 0;
                    $price      = $ingredient->stocks->last()->price;
                    $total      = $price * $actual_quantity;
                    $cost       = $total + $cost;
                }

                $ingredient->save();
            }

            $produce = Produce::updateOrCreate(
                [
                    'product_id' => $request->product_id,
                    'created_at' => date('Y-m-d h:i:s')
                ],
                [
                    'date'      => $request->date,
                    'quantity'  => $request->quantity
                ]
            );

            $product          = $produce->product;
            $product->produce = $product->produce + $request->quantity;
            $product->cost    = $cost * $request->quantity;
            $product->save();

            if ($product->save()) {
                $history                = new History();
                $history->product_id    = $product->id;
                $history->description   = $request->quantity.' '.$product->name.' has been produced';
                $history->status        = 'Add';

                if ($history->save()) {
                    $this->notification();

                    return redirect()->route('admin.commissary.produce.index')->withFlashSuccess('Record Saved!');
                }
            }
        } else {
            return redirect()->back()->withFlashDanger('Check inventory for item stock!');
        }
    }

    public function destroy(Produce $produce){
        if(!empty($produce->product))
        {
            $product = $produce->product;
            $product->produce = $product->produce - $produce->quantity;
            $product->save();

            $ingredients = $product->ingredients;

            foreach ($ingredients as $ingredient) {
                $ingredient->stock = $ingredient->stock + ($produce->quantity * $ingredient->pivot->quantity);
                $ingredient->save();
            }
        }
        $produce->delete();
    	return redirect()->route('admin.commissary.produce.index')->withFlashDanger('Produce Product Deleted Successfully!');
    }

    public function notification()
    {
        $inventories = Inventory::whereRaw('stock < reorder_level')->get();

        foreach ($inventories as $inventory) {
            $name = '';
            $desc = $inventory->name.' has '.$inventory->stock.' stocks left.';

            if($inventory->supplier == 'Other')
            {
                $name = $inventory->other_inventory->name;
            }
            else
            {
                $name = $inventory->drygood_inventory->name;
            }

            Notification::updateOrCreate(
                [
                    'name' => $name,
                    'date' => date('Y-m-d'), 
                    'description' => $desc,
                    'stock_from' => 'COMMISSARY',
                    'status' => 'new'
                ],
                [
                    'inventory_id' => $inventory->id
                ]
            ); 
        }
    }
}
