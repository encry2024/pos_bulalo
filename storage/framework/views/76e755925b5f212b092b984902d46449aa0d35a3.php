<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo e(trans('navs.frontend.user.account')); ?></div>

                <div class="panel-body">

                    <div role="tabpanel">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><?php echo e(trans('navs.frontend.user.profile')); ?></a>
                            </li>

                            <li role="presentation">
                                <a href="#edit" aria-controls="edit" role="tab" data-toggle="tab"><?php echo e(trans('labels.frontend.user.profile.update_information')); ?></a>
                            </li>

                            <?php if($logged_in_user->canChangePassword()): ?>
                                <li role="presentation">
                                    <a href="#password" aria-controls="password" role="tab" data-toggle="tab"><?php echo e(trans('navs.frontend.user.change_password')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane mt-30 active" id="profile">
                                <?php echo $__env->make('frontend.user.account.tabs.profile', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            </div><!--tab panel profile-->

                            <div role="tabpanel" class="tab-pane mt-30" id="edit">
                                <?php echo $__env->make('frontend.user.account.tabs.edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            </div><!--tab panel profile-->

                            <?php if($logged_in_user->canChangePassword()): ?>
                                <div role="tabpanel" class="tab-pane mt-30" id="password">
                                    <?php echo $__env->make('frontend.user.account.tabs.change-password', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                                </div><!--tab panel change password-->
                            <?php endif; ?>

                        </div><!--tab content-->

                    </div><!--tab panel-->

                </div><!--panel body-->

            </div><!-- panel -->

        </div><!-- col-xs-12 -->

    </div><!-- row -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>