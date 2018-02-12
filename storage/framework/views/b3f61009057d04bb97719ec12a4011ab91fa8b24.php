<?php $__env->startSection('title', 'Commissary Product Management | Add Product'); ?>

<?php $__env->startSection('after-styles'); ?>
    <?php echo e(Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css')); ?>

    <?php echo e(Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <h1>
        Commissary Product Management <small>Add Product</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e(Form::open(['route' => 'admin.commissary.product.store', 'class' => 'form-horizontal', 'Product' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Add Product</h3>

                <div class="box-tools pull-right">
                    <?php echo $__env->make('backend.commissary.product.includes.partials.product-header-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    <?php echo e(Form::label('name', 'Product Name', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-5">
                        <?php echo e(Form::text('name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Product Name'])); ?>

                    </div><!--col-lg-10-->

                </div><!--form control-->

                <div class="form-group">
                    <?php echo e(Form::label('category', 'Product Category', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-5">
                        <?php echo e(Form::select('category', $categories ,old('category'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required'])); ?>

                    </div><!--col-lg-10-->

                </div>

                <div class="form-group">
                    <?php echo e(Form::label('list', 'Ingredients', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-3">
                        <?php echo e(Form::select('list', $selections ,old('list'), ['class' => 'form-control', 'id' => 'list'])); ?>

                    </div><!--col-lg-10-->
                </div>

                <div class="form-group">
                    <?php echo e(Form::label('unit_type', 'Unit Type', ['class' => 'col-lg-2 control-label'])); ?>


                    <div class="col-lg-3">
                        <?php echo e(Form::select('unit_type', [],old('unit_type'), ['class' => 'form-control', 'id' => 'unit_type'])); ?>

                    </div>

                    <?php echo e(Form::label('quantity', 'Quantity', ['class' => 'col-lg-1 control-label', 'id' => 'lbl_quantity'])); ?>


                    <div class="col-lg-1">
                        <?php echo e(Form::text('quantity', old('quantity', 0), ['class' => 'form-control', 'id' => 'quantity'])); ?>

                    </div><!--col-lg-10-->

                    <div class="col-lg-2">
                        <button type="button" class="btn btn-primary" onclick="addIngredient()">ADD</button>
                    </div>

                    <?php echo e(Form::hidden('ingredients', '', ['id' => 'ingredients'])); ?>

                </div>

                <div class="form-group">
                    <div class="col-lg-6 col-lg-offset-1">
                        <table class="table table-bordered">
                            <thead>
                                <th>&nbsp;</th>
                                <th class="col-md-5">Name</th>
                                <th>Qty</th>
                                <th>Unit Type</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    <?php echo e(link_to_route('admin.product.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs'])); ?>

                </div><!--pull-left-->

                <div class="pull-right">
                    <?php echo e(Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs'])); ?>

                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    <?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('after-scripts'); ?>
    <?php echo e(Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js')); ?>

    <?php echo e(Html::script('js/timepicker.js')); ?>

    <?php echo e(Html::script('js/backend/access/users/script.js')); ?>


    <script type="text/javascript">
        var ingredients = <?php echo $ingredients; ?>;

        function get_unit(val) {
            var options = '';

            if(val == 'Mass')
            {
                options += '<option value="g">Gram</option>';
                options += '<option value="kg">Kilogram</option>';
                options += '<option value="ton">Ton</option>';
                options += '<option value="lbs">Pound</option>';
                options += '<option value="oz">Ounce</option>';
            }
            else if(val == 'Volume')
            {
                options += '<option value="ml">Milliliter</option>';
                options += '<option value="cl">Centiliter</option>';
                options += '<option value="dl">Deciliter</option>';
                options += '<option value="l">Liter</option>';
                options += '<option value="cup">Cup</option>';
                options += '<option value="tsp">Tea Spoon</option>';
                options += '<option value="tbsp">Table Spoon</option>';
                options += '<option value="gal">Gallon</option>';
            }
            else
            {
                options += '<option value="pc">Piece</option>';
                options += '<option value="bottle">Bottle</option>';
                options += '<option value="can">Can</option>';
            }

            return options;
        }

        function get_physical_quantity(id) {
            $.ajax({
                type: 'GET',
                url: '<?php echo e(URL::to("admin/commissary/product/inventory")); ?>/' + id,
                success: function(data){
                    $('#unit_type').find('option').remove();

                    $('#unit_type').append(get_unit(data));

                    $('#lbl_quantity').text($($('#unit_type').find('option')[0]).text());
                }
            });
        }

        $('#list').on('change', function() {

            get_physical_quantity($(this).val());

        });

        $(document).ready(function() {
            <?php if(count($ingredients)): ?>
            get_physical_quantity('<?php echo e($ingredients->first()->id); ?>');
            <?php endif; ?>
        }); 

        $('form').submit(function(e) {
            var ing  ='';
            var rows = $('tbody').find('tr');
            var obj  = [];

            for(var i = 0; i < rows.length; i++)
            {
                var cols = $(rows[i]).find('td');
                var id   = $(cols[0]).text();
                var name = $(cols[1]).text();
                var qty  = $(cols[2]).text();
                var unit = $(cols[3]).text();

                obj.push({id: id, name: name, quantity: qty, unit_type: unit});
            }

            $('#ingredients').val(JSON.stringify(obj));

            ing = $('#ingredients').val();

            if(ing == '[]')
                e.preventDefault();
        });

        $('#unit_type').on('change', function() {
            $('#lbl_quantity').text($(this).find('option:selected').text());      
        });

        function addIngredient() {
            var selected    = $('#list').val();
            var ing         = findIngredients(selected);
            var name        = ing[0]['supplier'] == 'Other' ? ing[0]['other_inventory']['name'] : ing[0]['drygood_inventory']['name'];
            var id          = ing[0]['id'];
            var qty         = $('#quantity').val();
            var unit        = $('#unit_type').val();
            var row         = '<tr id="' + id +'"><td>' + id + '</td><td>' + name +'</td><td>' + qty +'</td><td>' + unit + '</td></tr>';
            
            if(!exist(id))
                $('table tbody').append(row);
        }

        function exist(id) {
            return $('tr#'+id).length;
        }

        function findIngredients(id) {
            return $.grep(ingredients, function(n, i){
              return n.id == id;
            });
        };
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>