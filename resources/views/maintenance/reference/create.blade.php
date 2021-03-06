@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Reference</h3></legend>
      <ol class="breadcrumb">
          <li>
              <a href="{{ url('maintenance/reference') }}">Reference</a>
          </li>
          <li class="active">Create</li>
      </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
        {{ Form::open(array('class' => 'form-horizontal','method'=>'post','route'=>'reference.store','id'=>'referenceForm')) }}
          @include('errors.alert')
          @include('maintenance.reference.form')
          <div class="pull-right">
            <div class="btn-group">
              <button id="submit" class="btn btn-md btn-primary" type="submit">
                <span class="hidden-xs">Submit</span>
              </button>
            </div>
              <div class="btn-group">
                <button id="cancel" class="btn btn-md btn-default" type="button" onClick="window.location.href='{{ url("maintenance/reference") }}'" >
                  <span class="hidden-xs">Cancel</span>
                </button>
              </div>
            </div>
          </div> <!-- centered  -->
      {{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection