<?php $__env->startSection('title', 'POS Product Management | View'); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        POS Product Management
        <small>View</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Product (<small><?php echo e($product->name); ?></small>)</h3>

            <div class="box-tools pull-right">
                <?php echo $__env->make('backend.product.includes.partials.product-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <div role="tabpanel">
                <?php if(count($product->product_size)): ?>
                <?php $__currentLoopData = $product->product_size; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <h4><?php echo e(strtoupper($product->size)); ?></h4>
                            <h4>Price : <?php echo e($product->price); ?> PHP</h4>
                            <h4>Cost : <?php echo e($product->cost); ?> PHP</h4>

                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <th>INGREDIENT</th>
                                    <th>QUANTITY/UNIT TYPE</th>
                                    <th>CATEGORY</th>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $product->ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                        <?php 
                                            if($ingredient->supplier == 'Commissary Product')
                                            {
                                                echo $ingredient->commissary_product->name;
                                            }
                                            elseif($ingredient->supplier == 'Commissary Raw Material')
                                            {
                                                if($ingredient->commissary_inventory->supplier == 'Other')
                                                    echo $ingredient->commissary_inventory->other_inventory->name;
                                                else
                                                    echo $ingredient->commissary_inventory->drygood_inventory->name;
                                            }
                                            elseif($ingredient->supplier == 'DryGoods Material')
                                            {
                                                echo $ingredient->dry_good_inventory->name;
                                            }
                                            else
                                            {
                                                echo $ingredient->other->name;
                                            }
                                        ?>
                                        </td>
                                        <td>
                                        <?php echo e($ingredient->pivot->quantity.' '.
                                            $ingredient->pivot->unit_type); ?>

                                        </td>
                                        <td><?php echo e($ingredient->category->name); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div><!--tab panel-->

        </div><!-- /.box-body -->
    </div><!--box-->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>