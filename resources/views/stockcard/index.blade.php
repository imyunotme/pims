@extends('backpack::layout')

@section('after_styles')
	<style>
	
		a > hover{
			text-decoration: none;
		}

		th , tbody{
			text-align: center;
		}
	</style>
@endsection

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Stock Card</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('inventory/supply') }}">Inventory</a></li>
			<li class="active">{{ isset($product->supply) ? isset($product->supply->name) ? $product->supply->name : 'None' : 'None' }}</li>
			<li class="active">Stock Card</li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box" style="padding:10px">
    <div class="box-body">
			<table class="table table-hover table-striped table-bordered table-condensed" id="inventoryTable" cellspacing="0" width="100%">
				<thead>
		            <tr rowspan="2">
		                <th class="text-left" colspan="4">Name:  <span style="font-weight:normal">{{ isset($product->supply) ? isset($product->supply->name) ? $product->supply->name : 'None' : 'None' }}</span> </th>
		                <th class="text-left" colspan="4">Details:  <span style="font-weight:normal">{{ isset($product->supply) ? isset($product->supply->details) ? $product->supply->details : 'None' : 'None' }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="4">Unit Of Measurement:  <span style="font-weight:normal">{{ isset($product->unit) ? isset($product->unit->name) ? $product->unit->name : 'None' : 'None' }}</span>  </th>
		                <th class="text-left" colspan="4">Reorder Point: <span style="font-weight:normal">{{ $product->reorderpoint }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-center" colspan="4">Information</th>
		                <th class="text-center" colspan="1">In</th>
		                <th class="text-center" colspan="1">Out</th>
		                <th class="text-center" colspan="2"></th>
		            </tr>
					<tr>
						<th>Date</th>
						<th>Product</th>
						<th>Receipt</th>
						<th>Name/Supplier</th>
						<th>Quantity</th>
						<th>Quantity</th>
						<th>Balance</th>
						<th>Remarks</th>
						
					</tr>
				</thead>
			</table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')

<script>
	$(document).ready(function() {

	    var table = $('#inventoryTable').DataTable({
	    	serverSide: true,
			language: {
					searchPlaceholder: "Search..."
			},
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"columnDefs":[
				{ "type": "date", "targets": 0 },
			],
			"processing": true,
			ajax: '{{ url("stockcard/$product->id?") }}',
			columns: [
				{ data: "parsed_date" },
				{ data: "product_details" },
				{ data: function(callback){
					if(callback.receipt) return callback.receipt
						return null
				}},					
				{ data: function(callback){
					if(callback.organization) return callback.organization
						return null
				}},
				{ data: "issued"},
				{ data: "received"},
				{ data: "balance" },
				{ data: "remarks"},
			],
	    });

	 	$("div.toolbar").html(`
	       <a href="{{ url("stockcard/$product->id/print") }}" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
	        <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
	        <span id="nav-text"> Print</span>
	      </a>
		`);
	} );
</script>
@endsection
