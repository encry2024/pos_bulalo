<div class="pull-right mb-10 hidden-sm hidden-xs">
    <?php echo e(link_to_route('admin.category.index', 'All Category', [], ['class' => 'btn btn-primary btn-xs'])); ?>

    <?php echo e(link_to_route('admin.category.create', 'Add Category', [], ['class' => 'btn btn-success btn-xs'])); ?>

</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Category <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li><?php echo e(link_to_route('admin.category.index', 'All Category')); ?></li>
            <li><?php echo e(link_to_route('admin.category.create', 'Add Category')); ?></li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>