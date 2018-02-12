<?php $__env->startSection('title', 'Dry Good Inventory Management | Edit Item'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        Dry Good Inventory Management
        <small>Edit Inventory</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e(Form::model($inventory, ['route' => ['admin.dry_good.inventory.update', $inventory], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'edit-Inventory'])); ?>


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Item</h3>

                <div class="box-tools pull-right">
                    <?php echo $__env->make('backend.dry_good.inventory.includes.partials.inventory-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    <?php echo e(Form::label('name', trans('validation.attributes.backend.access.roles.name'), ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-10">
                        <?php echo e(Form::text('name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.backend.access.roles.name')])); ?>

                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    <?php echo e(Form::label('reorder_level', 'Critical Level', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::text('reorder_level', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required'])); ?>

                    </div>

                    <?php echo e(Form::label('category_id', 'Category', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-4">
                        <?php echo e(Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required'])); ?>

                    </div>
                </div>

            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <?php echo e(link_to_route('admin.inventory.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs'])); ?>

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