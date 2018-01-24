<div class="form-group">
	<div class="col-md-12">
	  {{ Form::label('name','Category Name') }}
	  {{ Form::text('name',isset($category->name) ? $category->name : Input::old('name'),[
	    'class'=>'form-control',
	    'placeholder'=>'Category Name'
	  ]) }}
	</div>
</div>