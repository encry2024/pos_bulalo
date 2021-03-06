@extends ('backend.layouts.app')

@section ('title', 'Dry Goods Return')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>Dry Goods Return</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">ITEMS LIST</h3>

            <div class="box-tools pull-right">
                @include('backend.dry_good.goods_return.includes.partials.goods-return-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>DISPOSAL/RETURN ITEM</th>
                        <th>DATE</th>
                        <th>QUANTITY</th>
                        <th>COST</th>
                        <th>TOTAL COST</th>
                        <th>REASON</th>
                        <th>WITNESS</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->
@endsection

@section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}

    <script>
        $(function() {
            $('#users-table').DataTable({
                dom: 'Blfrtip',
                processing: false,
                serverSide: true,
                ajax: '{!! route('admin.dry_good.goods_return.get') !!}',
                columns: [
                    { data: 'inventory.name' },
                    { data: 'date' },
                    { data: 'quantity' , searchable: false},
                    { data: 'cost' , searchable: false},
                    { data: 'total_cost' , searchable: false},
                    { data: 'reason' , searchable: false},
                    { data: 'witness' },
                    { data: 'actions' }
                ],
                order: [1, 'asc']
            });
        });
    </script>
@endsection