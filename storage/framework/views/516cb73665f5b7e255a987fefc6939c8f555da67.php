<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo $__env->yieldContent('title', app_name()); ?></title>

        <!-- Styles -->
        <?php echo $__env->yieldContent('before-styles-end'); ?>

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        <?php if (session()->has('lang-rtl')): ?>
            <?php echo e(Html::style(getRtlCss(mix('css/backend.css')))); ?>

        <?php else: ?>
            <?php echo e(Html::style(mix('css/backend.css'))); ?>

        <?php endif; ?>

        <?php echo $__env->yieldContent('after-styles-end'); ?>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/css/bootstrap-datetimepicker.min.css">
        <?php echo $__env->make('log-viewer::_template.style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <?php echo e(Html::script('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')); ?>

            <?php echo e(Html::script('https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js')); ?>

        <![endif]-->

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body class="skin-<?php echo e(config('backend.theme')); ?> <?php echo e(config('backend.layout')); ?>">
        <?php echo $__env->make('includes.partials.logged-in-as', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div class="wrapper">
            <?php echo $__env->make('backend.includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make('backend.includes.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <?php echo $__env->yieldContent('page-header'); ?>

                    
                    <?php echo Breadcrumbs::renderIfExists(); ?>

                </section>

                <!-- Main content -->
                <section class="content">
                    <?php echo $__env->make('includes.partials.messages', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->yieldContent('content'); ?>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <?php echo $__env->make('backend.includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div><!-- ./wrapper -->

        <!-- JavaScripts -->
        <?php echo $__env->yieldContent('before-scripts-end'); ?>
        <?php echo e(Html::script(mix('js/backend.js'))); ?>

        <?php echo $__env->yieldContent('after-scripts-end'); ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js"></script>
        <script>
            Chart.defaults.global.responsive      = true;
            Chart.defaults.global.scaleFontFamily = "'Source Sans Pro'";
            Chart.defaults.global.animationEasing = "easeOutQuart";
        </script>
        <?php echo $__env->yieldContent('modals'); ?>
        <?php echo $__env->yieldContent('scripts'); ?>
    </body>
</html>
