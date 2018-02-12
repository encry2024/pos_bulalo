<?php $__env->startSection('title', 'Category'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Category</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Category List</h3>

            <div class="box-tools pull-right">
                <?php echo $__env->make('backend.category.includes.partials.category-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive col-lg-4">
                <table id="users-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>CATEGORY NAME</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(count($categories)): ?>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($category->name); ?></td>
                                <td><?php echo $category->delete_button; ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td>No Category</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->


<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>