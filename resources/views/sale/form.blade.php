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

	@if(isset($category) && count($category) >= 0)
	<div class="form-group">
		<div class="col-md-12">
		  {{ Form::label('category','Category Name') }}
		  {{ Form::select('category', $category, isset($product->category_id) ? $product->category_id : old('category'),[
		    'class'=>'form-control'
		  ]) }}
		</div>
	</div>
	@endif

	@if(isset($supply) && count($supply) >= 0)
	<div class="form-group">
		<div class="col-md-12">
		  {{ Form::label('supply','Supply Name') }}
		  {{ Form::select('supply', $supply, isset($product->supply_id) ? $product->supply_id : old('supply'),[
		    'class'=>'form-control'
		  ]) }}
		</div>
	</div>
	@endif

	@if(isset($unit) && count($unit) >= 0)
	<div class="form-group">
		<div class="col-md-12">
		  {{ Form::label('unit','Unit Name') }}
		  {{ Form::select('unit', $unit, isset($product->unit_id) ? $product->unit_id : old('unit'),[
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
				<th>Category</th>
				<th>Supply</th>
				<th>Unit</th>
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
						<input type="text" class="category-list form-control text-center" value='{{ old("category.$row") }}' name='category[{{ $row }}]' style="border:none;" />
					</td>
					<td>
						<input type="text" class="supply-list form-control text-center" value='{{ old("supply.$row")}}' name="supply[{{ $row }}]" style="border:none;" />
					</td>
					<td>
						<input type="text" class="unit-list form-control text-center" value='{{ old("unit.$row") }}' name="unit[{{ $row }}]" style="border:none;" />
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
			
		</tbody>
	@endif
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

		category = $('#category').find(':selected').text()
		supply = $('#supply').find(':selected').text()
		unit = $('#unit').find(':selected').text()
		quantity = $('#quantity').val()
		amount = $('#amount').val()
		addForm(category, supply, unit, quantity, amount)
	})

	function addForm(category = "", supply = "", unit = "", _quantity = "", amount = 0)
	{

		row = parseInt( $('#supplyTable > tbody > tr').length )
		if(isNaN(row))
		{
			row = 1
		} else row++

		error_cat = false
		error_supp = false
		error_un = false

		$('.category-list').each(function() {
		    if (category == $(this).val())
		    {
		    	error_cat = true;	
		    	return;
		    }
		});

		$('.supply-list').each(function() {
		    if (supply == $(this).val())
		    {
		    	error_supp = true;	
		    	return;
		    }
		});

		$('.unit-list').each(function() {
		    if (unit == $(this).val())
		    {
		    	error_un = true;	
		    	return;
		    }
		});

		if(error_cat && error_supp && error_un)
		{
			swal("Error", "Product already exists", "error");
			return false;
		}

		$('#quantity').val("")
		$('#amount').val("")

		$('#supplyTable > tbody').append(`
			<tr>
				<td><input type="text" class="form-control text-center" value="` + row + `" name="row[` + row + `]" style="border:none;" /></td>
				<td><input type="text" class="category-list form-control text-center" value="` + category + `" name="category[` + row + `]" style="border:none;" /></td>
				<td><input type="text" class="supply-list form-control text-center" value="` + supply + `" name="supply[` + row + `]" style="border:none;" /></td>
				<td><input type="text" class="unit-list form-control text-center" value="` + unit + `" name="unit[` + row + `]" style="border:none;" /></td>
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