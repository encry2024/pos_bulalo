<?php $__env->startSection('title', 'Setting'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Setting</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Setting List</h3>

            <div class="box-tools pull-right">
                
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive col-lg-12">
                <table id="users-table" class="table table-condensed table-hover table-bordered">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" style="text-align: center">Update discounts setting.</td>
                        </tr>
                        <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="width:30%">
                                <a href="<?php echo e(route('admin.setting.edit', $setting)); ?>" class="btn btn-default btn-block">
                                    <?php echo e($setting->name); ?>

                                </a>
                            </td>
                            <td>
                               <?php echo e($setting->description); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <tr>
                            <td style="width:30%"> <a href="<?php echo e(route('admin.setting_table.index')); ?>" class="btn btn-default btn-block">
                                    Table
                                </a></td>
                            <td>Fixed Description</td>
                        </tr>
                    </tbody>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->


<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js")); ?>

    <?php echo e(Html::script("js/backend/plugin/datatables/dataTables-extend.js")); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>