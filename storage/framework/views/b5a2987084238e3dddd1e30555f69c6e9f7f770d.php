<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo e(access()->user()->picture); ?>" class="img-circle" alt="User Image" />
            </div><!--pull-left-->
            <div class="pull-left info">
                <p><?php echo e(access()->user()->full_name); ?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> <?php echo e(trans('strings.backend.general.status.online')); ?></a>
            </div><!--pull-left-->
        </div><!--user-panel-->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header"><?php echo e(trans('menus.backend.sidebar.general')); ?></li>

            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/dashboard'))); ?>">
                <a href="<?php echo e(route('admin.dashboard')); ?>">
                    <i class="fa fa-dashboard"></i>
                    <span><?php echo e(trans('menus.backend.sidebar.dashboard')); ?></span>
                </a>
            </li>

            <?php if (access()->hasRoles([1])): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/category'))); ?>">
                <a href="<?php echo e(route('admin.category.index')); ?>">
                    <i class="fa fa-bars"></i>
                    <span>Category</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (access()->hasRoles([1,2])): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/pos/*'))); ?> treeview">
                <a href="#">
                    <i class="fa fa-industry"></i>
                    <span>POS</span>
                </a>

                <ul class="treeview-menu <?php echo e(active_class(Active::checkUriPattern('admin/pos/*'), 'menu-open')); ?>" style="display: none; <?php echo e(active_class(Active::checkUriPattern('admin/pos/*'), 'display: block;')); ?>">
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/pos/inventory'))); ?>">
                        <a href="<?php echo e(route('admin.inventory.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Inventory Management</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/pos/product'))); ?>">
                        <a href="<?php echo e(route('admin.product.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Product Management</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/pos/stock'))); ?>">
                        <a href="<?php echo e(route('admin.stock.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Stock In</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/pos/cost'))); ?>">
                        <a href="<?php echo e(route('admin.cost.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Cost</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if (access()->hasRoles([1,3])): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/pos/*'))); ?> treeview">
                <a href="#">
                    <i class="fa fa-building"></i>
                    <span>Commissary</span>
                </a>

                <ul class="treeview-menu <?php echo e(active_class(Active::checkUriPattern('admin/commissary/*'), 'menu-open')); ?>" style="display: none; <?php echo e(active_class(Active::checkUriPattern('admin/commissary/*'), 'display: block;')); ?>">
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commisary/inventory'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.inventory.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Inventory</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commisary/product'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.product.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Product</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commissary/produce'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.produce.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Produce Product</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commissary/stock'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.stock.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Stock In</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commissary/delivery'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.delivery.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Deliver item</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commissary/dispose'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.dispose.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Dispose Item</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commissary/goods_return'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.goods_return.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Goods Return</span>
                        </a>
                    </li>
                    <!-- <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commissary/invoice'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.invoice.index')); ?>">
                            <i class="fa fa-money"></i>
                            <span>Sales Invoice</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/commissary/order_form'))); ?>">
                        <a href="<?php echo e(route('admin.commissary.order_form.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Order Form</span>
                        </a>
                    </li> -->

                </ul>
            </li>
            <?php endif; ?>


            <?php if (access()->hasRoles([1,3])): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/pos/*'))); ?> treeview">
                <a href="#">
                    <i class="fa fa-building"></i>
                    <span>Dry Goods</span>
                </a>

                <ul class="treeview-menu <?php echo e(active_class(Active::checkUriPattern('admin/dry_good/*'), 'menu-open')); ?>" style="display: none; <?php echo e(active_class(Active::checkUriPattern('admin/dry_good/*'), 'display: block;')); ?>">
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/dry_good/inventory'))); ?>">
                        <a href="<?php echo e(route('admin.dry_good.inventory.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Inventory</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/dry_good/stock'))); ?>">
                        <a href="<?php echo e(route('admin.dry_good.stock.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Stock In</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/dry_good/delivery'))); ?>">
                        <a href="<?php echo e(route('admin.dry_good.delivery.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Deliver item</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/dry_good/dispose'))); ?>">
                        <a href="<?php echo e(route('admin.dry_good.dispose.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Dispose Item</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/dry_good/goods_return'))); ?>">
                        <a href="<?php echo e(route('admin.dry_good.goods_return.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Goods Return</span>
                        </a>
                    </li>
                    <!-- <li class="<?php echo e(active_class(Active::checkUriPattern('admin/dry_good/invoice'))); ?>">
                        <a href="<?php echo e(route('admin.dry_good.invoice.index')); ?>">
                            <i class="fa fa-money"></i>
                            <span>Sales Invoice</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/dry_good/order_form'))); ?>">
                        <a href="<?php echo e(route('admin.dry_good.order_form.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Order Form</span>
                        </a>
                    </li> -->

                </ul>
            </li>
            <?php endif; ?>
            

            <?php if (access()->hasRoles([1, 2])): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/pos/sale/*'))); ?> treeview">
                <a href="#">
                    <i class="fa fa-bar-chart"></i>
                    <span>Report POS</span>
                </a>
                <ul class="treeview-menu <?php echo e(active_class(Active::checkUriPattern('admin/report/pos/*'), 'menu-open')); ?>" style="display: none; <?php echo e(active_class(Active::checkUriPattern('admin/report/pos/*'), 'display: block;')); ?>">
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/pos/sale/*'))); ?>">
                        <a href="<?php echo e(route('admin.report.pos.monthly.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Monthly</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/pos/sale/*'))); ?>">
                        <a href="<?php echo e(route('admin.report.pos.sale.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Sale</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/pos/inventory/*'))); ?>">
                        <a href="<?php echo e(route('admin.report.pos.inventory.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Inventory</span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/pos/item_use/*'))); ?>">
                        <a href="<?php echo e(route('admin.report.pos.item_use.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Item Use</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if (access()->hasRoles([1,3])): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('/admin/report/commissary/*', 'menu-open'))); ?> treeview">
                <a href="#">
                    <i class="fa fa-bar-chart"></i>
                    <span>Report Commissary</span>
                </a>
                <ul class="treeview-menu <?php echo e(active_class(Active::checkUriPattern('admin/report/commissary/*'), 'menu-open')); ?>" style="display: none; <?php echo e(active_class(Active::checkUriPattern('admin/report/commissary/*'), 'display: block;')); ?>">

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/commissary/daily/inventory'))); ?>">
                        <a href="<?php echo e(route('admin.report.commissary.daily.inventory.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Inventory Report</span>
                        </a>
                    </li>

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/commissary/daily/delivery'))); ?>">
                        <a href="<?php echo e(route('admin.report.commissary.daily.delivery.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Delivery Report</span>
                        </a>
                    </li>

                    <!-- <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/commissary/daily/sale'))); ?>">
                        <a href="<?php echo e(route('admin.report.commissary.daily.sale.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Sales Report</span>
                        </a>
                    </li> -->

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/commissary/disposal'))); ?>">
                        <a href="<?php echo e(route('admin.report.commissary.disposal.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Disposal Report</span>
                        </a>
                    </li>

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/commissary/goods_return'))); ?>">
                        <a href="<?php echo e(route('admin.report.commissary.goods_return.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Goods Return Report</span>
                        </a>
                    </li>

                    <!-- <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/commissary/summary'))); ?>">
                        <a href="<?php echo e(route('admin.report.commissary.summary.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Summary Report</span>
                        </a>
                    </li> -->
                </ul>
            </li>
            <?php endif; ?>

            <?php if (access()->hasRoles([1,4])): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('/admin/report/dry_good/*', 'menu-open'))); ?> treeview">
                <a href="#">
                    <i class="fa fa-bar-chart"></i>
                    <span>Report DryGood</span>
                </a>
                <ul class="treeview-menu <?php echo e(active_class(Active::checkUriPattern('admin/report/dry_good/*'), 'menu-open')); ?>" style="display: none; <?php echo e(active_class(Active::checkUriPattern('admin/report/dry_good/*'), 'display: block;')); ?>">

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/dry_good/daily/inventory'))); ?>">
                        <a href="<?php echo e(route('admin.report.dry_good.daily.inventory.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Inventory Report</span>
                        </a>
                    </li>

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/dry_good/daily/delivery'))); ?>">
                        <a href="<?php echo e(route('admin.report.dry_good.daily.delivery.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Delivery Report</span>
                        </a>
                    </li>

                    <!-- <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/dry_good/daily/sale'))); ?>">
                        <a href="<?php echo e(route('admin.report.dry_good.daily.sale.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Sales Report</span>
                        </a>
                    </li> -->

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/dry_good/disposal'))); ?>">
                        <a href="<?php echo e(route('admin.report.dry_good.disposal.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Disposal Report</span>
                        </a>
                    </li>

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/dry_good/goods_return'))); ?>">
                        <a href="<?php echo e(route('admin.report.dry_good.goods_return.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Goods Return Report</span>
                        </a>
                    </li>

                    <!-- <li class="<?php echo e(active_class(Active::checkUriPattern('admin/report/dry_good/summary'))); ?>">
                        <a href="<?php echo e(route('admin.report.dry_good.summary.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Summary Report</span>
                        </a>
                    </li> -->
                </ul>
            </li>
            <?php endif; ?>


            <?php if (access()->hasRole(1)): ?>
            <li class="header"><?php echo e(trans('menus.backend.sidebar.system')); ?></li>
            
            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/access/*'))); ?> treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span><?php echo e(trans('menus.backend.access.title')); ?></span>

                    <?php if($pending_approval > 0): ?>
                        <span class="label label-danger pull-right"><?php echo e($pending_approval); ?></span>
                    <?php else: ?>
                        <i class="fa fa-angle-left pull-right"></i>
                    <?php endif; ?>
                </a>

                <ul class="treeview-menu <?php echo e(active_class(Active::checkUriPattern('admin/access/*'), 'menu-open')); ?>" style="display: none; <?php echo e(active_class(Active::checkUriPattern('admin/access/*'), 'display: block;')); ?>">
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/access/user*'))); ?>">
                        <a href="<?php echo e(route('admin.access.user.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span><?php echo e(trans('labels.backend.access.users.management')); ?></span>

                            <?php if($pending_approval > 0): ?>
                                <span class="label label-danger pull-right"><?php echo e($pending_approval); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/access/role*'))); ?>">
                        <a href="<?php echo e(route('admin.access.role.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span><?php echo e(trans('labels.backend.access.roles.management')); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/setting/*'))); ?>">
                        <a href="<?php echo e(route('admin.setting.index')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span>Setting</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (access()->hasRole(1)): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/audit_trail/*'))); ?>">
                <a href="<?php echo e(route('admin.audit_trail.index')); ?>">
                    <i class="fa fa-circle-o"></i>
                    <span>User Logs</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (access()->hasRole(1)): ?>
            <li class="<?php echo e(active_class(Active::checkUriPattern('admin/log-viewer*'))); ?> treeview">
                <a href="#">
                    <i class="fa fa-list"></i>
                    <span><?php echo e(trans('menus.backend.log-viewer.main')); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu <?php echo e(active_class(Active::checkUriPattern('admin/log-viewer*'), 'menu-open')); ?>" style="display: none; <?php echo e(active_class(Active::checkUriPattern('admin/log-viewer*'), 'display: block;')); ?>">
                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/log-viewer'))); ?>">
                        <a href="<?php echo e(route('log-viewer::dashboard')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span><?php echo e(trans('menus.backend.log-viewer.dashboard')); ?></span>
                        </a>
                    </li>

                    

                    <li class="<?php echo e(active_class(Active::checkUriPattern('admin/log-viewer/logs'))); ?>">
                        <a href="<?php echo e(route('log-viewer::logs.list')); ?>">
                            <i class="fa fa-circle-o"></i>
                            <span><?php echo e(trans('menus.backend.log-viewer.logs')); ?></span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
        </ul><!-- /.sidebar-menu -->
    </section><!-- /.sidebar -->
</aside>