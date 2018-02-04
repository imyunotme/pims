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
		<legend><h3 class="text-muted">Sales</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('sales') }}">Sales</a></li>
			<li class="active">Home</li>
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
		                <th class="text-center" colspan="4">Information</th>
		                <th class="text-center" colspan="3">In</th>
		                <th class="text-center" colspan="3">Out</th>
		                <th class="text-center" colspan="2"></th>
		            </tr>
					<tr>
						<th>Date</th>
						<th>Product</th>
						<th>Receipt</th>
						<th>Name/Supplier</th>
						<th>Quantity</th>
						<th>Amount</th>
						<th>Total</th>
						<th>Quantity</th>
						<th>Amount</th>
						<th>Total</th>
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
			ajax: '{{ url("sale") }}',
			columns: [
					{ data: "parsed_date" },
					{ data: "product_details" },
					{ data: "receipt"},					
					{ data: "reference"},
					{ data: "issued"},
					{ data: "issued_amount"},
					{ data: "issued_total"},
					{ data: "received"},
					{ data: "received_amount"},
					{ data: "received_total"},
					{ data: "balance" },
					{ data: "remarks"},
			],
	    });

	 	$("div.toolbar").html(`
	       <a href="{{ url("sale/print_index") }}" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
	        <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
	        <span id="nav-text"> Print</span>
	      </a>
	       <a href="{{ url("sale/in") }}" id="in" class="print btn btn-sm btn-success ladda-button" data-style="zoom-in">
	        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
	        <span id="nav-text"> In</span>
	      </a>
	       <a href="{{ url("sale/out") }}" id="out" class="print btn btn-sm btn-danger ladda-button" data-style="zoom-in">
	        <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
	        <span id="nav-text"> Out</span>
	      </a>
		`);
	} );
</script>
@endsection

