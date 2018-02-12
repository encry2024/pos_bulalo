<?php $__env->startSection('title', 'Commissary Product Management | View'); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        Commissary Product Management
        <small>View</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">PRODUCT<small>(<?php echo e($product->name); ?>)</small></h3>

            <div class="box-tools pull-right">
                <?php echo $__env->make('backend.commissary.product.includes.partials.product-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <div role="tabpanel">
                <div class="col-lg-6">
                    <h3>Product Cost : <?php echo e($product->cost); ?></h3>

                    <table class="table table-bordered">
                        <thead>
                            <th>INGREDIENT NAME</th>
                            <th>QUANTITY</th>
                            <th>UNIT TYPE</th>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $product->ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($ingredient->supplier == 'Other' ? $ingredient->other_inventory->name : $ingredient->drygood_inventory->name); ?></td>
                                <td><?php echo e($ingredient->pivot->quantity); ?></td>
                                <td><?php echo e($ingredient->pivot->unit_type); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>                
            </div><!--tab panel-->

        </div><!-- /.box-body -->
    </div><!--box-->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>