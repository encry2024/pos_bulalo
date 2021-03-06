<?php $__env->startSection('title', 'Dry Good Delivery'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Dry Good Delivery</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Delivery List</h3>

            <div class="box-tools pull-right">
                <?php echo $__env->make('backend.dry_good.delivery.includes.partials.delivery-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>QUANTITY</th>
                        <th>PRICE</th>
                        <th>TOTAL</th>
                        <th>DATE</th>
                        <th>DELIVER TO</th>
                        <th>STATUS</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
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
                serverSide: true,
                ajax: '<?php echo route('admin.dry_good.delivery.get'); ?>',
                columns: [
                    { data: 'inventory.name' },
                    { data: 'quantity' , searchable: false},
                    { data: 'price' , searchable: false},
                    { data: 'total' , searchable: false},
                    { data: 'date' },
                    { data: 'deliver_to' },
                    { data: 'status' },
                    { data: 'actions' , searchable: false}
                ],
                order: [5, 'asc']
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>