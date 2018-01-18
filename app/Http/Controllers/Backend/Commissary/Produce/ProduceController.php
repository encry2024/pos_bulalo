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

class ProduceController extends Controller
{
    public function index(){
    	return view('backend.commissary.produce.index');
    }

    public function create(){
    	$products = Product::pluck('name', 'id');

    	return view('backend.commissary.produce.create', compact('products'));
    }

    public function store(Request $request) {
        $canProduce = 0;
        $cost       = 0;
        $product    = Product::findOrFail($request->product_id);
        $ingredients= $product->ingredients;

        foreach ($ingredients as $ingredient) {
            $qty_left = 0;
            $i        = 0;

            if($ingredient->physical_quantity == 'Mass')
            {
                $stock_qty = new Mass($ingredient->stock, $ingredient->unit_type);
                $req_qty  = new Mass(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);
                $qty_left  = $stock_qty->subtract($req_qty);
                $i = $qty_left->toUnit($ingredient->unit_type);
            }
            elseif($ingredient->physical_quantity == 'Volume')
            {
                $stock_qty = new Volume($ingredient->stock, $ingredient->unit_type);
                $req_qty   = new Volume(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);
                $qty_left  = $stock_qty->subtract($req_qty);
                $i = $qty_left->toUnit($ingredient->unit_type);
            }
            else
            {
                $i = $ingredient->stock - $request->quantity;
            }

            if($i >= 0)
                $canProduce++;
        }

        // can produce should match the number of ingredients
        // can product mus
        //
        if(count($ingredients) == $canProduce)
        {
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

            foreach ($ingredients as $ingredient) 
            {
                $qty_left = 0;
                $i        = 0;

                if($ingredient->physical_quantity == 'Mass')
                {
                    $stock_qty = new Mass($request->quantity, $ingredient->unit_type);
                    $req_qty   = new Mass(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);
                    $qty_left  = $stock_qty->subtract($req_qty);
                    $i = $qty_left->toUnit($ingredient->unit_type);
                }
                elseif($ingredient->physical_quantity == 'Volume')
                {
                    $stock_qty = new Volume($request->quantity, $ingredient->unit_type);
                    $req_qty   = new Volume(($request->quantity * $ingredient->pivot->quantity), $ingredient->pivot->unit_type);
                    $qty_left  = $stock_qty->subtract($req_qty);
                    $i = $qty_left->toUnit($ingredient->unit_type);
                }
                else
                {
                    $i = $inventory->stock - $request->quantity;
                }

                $ingredient->stock = $i;

                if(count($ingredient->stocks))
                {
                    $divisor = 1;
                    
                    if($ingredient->physical_quantity == 'Mass')
                    {
                        $stock_qty = new Mass(1, $ingredient->unit_type);
                        $req_qty   = new Mass($ingredient->pivot->quantity, $ingredient->pivot->unit_type);
                        $qty_left  = $stock_qty->subtract($req_qty);
                        $i = $qty_left->toUnit($ingredient->unit_type);
                        $actual_quantity = $req_qty->toUnit($ingredient->unit_type);
                    }
                    elseif($ingredient->physical_quantity == 'Volume')
                    {
                        $stock_qty = new Volume(1, $ingredient->unit_type);
                        $req_qty   = new Volume($ingredient->pivot->quantity, $ingredient->pivot->unit_type);
                        $qty_left  = $stock_qty->subtract($req_qty);
                        $i = $qty_left->toUnit($ingredient->unit_type);
                    }
                    else
                    {
                        $i = $inventory->stock - $request->quantity;
                    }

                    $total      = 0;
                    $price      = $ingredient->stocks->last()->price;
                    $last_stock = $ingredient->stocks->last()->quantity;
                    $total      = $price * $actual_quantity;
                    $cost       = $cost + $total;
                }
                $ingredient->save();
            }
            
            $product          = $produce->product;
            $product->produce = $product->produce + $request->quantity;
            $product->cost    = $cost;
            $product->save();

            $history                = new History();
            $history->product_id    = $product->id;
            $history->description   = $request->quantity.' '.$product->name.' has been produced';
            $history->status        = 'Add';
            $history->save();

            $this->notification();            
            return redirect()->route('admin.commissary.produce.index')->withFlashSuccess('Record Saved!');
        }
        else
        {
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
