<?php $__env->startSection('title', 'Sales Report'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

    <?php echo e(Html::style('https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Sales Report</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Report List</h3>

            <div class="box-tools pull-right">
                <button class="btn btn-warning btn-sm" onClick="$('#daily_log_table').tableExport({type:'excel',escape:'false'});"><i class="fa fa-bars"></i> Export Table Data</button>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <?php echo e(Form::open(['route' => 'admin.report.pos.item_use.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])); ?>

                    <div class="col-lg-12">
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
            
            <div class="table-responsive">
                <table id="datatable" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>ITEM NAME</th>
                        <th>QUANTITY</th>
                        <th>PRICE</th>
                        <th>TOTAL PRICE</th>
                        <th>SUPPLIER</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total_price = 0;
                            if(!empty($reports))
                            {
                                foreach($reports as $report)
                                {
                                    echo '<tr>';
                                    echo '<td>'.$report->name.'</td>';
                                    echo '<td>'.$report->quantity.'</td>';
                                    echo '<td>'.$report->price.'</td>';
                                    echo '<td>'.number_format(($report->price * $report->quantity), 2).'</td>';
                                    echo '<td>'.$report->supplier.'</td>';
                                    echo '</tr>';

                                    $total_price += $report->price * $report->quantity;
                                }
                            }
                            else
                            {
                                echo '<tr><td colspan="5">No records found!.</td></tr>';
                            }
                        ?>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5">TOTAL: <b><?php echo e(number_format($total_price, 2)); ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->


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

    <script>
        // $(function() {
        //     table = $('#datatable').DataTable({
        //         dom: 'Bfrtip',
        //         buttons: [
        //             'copyHtml5',
        //             'excelHtml5',
        //             'csvHtml5',
        //             'pdfHtml5'
        //         ],
        //         displayLength:100,
        //         order: [1, 'asc']
        //     });
        // });
            

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>