@extends('backpack::layout')


@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Categories</h3></legend>
      <ol class="breadcrumb">
          <li>
              <a href="{{ url('maintenance/category') }}">category</a>
          </li>
          <li class="active">Create</li>
      </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
        {{ Form::open(array('class' => 'form-horizontal','method'=>'post','route'=>'category.store','id'=>'categoryForm')) }}      
        @include('errors.alert')
        @include('maintenance.category.form')
        <div class="pull-right">
          <div class="btn-group">
            <button id="submit" class="btn btn-md btn-primary" type="submit">
              <span class="hidden-xs">Submit</span>
            </button>
          </div>
          <div class="btn-group">
            <button id="cancel" class="btn btn-md btn-default" type="button" onClick="window.location.href='{{ url("maintenance/category") }}'" >
              <span class="hidden-xs">Cancel</span>
            </button>
          </div>
        </div>
      {{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection