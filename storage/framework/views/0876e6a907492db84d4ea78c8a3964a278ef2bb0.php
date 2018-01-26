<?php $__env->startSection('title', 'Commissary Daily Inventory Report'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

    <?php echo e(Html::style('https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css')); ?>

    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>


    <style type="text/css">
        table{
            font-size: 9pt;
            font-weight: bold;
        }
        th{
            width: 11.11%;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Commissary Daily Inventory Report</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
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
                <?php echo e(Form::open(['route' => 'admin.report.commissary.daily.inventory.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])); ?>


                    <div class="row">
                        <div class="form-group col-lg-2">
                            <label>From</label>
                            <input class="form-control" type="text" name="from" id="from" readonly required value="<?php echo $from; ?>">                            
                        </div>

                        <div class="form-group col-lg-2">
                            <label>To</label>
                            <input class="form-control" type="text" name="to" id="to" readonly required value="<?php echo $to; ?>">                            
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
                    <th>Duration Date</th>
                    <th colspan="3"></th>
                    <th style="background:green">4</th>
                    <th style="background:blue">5</th>
                    <th style="background:yellow">6</th>
                    <th style="background:red">7</th>
                </thead>
                <tbody>
                <tr>
                    <th>ITEM</th>
                    <th>UNIT</th>
                    <th>COST</th>                    
                    <th>CONTENT</th>
                    <th>MON</th>
                    <th>TUE</th>
                    <th>WED</th>
                    <th>THURS</th>
                    <th>TOTAL AMOUNT</th>
                </tr>
                    <?php if(count($reports)): ?>
                        <?php $grand_total = 0; ?>
                        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php if(count($objects->items)): ?>
                                <?php 
                                    $subtotal    = 0;
                                ?>
                                <tr>
                                    <td colspan="9" style="color:red">
                                        <?php echo e($objects->category == 'Cleaning Material' ? $objects->category.'s' : 
                                            ( $objects->category == 'Fruit' ? $objects->category.'s':'Food Supply')); ?>

                                    </td>
                                </tr>

                                <?php $__currentLoopData = $objects->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php 
                                        $mon = 0;
                                        $tue = 0;
                                        $wed = 0;
                                        $thu = 0;

                                        if(count($item->days))
                                        {
                                            foreach ($item->days as $day) {
                                                $mon = $mon + $day['mon']->stocks;
                                                $tue = $tue + $day['tue']->stocks;
                                                $wed = $wed + $day['wed']->stocks;
                                                $thu = $thu + $day['thurs']->stocks;
                                            }
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo e($item->name); ?></td>
                                        <td><?php echo e($item->unit); ?></td>
                                        <td><?php echo e($cost  = number_format($item->cost, 2)); ?></td>
                                        <td><?php echo e($item->stock + $mon + $tue + $wed + $thu); ?></td>
                                        <td><?php echo e($mon); ?></td>
                                        <td><?php echo e($tue); ?></td>
                                        <td><?php echo e($wed); ?></td>
                                        <td><?php echo e($thu); ?></td>
                                        <td>
                                            <?php 
                                                $total    = number_format(($mon + $tue + $wed + $thu) * $cost, 2);
                                                $subtotal = $subtotal + $total;

                                                echo $total;
                                            ?>
                                        </td>

                                       
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td colspan="7"></td>
                                    <td><b>Subtotal:</b></td>
                                    <td><?php echo e(number_format($subtotal, 2)); ?></td>
                                    <?php $grand_total = $grand_total + $subtotal ?>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr><td colspan="9"></td></tr>
                        <tr>
                            <td colspan="6"></td>
                            <td><b>GRAND TOTAL:</b></td>
                            <td><b>PHP</b></td>
                            <td><?php echo e(number_format($grand_total, 2)); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr rowspan="2"><td colspan='9'>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2">Prepared by Date:</td>
                        <td colspan="2">Witness by and Date:</td>
                        <td></td>
                        <td>Acknowledge by:</td>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>