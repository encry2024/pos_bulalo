@extends ('backend.layouts.app')

@section ('title', 'Setting')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>Setting</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Setting List</h3>

            <div class="box-tools pull-right">
                
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive col-lg-12">
                <table id="users-table" class="table table-condensed table-hover table-bordered">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" style="text-align: center">Update discounts setting.</td>
                        </tr>
                        @foreach($settings as $setting)
                        <tr>
                            <td style="width:30%">
                                <a href="{{ route('admin.setting.edit', $setting) }}" class="btn btn-default btn-block">
                                    {{ $setting->name }}
                                </a>
                            </td>
                            <td>
                               {{ $setting->description }}
                            </td>
                        </tr>
                        @endforeach

                        <tr>
                            <td style="width:30%"> <a href="{{ route('admin.setting_table.index') }}" class="btn btn-default btn-block">
                                    Table
                                </a></td>
                            <td>Fixed Description</td>
                        </tr>
                    </tbody>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->


@endsection

@section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
@endsection
