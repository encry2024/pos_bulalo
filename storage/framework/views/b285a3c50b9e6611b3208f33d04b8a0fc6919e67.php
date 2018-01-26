<?php $__env->startSection('title', 'Commissary Summary Report'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

    <?php echo e(Html::style('https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css')); ?>

    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Commissary Summary Report</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success" style="overflow-x: scroll">
        <div class="box-header with-border">
            <h3 class="box-title">Report List</h3>

            <div class="box-tools pull-right">
                <div class="col-lg-2">
                    <button class="btn btn-warning btn-sm" onClick="$('#daily_log_table').tableExport({type:'excel',escape:'false'});"><i class="fa fa-bars"></i> Export Table Data</button>
                </div>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="col-lg-12">
                <?php echo e(Form::open(['route' => 'admin.report.commissary.summary.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])); ?>


                    <div class="row">
                        <div class="form-group col-lg-2">
                            <label>From</label>
                            <input class="form-control" type="text" name="from" id="from" readonly required value="<?php echo e($from); ?>">                            
                        </div>

                        <div class="form-group col-lg-2">
                            <label>To</label>
                            <input class="form-control" type="text" name="to" id="to" readonly required value="<?php echo e($to); ?>">                            
                        </div>

                        <div class="form-group col-lg-2">
                            <label>&nbsp;</label>

                            <div class="input-group">
                                <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                                <span>
                                    <i class="fa fa-calendar"></i> Select Date
                                </span>
                                <i class="fa fa-caret-down"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" style="margin-top: 25px"><i class="fa fa-calendar"></i> Search Date</button>
                        </div>
                    </div>

                <?php echo e(Form::close()); ?>  
            </div>

           <table class="table table-responsive table-bordered" id="daily_log_table">
                <thead>
                    <th colspan="4"></th>
                    <th colspan="2">Inventory Summary Report</th>
                    <th colspan="4"></th>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="10">Duration Date:</td>
                    </tr>
                    <tr>
                        <td>ITEM</td>
                        <td>UNIT</td>
                        <td>BEGINNING</td>
                        <td>DELIVERY (DDR)</td>
                        <td>SALE (DSI)</td>
                        <td>DISPOSAL (DF)</td>
                        <td>GOODS RETURN (GF)</td>
                        <td>ENDING INVENTORY</td>
                        <td>ACTUAL INVENTORY (DIC)</td>
                        <td>VARIANCE</td>
                    </tr>
                    <?php if(count($reports)): ?>
                        <?php $__currentLoopData = array_except($reports,['food']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td colspan="10" style="color:red;background:#ffe4e4"><?php echo e($report->category); ?></td>
                            </tr>

                            <?php if(count($report->summaries)): ?>
                                <?php $__currentLoopData = $report->summaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($summary->name); ?></td>
                                    <td><?php echo e($summary->unit); ?></td>
                                    <td><?php echo e($summary->beginning->quantity); ?></td>
                                    <td><?php echo e($summary->delivery->quantity); ?></td>
                                    <td><?php echo e($summary->sale->quantity); ?></td>
                                    <td><?php echo e($summary->dispose->quantity); ?></td>
                                    <td><?php echo e($summary->goods->quantity); ?></td>
                                    <td><?php echo e($summary->ending); ?></td>
                                    <td><?php echo e($summary->actual); ?></td>
                                    <td></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10">No record in list.</td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td colspan="18">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>ITEMS</td>
                            <td>UNIT</td>
                            <td></td>
                            <td>BEGINNING</td>
                            <td></td>
                            <td></td>
                            <td>DELIVERY</td>
                            <td></td>
                            <td></td>
                            <td>PRODUCT-OUT</td>
                            <td></td>
                            <td>EXCESS/FRUITS SKIN</td>
                            <td>RETURN</td>
                            <td>SHRINKAGE/DISPOSAL</td>
                            <td>PROFIT</td>
                            <td>ENDING</td>
                            <td>ACTUAL</td>
                            <td>VARIANCE</td>
                        </tr>
                        <tr>
                            <td style="color:red;background: #ffe4e4">Food</td>
                            <td></td>
                            <td>QUANTITY</td>
                            <td>COST</td>
                            <td style="background-color:yellow">TOTAL</td>
                            <td>QUANTITY</td>
                            <td>COST</td>
                            <td style="background-color:yellow">TOTAL</td>
                            <td>QUANTITY</td>
                            <td>COST</td>
                            <td style="background-color:yellow">TOTAL</td>
                            <td>KILOS</td>
                            <td>QTY/COST</td>
                            <td>QTY/COST</td>
                            <td colspan="4"></td>
                        </tr>

                        <?php if(count($reports['food']->summaries)): ?>
                            <?php $__currentLoopData = $reports['food']->summaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $total = 0; ?> 
                            <tr>
                                <td><?php echo e($summary->name); ?></td>
                                <td><?php echo e($summary->unit); ?></td>

                                <!-- beginning -->
                                <td><?php echo e($qty  = $summary->beginning->quantity); ?></td>
                                <td><?php echo e($cost = number_format($summary->beginning->cost, 2)); ?></td>
                                <td style="background-color:yellow">
                                    <?php 
                                        $sub   = number_format($cost * $qty, 2);

                                        echo $sub;
                                    ?>
                                </td>

                                <!-- delivery -->
                                <td><?php echo e($qty  = $summary->delivery->quantity); ?></td>
                                <td><?php echo e($cost = number_format($summary->delivery->cost, 2)); ?></td>
                                <td style="background-color:yellow">
                                    <?php 
                                        $sub   = number_format($cost * $qty, 2);

                                        echo $sub;
                                    ?>
                                </td>

                                <!-- sales -->
                                <td><?php echo e($qty  = $summary->sale->quantity); ?></td>
                                <td><?php echo e($cost = number_format($summary->sale->cost, 2)); ?></td>
                                <td style="background-color:yellow">
                                    <?php 
                                        $sub   = number_format($cost * $qty, 2);
                                        $total = $sub;
                                        echo $sub;
                                    ?>
                                </td>

                                <td></td>

                                <td><?php echo e($summary->goods->quantity.'/'.$summary->goods->cost); ?></td>
                                <td><?php echo e($summary->dispose->quantity.'/'.$summary->dispose->cost); ?></td>
                                <td><?php echo e(number_format($total, 2)); ?></td>
                                <td><?php echo e($summary->ending); ?></td>
                                <td><?php echo e($summary->actual); ?></td>
                                <td></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <tr>
                        <td style="color:red;background: #ffe4e4" colspan="18">Products</td>
                    </tr>

                    <tr>
                        <td colspan="18">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6">Preferred By:</td>
                        <td colspan="6">Acknowledge By:</td>
                    </tr>
                    <tr>
                        <td colspan="6">Date:</td>
                        <td colspan="6">Date:</td>
                    </tr>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div><!--box-->


<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script('js/tableExport.js')); ?>

    <?php echo e(Html::script('js/jquery.base64.js')); ?>

    <?php echo e(Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js')); ?>

    <?php echo e(Html::script('js/timepicker.js')); ?> 
    <script> 
        $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' }); 
        $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' }); 
    </script> 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>