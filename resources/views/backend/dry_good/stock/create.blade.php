@extends ('backend.layouts.app')

@section ('title', 'Dry Good Stock In | Add Stock')

@section('after-styles')
    {{ Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css') }}
@endsection

@section('page-header')
    <h1>
        Dry Good Stock In <small>Add Stock</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.dry_good.stock.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Add Stock</h3>

                <div class="box-tools pull-right">
                    @include('backend.dry_good.stock.includes.partials.stock-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('inventory_id', 'Ingredient Name', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        <select name="inventory_id" id="inventory_id" class="form-control select-inventory">
                            <option disabled selected>-- Select Item From Inventory --</option>
                            @foreach($inventories as $inventory)
                                <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                            @endforeach
                        </select>
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('quantity', 'Quantity', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        <div class="input-group">
                            <span class="input-group-addon" id="physical_type"></span>
                            {{ Form::text('quantity', old('quantity'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required']) }}
                        </div>
                    </div>

                    {{ Form::label('received', 'Received Date', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::text('received', date('Y-m-d'), ['class' => 'form-control date', 'maxlength' => '191',
                            'required' => 'required']) }}
                    </div>
                </div><!--form control-->

                <div class="form-group">

                </div>

                <div class="form-group">
                    {{ Form::label('price', 'Price', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::text('price', old('price'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required']) }}
                    </div>

                    {{ Form::label('expiration', 'Expiration Date', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::text('expiration', date('Y-m-d'), ['class' => 'form-control date', 'maxlength' => '191', 
                            'required' => 'required']) }}
                    </div>
                </div>
                
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.dry_good.stock.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
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
        $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' });
        $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' });

        $("#inventory_id").change(function() {
           item_id = $(this).val();

            $.ajax({
                type: "post",
                url: "{{ route('admin.dry_good.inventory.get_item') }}",
                data: {
                    _token:         '{{ csrf_token() }}',
                    inventory_id:    item_id,
                },
                dataType: 'JSON',
                success: function(data) {
                    document.getElementById("physical_type").innerHTML = data.unit_type;
                }
            });
        });
    </script>
@endsection
