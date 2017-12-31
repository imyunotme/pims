@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Supplies Inventory</h3></legend>
		<ol class="breadcrumb">
			<li>Inventory</li>
			<li class="active">Home</li>
		</ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">
		<table class="table table-hover table-bordered" id="supplyInventoryTable" width=100%>
			<thead>
				<tr>
					<th class="col-sm-1">Category</th>
					<th class="col-sm-1">Name</th>
					<th class="col-sm-1">Details</th>
					<th class="col-sm-1">Unit</th>
					<th class="col-sm-1">Cost</th>
					<th class="col-sm-1">Balance</th>
					@if(Auth::user()->access == 1 || Auth::user()->access == 2)
					<th class="col-sm-1 no-sort"></th>
					@endif
				</tr>
			</thead>
		</table>
		</div>

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')
<script>
	$(document).ready(function() {

	    var table = $('#supplyInventoryTable').DataTable({
	    	serverSide: true,
			language: {
					searchPlaceholder: "Search..."
			},
	    	columnDefs:[
	       	 { targets: 'no-sort', orderable: false },
	      	],
			@if(Auth::user()->access == 1 || Auth::user()->access == 2)
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			@endif
			"processing": true,
			ajax: "{{ url('inventory/supply') }}",
			columns: [
	            { data: "code" },
	            { data: "category.name" },
	            { data: function(callback){
	            	if(callback.supply) return callback.supply.name
	            		else return null
	            } },
	            { data: function(callback){
	            	if(callback.unit) return callback.unit.name
	            		else return null
	            } },
	            { data: "cost" },
	            { data: "balance" },
	            { data: function(callback){
	            	return `
	            			<a href="{{ url("stockcard") }}` + '/' + callback.id  + `" class="btn btn-sm btn-default">Stock Card</a>
	            			<a target="_blank" href="{{ url("stockcard") }}` + '/' + callback.id  + `/print" class="btn btn-sm btn-primary">Print</a>
	            	`;
	            } }
			],
	    });

		@if(Auth::user()->access == 1)
	 	$("div.toolbar").html(`
		      <a href="{{ url("inventory/supply/stockcard/print") }}" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
		        <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
		        <span id="nav-text"> Print</span>
		      </a>
		`);
		@endif
	} );
</script>
@endsection
