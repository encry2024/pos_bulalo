<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo $__env->yieldContent('title', app_name()); ?></title>

        <!-- Meta -->
        <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Laravel 5 Boilerplate'); ?>">
        <meta name="author" content="<?php echo $__env->yieldContent('meta_author', 'Anthony Rappa'); ?>">
        <?php echo $__env->yieldContent('meta'); ?>

        <!-- Styles -->
        <?php echo $__env->yieldContent('before-styles'); ?>

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        <?php if (session()->has('lang-rtl')): ?>
            <?php echo e(Html::style(getRtlCss(mix('css/frontend.css')))); ?>

        <?php else: ?>
            <?php echo e(Html::style(mix('css/frontend.css'))); ?>

        <?php endif; ?>

        <?php echo $__env->yieldContent('after-styles'); ?>

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body id="app-layout">
        <div id="app">
            <?php echo $__env->make('includes.partials.logged-in-as', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make('frontend.includes.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <div class="container">
                <?php echo $__env->make('includes.partials.messages', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div><!-- container -->
        </div><!--#app-->


        <div id="responseModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Response</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Status:</label>
                                <input type="text" class="form-control" name="status" id="status" readonly>
                            </div>
                            <div class="form-group">
                                <label for="request_ingredient">Message:</label>
                                <textarea class="form-control" name="response_msg" id="response_msg" rows='6' readonly></textarea>
                            </div>       
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Scripts -->
        <?php echo $__env->yieldContent('before-scripts'); ?>
        <?php echo Html::script(mix('js/frontend.js')); ?>

        <?php echo $__env->yieldContent('after-scripts'); ?>

        <?php echo $__env->make('includes.partials.ga', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </body>
</html>