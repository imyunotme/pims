@extends('backpack::layout')

@section('header')
	<section class="content-header">
    <legend><h3 class="text-muted">Account Creation</h3></legend>
      <ol class="breadcrumb">
          <li><a href="{{ url('account') }}">Account</a></li>
          <li class="active">Create</li>
      </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box" style="padding:10px;">
    <div class="box-body">
      @if (count($errors) > 0)
         <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <ul class="list-unstyled" style='margin-left: 10px;'>
                  @foreach ($errors->all() as $error)
                      <li class="text-capitalize">{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
      {{ Form::open([
        'class' =>'form-horizontal',
        'id'=>'registrationForm',
        'route'=>'account.store'
      ]) }}
      <div class="col-md-6">
        <legend>Personal Information</legend>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('firstname','Firstname') }}
          {{ Form::text('firstname',Input::old('firstname'),[
              'class' => 'form-control',
              'id' => 'firstname',
              'placeholder' => 'First name'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('middlename','Middlename') }}
          {{  Form::text('middlename',Input::old('middlename'),[
                'class' => 'form-control',
                'id' => 'middlename',
                'placeholder' => 'Middle name'
              ])}}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('lastname','Lastname') }}
           {{ Form::text('lastname',Input::old('lastname'),[
              'class' => 'form-control',
              'id' => 'lastname',
              'placeholder' => 'Last name'
           ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('email','Email') }}
          {{ Form::text('email',Input::old('email'),[
              'class' => 'form-control',
              'id' => 'email',
              'placeholder' => 'Email'
          ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('office','Office') }}
          {{ Form::select('office',$office,Input::old('office'),[
            'class'=>'form-control'
          ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('position','Position') }}
          {{ Form::text('position',Input::old('position'),[
            'class'=>'form-control',
            'placeholder' => 'Position'
          ]) }}
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <legend>Account Information</legend>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('username','Username') }}
          {{ Form::text('username',Input::old('username'),[
              'class' => 'form-control',
              'id' => 'username',
              'placeholder' => 'Username'
          ])}}
          <p class="text-muted" style="font-size: 10px;"><span class="text-success">Note:</span>The Username will be used for logging in.</p>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('access','Access Type') }}
          {{ Form::select('access',[
            '0' => 'Administrator',
            '1' => 'AMO',
            '2' => 'Accounting',
            '3' => 'Colleges',
          ],Input::old('access'),[
            'class'=>'form-control'
          ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('password','Password') }}
          {{ Form::password('password',[
              'class' => 'form-control',
              'id' => 'password',
              'placeholder' => 'Password'
          ]) }}
            <p class="text-danger" style="font-size: 10px;"><span class="text-success">Note:</span>This field must be atleast eight(8) characters.</p>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
          {{ Form::label('confirm','Confirm Password') }}
          {{ Form::password('confirm',[
              'class' => 'form-control',
              'id' => 'confirm',
              'placeholder' => 'Confirm Password'
          ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            <button class="btn btn-lg btn-primary btn-block" id="register" type="submit" value="Create"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Create</button>
          </div>
        </div>
      </div><!-- /.box-body -->
    {{ Form::close() }}
  </div><!-- /.box -->

@endsection

@section('after_scripts')
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>

    <script>
      $(document).ready(function(){
        @if( Session::has("success-message") )
            new PNotify({
                title: "Success!",
                text: "{{ Session::pull('success-message') }}",
                type: "success"
            });
        @endif
        @if( Session::has("error-message") )
            new PNotify({
                title: "Oops...",
                text: "{{ Session::pull('error-message') }}",
                type: "warning"
            });
        @endif

        $( "#registrationForm" ).validate( {
          rules: {
            firstname: "required",
            lastname: "required",
            username: {
              required: true,
              minlength: 4
            },
            password: {
              required: true,
              minlength: 8
            },
            confirm: {
              required: true,
              minlength: 8,
              equalTo: "#password"
            },
            contactnumber: {
              required: true,
              minlength: 11,
              maxlength: 11
            },
            email: {
              required: true,
              email: true
            }
          },
          messages: {
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            username: {
              required: "Please enter a username",
              minlength: "Your username must consist of at least 4 characters"
            },
            password: {
              required: "Please provide a password",
              minlength: "Your password must be at least 8 characters long"
            },
            confirm: {
              required: "Please provide a password",
              minlength: "Your password must be at least 8 characters long",
              equalTo: "Please enter the same password as above"
            },
            contactnumber: {
              required: "Please provide your contact number",
              minlength: "Contact Number must be 11-digit",
              minlength: "Contact Number must be 11-digit"
            },
            email: "Please enter a valid email address"
          },
          errorElement: "em",
          errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents( ".form-group" ).addClass( "has-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
              error.insertAfter( element.parent( "label" ) );
            } else {
              error.insertAfter( element );
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !element.next( "span" )[ 0 ] ) {
              $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
            }
          },
          success: function ( label, element ) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !$( element ).next( "span" )[ 0 ] ) {
              $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
            }
          },
          submitHandler: function(form) {
            // do other things for a valid form
            swal({
              title: "Are you sure?",
              text: "Account information will be added to the database.",
              type: "warning",
              showCancelButton: true,
              confirmButtonText: "Yes, submit it!",
              cancelButtonText: "No, cancel it!",
              closeOnConfirm: false,
              closeOnCancel: false
            },
            function(isConfirm){
              if (isConfirm) {
                form.submit();
              } else {
                swal("Cancelled", "Registration Cancelled", "error");
              }
            })
          },
          highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
            $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
          },
          unhighlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
            $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
          }
        } );
      });
    </script>
@endsection
