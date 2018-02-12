<?php $__env->startSection('title', app_name() . ' | Login'); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo e(trans('labels.frontend.auth.login_box_title')); ?></div>

                <div class="panel-body">

                    <?php echo e(Form::open(['route' => 'frontend.auth.login.post', 'class' => 'form-horizontal'])); ?>


                    <div class="form-group">
                        <?php echo e(Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label'])); ?>

                        <div class="col-md-6">
                            <?php echo e(Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.email')])); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <?php echo e(Form::label('password', trans('validation.attributes.frontend.password'), ['class' => 'col-md-4 control-label'])); ?>

                        <div class="col-md-6">
                            <?php echo e(Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password')])); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <?php echo e(Form::checkbox('remember')); ?> <?php echo e(trans('labels.frontend.auth.remember_me')); ?>

                                </label>
                            </div>
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <?php echo e(Form::submit(trans('labels.frontend.auth.login_button'), ['class' => 'btn btn-primary', 'style' => 'margin-right:15px'])); ?>


                            <?php echo e(link_to_route('frontend.auth.password.reset', trans('labels.frontend.passwords.forgot_password'))); ?>

                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <?php echo e(Form::close()); ?>


                    <div class="row text-center">
                        <?php echo $socialite_links; ?>

                    </div>
                </div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-8 -->

    </div><!-- row -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>