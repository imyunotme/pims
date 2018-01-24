
<div class="col-md-12">
	<div class="form-group">
		{{ Form::label('Receipt') }}
		{{ Form::text('receipt',Input::old('receipt'),[
			'class' => 'form-control'
		]) }}
	</div>
</div>
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
<div class="form-group">
	<div class="col-md-12">
	{{ Form::label('product','Product') }}
	</div>
	<div class="col-md-9">
	{{ Form::text('product',null,[
		'id' => 'product',
		'class' => 'form-control'
	]) }}
	</div>
	<div class="col-md-1">
		<button type="button" id="add-stocknumber" class="btn btn-sm btn-primary">Select</button>
	</div>
</div>
<input type="hidden" id="supply-item" />
<div id="stocknumber-details">
</div>
<div class="col-md-12">
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
	{{ Form::label('Unit Cost') }}
	{{ Form::text('unitcost','',[
		'id' => 'unitcost',
		'class' => 'form-control'
	]) }}
	</div>
</div>
<div class="btn-group" style="margin-bottom: 20px;">
	<button type="button" id="add" class="btn btn-md btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>
</div>
</div>
<div class="col-sm-8">
<legend class="text-muted"><h3>Supplies List</h3></legend>
<table class="table table-hover table-condensed table-bordered" id="supplyTable">
	<thead>
		<tr>
			<th>Stock Number</th>
			<th>Information</th>
			<th>Quantity</th>
			<th>Unit Cost</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>