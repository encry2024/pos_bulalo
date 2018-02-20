<script>
    /**
     * Created by christanjake2024 on 2/20/18.
     */
    var order           = [];
    var order_list      = [];
    var total_amt       = 0;
    var flag            = false;
    var transaction_no  = '';
    var global_table    = 0;
    var dine_in         = false;

    function getIngredients(id) {
        $.ajax({
            type: 'get',
            url : '{{ url("dashboard") }}/' + id + '/product',
            success: function(data){
                var hasNoStock  = 0;
                var product     = data['product'];
                var prod_sizes  = data['product_size'];
                var body        = $('#ingredient_list tbody');

                $(body).find('tr').remove();
                $('#cup_sizes').find('li').remove();

                for(var i = 0; i < prod_sizes.length; i++) {
                    var size        = prod_sizes[i]['size'];
                    var price       = prod_sizes[i]['price'];
                    var ingredients = prod_sizes[i]['ingredients'];
                    var size_list   =  '<li class="list-group-item" onclick="product_size(this)">';
                    size_list   += '<a href="#" data-size="' + size + '">' + size + '<span class="pull-right">';
                    size_list   += price;
                    size_list   += '</span></a></li>';

                    for(var j = 0; j < ingredients.length; j++) {
                        var name     = '';
                        var stock    = ingredients[j]['stock'];
                        var crit     = ingredients[j]['reorder_level'];
                        var quantity = ingredients[j]['quanity'];

                        if(ingredients[j]['supplier'] == 'Other')
                        {
                            name = ingredients[j]['other']['name'];
                        }
                        else if(ingredients[j]['supplier'] == 'Commissary Product')
                        {
                            name = ingredients[j]['commissary_product']['name'];
                        }
                        else if(ingredients[j]['supplier'] == 'DryGoods Material')
                        {
                            name = ingredients[j]['dry_good_inventory']['name'];
                        }
                        else
                        {
                            if(ingredients[j]['commissary_inventory']['supplier'] == 'Other')
                                name = ingredients[j]['commissary_inventory']['other_inventory']['name'];
                            else
                                name = ingredients[j]['commissary_inventory']['drygood_inventory']['name'];
                        }

                        var row      =  '<tr' + (stock == 0 || crit > stock ? ' style="background:#b10303;color:white"': '') + ' data-tr-id="' + name + '">';
                        row      += '<td>' + name + '</td>';
                        row      += '<td>' + stock + '</td>';
                        row      += '</tr>';

                        if(stock == 0)
                            hasNoStock++;

                        var exist = $(body).find($('tr').data('tr-id'));

                        //check for same ingredient name
                        if(exist.length == 0)
                            $(body).append(row);
                    }

                    $('#cup_sizes').append(size_list);
                    product_size($('#cup_sizes').find('li')[0]);
                }

                if(hasNoStock){
                    $('#btn_addOrder').attr('disabled', 'disabled');
                } else {
                    $('#btn_addOrder').removeAttr('disabled');
                }

                $('#productModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            error: function(data){
                // console.log('error');
            }
        });
    }

    function product_click(e) {
        var id   = $(e).attr('id');
        var code = $(e).attr('data-code');
        var name = $(e).find('.product-title').text();

        order[0] = id;
        order[1] = code;

        $('#ingredient-title').html(code);
        getIngredients(id);
    }

    function product_size(e) {
        $('li.list-group-item').removeClass('product-active');
        $(e).addClass('product-active');
    }

    $('a').on('dragstart', function(){
        return false;
    });

    $('#btn_addOrder').on('click', function(){
        var product = {};
        var html    = '';
        var code_sz = '';
        var qty     = '';

        order[2] = $('.product-active').find('span').text();
        order[3] = $('#qty').val();
        order[4] = $('.product-active').find('a').attr('data-size');

        product['id']   = order[0];
        product['code'] = order[1];
        product['price']= order[2];
        product['qty']  = order[3];
        product['size'] = order[4];

        if(searchItem(product.id, product.size)) {
            var row = $('#order_list').find('tr[data-id="' + product.id + '"], tr[data-size="'+ product.size +'"]');
            var cols = $(row).find('td');
            var temp = parseInt($($(cols)[1]).text()) + parseInt(product.qty);

            $($(cols)[1]).text(temp);
            $($(cols)[2]).text(temp * product.price);

            updateItem(product.id, product.size, temp);
        } else {
            //
            //append product to orderlist
            //
            order_list.push(product);

            //
            // check product size and increase price
            //
            if(product.size == 'Large' || product.size == 'Medium') {
                product.price   = (parseFloat(product.price)).toFixed(2);
                code_sz         = product.code  + ' ' + product.size;
                qty             = product.qty;

                product.price   = parseFloat(product.qty * product.price).toFixed(2);
            } else  {
                code_sz         = product.code;
                qty             = product.qty;
                product.price   = (product.qty * product.price).toFixed(2);
            }
            //
            // add table row
            //
            html  = '<tr data-id="' + product.id + '" data-size="' + product.size + '" onclick="toggleActive(this)">';
            html  = html + '<td>' + code_sz + '</td><td>' + qty + '</td><td>' + product.price + '</td></tr>';
        }

        $('#total_amount').text(recompute());
        $('#qty').val(1);
        $('#order_list tbody').append(html);
        $('#productModal').modal('hide');
    });

    function searchItem(id, size)
    {
        index = order_list.findIndex(x => x.id == id && x.size == size);
        return index >= 0 ? true: false;
    }

    function updateItem(id, size, quantity) {
        index = order_list.findIndex(x => x.id == id && x.size == size);
        if(index != -1)
        {
            order_list[index].qty = quantity;
            order_list[index].price = quantity * order_list[index].price;
        }
    }

    function removeItem(id, size){
        index = order_list.findIndex(x => x.id == id && x.size == size);
        if(index != -1)
        {
            order_list.splice(index, 1);
            $('#total_amount').text(recompute());
            $('tr.selected').remove();
        }
    }

    function toggleActive(e){
        var has = $(e).hasClass('selected');
        if(has){
            $(e).removeClass('selected');
        } else {
            $(e).addClass('selected');
        }
    }

    $('#btn-remove').on('click', function() {
        if($('#order_list tbody tr.selected').length > 0) {
            swal({
                title: "Are you sure?",
                text: "You want to remove item from order list?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Remove Item",
                closeOnConfirm: false
            }).then(function() {
                    var rows = $('tr.selected');
                    for(var i = 0; i < rows.length; i++)
                    {
                        var id   = parseInt($(rows[i]).attr('data-id'));
                        var size = $(rows[i]).attr('data-size');
                        var fix  = $(rows[i]).attr('data-fixed');
                        /* if not fixed remove */
                        if(!fix) {
                            console.log("Item can't be remove!");
                            removeItem(id, size);
                            swal("Removed!", "Item has been removed!", "success");
                        } else {
                            swal("Removed!", "Item can't be remove!", "warning");
                        }
                    }
                }
            );
        }
    });

    $('#btn-clear').on('click', function(){
        if($('#order_list tbody tr').length > 0)
        {
            swal(
                {
                    title: "Are you sure?",
                    text: "You want to remove all item from order list?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Remove All",
                    closeOnConfirm: false
                }).then(function() {
                    clearAll();
                    swal("Removed!", "All item has been removed!", "success");
                }
            );
        }
    });

    $('#btn-save').on('click', function()
    {
        if($('#order_list tbody tr').length > 0){
            get_available_table();
            if(global_table != 0)
            {
                $('#btn_submit').trigger('click');
                transaction_no = '';
                global_table = 0;
            }

            $('#saveModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        }
    });

    $('#btn_submit').on('click', function(){
        var cash        = parseFloat($('#cash').val());
        var change      = parseFloat($('#change').val());
        var payable     = parseFloat($('#payable').val());
        var discount    = parseFloat($('#discount').val());
        var vat         = parseFloat($('#vat').val());
        var charge      = 0;
        var amount_due  = parseFloat($('#total_amount_due').val());
        var order_type  = $('#order_type').val();
        var table_no    = order_type == 'Take Out' ? 0 : $('#table option:selected').text();

        if((cash >= amount_due) && (order_type == 'Take Out'))
        {
            $('#btn_submit').attr('readonly','');

            $.ajax({
                url: '{{  url("sale/save") }}',
                type: 'POST',
                data: {
                    _token          : '{{ csrf_token() }}',
                    orders          : JSON.stringify(order_list),
                    cash            : cash,
                    change          : change,
                    payable         : payable,
                    discount        : discount,
                    vat             : vat,
                    charge          : charge,
                    amount_due      : amount_due,
                    table           : table_no,
                    order_type      : order_type
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if(data.status == 'success')
                    {
                        $('#printModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        $('#printModal').on('shown.bs.modal', function() {
                            $('#saveModal').modal('hide');

                            print_receipt(data);
                            window.print($('#official_receipt'));
                        });
                    }
                    else
                    {
                        swal('Order not save!','', 'error');
                        $('#btn_print').css('visibility', 'hidden');
                    }

                    $('#btn_submit').removeAttr('readonly');
                },
                error: function(data){
                    swal("Error Saving!", '', 'error');
                }
            });
        } else if(order_type == 'Dine-in') {
            $('#btn_submit').attr('readonly','');
            if(global_table != 0)
            {
                table_no   = global_table;
                order_type = dine_in ? 'Dine-in' : order_type;
            }

            $.ajax({
                url: '{{  url("sale/save") }}',
                type: 'POST',
                data: {
                    orders      : JSON.stringify(order_list),
                    cash        : cash,
                    change      : change,
                    payable     : payable,
                    discount    : discount,
                    vat         : vat,
                    charge      : charge,
                    amount_due  : amount_due,
                    table       : table_no,
                    order_type  : order_type,
                    transaction_no: transaction_no
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if(data.status == 'success')
                    {
                        flag = true;
                        $('#saveModal').modal('hide');
                        swal("Order has been saved!", '', 'success');
                    }
                    else
                    {
                        swal('Order not save!','', 'error');
                        $('#btn_print').css('visibility', 'hidden');
                    }

                    $('#btn_submit').removeAttr('readonly');
                },
                error: function(data){
                    swal("Error Saving!", '', 'error');
                }
            });
        }
        else
        {
            $('#notify').text('Input Cash Amount.')
        }//end statement
    });

    $('#saveModal').on('hidden.bs.modal', function() {
        if(flag) {
            clearAll();
        }

        $('#order_type').val('Dine-in');
        $('#panel_payable').hide();
        $('#panel_cash').hide();
        $('#panel_change').hide();
        $('#panel_discount').hide();
        $('#panel_discount_type').hide();
        $('#panel_vat').hide();
        // $('#panel_service_charge').hide();
        $('#panel_total_amount').hide();
        $('#panel_table').show();
        $('#btn_submit').css('visibility', 'visible');
        $('#btn_submit').text('Submit');

        $('#payable').val('0.00');
        $('#cash').val('0.00');
        $('#change').val('0.00');
        $('#vat').val('0.00');
        $('#discount').val('0.00');
        // $('#service_charge').val('0.00');
        $('#total_amount_due').val('0.00');
        // $('#official_receipt').hide();
        // $('#payment').show();
    });

    $('#printModal').on('hidden.bs.modal', function() {
        swal('Order has been saved!','', 'success');
    });

    $('#chargeModal').on('hidden.bs.modal', function() {
        order_list.splice(0, order_list.length);
    });

    function recompute(){
        total_amt = 0;
        for(var i = 0; i < order_list.length; i++)
        {
            total_amt += parseFloat(order_list[i].price);
        }
        return total_amt.toFixed(2);
    }

    function clearAll(){
        var count = 0;
        flag = false;

        if(transaction_no.length == 0)
        {
            $('#total_amount').text('0.00');
            total_amt =  0;
        }

        $('#customer_table').val('1');
        var rows = $('#order_list tbody').find('tr');

        for(var i = 0; i < rows.length; i++) {
            var id   = $(rows[i]).attr('data-id');
            var fix  = $(rows[i]).attr('data-fixed');
            var size = $(rows[i]).attr('data-size');
            removeItem(id, size);
            $(rows[i]).remove();
            count++;
        }
        /* set transaction no value */
        if(count == 0)
        {
            transaction_no = '';
            global_table   = 0;
            dine_in        = false;
        }
    }

    function change() {
        var type = $('#discount_type option:selected').text();
        var val  = $('#discount_type').val();

        get_amount_due(recompute(), val, type, 0, table_charge);
        var total  = parseFloat($('#total_amount_due').val());
        var change = parseFloat($('#cash').val()) - total;

        if(change < 0 || change == undefined || isNaN(change)){
            change = 0;
        }

        $('#change').val(change.toFixed(2));
    }

    function charge_change() {
        var charge = $('#chargeSaveModal');
        var type = $(charge).find('#discount_type option:selected').text();
        var val  = $(charge).find('#discount_type').val();
        var table_charge = $('#chargeModal').find('#table_rent_price_charge_modal').text();

        charge_get_amount_due($(charge).find('#payable').val(), val, type, 0, table_charge);
        var total  = parseFloat($(charge).find('#total_amount_due').val());
        var change = parseFloat($(charge).find('#cash').val()) - total;

        if(change < 0 || change == undefined || isNaN(change)){
            change = 0;
        }

        $(charge).find('#change').val(change.toFixed(2));
    }

    function isFocus() {
        var cash = parseInt($('#cash').val());
        var val  = 0;

        if(cash > 0){
            val = cash;
        }

        $('#cash').val(val.toFixed(2));
    }

    $('#discount_type').on('change', function(){
        var type = $('#discount_type option:selected').text();
        var val  = $('#discount_type').val();
        get_amount_due(recompute(), val, type, 0);
        change();
    });

    function charge_discount_change(e) {
        if($(e).text() != 'None') {
            var charge      = $('#chargeSaveModal');
            var percent     = $(e).val();
            var payable     = $(charge).find('#payable').val();
            var total       = payable * (percent / 100);

            $(charge).find('#discount').val(total.toFixed(2));

            charge_change();
        }
        else
        {
            $(charge).find('#discount').val('0.00');
        }
    }

    $('#order_type').on('change', function() {
        if(this.value == 'Dine-in')
        {
            $('#panel_discount_type').hide();
            $('#panel_payable').hide();
            $('#panel_cash').hide();
            $('#panel_change').hide();
            $('#panel_discount').hide();
            $('#panel_vat').hide();
            // $('#panel_service_charge').hide();
            $('#panel_total_amount').hide();
            $('#panel_table').show();
            $('#btn_submit').text('Submit');
        } else {
            get_amount_due(recompute(), 0, 'None', 0);
            $('#panel_discount_type').show();
            $('#panel_payable').show();
            $('#panel_cash').show();
            $('#panel_change').show();
            $('#panel_discount').show();
            $('#panel_vat').show();
            // $('#panel_service_charge').show();
            $('#panel_total_amount').show();
            $('#panel_table').hide();
            $('#btn_submit').text('Charge');
        }
    });

    $('#btn-tables').on('click', function() {
        $('#table_order_list tbody tr').remove();
        $('#table_order_list tbody').append('<tr><td>No records found.</td></tr>');
        $('#table_order_transact').text('');
        $('#table_order_total').text('0.00');
        $('#tablesModal').find('.modal-dialog').css('width','60%');

        $.ajax({
            type: 'GET',
            url: '{{ url("sale/unpaid") }}',
            success: function(data) {
                var options = '';
                total_amt = 0;
                $('#table_order_total').val('0.00');
                $('#table_list').find('option').remove();

                for(i = 0; i < data.length; i++) {
                    options += '<option>' + data[i].table_no + '</option>';
                }

                $('#table_list').append(options);

                if(data.length > 0) {
                    table_order($('#table_list').val());
                }
            }
        });

        $('#tablesModal').modal({
            backdrop: 'static',
            keyboard: false
        })
    });

    $('#btn_additional').on('click', function() {
        transaction_no  = $('#table_order_transact').text();
        global_table    = $('#table_list').val();
        dine_in         = true;

        $.ajax({
            type: 'GET',
            url : '{{ url("sale/get_order_list") }}/' + transaction_no,
            success: function(data) {

                $('#order_list tbody').find('tr').remove();
                order_list.splice(0, Object.keys(order_list).length);

                for(var i = 0; i < Object.keys(data).length; i++)
                {
                    var html = '';
                    var product = {};
                    var order   = data[i];

                    product['id']   = order.product.id;
                    product['code'] = order.product.code;
                    product['price']= order.product_size.price;
                    product['qty']  = order.quantity;
                    product['size'] = order.product_size.size;

                    //
                    //append product to orderlist
                    //
                    order_list.push(product);

                    //
                    // check product size and increase price
                    //
                    if(product.size == 'Large' || product.size == 'Medium') {
                        product.price   = (parseFloat(product.price)).toFixed(2);
                        code_sz         = product.code  + ' ' + product.size;
                        qty             = product.qty;
                        product.price   = parseFloat(product.qty * product.price).toFixed(2);
                    } else {
                        code_sz         = product.code;
                        qty             = product.qty;
                        product.price   = (product.qty * product.price).toFixed(2);
                    }

                    //
                    // add table row
                    //
                    html  = '<tr data-id="' + product.id + '" data-size="' + product.size + '" onclick="toggleActive(this)" data-fixed="1">';
                    html  += '<td>' + code_sz + '</td><td>' + qty + '</td><td>' + product.price + '</td></tr>';
                }

                $('#total_amount').text(recompute());
                $('#order_list tbody').append(html);
                $('#tablesModal').modal('hide');
            }
        });
    });

    $('#btn-charge').on('click', function() {
        var transact_no = $('#table_order_transact').text();
        order_list.splice(0, order_list.length);
        clearAll();
        var product = {};
        var order;

        if($('#table_list').find('option').length > 0)
        {
            $.ajax({
                type: 'GET',
                url : '{{ url("sale/get_order_list") }}/' + transact_no,
                success: function(data) {
                    var html = '';
                    var total = 0;
                    var table_price = '';
                    var creditable_amount = data[0].order.table.price;

                    $('#charge_table tbody').find('tr').remove();
                    $('#charge_transaction_no').text(data[0].order.transaction_no);

                    for(var i = 0; i < Object.keys(data).length; i++) {
                        order   = data[i];
                        total = total + (order.quantity * order.product_size.price);

                        product['id']       = order.id;
                        product['code']     = order.product.code;
                        product['price']    = order.product_size.price;
                        product['qty']      = order.quantity;
                        product['size']     = order.product_size.size;

                        //
                        //append product to orderlist
                        //
                        order_list.push(product);

                        //
                        // check product size and increase price
                        //
                        if(product.size == 'Large' || product.size == 'Medium') {
                            product.price   = (parseFloat(product.price)).toFixed(2);
                            code_sz         = product.code  + ' ' + product.size;
                            qty             = product.qty;
                            product.price   = parseFloat(product.qty * product.price).toFixed(2);
                        } else {
                            code_sz         = product.code;
                            qty             = product.qty;
                            product.price   = (product.qty * product.price).toFixed(2);
                        }

                        //
                        // add table row
                        //
                        html  = '<tr data-id="' + product.id + '" data-size="' + product.size + '" onclick="toggleActive(this)" data-fixed="1">';
                        html  = html + '<td>' + code_sz + '</td><td>' + qty + '</td><td>' + product.price + '</td></tr>';
                        $('#charge_table tbody').append(html);
                    }

                    if(data[0].order.table.price != null) {
                        table_price += '<tr><td>Rent Table</td><td>-</td>';
                        table_price += '<td>' + data[0].order.table.price + '</td>';
                        table_price += '</tr>';
                    }

                    charge_modal_price = parseFloat(data[0].order.table.price).toFixed(2) - 0;
                    total_charge_amount = creditable_amount - total;

                    $('#table_rent_price_charge_modal').text(charge_modal_price);
                    $('#table_total_rent_price_charge_modal').text(total_charge_amount);
                    $('#charge_total').text(parseFloat(total).toFixed(2));
                    $('#tablesModal').modal('hide');
                }
            });

            $('#chargeModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });

    $('#btn_charge_submit').on('click', function() {
        var modal      = $('#chargeSaveModal');
        var cash        = parseFloat($(modal).find('#cash').val());
        var change      = parseFloat($(modal).find('#change').val());
        var payable     = parseFloat($(modal).find('#payable').val());
        var discount    = parseFloat($(modal).find('#discount').val());
        var vat         = parseFloat($(modal).find('#vat').val());
        var amount_due  = parseFloat($(modal).find('#total_amount_due').val());
        // var charge      = parseFloat($(modal).find('#service_charge').val());
        var transact    = $(modal).find('.modal-title').text();

        if(cash >= (payable - discount))
        {
            $('#btn_charge_submit').attr('readonly','');

            $.ajax({
                url: '{{  url("sale/charge_save") }}',
                type: 'POST',
                data: {
                    _token:         '{{ csrf_token() }}',
                    transaction_no: transact,
                    cash        : cash,
                    change      : change,
                    payable     : payable,
                    discount    : discount,
                    vat         : vat,
                    //charge      : charge,
                    amount_due  : amount_due
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if(data.status == 'success')
                    {
                        $('#printModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        $('#printModal').on('shown.bs.modal', function() {
                            $('#saveModal').modal('hide');
                            $('#chargeSaveModal').modal('hide');

                            print_receipt(data);
                            window.print($('#official_receipt'));
                            clearAll();
                            global_table = 0;
                            transaction_no = '';
                        });
                    }
                    else
                    {
                        swal('Order not save!','', 'error');
                        $('#btn_print').css('visibility', 'hidden');
                    }

                    $('#btn_charge_submit').removeAttr('readonly');
                },
                error: function(data){
                    swal("Error Saving!", '', 'error');
                }
            });
        }
    });

    $('#table_list').on('change', function() {
        table_order($('#table_list').val());
    });

    function table_order(val)
    {
        $.ajax({
            type: 'GET',
            url: '{{ url("sale/order") }}/' + val,
            success: function(data) {
                data = JSON.parse(data);

                var creditable_amount = data.order.table.price;
                var rows = '';
                var _order  =  data.order;
                var _order_list = _order.order_list;
                var _order_total = 0;
                var total_creditable_amount = 0;

                $('#table_order_transact').text(_order.transaction_no);
                $('#table_order_total').text(_order.payable);
                $('#table_order_list tbody').find('tr').remove();

                for(var i = 0; i < _order_list.length; i++)
                {
                    rows += '<tr onclick="toggleActive(this)" id="' + _order_list[i].id + '">';
                    rows += '<td>' + _order_list[i].product.name + '</td>';
                    rows += '<td>' + _order_list[i].quantity + '/' + _order_list[i].product_size.size + '</td>';
                    rows += '<td>' + _order_list[i].product_size.price + '</td>';
                    rows += '</tr>';
                    _order_total = _order_total + (_order_list[i].quantity * _order_list[i].product_size.price);
                }

                /*if(data.order.table != null)
                 {
                 rows += '<tr><td>Rent Table</td><td>-</td>';
                 rows += '<td>' + data.order.table.price + '</td>';
                 rows += '</tr>';
                 _order_total -= parseFloat(data.order.table.price);
                 }*/

                total_creditable_amount = creditable_amount - _order_total
                credits = creditable_amount - 0;

                $('#table_order_list tbody').append(rows);

                $('#table_order_total').text(_order_total.toLocaleString(undefined, {minimumFractionDigits: 2}));
                $('#table_rent_price').text("PHP " + credits.toLocaleString(undefined, {minimumFractionDigits: 2}));
                $('#total_creditable_amount').text("PHP " + total_creditable_amount.toLocaleString(undefined, {minimumFractionDigits: 2}));
            }
        });
    }

    $('#btn_charge_payment').on('click', function() {
        var charge_total = parseFloat($('#charge_total').text()).toFixed(2);
        var transact     = $('#charge_transaction_no').text();

        $('#chargeSaveModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('#chargeSaveModal').on('shown.bs.modal', function() {
            $('#chargeModal').modal('hide');
            $(this).find('.modal-title').text(transact);
            $(this).find('#payable').val(charge_total);
            var val = $(this).find('#discount_type option:selected').val();
            var type = $(this).find('#discount_type option:selected').text();
            var table_charge = $('#chargeModal').find('#table_rent_price_charge_modal').text();

            console.log(table_charge)

            charge_get_amount_due(charge_total, val, type, 0, table_charge);
        });
    });

    $('#btn_cancel_order').on('click', function() {
        var selected_transaction = $('#table_order_transact').text();
        var rows = $('#table_order_list tbody').find('tr.selected');
        var row_count = rows.length;

        if(row_count > 0)
        {
            swal({
                title: 'Administrator Password',
                input: 'password',
                showCancelButton: true,
                confirmButtonText: 'Remove Order',
                showLoaderOnConfirm: false,
                preConfirm: (password) => {
                return new Promise((resolve) =>
                {
                    $.ajax(
                    {
                        type: 'get',
                        url: '{{ url("admin_password") }}/' + password,
                        success: function(data)
                        {
                            if(data > 0)
                                resolve();
                            else
                                swal('Invalid Password', '', 'warning');
                        }
                    }
                );
            //end ajax
        })
        },
            allowOutsideClick: () => !swal.isLoading()
        }).then(
            (result) =>
            {
                if (result.value)
                {
                    var _c_order = [];

                    for(var i = 0; i < rows.length; i++)
                    {
                        _c_order[i] = $(rows[i]).attr('id');
                    }

                    $.ajax({
                        type: 'POST',
                        url : '{{ url("sale/cancel_order") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            transaction_no: selected_transaction,
                            list: _c_order
                        }, success: function(data) {
                            console.log(data);
                            swal('Item has been removed', '', 'success');
                        }
                    });
                    $('#btn-tables').click();
                }
            }
        )
        }
        else
        {
            swal('Select item to cancel order', '', 'warning');
        }
        //end of statement
    });

    function get_available_table() {
        $.ajax({
            type: 'GET',
            url: '{{ url("sale/available_table") }}',
            success: function(data) {
                data = JSON.parse(data);
                var options = '';
                $('#table').find('option').remove();

                for(var i = 0; i < Object.keys(data).length; i++)
                {
                    options += '<option value="' + data[i].price + '">' + + data[i].number + '</option>';
                }

                $('#table').append(options);
            }
        });
    }

    function get_amount_due(bill, discount, discount_type, companion, table_charge) {
        bill            = parseFloat(bill);
        discount        = parseFloat(discount);
        var discounted  = 0;
        var vat         = 0;
        var less_vat    = 0;
        var charge      = 0;
        var amount_due  = 0;
        var table_charge = parseFloat(table_charge);

        if(discount_type == 'Senior Citizen')
        {
            var discounted      = bill * (discount / 100);  //discount
            var less_discount   = bill - discounted;        //less discount

            vat         = less_discount / 1.12; //net of vat
            less_vat    = less_discount - vat;  //less vat
            // charge      = vat * 0.05;           //service charge
            amount_due  = bill + charge+ table_charge;        //total amount due
        }
        else if(discount_type == 'PWD')
        {
            var discounted      = bill * (discount / 100);  //discount
            var less_discount   = bill - discounted;        //less discount

            vat         = less_discount / 1.12; //net of vat
            less_vat    = less_discount - vat;  //less vat
            // charge      = vat * 0.05;        //service charge
            amount_due  = bill + charge + table_charge;        //total amount due
        }
        else
        {
            vat         = bill / 1.12;
            less_vat    = bill - vat;
            // charge      = vat * 0.05;
            amount_due  = bill + charge + table_charge;
        }

        $('#vat').val(less_vat.toFixed(2));
        $('#discount').val(discounted.toFixed(2));
        // $('#service_charge').val(charge.toFixed(2));
        // here payable
        $('#payable').val(bill.toFixed(2));
        $('#total_amount_due').val(amount_due.toFixed(2));
    }

    function charge_get_amount_due(bill, discount, discount_type, companion, table_charge) {
        bill            = parseFloat(bill);
        discount        = parseFloat(discount);
        var discounted  = 0;
        var vat         = 0;
        var less_vat    = 0;
        var charge      = 0;
        var amount_due  = 0;
        var table_charge = parseFloat(table_charge);

        if(discount_type == 'Senior Citizen') {
            var discounted      = bill * (discount / 100);  //discount
            var less_discount   = bill - discounted;        //less discount

            vat         = less_discount / 1.12; //net of vat
            less_vat    = less_discount - vat;  //less vat
            // charge      = vat * 0.05;        //service charge
            amount_due  = bill + charge;        //total amount due
        } else if(discount_type == 'PWD') {
            var discounted      = bill * (discount / 100);  //discount
            var less_discount   = bill - discounted;        //less discount

            vat         = less_discount / 1.12; //net of vat
            less_vat    = less_discount - vat;  //less vat
            // charge      = vat * 0.05;        //service charge
            amount_due  = bill + charge;        //total amount due
        } else {
            vat         = bill / 1.12;
            less_vat    = bill - vat;
            // charge      = vat * 0.05;
            amount_due  = bill + charge;
        }

        if(bill > table_charge) {
            total_bill = bill;
        } else {
            total_bill = table_charge;
            total_bill_with_12_vat = table_charge / 1.12;
            vatable_sales = table_charge - total_bill_with_12_vat;
        }

        if (amount_due > table_charge) {
            overall_amount_due = amount_due;
        } else {
            overall_amount_due = table_charge;
        }

        console.log(overall_amount_due);

        var modal = $('#chargeSaveModal');

        // $(modal).find('#vat').val(less_vat.toFixed(2));

        $(modal).find('#discount').val(discounted.toFixed(2));
        $(modal).find('#vat').val(total_bill_with_12_vat.toFixed(2));
        $(modal).find('#vatable_sales').val(vatable_sales.toFixed(2));

        // $(modal).find('#service_charge').val(charge.toFixed(2));
        // here payable table charge

        $(modal).find('#payable').val(total_bill.toFixed(2));
        $(modal).find('#total_amount_due').val(overall_amount_due.toFixed(2));
    }

    function print_receipt(data)
    {
        var list = '';
        var _order_list = data;
        flag = true;

        // console.log(data);

        // console.log(_order_list);

        $('#notify').text('')
        $('#items tbody').find('tr').remove();

        for(var i = 0; i < Object.keys(_order_list.order).length; i++)
        {
            // console.log(_order_list);
            var code  = _order_list.order[i].product.code;
            var qty   = _order_list.order[i].quantity;
            var price = _order_list.order[i].price;
            var size  = _order_list.order[i].product_size.size;

            list += '<tr>';

            if(qty > 1) {
                list += '<td>' + qty + '</td>';
                list += '<td>' + code + ' ' + (size == 'Small' ? '': size) + ' @ ' + (price / qty) + '</td>';
            } else {
                list += '<td>' + qty + '</td>';
                list += '<td>' + code + ' ' + (size == 'Small' ? '': size) + '</td>';
            }

            list += '<td>' + price + '</td>';
            list += '</tr>';
        }

        if(data.order.table != null) {
            if(data.order.table.price > 0) {
                list += '<tr><td></td><td>Rent Table</td>';
                list += '<td>' + data.order.table.price + '</td>';
                list += '</tr>';
            }
        }

        console.log(data.order[0]);

        $('#transaction_no').text('#' + data.order[0].order.transaction_no);
        $('#print_total').text(parseFloat(data.order[0].order.payable).toFixed(2));
        $('#print_cash').text(parseFloat(data.order[0].order.cash).toFixed(2));
        $('#print_change').text(parseFloat(data.order[0].order.change).toFixed(2));
        $('#print_discount').text(parseFloat(data.order[0].order.discount).toFixed(2));
        $('#print_vat').text(parseFloat(data.order[0].order.vat).toFixed(2));
        // $('#print_charge').text(parseFloat(data.order[0].order.charge).toFixed(2));
        $('#print_amount_due').text(parseFloat(data.order[0].order.total).toFixed(2));
        $('#print_type').text(data.order[0].order.type);
        $('#print_table').text(data.order[0].order.type == 'Take Out' ? 'N/A' : data.order[0].order.table_no);
        $('#print_creditable_amount').text(data.order[0].order.table.price);
        $('#items tbody').append(list);
        // $('#payment').hide();
        // $('#official_receipt').show();
        $('#btn_print').css('visibility', 'visible');
        $('#btn_submit').css('visibility', 'hidden');
        clearAll();
    }
</script>