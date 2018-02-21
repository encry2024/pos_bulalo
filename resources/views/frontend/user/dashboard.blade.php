@extends('frontend.layouts.app')

@section('after-styles')
{{ Html::style('css/dashboard.css') }}
{{ Html::style('css/sweetalert2.css') }}
<style type="text/css">
    ul.list-group{
        list-style: none;
        padding: 0;
    }
    li.list-group-item a{
        display: block;
        width: 100%;
        height: 100%;
        text-decoration: none;
    }
</style>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-4 col-md-push-8">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Order List</h5>
                            </div><!--panel-heading-->

                            <div class="panel-body">
                                <input type="hidden" id="prod_id">
                                <input type="hidden" id="prod_name">
                                <input type="hidden" id="prod_price">
                                <input type="hidden" id="prod_size">

                                <div class="row">
                                    <div id="table-wrapper">
                                        <div id="table-scroll">
                                            <table class="table table-responsive" id="order_list">
                                                <thead>
                                                    <th style="width:55%;text-align:left"><span class="text">ITEM</span></th>
                                                    <th style="width:15%;text-align:left"><span class="text">QTY</span></th>
                                                    <th style="width:30%;text-align:left"><span class="text">PRICE</span></th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h4 class="col-md-8">TOTAL</h4>
                                    <h4 class="col-md-4" id="total_amount">0.00</h4>
                                </div>

                                <div id="options">
                                    <button class="btn btn-default" id="btn-clear"><i class="fa fa-refresh"></i> CLEAR</button>
                                    <button class="btn btn-danger" id="btn-remove"><i class="fa fa-trash-o"></i> REMOVE</button>
                                    <button class="btn btn-success" id="btn-save"><i class="fa fa-save"></i> SAVE</button>
                                </div>
                            </div><!--panel-body-->
                        </div><!--panel-->

                        <div class="panel panel-default">
                            <div class="panel-heading">&nbsp;</div>
                            <div class="panel-body">
                                <button class="btn btn-default" id="btn-tables"><i class="fa fa-table"></i> TABLES</button>
                            </div>
                        </div>
                    </div>

                </div><!--col-md-4-->

                <div class="col-md-8 col-md-pull-4">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>PRODUCT LIST</h4>
                                </div><!--panel-heading-->

                                <div class="panel-body">
                                    @if(count($products))
                                        @foreach($products as $product)
                                            <a class="product-box" id="{{ $product->id }}" data-code="{{ $product->code }}" onclick="product_click(this)">
                                                <div class="product-title">{{ $product->name }}</div>

                                                <div class="product-body">
                                                    <img src="{{ url('img/product').'/'.$product->image }}">
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                    <p>No Product.</p>
                                    @endif
                                </div><!--panel-body-->
                            </div><!--panel-->
                        </div><!--col-xs-12-->
                    </div><!--row-->
                </div>
            </div><!--row-->
        </div><!-- col-md-10 -->
    </div><!-- row -->

    <!-- Modal -->
    <div id="productModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="ingredient-title"></h4>
                </div>
                <div class="modal-body">
                    <table class="table" id="ingredient_list">
                        <thead>
                            <th>INGREDIENT NAME</th>
                            <th>STOCKS</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-8">
                            <h5>DRINK SIZE</h5>
                            <ul class="list-group col-lg-8" id="cup_sizes">
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <label>Quantity</label>
                            <input type="number" class="form-control" id="qty" value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn_addOrder">Add Order</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

    <!-- Modal -->
    <div id="saveModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12" id="payment">
                            <div class="form-group col-lg-6">
                                <label for="order_type">Order Type</label>
                                <select class="form-control" id="order_type">
                                    <option>Dine-in</option>
                                    <option>Take Out</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6" id="panel_table">
                                <label for="table_no">Table</label>
                                <select class="form-control" id="table">
                                </select>
                            </div>
                            <div class="form-group col-lg-6" id="panel_discount_type" hidden>
                                <label for="discount">Discount Type</label>
                                <select class="form-control" id="discount_type">
                                    <option value="0">None</option>
                                    @foreach($settings as $setting)
                                    <option value="{{ $setting->discount }}">{{ $setting->name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group col-lg-6" id="panel_payable" hidden>
                                <label for="payable">Total Payable</label>
                                <input type="input" class="form-control" id="payable" value='0.00' readonly>
                            </div>
                            <div class="form-group col-lg-6" id="panel_discount" hidden>
                                <label for="discount">Discount</label>
                                <input type="input" class="form-control" id="discount" value='0.00' readonly>
                            </div>
                            <div class="form-group col-lg-6" id="panel_vat" hidden>
                                <label for="vat">12% VAT</label>
                                <input type="input" class="form-control" id="vat" value='0.00' readonly>
                            </div>
                            <!-- <div class="form-group col-lg-6" id="panel_service_charge" hidden>
                                <label for="vat">5% Service Charge</label>
                                <input type="input" class="form-control" id="service_charge" value='0.00' readonly>
                            </div> -->
                            <div class="form-group col-lg-6" id="panel_change" hidden>
                                <label for="change">Change</label>
                                <input type="input" class="form-control" id="change" value='0.00' readonly>
                            </div>  
                            <div class="form-group col-lg-6" id="panel_total_amount" hidden>
                                <label for="total_amount_due">Total Amount Due</label>
                                <input type="input" class="form-control" id="total_amount_due" value='0.00' readonly>
                            </div>
                            <div class="form-group col-lg-6" id="panel_cash" hidden>
                                <label for="cash">Cash</label>
                                <input type="input" class="form-control" id="cash" value='0.00' onkeyup="change()" onfocus="this.value = ''" onblur="isFocus()" pattern="[0-9]">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="notify" style="color:red" class="pull-left"></span>
                    <button type="button" class="btn btn-success" id="btn_submit">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

     <!-- Modal -->
    <div id="tablesModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">TABLES</h4>
                </div>
                <div class="modal-body">
                    <p class="pull-left" style="font-size: 15px; font-weight: bold">Creditable Amount: <span id="table_rent_price">0.00</span></p>
                    <br>
                    <hr>
                    <label style="font-size: 15px; font-weight: bold;">Total Creditable Amount: <span id="total_creditable_amount">0.00</span></label>
                    <br>
                    <hr>
                    <div class="form-group">
                        <label for="table_list">Select Table</label>
                        <select class="form-control" id="table_list">
                            
                        </select>
                    </div>
                    <hr>
                    <h4>Order List</h4>
                    <p>TRANSACTION NO#: <span id="table_order_transact"></span></p>

                    <table class="table" id="table_order_list">
                        <thead>
                            <th>PRODUCT</th>
                            <th>QTY/SIZE</th>
                            <th>PRICE</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3">No records found.</td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="btn_cancel_order">CANCEL ORDER</button>
                    <button type="button" class="btn btn-success" id="btn_additional">ADDITIONAL ORDER</button>
                    <button class="btn btn-default" id="btn-charge">CHARGE BILL</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>

                    <p class="pull-left" style="font-size: 18px; font-weight: bold">TOTAL: <span id="table_order_total">0.00</span></p>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

     <!-- Modal -->
    <div id="chargeModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Transaction No: <b id="charge_transaction_no"></b>&nbsp;</h4>
                </div>
                <div class="modal-body">
                    <p class="pull-left" style="font-size: 15px; font-weight: bold">Creditable Amount: <span id="table_rent_price_charge_modal">0.00</span></p>
                    <br>
                    <hr>
                    <p class="pull-left" style="font-size: 15px; font-weight: bold">Total Credit Amount: <span id="table_total_rent_price_charge_modal">0.00</span></p>
                    <br>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="table-wrapper">
                                <div id="table-scroll">
                                    <table class="table table-responsive" id="charge_table">
                                        <thead>
                                            <th style="width:55%;text-align:left"><span class="text">ITEM</span></th>
                                            <th style="width:15%;text-align:left"><span class="text">QTY</span></th>
                                            <th style="width:30%;text-align:left"><span class="text">PRICE</span></th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <h4 class="pull-left">TOTAL: <span id="charge_total">0.00</span></h4>
                    <button type="button" class="btn btn-success" id="btn_charge_payment">Proceed To Payment</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

    <!-- Modal -->
    <div id="chargeSaveModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Transaction No#: <b class="modal-title"></b></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12" id="payment">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="discount">Discount Type</label>
                                    <select class="form-control" id="discount_type" onchange="charge_discount_change(this)">
                                        <option value="0">None</option>
                                        @foreach($settings as $setting)
                                            <option value="{{ $setting->discount }}">{{ $setting->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="payable">Total Payable</label>
                                        <input type="input" class="form-control" id="payable" value='0.00' readonly>
                                    </div>
                                </div> <!-- col-lg-4 -->

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="vat">12% VAT / VAT Amount</label>
                                        <input type="input" class="form-control" id="vat" value='0.00' readonly>
                                    </div> <!-- form-group -->

                                    <div class="form-group">
                                        <label for="vat">Vatable Sales</label>
                                        <input type="input" class="form-control" id="vatable_sales" value='0.00' readonly>
                                    </div> <!-- form-group -->

                                    <div class="form-group">
                                        <label for="vat">VAT-Exempt Sales</label>
                                        <input type="input" class="form-control" id="vat_exempt_sales" value='0.00' readonly>
                                    </div> <!-- form-group -->

                                    <div class="form-group">
                                        <label for="vat">VAT-Zero-Rated Sales</label>
                                        <input type="input" class="form-control" id="vat_zero_rated_sales" value='0.00' readonly>
                                    </div> <!-- form-group -->
                                </div> <!-- col-lg-4 -->

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="discount">Discount</label>
                                        <input type="input" class="form-control" id="discount" value='0.00' readonly>
                                    </div> <!-- form-group -->

                                    <!-- <div class="form-group col-lg-6">
                                        <label for="vat">5% Service Charge</label>
                                        <input type="input" class="form-control" id="service_charge" value='0.00' readonly>
                                    </div> -->

                                    <div class="form-group">
                                        <label for="change">Change</label>
                                        <input type="input" class="form-control" id="change" value='0.00' readonly>
                                    </div> <!-- form-group -->

                                    <div class="form-group">
                                        <label for="change">Total Amount Due</label>
                                        <input type="input" class="form-control" id="total_amount_due" value='0.00' readonly>
                                    </div> <!-- form-group -->

                                    <div class="form-group">
                                        <label for="cash">Cash</label>
                                        <input type="input" class="form-control" id="cash" value='0.00' onkeyup="charge_change()" onfocus="this.value = ''" onblur="isFocus()" pattern="[0-9]">
                                    </div> <!-- form-group -->
                                </div> <!-- col-lg-4 -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="notify" style="color:red" class="pull-left"></span>
                    <button type="button" class="btn btn-success" id="btn_charge_submit">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

     <!-- Receipt Modal -->
    <div id="printModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="scrollable">
                        <div id="official_receipt">
                            <h5>OFFICIAL RECEIPT</h5>
                            <div id="printable">
                                <p><h4 id="receipt_header">MANDA’S BULALOHAN & GRILL</h4></p>
                                <p class="text-center">#468 Barangka Drive, Barangay Plainview Mandaluyong City</p>
                                <p class="text-center">SEC reg #: CS201733365</p>
                                <p class="text-center">TIN# 009-841-115-000</p>
                                <br>
                                <hr>
                                <br>
                                <p>Transaction No: <span class="pull-right" id="transaction_no">#00000000</span></p>
                                <p>Date: <span class="pull-right">{{ date('m-d-y - h:i:s A') }}</span></p>
                                <p>Cashier: <span class="pull-right">{{ Auth::user()->name }}</span></p>
                                <p>Order Type: <span class="pull-right" id="print_type"></span></p>
                                <p>Table #: <span class="pull-right" id="print_table"></span></p>
                                <hr>
                                <div style="min-height:150px">
                                    <table id="items">
                                        <thead>
                                            <th style="width:25%">Qty</th>
                                            <th>Item(s)</th>
                                            <th style="width:25%">Total</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <!-- Credit Section -->
                                <p>Creditable Amount <span class="pull-right" id="print_creditable_amount">0.00</span></p>
                                <p>Excess Amount <span class="pull-right" id="print_creditable_amount">0.00</span></p>
                                <hr>
                                <!-- <p>Service Charge <span class="pull-right" id="print_charge">0.00</span></p> -->
                                <p>VAT Amount <span class="pull-right" id="print_vat">0.00</span></p>
                                <p>Vatable Sales <span class="pull-right" id="print_vatable_sales">0.00</span></p>
                                <p>VAT-Exempt Sales <span class="pull-right" id="print_vat_exempt_sales">0.00</span></p>
                                <p>VAT Zero-Rated Sales <span class="pull-right" id="print_vat_zero_rated_sales">0.00</span></p>
                                <hr>
                                <p>Sub Total <span class="pull-right" id="print_total">0.00</span></p>
                                <p>Cash <span class="pull-right" id="print_cash">0.00</span></p>
                                <p>Change <span class="pull-right" id="print_change">0.00</span></p>
                                <p>Discount <span class="pull-right" id="print_discount">0.00</span></p>
                                <p>Amount Due <span class="pull-right" id="print_amount_due">0.00</span></p>
                                <p>&nbsp;</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->
@endsection

@section('after-scripts')
    {{ Html::script('js/sweetalert2.all.min.js') }}
    @include('pos/order_script')
@stop