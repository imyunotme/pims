@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Products</h3></legend>
		<ol class="breadcrumb">
			<li>Product</li>
			<li>Home</li>
		</ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">
			<table class="table table-striped table-hover table-bordered" id='productTable'>
				<thead>
					<th class="col-sm-1">ID</th>
					<th class="col-sm-1">Code</th>
					<th class="col-sm-1">Category</th>
					<th class="col-sm-1">Supply</th>
					<th class="col-sm-1">Unit</th>
					<th class="col-sm-1">Cost</th>
					<th class="no-sort col-sm-1"></th>
				</thead>
			</table>
		</div>

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')

<script>
	$(document).ready(function(){
	    var table = $('#productTable').DataTable( {
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('maintenance/product') }}",
	        columns: [
	            { data: "id" },
	            { data: "code" },
	            { data: function(callback){
	            	if(callback.category) return callback.category.name
	            } },
	            { data: function(callback){
	            	if(callback.supply) return callback.supply.name
	            		return null
	            } },
	            { data: function(callback){
	            	if(callback.unit) return callback.unit.name
	            		return null
	            } },
	            { data: "cost" },
	            { data: function(callback){
	            	return `
	            			<a href="{{ url("maintenance/product") }}` + '/' + callback.id + '/edit' + `" class="btn btn-sm btn-default">Edit</a>
	            			<button type="button" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Removing Product" data-id="`+callback.id+`" class="remove btn btn-sm btn-danger">Remove</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a href="{{ url('maintenance/product/create') }}" id="new" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>  Add
 			</a>
		`);

		$('#productTable').on('click','button.remove',function(){
		  	var removeButton = $(this);
			removeButton.button('loading');
			$.ajax({
				type: 'delete',
				url: '{{ url("maintenance/product") }}' + '/' + $(this).data('id'),
				dataType: 'json',
				success: function(response){
					if(response == 'success')
					swal("Operation Success",'A product has been removed.',"success")
					else
						swal("Error Occurred",'An error has occurred while processing your data.',"error")
					table.ajax.reload()
			  		removeButton.button('reset');
				},
				error: function(response){
					swal("Error Occurred",'An error has occurred while processing your data.',"error")
				}

			})
		})

	});
</script>
@endsection
