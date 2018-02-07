<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">MONTHLY SALE FOR <?php echo e(strtoupper($date)); ?></div>

                <div class="panel-body">
                    <div class="col-lg-6">
                        <button class="btn btn-warning btn-sm" onClick="$('#daily_log_table').tableExport({type:'excel',escape:'false'});"><i class="fa fa-bars"></i> Export Table Data</button>
                    </div>

                    <div class="col-lg-6">
                        <h3 class="pull-right" style="margin:5px">
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
                    </div>

                    <table class="table table-bordered" id="daily_log_table">
                        <thead>
                            <th>TRANSACTION NO.</th>
                            <th>DATE</th>
                            <th>TIME</th>
                            <th>PRICE</th>
                            <th>DISCOUNT</th>
                            <th>TOTAL</th>
                        </thead>
                        <tbody>
                            <?php if(count($orders)): ?>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($order->transaction_no); ?></td>
                                    <td><?php echo e($order->created_at->format('F d, Y')); ?></td>
                                    <td><?php echo e($order->created_at->format('h:i A')); ?></td>
                                    <td><?php echo e(number_format($order->payable, 2)); ?></td>
                                    <td><?php echo e(number_format($order->discount, 2)); ?></td>
                                    <td><?php echo e(number_format($order->payable - $order->discount, 2)); ?></td>
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


<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script('js/tableExport.js')); ?>

    <?php echo e(Html::script('js/jquery.base64.js')); ?>

    <?php echo e(Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js")); ?>

    <?php echo e(Html::script("https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js")); ?>

    <?php echo e(Html::script("https://cdn.datatables.net/plug-ins/1.10.16/filtering/row-based/range_dates.js")); ?>

    <?php echo e(Html::script("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js")); ?>

    <?php echo e(Html::script("https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js")); ?>

    <?php echo e(Html::script("https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js")); ?>

    <?php echo e(Html::script("https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js")); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>