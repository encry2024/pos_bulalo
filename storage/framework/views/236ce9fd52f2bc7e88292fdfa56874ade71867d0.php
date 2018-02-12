<?php $__env->startSection('title', 'Commissary Product Management'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>Commissary Product Management</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Product List</h3>

            <div class="box-tools pull-right">
                <?php echo $__env->make('backend.commissary.product.includes.partials.product-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>NAME</th>
                        <th>PRODUCE</th>
                        <th>COST</th>
                        <th>CATEGORY</th>
                        <th>&nbsp;</th>
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
            <?php if(count($histories)): ?>
                <ul class='timeline'>
                <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <i class="fa <?php echo e($history->status == 'Add' ? ' fa-plus bg-green' : 'fa-minus bg-red'); ?>"></i> 
                    <div class="timeline-item">
                        <span class="time">
                            <i class="fa fa-clock-o"></i>
                            <?php echo e($history->created_at->format('h:i:s A')); ?>

                        </span>

                        <span class="time">
                            <i class="fa fa-calendar-o"></i>
                            <?php echo e($history->created_at->format('F d, Y')); ?>

                        </span>
                        <?php echo e($history->description); ?>

                    </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
            <h5>No record to display.</h5>
            <?php endif; ?>
        </div><!-- /.box-body -->
    </div>

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
                ajax: '<?php echo route('admin.commissary.product.get'); ?>',
                columns: [
                    { data: 'name' },
                    { data: 'produce' },
                    { data: 'cost' },
                    { data: 'category' },
                    { data: 'actions' }
                ],
                order: [1, 'asc']
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>