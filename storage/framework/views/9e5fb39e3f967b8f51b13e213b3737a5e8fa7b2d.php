<?php $__env->startSection('title', 'Inventory Management | Add Inventory'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        Commissary Inventory Management <small>Add Inventory</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e(Form::open(['route' => 'admin.commissary.inventory.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])); ?>


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Add Inventory</h3>

                <div class="box-tools pull-right">
                    <?php echo $__env->make('backend.commissary.inventory.includes.partials.inventory-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">                    
                    <?php echo e(Form::label('supplier', 'Supplier', ['class' => 'col-lg-2 control-label'])); ?>

                    <div class="col-lg-4">
                        <?php echo e(Form::select(
                                'supplier', 
                                [
                                    'DryGoods Material'         => 'DryGoods',
                                    'Other'                     => 'Other'
                                ], 
                                old('commissaries'), 
                                [
                                    'class' => 'form-control', 
                                    'id' => 'supplier'
                                ]
                            )); ?>

                    </div>
                </div>

                <div class="form-group">
                    <?php echo e(Form::label('name', 'Item Name', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4" id="inventory_panel">
                        <?php echo e(Form::select(
                                'inventory_id',
                                $dry_goods, 
                                old('commissaries'), 
                                [
                                    'class'     => 'form-control', 
                                    'id'        => 'item',
                                    'onchange'  => 'item_change()'
                                ]
                            )); ?>

                    </div><!--col-lg-10-->

                    <?php echo e(Form::label('reorder_level', 'Critical Level', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('reorder_level', 0, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required'])); ?>

                    </div>
                </div><!--form control-->

                <div class="form-group">
                    <?php echo e(Form::label('physical_quantity', 'Physical Quantity', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <select class="form-control" name="physical_quantity" id="physical_quantity">
                            <option>Mass</option>
                            <option>Volume</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <?php echo e(Form::label('category_id', 'Category', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required'])); ?>

                    </div>
                </div>

                <div class="form-group">
                    <?php echo e(Form::label('unit_type', 'Unit Type', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <select class="form-control" name="unit_type" id="unit_type">
                            <option value="g">Gram</option>
                            <option value="kg">Kilogram</option>
                            <option value="ton">Ton</option>
                            <option value="lbs">Pound</option>
                            <option value="oz">Ounce</option>
                        </select>
                    </div>
                </div>


            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    <?php echo e(link_to_route('admin.inventory.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs'])); ?>

                </div><!--pull-left-->

                <div class="pull-right">
                    <?php echo e(Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs'])); ?>

                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    <?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js')); ?>

    <?php echo e(Html::script('js/timepicker.js')); ?>

    <?php echo e(Html::script('js/backend/access/users/script.js')); ?>

    <script type="text/javascript">
        var drygoods    = '<?php echo Form::select("inventory_id", $dry_goods, old("inventory_id"), ["class" => "form-control", "required" => "required", "id" => "item", "onchange" => "item_change()"]); ?>';
        var others      = '<?php echo Form::text("inventory_id", old("inventory_id"), ["class" => "form-control", "required" => "required", "id" => "item", "onchange" => "item_change()"]); ?>';
        
        $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' });
        $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' });


        $(document).ready(function(){
            $('#inventory_panel').find('select').remove();

            <?php if(count($dry_goods) == 0): ?>
                $('#inventory_panel').find('select').remove();
                $('#supplier').val('Other');
                $('#inventory_panel').append(others);
            <?php else: ?>
                $('#inventory_panel').find('input').remove();
                $('#supplier').val('DryGoods Material');
                $('#inventory_panel').append(drygoods);
            <?php endif; ?>

        });

        $('#supplier').on('change', function(){
            var val = $(this).val();

            $('#inventory_panel').find('select').remove();
            $('#inventory_panel').find('input').remove();

            if(val == 'Other')
            {
                $('#inventory_panel').append(others);
            }
            else if(val == 'DryGoods Material')
            {
                $('#inventory_panel').append(drygoods);
            }
        });

        $('#physical_quantity').on('change', function(){
            units($(this).val());
        });

        function units(val){
            var options = '';
            $('#unit_type').find('option').remove();
            
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

            $('#unit_type').append(options);
        }

        function item_change() {
            var supplier = $('#supplier').val();
            var item     = $('#item').val();

            if(supplier.length && item.length)
            {
                getCurrentUnit(item, supplier);
            }
        }

        function getCurrentUnit(id, supplier, )
        {
            var url = '<?php echo e(URL::to("admin/commissary/inventory/get_unit/")); ?>/' + id + '/' + supplier;

            $.ajax({
                type : 'GET',
                url  : url,
                success: function(data) {
                    if(supplier == 'DryGoods Material')
                    {
                        var physical  = data['physical_quantity'];
                        var unit_type = data['unit_type'];

                        units(physical);

                        $('#physical_quantity').val(physical);
                        $('#unit_type').val(unit_type);
                    }
                    

                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>