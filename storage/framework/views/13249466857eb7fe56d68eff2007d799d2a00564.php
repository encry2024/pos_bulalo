<?php $__env->startSection('after-styles'); ?>
<?php echo e(Html::style('css/highcharts.css')); ?>

<?php echo e(Html::style('css/dashboard.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        <?php echo e(app_name()); ?>

        <small><?php echo e(trans('strings.backend.dashboard.title')); ?></small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if (access()->hasRoles([1, 2])): ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">POS MONTHLY SALES</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <div id="posChart"></div>
        </div><!-- /.box-body -->
    </div><!--box box-success-->
    <?php endif; ?>

    <?php if (access()->hasRoles([1,3])): ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">COMMISSARY MONTHLY SALES</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <div id="commissaryChart"></div>
        </div><!-- /.box-body -->
    </div><!--box box-success-->
    <?php endif; ?>

    <?php if (access()->hasRoles([1,2])): ?>
    <div class="col-lg-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Top Products for <?php echo e(date('F')); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div><!-- /.box tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <ul class="list-group">
                    <?php if(count($topProducts)): ?>
                        <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <?php echo e($key); ?>   
                                <span class="label label-primary pull-right">
                                    <?php echo e($value); ?>

                                </span>                             
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <li class="list-group-item">
                            No product to be list.                              
                        </li>
                    <?php endif; ?>
                </ul>
            </div><!-- /.box-body -->
        </div><!--box box-success-->
    </div>

    <div class="col-lg-6">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Request</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php if(count($requests)): ?>
                    <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item">
                            <?php echo e($request->title); ?>&nbsp;
                            <a href="<?php echo e(route('admin.request.show', $request)); ?>" class="btn btn-xs btn-primary pull-right">View</a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item" style="text-align: center">
                        <a href="<?php echo e(route('admin.request.index')); ?>">View All</a>
                    </li>
                <?php else: ?>
                <li class="list-group-item">No Request</li>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
<?php echo e(Html::script('js/highcharts.js')); ?>

<script type="text/javascript">
    var request_id = 0;
    var request_from = '';

    <?php if (access()->hasRoles([1,2])): ?>
    Highcharts.chart('posChart', {

        title: {
            text: ' '
        },

        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php $__currentLoopData = $monthNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e($name); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ]
        },
        yAxis: {
            title: {
                text: 'Value'
            }
        },
        tooltip: {
            valueSuffix: ' PHP'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            lineWidth: 1,
                shadow: true,
        },

        series: [{
            name: 'SALES',
            data: [
                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($month); ?>,
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ]
        }]

    });
    <?php endif; ?>

    <?php if (access()->hasRoles([1,3])): ?>
    Highcharts.chart('commissaryChart', {

        title: {
            text: ' '
        },

        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php $__currentLoopData = $monthNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e($name); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ]
        },
        yAxis: {
            title: {
                text: 'Value'
            }
        },
        tooltip: {
            valueSuffix: ' PHP'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            lineWidth: 1,
                shadow: true,
        },

        series: [{
            name: 'SALES',
            data: [
                <?php $__currentLoopData = $commissaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commissary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($commissary); ?>,
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ]
        }]

    });
    <?php endif; ?>

    $('.highcharts-credits').hide();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>