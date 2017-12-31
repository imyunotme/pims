@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Products</h3></legend>
      <ol class="breadcrumb">
          <li>
              <a href="{{ url('maintenance/product') }}">Product</a>
          </li>
          <li class="active">{{ $product->id }}</li>
          <li class="active">Edit</li>
      </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
        {{ Form::open(array('method'=>'put','class' => 'form-horizontal','route'=>array('product.update',$product->id),'id'=>'productForm')) }}
        @include('errors.alert')
        @include('maintenance.product.form')
        <div class="pull-right">
          <div class="btn-group">
            <button id="submit" class="btn btn-md btn-primary" type="submit">
              <span class="hidden-xs">Update</span>
            </button>
          </div>
            <div class="btn-group">
              <button id="cancel" class="btn btn-md btn-default" type="button" onClick="window.location.href='{{ url("maintenance/product") }}'" >
                <span class="hidden-xs">Cancel</span>
              </button>
            </div>
        </div>
      {{ Form::close() }}

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection
