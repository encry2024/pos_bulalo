@extends ('backend.layouts.app')

@section ('title', 'Setting | Senior')

@section('page-header')
    <h1>
        Branch <small>Add </small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => ['admin.setting.update', $setting], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'patch']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools pull-right">

                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('name', 'Name', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::text('name', $setting->name, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('discount', 'Discount in Percentage', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('discount', $setting->discount, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required']) }}
                    </div><!--col-lg-10-->
                </div>

                <div class="form-group">
                    {{ Form::label('description', 'Description', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::textArea('description', $setting->description, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'rows' => '6']) }}
                    </div><!--col-lg-10-->
                </div>
                
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.setting.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js') }}
@endsection
