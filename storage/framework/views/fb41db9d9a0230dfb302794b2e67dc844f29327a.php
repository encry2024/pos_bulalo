<?php $__env->startSection('title', 'Commissary Daily Delivery Report'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

    <?php echo e(Html::style('https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css')); ?>

    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>


    <style type="text/css">
        table{
            font-size: 9pt;
        }
        th{
            width: 11.11%;
        }
        td{
            font-weight: bold;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Commissary Daily Delivery Report</h1>
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
                <?php echo e(Form::open(['route' => 'admin.report.dry_good.daily.delivery.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])); ?>


                   <div class="form-group col-md-2">
                        <label for="date">Date:</label>
                        <input type="text" class="form-control datepicker" name="date" value="<?php echo e($date); ?>">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit" style="margin-top: 25px"><i class="fa fa-calendar"></i> Search Date</button>
                    </div>

                <?php echo e(Form::close()); ?>  
            </div>            

            <table class="table table-responsive table-bordered" id="daily_log_table">
                <thead>
                    <th>DATE:</th>
                    <th><?php echo e($date); ?></th>
                </thead>
                <tr>
                    <td>DELIVER TO</td>
                    <td>ITEM</td>
                    <td>UNIT</td>
                    <td>UNIT TYPE</td>
                    <td>U/PRICE</td>
                    <td>DATE</td>
                    <td>TOTAL</td>
                </tr>
                <tbody>
                    <?php
                        $g_total = 0;
                        if(count($items)) {
                            foreach($items as $item)
                            {
                                $total = 0;
                                $inventory = $item->inventory;

                                $total = $item->quantity * $item->price;
                                $g_total += $total;

                                echo '<tr>';
                                echo '<td>'.$item->deliver_to.'</td>';
                                echo '<td>'.$inventory->name.'</td>';
                                echo '<td>'.$item->quantity.'</td>';
                                echo '<td>'.$inventory->unit_type.'</td>';
                                echo '<td>'.$item->price.'</td>';
                                echo '<td>'.$item->date.'</td>';
                                echo '<td>'.number_format($total, 2).'</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7">No records</td></tr>';
                        }

                        echo '<tr><td colspan="7">&nbsp;</td></tr>';
                        echo '<tr>';
                        echo '<td colspan="5"></td>';
                        echo '<td>GRAND TOTAL: </td>';
                        echo '<td><b>'.number_format($g_total, 2).'</b></td>';
                        echo '</tr>';
                    ?>
            </table>
        </div><!-- /.box-body -->
    </div><!--box-->


<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script('js/tableExport.js')); ?>

    <?php echo e(Html::script('js/jquery.base64.js')); ?>

    <?php echo e(Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js')); ?>

    <script type="text/javascript">
        var startDate = new Date();
        
        $('.datepicker').datepicker({
            autoclose: true,
            minViewMode: 1,
            format: 'yyyy/mm/dd'
        }).on('changeDate', function(selected){
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.to').datepicker('setStartDate', startDate);
        }); 
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>