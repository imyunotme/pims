<div class="col-md-4">
	@if(isset($supplier) && count($supplier) >= 0)
	<div class="col-md-12">
		<div class="form-group">
			{{ Form::label('Supplier') }}
			{{ Form::select('supplier', $supplier, isset($sale->reference) ? $sale->reference : old('supplier'),[
				'id' => 'supplier',
				'class' => 'form-control'
			]) }}
		</div>
	</div>	
	@endif

	@if(isset($customer) && count($customer) >= 0)
	<div class="col-md-12">
		<div class="form-group">
			{{ Form::label('Customer') }}
			{{ Form::select('customer', $customer, isset($sale->reference) ? $sale->reference : old('customer'),[
				'id' => 'customer',
				'class' => 'form-control'
			]) }}
		</div>
	</div>	
	@endif

	@if(isset($type) && $type == 'out')
	<div class="col-md-12">
		<div class="form-group">
			{{ Form::label('Receipt') }}
			<p>This field accepts official receipt number or invoice.</p>
			{{ Form::text('receipt', isset($sale->receipt) ? $sale->receipt : old('receipt'),[
				'class' => 'form-control'
			]) }}
		</div>
	</div>
	@endif

	<div class="col-md-12">
		<div class="form-group">
			{{ Form::label('Date') }}
			{{ Form::text('date',Input::old('date'),[
				'id' => 'date',
				'class' => 'form-control',
				'readonly',
				'style' => 'background-color: white;'
			]) }}
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$( "#date" ).datepicker({
				  changeMonth: true,
				  changeYear: true,
				  maxAge: 59,
				  minAge: 15,
			});
		})
	</script>

	@if(isset($type) && $type == 'out')
	<div class="col-md-12">
		<div class="form-group">
			{{ Form::label('Status') }}
			{{ Form::select('status', $status, isset($sale->status) ? $sale->status : old('status'),[
				'class' => 'form-control'
			]) }}
		</div>
	</div>
	@endif

	@if(isset($products) && count($products) >= 0)
	<div class="form-group">
		<div class="col-md-12">
		  {{ Form::label('product','Product') }}
		  {{ Form::select('product', $products, old('product'),[
		    'class'=>'form-control'
		  ]) }}
		</div>
	</div>
	@endif

	<div class="col-md-12" id="quantity-field">
		<div class="form-group">
		{{ Form::label('Quantity') }}
		{{ Form::text('quantity','',[
			'id' => 'quantity',
			'class' => 'form-control'
		]) }}
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		{{ Form::label('Amount') }}
		{{ Form::text('amount','',[
			'id' => 'amount',
			'class' => 'form-control'
		]) }}
		</div>
	</div>
	<div class="btn-group" style="margin-bottom: 20px;">
		<button type="button" id="add" class="btn btn-md btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>
	</div>
</div>
<div class="col-md-8">
	<legend class="text-muted"><h3>Items</h3></legend>
	<table class="table table-hover table-condensed table-bordered" id="supplyTable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Product</th>
				<th>Quantity</th>
				<th>Unit Cost</th>
				<th></th>
			</tr>
		</thead>

		<tbody>


		@if(null !== old('row'))

			@foreach(old('row') as $row)
			<tr>
				<td>
					<input type="text" class="form-control text-center" value="{{ $row }}" name="row[{{ $row }}]" style="border:none;" />
				</td>
				<td>
					<input type="text" class="product-list form-control text-center" value='{{ old("product.$row") }}' name='product[{{ $row }}]' style="border:none;" />
				</td>
				<td>
					<input type="number" class="form-control text-center" value='{{ old("quantity.$row") }}' name="quantity[{{ $row }}]" style="border:none;"  />
				</td>
				<td>
					<input type="text" class="form-control text-center" value='{{ old("amount.$row") }}' name="amount[{{ $row }}]" style="border:none;"  />
				</td>
				<td>
					<button type="button" class="remove btn btn-md btn-danger text-center"><span class="glyphicon glyphicon-remove"></span></button>
				</td>
			</tr>
			@endforeach
			
		@endif

			<tr>
				<td colspan=5 class="text-muted text-center"> ** End of List ** </td>
			</tr>

		</tbody>
	</table>
</div>
<div class="col-md-12">
	<div class="form-group">
		<label>Remarks</label>
		<textarea name="remarks" class="form-control" rows="4" placeholder="Input remarks here..."></textarea>
	</div>
</div>

@section('after_scripts')
<script type="text/javascript">
$('document').ready(function(){

	$('#accept').on('click',function(){

		if($('#supplyTable > tbody > tr').length == 0)
		{
			swal('Blank Field Notice!','Ttable must have atleast 1 item','error')
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
	            $('#salesForm').submit();
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        })
		}

	})

	@if(old('date'))
		$('#date').val('{{ old('date') }}');
	@else
		$('#date').val('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
	@endif

	$('#date').trigger('change')

	$('#add').on('click',function(){

		product = $('#product').find(':selected').text()
		quantity = $('#quantity').val()
		amount = $('#amount').val()
		addForm(product, quantity, amount)
	})

	function addForm(product = "", _quantity = 0, amount = 0)
	{

		row = parseInt( $('#supplyTable > tbody > tr').length )
		if(isNaN(row))
		{
			row = 1
		} else row++

		error = false

		$('.product-list').each(function() {
			product_list = $(this).val()
		    if (product == product_list)
		    {
		    	error = true;	
		    	return;
		    }
		});

		if(error)
		{
			swal("Error", "Product already exists", "error");
			return false;
		}

		$('#quantity').val("")
		$('#amount').val("")

		$('#supplyTable > tbody').prepend(`
			<tr>
				<td><input type="text" class="form-control text-center" value="` + row + `" name="row[` + row + `]" style="border:none;" /></td>
				<td><input type="text" class="product-list form-control text-center" value="` + product + `" name="product[` + row + `]" style="border:none;" /></td>
				<td><input type="number" class="form-control text-center" value="` + _quantity + `" name="quantity[` + row + `]" style="border:none;"  /></td>
				<td><input type="text" class="form-control text-center" value="` + amount + `" name="amount[` + row + `]" style="border:none;"  /></td>
				<td><button type="button" class="remove btn btn-md btn-danger text-center"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		`)

		return true;
	}

	@if(old('date'))
	$('#date').val('{{ old('date') }}');
	@else
	$('#date').val('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
	@endif

	$('#date').trigger('change')

	$('#date').on('change',function(){
		$(this).val(moment($(this).val()).format('MMM DD, YYYY'));
	});

	$('#cancel').on('click',function(){
		window.location.href = "{{ url('inventory/supply') }}"
	})

	$('#supplyTable').on('click','.remove',function(){
		$(this).parents('tr').remove()
	})

	$('#category').on('change',function(){
		category = $('#category').find(':selected').text()
		if(category == 'Vale' || category == 'Sulong')
		{
			$('#quantity').val(0)
			$('#quantity-field').hide(400)
		}
		else
			$('#quantity-field').show(400)
	})

	$('#category').trigger('change')

})
</script>

@yield('additionals_scripts')
@endsection