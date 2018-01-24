<div class="form-group">
	<div class="col-md-12">
	  {{ Form::label('code','Code') }}
	  {{ Form::text('code',isset($product->code) ? $product->code : Input::old('code'),[
	  	'id' => 'code',
	    'class'=>'form-control',
	    'placeholder'=>'Code'
	  ]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-md-12">
	  {{ Form::label('category','Category Name') }}
	  {{ Form::select('category', $category, isset($product->category_id) ? $product->category_id : Input::old('category'),[
	  	'id' => 'category',
	    'class'=>'form-control'
	  ]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-md-12">
	  {{ Form::label('supply','Supply Name') }}
	  {{ Form::select('supply', $supply, isset($product->supply_id) ? $product->supply_id : Input::old('supply'),[
	  	'id' => 'supply',
	    'class'=>'form-control'
	  ]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-md-12">
	  {{ Form::label('unit','Unit Name') }}
	  {{ Form::select('unit', $unit, isset($product->unit_id) ? $product->unit_id : Input::old('unit'),[
	  	'unit',
	    'class'=>'form-control'
	  ]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-md-12">
	  {{ Form::label('cost','Cost') }}
	  {{ Form::text('cost',isset($product->cost) ? $product->cost : Input::old('cost'),[
	    'class'=>'form-control',
	    'placeholder'=>'Cost'
	  ]) }}
	</div>
</div>
<script>
	$(document).ready(function(){

		category = $('#category')
		supply = $('#supply')
		unit = $('#unit')
		code = $('#code')

		$('#category').on('change',function(){
			generateCode()
		})

		$('#supply').on('change',function(){
			generateCode()
		})

		$('#unit').on('change',function(){
			generateCode()
		})

		generateCode()

		function generateCode()
		{

			first = category.find(':selected').text().substr(0,3)
			second = supply.find(':selected').text().substr(0,3)
			third = unit.find(':selected').text().substr(0,3)

			code.val( function(){
				ret_val = ''
				if(first != 'None' && first != '' && first != 'Non') ret_val += first
				if(second != 'None' && second != '' && second != 'Non') ret_val += second
				if(third != 'None' && third != '' && third != 'Non') ret_val += third
					return ret_val
			})
		}
	})
</script>