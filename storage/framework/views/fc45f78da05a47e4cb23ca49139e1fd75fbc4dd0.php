<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#frontend-navbar-collapse">
                <span class="sr-only"><?php echo e(trans('labels.general.toggle_navigation')); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <?php echo e(link_to_route('frontend.index', app_name(), [], ['class' => 'navbar-brand'])); ?>

        </div><!--navbar-header-->

        <div class="collapse navbar-collapse" id="frontend-navbar-collapse">

            <ul class="nav navbar-nav navbar-right">

                <?php if($logged_in_user): ?>
                    <li><?php echo e(link_to_route('frontend.user.dashboard', trans('navs.frontend.dashboard'), [], ['class' => active_class(Active::checkRoute('frontend.user.dashboard')) ])); ?></li>

                <?php endif; ?>

                <?php if(! $logged_in_user): ?>
                    <li><?php echo e(link_to_route('frontend.auth.login', trans('navs.frontend.login'), [], ['class' => active_class(Active::checkRoute('frontend.auth.login')) ])); ?></li>

                    <?php if(config('access.users.registration')): ?>
                        <li><?php echo e(link_to_route('frontend.auth.register', trans('navs.frontend.register'), [], ['class' => active_class(Active::checkRoute('frontend.auth.register')) ])); ?></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                           Sales <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo e(route('frontend.sale.daily')); ?>">Daily</li>
                            <li><a href="<?php echo e(route('frontend.sale.monthly')); ?>">Monthly</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                           Request <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo e(route('frontend.user.request.create')); ?>">Create Request</a></li>
                            <li><a href="<?php echo e(route('frontend.user.request.index')); ?>">View Requests</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <?php echo e($logged_in_user->name); ?> <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <?php if (access()->allow('view-backend')): ?>
                                <li><?php echo e(link_to_route('admin.dashboard', trans('navs.frontend.user.administration'))); ?></li>
                            <?php endif; ?>
                            <li><?php echo e(link_to_route('frontend.user.account', trans('navs.frontend.user.account'), [], ['class' => active_class(Active::checkRoute('frontend.user.account')) ])); ?></li>
                            <li><?php echo e(link_to_route('frontend.auth.logout', trans('navs.general.logout'))); ?></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- <li><?php echo e(link_to_route('frontend.contact', trans('navs.frontend.contact'), [], ['class' => active_class(Active::checkRoute('frontend.contact')) ])); ?></li> -->
            </ul>
        </div><!--navbar-collapse-->
    </div><!--container-->
</nav>