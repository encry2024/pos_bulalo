<?php $__env->startSection('title', 'Commissary Product Management | Add Product'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        Commissary Product Management <small>Add Product</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e(Form::open(['route' => 'admin.commissary.produce.store', 'class' => 'form-horizontal', 'Product' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Add Product</h3>

                <div class="box-tools pull-right">
                    <?php echo $__env->make('backend.commissary.produce.includes.partials.product-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    <?php echo e(Form::label('product_id', 'Product Name', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::select('product_id', $products, old('product_id'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus'])); ?>

                    </div><!--col-lg-10-->

                    <?php echo e(Form::label('date', 'Date Created', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('date', old('date', date('Y-m-d')), ['class' => 'form-control date', 'maxlength' => '191', 'required' => 'required',])); ?>

                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    <?php echo e(Form::label('quantity', 'Quantity', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::number('quantity', old('quantity', 0), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required',])); ?>

                    </div><!--col-lg-10-->

                </div><!--form control-->

            </div>
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    <?php echo e(link_to_route('admin.product.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs'])); ?>

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

    <?php echo e(Html::script('js/timepicker.js')); ?>


    <script type="text/javascript">
        $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' });
        $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>