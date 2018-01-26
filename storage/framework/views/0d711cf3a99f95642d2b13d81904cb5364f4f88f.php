<header class="main-header">

    <a href="<?php echo e(route('frontend.index')); ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
           <?php echo e(substr(app_name(), 0, 1)); ?>

        </span>

        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <?php echo e(app_name()); ?>

        </span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"><?php echo e(trans('labels.general.toggle_navigation')); ?></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown notifications-menu" onclick='notifications()'>
                    <?php
                        $notifications = \App\Models\Notification\Notification::orderBy('date', 'desc')->take(5)->get();
                    ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-default" id="notification_head">
                            <?php echo e(count($notifications->where('status', 'new'))); ?>

                        </span>
                    </a>

                    <ul class="dropdown-menu" id="notification_menu">
                        <?php if(count($notifications)): ?>
                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="header">
                                <?php if($notification->status == 'new'): ?>
                                <span class="label label-danger">New</span>
                                <?php else: ?>
                                <span class="label label-default">Read</span>
                                <?php endif; ?>
                                &nbsp;<?php echo e($notification->description); ?>

                                <small class="pull-right"><?php echo e($notification->date); ?></small>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <li class="header">
                            <?php echo e(trans_choice('strings.backend.general.you_have.notifications', 0)); ?>

                        </li>
                        <?php endif; ?>
                        <li class="footer">
                            <?php echo e(link_to(route("admin.notification.index"), trans('strings.backend.general.see_all.notifications'))); ?>

                        </li>
                    </ul>
                </li><!-- /.notifications-menu -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo e(access()->user()->picture); ?>" class="user-image" alt="User Avatar"/>
                        <span class="hidden-xs"><?php echo e(access()->user()->full_name); ?></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="<?php echo e(access()->user()->picture); ?>" class="img-circle" alt="User Avatar" />
                            <p>
                                <?php echo e(access()->user()->full_name); ?> - <?php echo e(implode(", ", access()->user()->roles->pluck('name')->toArray())); ?>

                                <small><?php echo e(trans('strings.backend.general.member_since')); ?> <?php echo e(access()->user()->created_at->format("m/d/Y")); ?></small>
                            </p>
                        </li>

                        <li class="user-body">
                        </li>

                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo route('frontend.index'); ?>" class="btn btn-default btn-flat">
                                    <i class="fa fa-home"></i>
                                    <?php echo e(trans('navs.general.home')); ?>

                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo route('frontend.auth.logout'); ?>" class="btn btn-danger btn-flat">
                                    <i class="fa fa-sign-out"></i>
                                    <?php echo e(trans('navs.general.logout')); ?>

                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-custom-menu -->
    </nav>
</header>
