<?php $__env->startSection('title', trans('labels.backend.access.users.management')); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        <?php echo e(trans('labels.backend.access.users.management')); ?>

        <small><?php echo e(trans('labels.backend.access.users.active')); ?></small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo e(trans('labels.backend.access.users.active')); ?></h3>

            <div class="box-tools pull-right">
                <?php echo $__env->make('backend.access.includes.partials.user-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th><?php echo e(trans('labels.backend.access.users.table.last_name')); ?></th>
                        <th><?php echo e(trans('labels.backend.access.users.table.first_name')); ?></th>
                        <th><?php echo e(trans('labels.backend.access.users.table.email')); ?></th>
                        <th><?php echo e(trans('labels.backend.access.users.table.confirmed')); ?></th>
                        <th><?php echo e(trans('labels.backend.access.users.table.roles')); ?></th>
                        <th><?php echo e(trans('labels.backend.access.users.table.social')); ?></th>
                        <th><?php echo e(trans('labels.backend.access.users.table.created')); ?></th>
                        <th><?php echo e(trans('labels.backend.access.users.table.last_updated')); ?></th>
                        <th><?php echo e(trans('labels.general.actions')); ?></th>
                    </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo e(trans('history.backend.recent_history')); ?></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <?php echo history()->renderType('User'); ?>

        </div><!-- /.box-body -->
    </div><!--box box-success-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js")); ?>

    <?php echo e(Html::script("js/backend/plugin/datatables/dataTables-extend.js")); ?>


    <script>
        $(function () {
            $('#users-table').DataTable({
                dom: 'lfrtip',
                processing: false,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '<?php echo e(route("admin.access.user.get")); ?>',
                    type: 'post',
                    data: {status: 1, trashed: false},
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'last_name', name: '<?php echo e(config('access.users_table')); ?>.last_name'},
                    {data: 'first_name', name: '<?php echo e(config('access.users_table')); ?>.first_name'},
                    {data: 'email', name: '<?php echo e(config('access.users_table')); ?>.email'},
                    {data: 'confirmed', name: '<?php echo e(config('access.users_table')); ?>.confirmed'},
                    {data: 'roles', name: '<?php echo e(config('access.roles_table')); ?>.name', sortable: false},
                    {data: 'social', name: 'social', sortable: false},
                    {data: 'created_at', name: '<?php echo e(config('access.users_table')); ?>.created_at'},
                    {data: 'updated_at', name: '<?php echo e(config('access.users_table')); ?>.updated_at'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>