<?php $__env->startSection('title', 'Sales Invoice'); ?>

<?php $__env->startSection('after-styles'); ?>
<style type="text/css">
    tr.title td{
        background: #9bcae4;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Sales Invoice</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                
            </h3>

            <div class="box-tools pull-right">
                <div class="col-lg-2">
                    <button class="btn btn-warning btn-sm" onClick="$('#daily_log_table').tableExport({type:'excel',escape:'false'});"><i class="fa fa-bars"></i> Export Table Data</button>
                </div>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="col-lg-12">
                <?php echo e(Form::open(['route' => 'admin.commissary.invoice.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])); ?>


                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label>DATE</label>
                            <input class="form-control" type="text" name="date" id="datepicker" required value="<?php echo e($date); ?>">                
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" style="margin-top: 25px"><i class="fa fa-calendar"></i> Search Date</button>
                        </div>
                    </div>

                <?php echo e(Form::close()); ?>  
            </div>

            <table class="table table-bordered table-stripped" id="daily_log_table">
                <thead>
                    <th style="text-align:center" colspan="4">Delivery Report</th>
                </thead>
                <tbody>
                    <tr>
                        <td>From:</td>
                        <td><?php echo e(app_name()); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Address</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Phone No#</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr class="title">
                        <td><b>DATE</b></td>
                        <td><b>YOUR NO#</b></td>
                        <td><b>OUR NO#</b></td>
                        <td><b>SALES PERSON</b></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="title">
                        <td>&nbsp;</td>
                        <td>QUANTITY</td>
                        <td>PRODUCT NO.</td>
                        <td>DESCRIPTION</td>
                    </tr>

                    <?php if(count($datas)): ?>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php
                                    $total = 0;

                                    foreach($data->stocks as $stock)
                                    {
                                        $total = $total + $stock->quantity;
                                    }

                                    echo $total;
                                ?>
                            </td>
                            <td><?php echo e($data->id); ?></td>
                            <td><?php echo e($data->supplier == 'Other' ? $data->other_inventory->name : $data->drygood_inventory->name); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No Record in list.</td>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>