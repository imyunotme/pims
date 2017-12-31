<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('type','Type') }}
    <select id="type" name="type" value="{{ isset($reference->type) ? $reference->type : old('type') }}" class="form-control">
      @foreach(App\Reference::$types as $type)
      <option value="{{ $type }}">{{ ucfirst($type) }}</option>
      @endforeach
    </select>
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('lastname','Last Name') }}
    {{ Form::text('lastname',isset($reference->lastname) ? $reference->lastname : old('lastname'),[
      'class'=>'form-control',
      'placeholder'=>'Last Name'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('firstname','First Name') }}
    {{ Form::text('firstname',isset($reference->firstname) ? $reference->firstname : old('firstname'),[
      'class'=>'form-control',
      'placeholder'=>'First Name'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('middlename','Middle Name') }}
    {{ Form::text('middlename',isset($reference->middlename) ? $reference->middlename : old('middlename'),[
      'class'=>'form-control',
      'placeholder'=>'Middle Name'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('company','Company Name') }}
    {{ Form::text('company',isset($reference->company) ? $reference->company : old('company'),[
      'class'=>'form-control',
      'placeholder'=>'Company Name'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('address','Address') }}
    {{ Form::textarea('address',isset($reference->address) ? $reference->address : old('address') ,[
      'class'=>'form-control',
      'placeholder'=>'Suppliers Address',
      'rows' => 4
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('contact','Contact Number') }}
    {{ Form::text('contact', isset($reference->contact) ? $reference->contact : old('contact') ,[
      'class'=>'form-control',
      'placeholder'=>'Contact Number'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('email','Email Address') }}
    {{ Form::email('email', isset($reference->email) ? $reference->email : old('email') ,[
      'class'=>'form-control',
      'placeholder'=>'Email'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('description','Description') }}
    {{ Form::text('description', isset($reference->description) ? $reference->description : old('description') ,[
      'class'=>'form-control',
      'placeholder'=>'Description'
    ]) }}
  </div>
</div>