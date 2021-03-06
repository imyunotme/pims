@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    {{ $request->code }}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url('request') }}">Request</a></li>
	    <li class="active">Cancel</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box" style="padding:10px;">
    <div class="box-body">
    {{ Form::open(['method'=>'put','route'=>array('request.cancel',$request->id),'class'=>'form-horizontal','id'=>'requestForm']) }}
      @if (count($errors) > 0)
          <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <ul style='margin-left: 10px;'>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
      <legend><h4 class="">Cancel Request No. {{ $request->code }} ? </h4></legend>
      <table class="table table-hover table-bordered table-condensed" id="supplyTable">
        <thead>
          <tr>
            <th class="col-sm-1">Stock Number</th>
            <th class="col-sm-1">Information</th>
            <th class="col-sm-1">Remaining Balance</th>
            <th class="col-sm-1">Requested Quantity</th>
          </tr>
        </thead>
        <tbody>
          @foreach($supplyrequest as $supplyrequest)
          <tr>
            <td>{{ $supplyrequest->stocknumber }}<input type="hidden" name="stocknumber[]" value="{{ $supplyrequest->stocknumber }}"</td>
            <td>{{ $supplyrequest->supply->details }}</td>
            <td>{{ $supplyrequest->supply->balance }}</td>
            <td>{{ $supplyrequest->quantity_requested }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="form-group">
        <div class="col-md-12">
          {{ Form::label('Details for Cancellation') }}
          {{ Form::textarea('details',Input::old('details'),[
              'class' => 'form-control'
            ]) }}
        </div>
      </div>
      <div class="pull-right">
        <div class="btn-group">
          <button type="button" id="cancel" class="btn btn-md btn-danger btn-block">Cancel</button>
        </div>
        <div class="btn-group">
          <button type="button" id="back" class="btn btn-md btn-default">Go Back</button>
        </div>
      </div>
      {{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->
@endsection

@section('after_scripts')

<script>
  jQuery(document).ready(function($) {

    $('#cancel').on('click',function(){
      swal({
        title: "Are you sure?",
        text: "This will no longer be editable once submitted. Do you want to continue?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, submit it!",
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm){
        if (isConfirm) {
          $('#requestForm').submit();
        } else {
          swal("Cancelled", "Operation Cancelled", "error");
        }
      })
    })

    $('#back').on('click',function(){
      window.location.href = "{{ url('request') }}"
    })

  });
</script>
@endsection
