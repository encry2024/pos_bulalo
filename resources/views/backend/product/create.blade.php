@extends ('backend.layouts.app')

@section ('title', 'POS Product Management | Add Product')

@section('after-styles')
    {{ Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css') }}
@endsection

@section('page-header')
    <h1>
        POS Product Management <small>Add Product</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.product.store', 'class' => 'form-horizontal', 'Product' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Add Product</h3>

                <div class="box-tools pull-right">
                    @include('backend.product.includes.partials.product-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('name', 'Product Name', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Product Name']) }}
                    </div><!--col-lg-10-->

                    {{ Form::label('code', 'Product Code', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::text('code', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('category', 'Product Category', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::select('category', [
                                'SOUP'              => 'SOUP', 
                                'GRILL'             => 'GRILL', 
                                'FRY'               => 'FRY', 
                                'PINOY SPECIALTIES' => 'PINOY SPECIALTIES', 
                                'MERIENDA CLASSIC'  => 'MERIENDA CLASSIC',
                                'BREAKFAST'         => 'BREAKFAST', 
                                'VEGETABLES'        => 'VEGETABLES', 
                                'PULUTAN'           => 'PULUTAN', 
                                'DRINK'             => 'DRINK'
                            ] ,old('category'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required']) }}
                    </div><!--col-lg-10-->

                    {{ Form::label('image', 'Product Image', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::file('image', old('image'), ['class' => 'form-control', 'required' => 'required']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('product_size', 'Product Size', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::select('product_size', 
                            [
                                'Short Order'   => 'Short Order',
                                'Small'         => 'Small',
                                'Medium'        => 'Medium',
                                'Large'         => 'Large',
                                'Bilao(5pax)'   => 'Bilao(5pax)',
                                'Bilao(10pax)'  => 'Bilao(10pax)',
                                'Bilao(15pax)'  => 'Bilao(15pax)',
                            ], old('product_size'), 
                            [
                                'class' => 'form-control select2', 
                                'required' => 'required'
                            ]) 
                        }}

                        {{ Form::hidden('product_ingredients', '', ['id' => 'product_ingredients']) }}
                    </div>

                    <div class="col-lg-2">
                        <button type="button" class="btn btn-primary" id="btn_add_size">Add Size</button>
                    </div>
                </div>

                <div id="panel_sizes"></div>

            </div>
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.product.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js') }}
    {{ Html::script('js/timepicker.js') }}
    {{ Html::script('js/backend/access/users/script.js') }}
    <script type="text/javascript">
        var index       = 0;
        var ingredients = {!! json_encode($ingredients) !!};
        var obj = [
                    <?php 

                        foreach($selections as $ingredient)
                        {
                            echo '"'.$ingredient.'",';
                            // echo '"'.($ingredient->supplier == 'Dry Goods' ? $ingredient->dry_good_inventory->name : $ingredient->commissary_inventory->name).'",';
                        }

                    ?>
                  ];


        function get_unit(val){
            var options = '';

            if(val == 'Mass')
            {
                options += '<option value="g">Gram</option>';
                options += '<option value="kg">Kilogram</option>';
                options += '<option value="ton">Ton</option>';
                options += '<option value="lbs">Pound</option>';
                options += '<option value="oz">Ounce</option>';
            }
            else if(val == 'Volume')
            {
                options += '<option value="ml">Milliliter</option>';
                options += '<option value="cl">Centiliter</option>';
                options += '<option value="dl">Deciliter</option>';
                options += '<option value="l">Liter</option>';
                options += '<option value="cup">Cup</option>';
                options += '<option value="tsp">Tea Spoon</option>';
                options += '<option value="tbsp">Table Spoon</option>';
                options += '<option value="gal">Gallon</option>';
            }
            else
            {
                options += '<option value="pc">Piece</option>';
                options += '<option value="bottle">Bottle</option>';
                options += '<option value="can">Can</option>';
            }

            return options;
        }

        function get_physical_quantity(id, size){

            $.ajax({
                type: 'GET',
                url: "{!! URL::to('admin/pos/product/unit_type') !!}/" + id,
                success: function(data){
                    console.log(data);
                    var table   = $('table[data-id="' + size + '"]');
                    var product = $(table).closest('.panel_product');
                    var select  = $(product).find('#physical_quantity'); 

                    $(select).find('option').remove();

                    $(select).append(get_unit(data));
                }
            });
        }

        $('form').on('submit', function(e){
            //filter table returns table with existing rows
            var products = filterTable();

            if(products.length > 0){
                var objs = [];

                for(var i = 0; i < products.length; i++){
                    var table = $(products[i]).find('table');
                    var body  = $(table).find('tbody');
                    var rows  = $(body).find('tr');
                    var size  = $(table).attr('data-id');
                    var price = $(products[i]).find('input#price').val();
                    var ingredient   = [];

                    for(var j = 0; j < rows.length; j++){
                        var cols = $(rows[j]).find('td');
                        var id   = $(cols[0]).text();
                        var name = $(cols[1]).text();
                        var qty  = $(cols[2]).text();
                        var unit = $(cols[3]).text();

                        ingredient.push({ id: id, name: name, quantity: qty, unit_type: unit });

                    }

                    objs.push({size: size, price: price, ingredient });
                }

                $('#product_ingredients').val(JSON.stringify(objs));
            } else {
                e.preventDefault();
            }
        });


        $('#btn_add_size').on('click', function(){
            var count = 0;
            var size = $('#product_size').val();

            var products = $('body').find('.panel_product');

            //
            // find table size
            //
            for(var i = 0; i < products.length; i++)
            {
                var exist_size = $(products[i]).find('table').attr('data-id');
                
                if(exist_size == size)
                {
                    count++;
                }

            }

            if(count == 0)
            {
                var html =  '<div class="panel_product">'
                    html += '<hr>';

                    //form group
                    html += '<div class="form-group">';

                    //form header
                    html += '<h4 class="col-lg-10 col-lg-offset-1"><small>Product Size:</small> ' + size + '</h4>';
                    html += '<div class="col-lg-1">';
                    html += '<button type="button" class="btn btn-xs btn-danger pull-right" onclick="removeTable(this)"><i class="fa fa-times"></i></button>';
                    html += '</div>';

                    //form price field
                    html += '<div class="col-lg-12">';
                    html += '{{ Form::label("price", "Price", ["class" => "col-lg-2 control-label"]) }}';
                    html += '<div class="form-group col-lg-2" style="margin-left:0">';
                    html += '{{ Form::text("price", 0, ["class" => "form-control"]) }}';
                    html += '</div>';

                    html += '{{ Form::label("ingredient_list", "Ingredient", ["class" => "col-lg-1 control-label"]) }}';
                    html += '<div class="form-group col-lg-2" style="margin-left:0">';
                    html += '{{ Form::select("ingredient_list", $selections, old("ingredient_list"), ["class" => "form-control select2", "onchange" => "fetchUnitType(this)"]) }}'; 
                    html += '</div>';
                    html += '</div>';

                    //form quantity field
                    html += '<div class="col-lg-12">';
                    html += '{{ Form::label("physical_quantity", "Unit Type", ["class" => "col-lg-2 control-label"]) }}';
                    html += '<div class="form-group col-lg-2" style="margin-left:0">';
                    html += '{{ Form::select("physical_quantity", [], old("physical_quantity"), ["class" => "form-control select2"]) }}'; 
                    html += '</div>';

                    html += '{{ Form::label("ingredient_quantity", "Unit", ["class" => "col-lg-1 control-label", "id" => "unit_type"]) }}';
                    html += '<div class="form-group col-lg-2" style="margin-left:0">';
                    html += '{{ Form::number("ingredient_quantity", 0, ["class" => "form-control"]) }}';
                    html += '</div>';

                    html += '{{ Form::label("ingredient_unit", "Quantity/Unit", ["class" => "col-lg-1 control-label", "id" => "unit_type"]) }}';
                    html += '<div class="form-group col-lg-2" style="margin-left:0">';
                    html += '{{ Form::number("ingredient_unit", 0, ["class" => "form-control"]) }}';
                    html += '</div>';

                    html += '</div>';

                    html += '<div class="col-lg-12">';
                    html += '<div class="col-lg-4 col-lg-offset-2">';
                    html += '<button type="button" class="btn btn-primary" onclick="add_ingredient(this)">Add Ingredient</button>';
                    html += '</div>';
                    html += '</div>';

                    //form group end
                    html += '</div>';


                    html += '<div class="form-group">';
                    html += '<div class="col-lg-7 col-lg-offset-1">'
                    html += '<table class="table table-bordered" id="table_product" data-id="'+ size +'" >';
                    html += '<thead>';
                    html += '<th>ID</th>';
                    html += '<th>INGREDIENT</th>';
                    html += '<th>Quantity</th>';
                    html += '<th>Unit Type</th>';
                    html += '<th style="width:20%">&nbsp;</th>';
                    html += '</thead>';
                    html += '<tbody>';
                    html += '</tbody>';
                    html += '</table>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                $('#panel_sizes').append(html);

                var products = $('body').find('.panel_product');
                var index    = $(products).length - 1;

                scrollTo($(products[index]).offset().top);

                get_physical_quantity(ingredients[0]['id'], size);
            }
        });

        function add_ingredient(e){
            var div         = $(e).closest('.panel_product');
            var select      = $(div).find('select');
            var id          = $(select).val();
            var obj         = findIngredients(id)[0];
            var qty         = $(div).find('input#ingredient_quantity').val();
            var unit        = $(div).find('input#ingredient_unit').val();
            var physical    = $(div).find('select#physical_quantity').val();

            if(!existIngredient(div, obj['id']))
            {
                if(qty > 0 && unit > 0)
                {
                    var name    = '';

                    if(obj['supplier'] == 'Other')
                    {
                        name = obj['other']['name'];
                    }
                    else if(obj['supplier'] == 'Commissary Product')
                    {
                        name = obj['commissary_product']['name'];
                    }
                    else if(obj['supplier'] == 'DryGoods Material')
                    {
                       name = obj['dry_good_inventory']['name']; 
                    }
                    else
                    {
                        if(obj['commissary_inventory']!= null)
                        {
                            if(obj['commissary_inventory']['supplier'] == 'Other')
                            {
                                name = obj['commissary_inventory']['other_inventory']['name'];
                            }
                            else
                            {
                                name = obj['commissary_inventory']['drygood_inventory']['name'];
                            }
                        }
                    }

                    var row     = '<tr id="' + obj['id'] + '">';
                        row     += '<td>' + obj['id'] + '</td>';
                        row     += '<td>' + name + '</td>';
                        row     += '<td>' + (qty * unit) + '</td>';
                        row     += '<td>' + physical + '</td>';
                        row     += '<td><button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this)">Remove</button></td>';
                        row     += '</tr>';

                    $(div).find('table').find('tbody').append(row);
                    $(div).find('input#ingredient_quantity').val(1);
                    $(div).find('input#ingredient_unit').val(1);

                    scrollTo($(div).find('table').offset().top);
                }
                else
                {
                    swal("Alert!", "Unit and Quantity must be greater than 1", "warning");
                }
            }
        }

        function removeTable(e)
        {
            $(e).closest('.panel_product').remove();
        }

        function existIngredient(element, val){
            var body = $(element).find('tbody');
            var row  = $(body).find('tr#'+val);

            return row.length;
        }

        function removeRow(e){
            var body = $(e).closest('tbody');
            $(e).closest('tr').remove();
            scrollTo($(body).offset().top);
        }

        function findIngredients(id){
            return $.grep(ingredients, function(n, i){
              return n.id == id;
            });
        };

        function filterTable(){
            var products = $('body').find('.panel_product');

            //check table if has row
            for(var i = 0; i < products.length; i++){
                var rows = $(products[i]).find('tr');

                if(rows.length == 1){
                    $(products[i]).remove();
                }
            }

            return $('body').find('.panel_product');
        }

        function scrollTo(height){
            $('html, body').animate({
                scrollTop: height
            }, 500);
        }

        function fetchUnitType(e){
            var id = $(e).val();
            var product = $(e).closest('.panel_product');
            var size = $(product).find('table').attr('data-id');

            get_physical_quantity(id, size);
        }
    </script>
@endsection
