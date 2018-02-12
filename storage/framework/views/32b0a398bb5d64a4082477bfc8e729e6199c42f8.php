<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-home"></i> <?php echo e(trans('navs.general.home')); ?>

                </div>

                <div class="panel-body">
                    <?php echo e(trans('strings.frontend.welcome_to', ['place' => app_name()])); ?>

                </div>
            </div><!-- panel -->

        </div><!-- col-md-10 -->

    </div><!--row-->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>