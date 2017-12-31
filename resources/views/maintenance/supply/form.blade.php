
<div class="col-md-12">
	<div class="form-group">
		{{ Form::label('Name') }}
		{{ Form::text('name',isset($supply->name) ? $supply->name : Input::old('name'),[
			'id' => 'name',
			'class' => 'form-control'
		]) }}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
		{{ Form::label('Details') }}
		{{ Form::text('details',isset($supply->details) ? $supply->details : Input::old('details'),[
			'id' => 'details',
			'class' => 'form-control'
		]) }}
	</div>
</div>