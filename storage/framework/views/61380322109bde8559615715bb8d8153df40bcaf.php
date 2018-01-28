<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">TODAY SALES</div>

                <div class="panel-body">

                    <h3>
                        <small>TOTAL SALE:</small> 
                        <?php
                            $total = 0;
                            foreach($orders as $order)
                            {
                                if($order->status == 'Paid')
                                {
                                    $total += ($order->payable - $order->discount);
                                }
                            }
                            echo number_format($total, 2);
                        ?>
                    </h3>

                    <table class="table table-bordered">
                        <thead>
                            <th>TRANSACTION NO.</th>
                            <th>DATE</th>
                            <th>TIME</th>
                            <th>PRICE</th>
                            <th>DISCOUNT</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                        </thead>
                        <tbody>
                            <?php if(count($orders)): ?>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($order->transaction_no); ?></td>
                                    <td><?php echo e($order->created_at->format('F d, Y')); ?></td>
                                    <td><?php echo e($order->created_at->format('h:i A')); ?></td>
                                    <td><?php echo e($order->payable); ?></td>
                                    <td><?php echo e(number_format($order->discount, 2)); ?></td>
                                    <td><?php echo e(number_format($order->payable - $order->discount, 2)); ?></td>
                                    <td style='color:<?php echo e($order->status =="Cancelled" ? "red":"green"); ?>'>
                                        <?php echo e($order->status); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan=3>No record to display.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div><!--panel body-->

            </div><!-- panel -->

        </div><!-- col-xs-12 -->

    </div><!-- row -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>