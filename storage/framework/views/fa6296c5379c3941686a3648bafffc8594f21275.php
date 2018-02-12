<?php $__env->startSection('after-styles'); ?>

<?php echo e(Html::style('css/dashboard.css')); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-xs-12">

          
          <div class="panel panel-default">
              <div class="panel-heading">REQUEST LIST</div>

              <div class="panel-body">
                  <table class="table table-bordered">
                    <thead>
                      <th>TITLE</th>
                      <th>MESSAGE</th>
                      <th>DATE</th>
                      <th>TIME</th>
                      <th>&nbsp;</th>
                    </thead>
                    <tbody>
                      <?php if(count($requests)): ?>
                        <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td><?php echo e($request->title); ?></td>
                          <td><?php echo e($request->message); ?></td>
                          <td><i class="fa fa-calendar"></i> <?php echo e($request->created_at->format('F d, Y')); ?></td>
                          <td><i class="fa fa-clock-o"></i> <?php echo e($request->created_at->format('h:i:s A')); ?></td>
                          <td>
                            <?php if(count($request->response)): ?>
                            <a href="<?php echo e(route('frontend.user.request.show', $request)); ?>" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i> View</a>
                            <?php else: ?>
                            <label class="label label-default">Pending</label>                         
                            <?php endif; ?>
                          </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php else: ?>
                      <tr>
                        <td colspan="4">No records in list</td>
                      </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
              </div>
          </div>


        </div><!-- col-md-10 -->

    </div>
    <!-- end modal -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>