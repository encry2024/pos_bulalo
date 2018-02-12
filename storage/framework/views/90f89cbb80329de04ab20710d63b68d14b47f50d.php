<div class="pull-right mb-10 hidden-sm hidden-xs">
    <?php echo e(link_to_route('admin.access.role.index', trans('menus.backend.access.roles.all'), [], ['class' => 'btn btn-primary btn-xs'])); ?>

    <?php echo e(link_to_route('admin.access.role.create', trans('menus.backend.access.roles.create'), [], ['class' => 'btn btn-success btn-xs'])); ?>

</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <?php echo e(trans('menus.backend.access.roles.main')); ?> <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li><?php echo e(link_to_route('admin.access.role.index', trans('menus.backend.access.roles.all'))); ?></li>
            <li><?php echo e(link_to_route('admin.access.role.create', trans('menus.backend.access.roles.create'))); ?></li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>