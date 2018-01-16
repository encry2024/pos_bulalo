@extends ('backend.layouts.app')

@section ('title', 'Commissary Disposal Form')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>Commissary Disposal Form</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">ITEMS LIST</h3>

            <div class="box-tools pull-right">
                @include('backend.commissary.dispose.includes.partials.dispose-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>DISPOSED ITEM</th>
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
                ajax: '{!! route('admin.commissary.dispose.get') !!}',
                columns: [
                    { data: 'name' },
                    { data: 'date' },
                    { data: 'quantity' },
                    { data: 'cost' },
                    { data: 'total_cost' },
                    { data: 'reason' },
                    { data: 'witness' },
                    { data: 'actions' }
                ],
                order: [1, 'asc']
            });
        });
    </script>
@endsection