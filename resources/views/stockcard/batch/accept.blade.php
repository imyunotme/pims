@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Accept</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('inventory/supply') }}">Supply Inventory</a></li>
			<li class="active">Accept</li>
		</ul>
	</section>
@endsection

@section('content')
@include('modal.request.supply')
<!-- Default box -->
  <div class="box" style="padding:10px;">
    <div class="box-body">
		{{ Form::open(['method'=>'post','route'=>array('supply.store'),'class'=>'form-horizontal','id'=>'acceptForm']) }}
		@include('errors.alert')
		<div class="col-md-12">
		<div class="form-group">
			{{ Form::label('Supplier') }}
			{{ Form::select('supplier',$supplier,Input::old('supplier'),[
				'id' => 'supplier',
				'class' => 'form-control'
			]) }}
		</div>
		</div>
		@include('stockcard.batch.form')
			<div class="pull-right">
				<div class="btn-group">
					<button type="button" id="accept" class="btn btn-md btn-primary btn-block">Accept</button>
				</div>
				<div class="btn-group">
					<button type="button" id="cancel" class="btn btn-md btn-default">Cancel</button>
				</div>
			</div>
		</div>
		{{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')
<script>
$('document').ready(function(){

	$('#stocknumber').autocomplete({
		source: "{{ url("inventory/supply") }}"
	})

	$('#accept').on('click',function(){

		if($('#supplyTable > tbody > tr').length == 0)
		{
			swal('Blank Field Notice!','Supply table must have atleast 1 item','error')
		} else {
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
	            $('#acceptForm').submit();
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        })
		}

	})

	function setStockNumberDetails(){
		$.ajax({
			type: 'get',
			url: '{{ url('inventory/supply') }}' +  '?stocknumber=' + $('#stocknumber').val(),
			dataType: 'json',
			success: function(response){
				try{
					details = response.data.details
					unitcost = response.data.unitcost
					$('#supply-item').val(details.toString())
					$('#stocknumber-details').html(`
						<div class="alert alert-info">
							<ul class="list-unstyled">
								<li><strong>Item:</strong> ` + details + ` </li>
								<li><strong>Cost:</strong> ` + unitcost + ` </li>
								<li><strong>Remaining Balance:</strong> `
								+ response.data.balance +
								`</li>
							</ul>
						</div>
					`)

					$('#add').show()
				} catch (e) {
					$('#stocknumber-details').html(`
						<div class="alert alert-danger">
							<ul class="list-unstyled">
								<li>Invalid Item</li>
							</ul>
						</div>
					`)

					$('#add').hide()
				}
			}
		})
	}

	$( "#date" ).datepicker({
		  changeMonth: true,
		  changeYear: true,
		  maxAge: 59,
		  minAge: 15,
	});

	@if(Input::old('date'))
		$('#date').val('{{ Input::old('date') }}');
		setDate("#date");
	@else
		$('#date').val('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
		setDate("#date");
	@endif

	$('#add').on('click',function(){
		row = parseInt($('#supplyTable > tbody > tr:last').text())
		if(isNaN(row))
		{
			row = 1
		} else row++

		stocknumber = $('#stocknumber').val()
		quantity = $('#quantity').val()
		details = $('#supply-item').val()
		unitcost = $('#unitcost').val()
		if(addForm(row,stocknumber,details,quantity,unitcost))
		{
			$('#stocknumber').text("")
			$('#quantity').text("")
			$('#unitcost').text("")
			$('#stocknumber-details').html("")
			$('#stocknumber').val("")
			$('#quantity').val("")
			$('#unitcost').val("")
		}
	})

	function addForm(row,_stocknumber = "",_info ="" ,_quantity = "", _unitcost = 0)
	{
		error = false
		$('.stocknumber-list').each(function() {
		    if (_stocknumber == $(this).val())
		    {
		    	error = true;	
		    	return;
		    }
		});

		if(error)
		{
			swal("Error", "Stocknumber already exists", "error");
			return false;
		}

		$('#supplyTable > tbody').append(`
			<tr>
				<td><input type="text" class="stocknumber-list form-control text-center" value="` + _stocknumber + `" name="stocknumber[` + _stocknumber + `]" style="border:none;" /></td>
				<td><input type="hidden" class="form-control text-center" value="` + _info + `" name="info[` + _stocknumber + `]" style="border:none;" />` + _info + `</td>
				<td><input type="number" class="form-control text-center" value="` + _quantity + `" name="quantity[` + _stocknumber + `]" style="border:none;"  /></td>
				<td><input type="text" class="form-control text-center" value="` + _unitcost + `" name="unitcost[` + _stocknumber + `]" style="border:none;"  /></td>
				<td><button type="button" class="remove btn btn-md btn-danger text-center"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		`)

		return true;
	}

	$('#date').on('change',function(){
		setDate("#date");
	});

	$('#cancel').on('click',function(){
		window.location.href = "{{ url('inventory/supply') }}"
	})

	$('#supplyTable').on('click','.remove',function(){
		$(this).parents('tr').remove()
	})

	function setDate(object){
			var object_val = $(object).val()
			var date = moment(object_val).format('MMM DD, YYYY');
			$(object).val(date);
	}

	@if(null !== old('stocknumber'))

	function init()
	{

		@foreach(old('stocknumber') as $stocknumber)
		row = parseInt($('#supplyTable > tbody > tr:last').text())
		if(isNaN(row))
		{
		  row = 1
		} else row++

	    addForm(row,"{{ $stocknumber }}","{{ old("info.$stocknumber") }}", "{{ old("quantity.$stocknumber") }}", "{{ old("unitcost.$stocknumber") }}")
		@endforeach

	}

	init();

	@endif
	$('#supplier').on('change',function(){
		setReferenceLabel($("#supplier option:selected").text())
	})

    $('#stocknumber').on('change',function(){
      setStockNumberDetails()
    })

    $('#add-stocknumber').on('click',function(){
      $('#addStockNumberModal').modal('show');
    })

    $('#supplyInventoryTable').on('click','.add-stock',function(){
      $('#stocknumber').val($(this).data('id'))
      $('#addStockNumberModal').modal('hide')
      setStockNumberDetails()
    })
})
</script>
@endsection
