<?php $__env->startSection('title', 'Commissary Delivery | Record Item'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        Commissary Delivery <small>Record Item</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e(Form::open(['route' => 'admin.commissary.delivery.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])); ?>


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Delivered Item</h3>

                <div class="box-tools pull-right">
                    <?php echo $__env->make('backend.commissary.delivery.includes.partials.delivery-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">

                <div class="form-group">
                    <?php echo e(Form::label('item_type', 'Item Type', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::select('item_type', ['PRODUCT' => 'PRODUCT', 'RAW MATERIAL' => 'RAW MATERIAL'] ,null, ['class' => 'form-control', 'required' => 'required', 'id' => 'item_type'])); ?>

                    </div>

                    <?php echo e(Form::label('date', 'Date', ['class' => 'col-lg-1 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('date', date('Y-m-d'), ['class' => 'form-control date', 'maxlength' => '191', 
                            'required' => 'required'])); ?>

                    </div>
                </div>

                <div class="form-group">
                    <?php echo e(Form::label('item_id', 'Item Name', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4" id="item_panel">
                        <?php echo e(Form::select('item_id', $products ,old('item_id'), ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus'])); ?>

                    </div>

                    <?php echo e(Form::label('quantity', 'Quantity', ['class' => 'col-lg-1 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('quantity', old('quantity'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required'])); ?>

                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    <?php echo e(link_to_route('admin.commissary.delivery.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs'])); ?>

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
        var products    = '<?php echo Form::select("item_id", $products, old("item_id"), ["class" => "form-control", "required" => "required"]); ?>';
        var inventories = '<?php echo Form::select("item_id", $inventories, old("item_id"), ["class" => "form-control", "required" => "required"]); ?>';

        $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' });
        $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' });

        $('#item_type').on('change', function(){
            if($(this).val() == 'PRODUCT')
            {
                $('#item_panel').find('select').remove();
                $('#item_panel').append(products);
            }
            else
            {
                $('#item_panel').find('select').remove();
                $('#item_panel').append(inventories);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>