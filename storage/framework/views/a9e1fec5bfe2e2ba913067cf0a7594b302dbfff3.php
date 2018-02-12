<?php echo e(Form::open(['route' => ['frontend.auth.password.change'], 'class' => 'form-horizontal', 'method' => 'patch'])); ?>


    <div class="form-group">
        <?php echo e(Form::label('old_password', trans('validation.attributes.frontend.old_password'), ['class' => 'col-md-4 control-label'])); ?>

        <div class="col-md-6">
            <?php echo e(Form::password('old_password', ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.old_password')])); ?>

        </div>
    </div>

    <div class="form-group">
        <?php echo e(Form::label('password', trans('validation.attributes.frontend.new_password'), ['class' => 'col-md-4 control-label'])); ?>

        <div class="col-md-6">
            <?php echo e(Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.new_password')])); ?>

        </div>
    </div>

    <div class="form-group">
        <?php echo e(Form::label('password_confirmation', trans('validation.attributes.frontend.new_password_confirmation'), ['class' => 'col-md-4 control-label'])); ?>

        <div class="col-md-6">
            <?php echo e(Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.new_password_confirmation')])); ?>

        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <?php echo e(Form::submit(trans('labels.general.buttons.update'), ['class' => 'btn btn-primary', 'id' => 'change-password'])); ?>

        </div>
    </div>

<?php echo e(Form::close()); ?>