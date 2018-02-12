<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Notifications</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">List</h3>

            <div class="box-tools pull-right">
                
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>DATE</th>
                        <th>DESCRIPTION</th>
                        <th>STOCK FROM</th>
                        <th>STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->


    

<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js")); ?>

    <?php echo e(Html::script("js/backend/plugin/datatables/dataTables-extend.js")); ?>


    <script>
        $(function() {
            $('#users-table').DataTable({
                dom: 'Blfrtip',
                processing: false,
                serverSide: false,
                ajax: '<?php echo route('admin.notification.get'); ?>',
                columns: [
                    { data: 'name' },
                    { data: 'date' },
                    { data: 'description' },
                    { data: 'stock_from' },
                    { data: 'status' }
                ],
                order: [1, 'asc']
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>