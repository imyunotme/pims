@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Out</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('sale') }}">Sales</a></li>
			<li class="active">Out</li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box" style="padding:10px;">
    <div class="box-body">
		{{ Form::open(['method'=>'post','url'=>array('sale/out'),'class'=>'form-horizontal','id'=>'salesForm']) }}
		@include('errors.alert')
		<div class="row">
		@include('sale.form')
		</div>
		<div class="pull-right">
			<div class="btn-group">
				<button type="button" id="accept" class="btn btn-md btn-primary btn-block">Submit</button>
			</div>
			<div class="btn-group">
				<button type="button" id="cancel" class="btn btn-md btn-default">Cancel</button>
			</div>
		</div>
		{{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection