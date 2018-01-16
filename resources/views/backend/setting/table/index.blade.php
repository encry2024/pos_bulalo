@extends ('backend.layouts.app')

@section ('title', 'Setting | Table')

@section('page-header')
    <h1>
        Setting <small>Number of table </small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => ['admin.setting_table.update', $setting], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'patch']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools pull-right">

                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('name', 'Number Of Table', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::number('count', $setting->count, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->
                
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.setting.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js') }}
@endsection
