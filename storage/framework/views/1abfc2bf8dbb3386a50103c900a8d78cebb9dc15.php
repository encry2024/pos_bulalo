<?php $__env->startSection('title', 'Commissary Disposal Report'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

    <?php echo e(Html::style('https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css')); ?>

    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Commissary Disposal Report</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header witd-border">
            <h3 class="box-title">Report List</h3>

            <div class="box-tools pull-right">
                <div class="col-lg-2">
                    <button class="btn btn-warning btn-sm" onClick="$('#daily_log_table').tableExport({type:'excel',escape:'false'});"><i class="fa fa-bars"></i> Export Table Data</button>
                </div>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-lg-10">
                    <?php echo e(Form::open(['route' => 'admin.report.commissary.disposal.store', 'class' => 'form-horizontal', 'role' => 'form', 'metdod' => 'post'])); ?>


                        <div class="form-group">
                            <?php echo e(Form::label('date', 'Date', ['class' => 'col-lg-1 control-label'])); ?>


                            <div class="col-lg-3">
                                <?php echo e(Form::text('date', old('date', date('Y-m-d')), ['class' => 'form-control date', 
                                    'required' => 'required'])); ?>

                            </div>

                            <div class="col-lg-2">
                                <?php echo e(Form::submit('Get Record', ['class' => 'btn btn-primary'])); ?>

                            </div>
                        </div>

                    <?php echo e(Form::close()); ?>  
                </div>
            </div>

            <table class="table table-responsive table-bordered" id="daily_log_table">
                <thead>
                    <th style="color:red">DURATION DATE</th>
                </thead>
                <tr>
                    <td>DATE</td>
                    <td>DISPOSAL ITEM</td>
                    <td>QUANTITY</td>
                    <td>COST</td>
                    <td>TOTAL COST</td>
                    <td>REASON</td>
                    <td>WITNESS</td>
                </tr>
                <tbody>
                    <?php if(count($disposals)): ?>
                        <?php $__currentLoopData = $disposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($disposal->date); ?></td>
                                <td>
                                    <?php
                                        if($disposal->type == 'Raw Material')
                                        {
                                            if($disposal->inventory->supplier == 'Other')
                                            {
                                                echo $disposal->inventory->other_inventory->name;
                                            }
                                            else
                                            {
                                                echo $disposal->inventory->drygood_inventory->name;
                                            }
                                        }
                                        else
                                        {
                                            echo $disposal->inventory->name;
                                        }
                                    ?>
                                </td>
                                <td><?php echo e($disposal->quantity); ?></td>
                                <td><?php echo e($disposal->cost); ?></td>
                                <td><?php echo e($disposal->total_cost); ?></td>
                                <td><?php echo e($disposal->reason); ?></td>
                                <td><?php echo e($disposal->witness); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td colspan="8">&nbsp;</td>
                        </tr>
                        <tr style="color:red">
                            <td colspan="4"></td>
                            <td><b>GRAND TOTAL: </b></td>
                            <td colspan="2"><b><?php echo e(number_format($disposals->sum('total_cost'), 2)); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>Prepared by:</td>
                        </tr>
                    <?php endif; ?>
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