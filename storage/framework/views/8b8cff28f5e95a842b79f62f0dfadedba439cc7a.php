<?php $__env->startSection('title', 'POS Stock Management | Edit'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        POS Stock In
        <small>Edit</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e(Form::model($stock, ['route' => ['admin.stock.update', $stock], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'edit-Stock'])); ?>


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Stock</h3>

                <div class="box-tools pull-right">
                    <?php echo $__env->make('backend.stock.includes.partials.stock-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    <?php echo e(Form::label('name', trans('validation.attributes.backend.access.roles.name'), ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-10">
                        <?php echo e(Form::hidden('inventory_id', old('inventory_id'))); ?>

                        <?php echo e(Form::text('name', $name, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'readonly' => 'true'])); ?>

                        
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    <?php echo e(Form::label('quantity', 'Quantity', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('quantity', old('quantity'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'readonly' => 'true'])); ?>

                    </div>

                    <?php echo e(Form::label('received', 'Received Date', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('received', old('received'), ['class' => 'form-control date', 'maxlength' => '191', 
                            'required' => 'required'])); ?>

                    </div>
                </div><!--form control-->

                <div class="form-group">
                    <?php echo e(Form::label('price', 'Price', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('price', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required'])); ?>

                    </div>

                    <?php echo e(Form::label('expiration', 'Expiration Date', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('expiration', null, ['class' => 'form-control date', 'maxlength' => '191', 
                            'required' => 'required'])); ?>

                    </div>
                </div>

                <div class="form-group">
                    <?php echo e(Form::label('status', 'Status', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::select('status', ['EXPIRE' => 'EXPIRE', 'SPOIL' => 'SPOIL'], old('status'), ['class' => 'form-control', 'required' => 'required'])); ?>

                    </div>
                </div>

            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <?php echo e(link_to_route('admin.stock.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs'])); ?>

                </div><!--pull-left-->

                <div class="pull-right">
                    <?php echo e(Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-xs'])); ?>

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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>