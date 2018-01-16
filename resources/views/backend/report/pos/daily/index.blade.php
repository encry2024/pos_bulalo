@extends ('backend.layouts.app')

@section ('title', 'Daily Report')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
    {{ Html::style('https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css') }}
    {{ Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css') }}
@endsection

@section('page-header')
    <h1>Daily Report</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Report List</h3>

            <div class="box-tools pull-right">

            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-lg-10">
                    
                </div>
                
                <div class="col-lg-2">
                    
                </div>
            </div>

            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->


@endsection

@section('after-scripts')

{{ Html::script('js/tableExport.js')}}
{{ Html::script('js/jquery.base64.js')}}
{{ Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js') }}
{{ Html::script('js/timepicker.js') }}
{!! $dataTable->scripts() !!}

<script type="text/javascript">
    $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' });
    $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' });
</script>
@endsection
