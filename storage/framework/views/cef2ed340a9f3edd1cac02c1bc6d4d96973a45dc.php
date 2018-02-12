<?php $__env->startSection('title', app_name() . ' | Register'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo e(trans('labels.frontend.auth.register_box_title')); ?></div>

                <div class="panel-body">

                    <?php echo e(Form::open(['route' => 'frontend.auth.register.post', 'class' => 'form-horizontal'])); ?>


                    <div class="form-group">
                        <?php echo e(Form::label('first_name', trans('validation.attributes.frontend.first_name'),
                        ['class' => 'col-md-4 control-label'])); ?>

                        <div class="col-md-6">
                            <?php echo e(Form::text('first_name', null,
                            ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.first_name')])); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <?php echo e(Form::label('last_name', trans('validation.attributes.frontend.last_name'),
                        ['class' => 'col-md-4 control-label'])); ?>

                        <div class="col-md-6">
                            <?php echo e(Form::text('last_name', null,
                            ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.last_name')])); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <?php echo e(Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label'])); ?>

                        <div class="col-md-6">
                            <?php echo e(Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.email')])); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <?php echo e(Form::label('password', trans('validation.attributes.frontend.password'), ['class' => 'col-md-4 control-label'])); ?>

                        <div class="col-md-6">
                            <?php echo e(Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password')])); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <?php echo e(Form::label('password_confirmation', trans('validation.attributes.frontend.password_confirmation'), ['class' => 'col-md-4 control-label'])); ?>

                        <div class="col-md-6">
                            <?php echo e(Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password_confirmation')])); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <?php if(config('access.captcha.registration')): ?>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <?php echo Form::captcha(); ?>

                                <?php echo e(Form::hidden('captcha_status', 'true')); ?>

                            </div><!--col-md-6-->
                        </div><!--form-group-->
                    <?php endif; ?>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <?php echo e(Form::submit(trans('labels.frontend.auth.register_button'), ['class' => 'btn btn-primary'])); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <?php echo e(Form::close()); ?>


                </div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-8 -->

    </div><!-- row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php if(config('access.captcha.registration')): ?>
        <?php echo Captcha::script(); ?>

    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>