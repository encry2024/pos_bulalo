<?php $__env->startSection('title', 'Dry Good Delivery | Record Item'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        Dry Good Delivery <small>Record Item</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e(Form::open(['route' => 'admin.dry_good.delivery.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])); ?>


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Delivered Item</h3>

                <div class="box-tools pull-right">
                    <?php echo $__env->make('backend.dry_good.delivery.includes.partials.delivery-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">

                <div class="form-group">
                    <?php echo e(Form::label('item_id', 'Item Name', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-3" id="item_panel">
                        <select name="item_id" id="item_id" class="form-control">
                            <option disabled selected>-- Select Item From Inventory --</option>
                            <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($inventory->stock != 0): ?>
                                    <option value="<?php echo e($inventory->id); ?>"><?php echo e($inventory->name); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div><!--col-lg-3-->

                    <?php echo e(Form::label('date', 'Date', ['class' => 'col-lg-2 control-label'])); ?>

                    <div class="col-lg-3">
                        <?php echo e(Form::text('date', date('Y-m-d'), ['class' => 'form-control date', 'maxlength' => '191', 
                            'required' => 'required'])); ?>

                    </div>
                </div><!--form control-->

                <div class="form-group">
                    <?php echo e(Form::label('quantity', 'Quantity', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon" id="physical_type"></span>
                            <input name="quantity" type="number" class="form-control" maxlength="191" required="required" id="item-quantity" min="0">
                        </div>
                    </div>

                    <?php echo e(Form::label('deliver_to', 'Deliver To', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-3">
                        <?php echo e(Form::select('deliver_to', ['Commissary' => 'Commissary', 'POS' => 'POS'] ,old('deliver_to'), ['class' => 'form-control', 'required' => 'required'])); ?>

                    </div>
                </div>
                
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Empty Stocks</h3>
            </div><!-- /.box-header -->

            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <th>Item ID</th>
                            <th>Name</th>
                            <th></th>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($inventory->stock == 0): ?>
                                <tr>
                                    <td><?php echo e($inventory->id); ?></td>
                                    <td><?php echo e($inventory->name); ?></td>
                                    <td><a href="<?php echo e(route('admin.dry_good.stock.create')); ?>" class="btn btn-xs btn-success">Re-stock</a></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    <?php echo e(link_to_route('admin.dry_good.delivery.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs'])); ?>

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
        $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' });
        $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' });
        var max_stock = 0;

        $("#item_id").change(function() {
            item_id = $(this).val();

            $.ajax({
                type: "post",
                url: "<?php echo e(route('admin.dry_good.delivery.get_item')); ?>",
                data: {
                    _token:         '<?php echo e(csrf_token()); ?>',
                    inventory_id:    item_id,
                },
                dataType: 'JSON',
                success: function(data) {
                    document.getElementById("physical_type").innerHTML = data.unit_type;
                    $("#item-quantity").attr('max', data.stock);
                    max_stock = data.stock;
                }
            });
        });

        $("#item-quantity").on('keypress', function(e) {
            // console.log(max_stock);
            var currentValue = String.fromCharCode(e.which);
            var value = $(this).val() + currentValue;
            var finalValue = parseFloat(parseFloat(value).toFixed(2));
            var formattedStock = parseFloat(parseFloat(max_stock).toFixed(2));

            if(finalValue >= formattedStock) {
                e.preventDefault();
                document.getElementById('item-quantity').value = parseFloat(max_stock).toFixed(2);

            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>