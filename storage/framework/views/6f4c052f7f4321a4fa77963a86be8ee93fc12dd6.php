<?php $__env->startSection('title', 'Dry Goods Return | Return Item'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        Dry Goods Return <small>Return Item</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e(Form::open(['route' => 'admin.dry_good.goods_return.store', 'class' => 'form-horizontal', 'Product' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Dispose Item</h3>

                <div class="box-tools pull-right">
                    <?php echo $__env->make('backend.dry_good.goods_return.includes.partials.goods-return-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">

                <div class="form-group">
                    <?php echo e(Form::label('inventory_id', 'Item Name', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4" id="replace">
                        <?php echo e(Form::select('inventory_id', $commissaries, old('inventory_id'), ['class' => 'form-control date', 'maxlength' => '191', 'required' => 'required'])); ?>

                    </div>

                    <?php echo e(Form::label('date', 'Date', ['class' => 'col-lg-1 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('date', old('date', date('Y-m-d')), ['class' => 'form-control date', 'maxlength' => '191', 
                            'required' => 'required'])); ?>

                    </div>
                </div>

                <div class="form-group">
                    <?php echo e(Form::label('quantity', 'Quantity', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::number('quantity', old('quantity', 0), ['class' => 'form-control', 'maxlength' => '191', 
                            'required' => 'required'])); ?>

                    </div>

                    <?php echo e(Form::label('witness', 'Witness', ['class' => 'col-lg-1 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('witness', old('witness'), ['class' => 'form-control', 'maxlength' => '191', 
                            'required' => 'required'])); ?>

                    </div>
                </div>

                <div class="form-group">
                    <?php echo e(Form::label('reason', 'Reason', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-9">
                        <?php echo e(Form::textarea('reason', old('reason'), ['class' => 'form-control', 'maxlength' => '191', 
                            'required' => 'required', 'rows' => '4'])); ?>

                    </div>
                </div>

            </div>
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    <?php echo e(link_to_route('admin.dry_good.goods_return.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs'])); ?>

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

    <?php echo e(Html::script('js/backend/access/users/script.js')); ?>

    <?php echo e(Html::script('js/timepicker.js')); ?>

    <script>
        $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' });
        $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>