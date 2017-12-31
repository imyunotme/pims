@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Reference</h3></legend>
		<ol class="breadcrumb">
			<li>Reference</li>
			<li class="active">Home</li>
		</ol>
		</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">
			<table class="table table-striped table-hover table-bordered" id='referenceTable'>
				<thead>
					<th class="">ID</th>
					<th class="">Name</th>
					<th class="">Company</th>
					<th class="">Type</th>
					<th class="">Address</th>
					<th class="">Contact</th>
					<th class="">Email</th>
					<th class="">Description</th>
					<th class="no-sort col-sm-2"></th>
				</thead>
			</table>
		</div>

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')
<script>
	$(document).ready(function(){

	    var table = $('#referenceTable').DataTable( {
	    	serverSide: true,
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
	        ajax: "{{ url('maintenance/reference') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: "company" },
	            { data: "type" },
	            { data: "address" },
	            { data: "contact" },
	            { data: "email" },
	            { data: "description" },
	            { data: function(callback){
	            	return `
	            			<a href="{{ url("maintenance/reference") }}` + '/' + callback.id + '/edit' + `" class="btn btn-sm btn-default">Edit</a>
	            			<button type="button" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Removing" data-id="`+callback.id+`" class="remove btn btn-sm btn-danger">Remove</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a href="{{ url('maintenance/reference/create') }}" id="new" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>  Add
 			</a>
		`);

		$('#referenceTable').on('click','button.remove',function(){
		  	var removeButton = $(this);
			removeButton.button('loading');
			$.ajax({
				type: 'delete',
				url: '{{ url("maintenance/reference") }}' + '/' + $(this).data('id'),
				dataType: 'json',
				success: function(response){
					if(response == 'success')
						swal("Operation Success",'Reference removed.',"success")
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
