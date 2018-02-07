<?php $__env->startSection('title', 'Setting | Table'); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Table List</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools pull-right">
                     <?php echo $__env->make('backend.setting.table.includes.partials.setting-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="table-responsive">
                    <table id="users-table" class="table table-condensed table-hover">
                        <thead>
                            <tr>
                            <th>TABLE NUMBER</th>
                            <th>PRICE</th>
                            <th>&nbsp;</th>
                            </tr>
                        </thead>
                    </table>
                </div><!--table-responsive-->
            </div><!-- /.box-body -->
        </div><!--box-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js')); ?>

    <?php echo e(Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js")); ?>

    <script>
        $(function() {
            $('#users-table').DataTable({
                dom: 'Blfrtip',
                processing: false,
                serverSide: true,
                ajax: '<?php echo route('admin.setting_table.get'); ?>',
                columns: [
                    { data: 'number' },
                    { data: 'price' },
                    { data: 'actions' }
                ],
                order: [0, 'asc']
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>