@extends ('backend.layouts.app')

@section ('title', 'Setting | Table')

@section('page-header')
    <h1>Table List</h1>
@endsection

@section('content')
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools pull-right">
                     @include('backend.setting.table.includes.partials.setting-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="table-responsive">
                    <table id="users-table" class="table table-condensed table-hover">
                        <thead>
                            <tr>
                            <th>TABLE NUMBER</th>
                            <th>PRICE</th>
                            <th>&nbsp;</th>
                            </tr>
                        </thead>
                    </table>
                </div><!--table-responsive-->
            </div><!-- /.box-body -->
        </div><!--box-->
@endsection

@section('after-scripts')
    {{ Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js') }}
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    <script>
        $(function() {
            $('#users-table').DataTable({
                dom: 'Blfrtip',
                processing: false,
                serverSide: true,
                ajax: '{!! route('admin.setting_table.get') !!}',
                columns: [
                    { data: 'number' },
                    { data: 'price' },
                    { data: 'actions' }
                ],
                order: [0, 'asc']
            });
        });
    </script>
@endsection
