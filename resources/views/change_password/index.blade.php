@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')


@section('content')

    <!-- Main content -->
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
               {!! Form::model($user, ['method' => 'POST', 'route' => ['admin.password.update', $user->id]]) !!}
              
              <div class="card-body">

                  <div class="col-xs-12 form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                      {!! Form::label('password','Old Password *', ['class' => 'control-label']) !!}
                      {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '']) !!}
                       <em class="invalid-feedback" id="password-error"></em>
                  </div>

                 
                     <div class="col-xs-12 form-group {{ $errors->has('new_password') ? 'has-error' : '' }}">
                      {!! Form::label('new_password','New password *', ['class' => 'control-label']) !!}
                      {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => '']) !!}
                       <em class="invalid-feedback" id="new_password-error"></em>
                  </div>


                  <div class="col-xs-12 form-group {{ $errors->has('confirm_new_password') ? 'has-error' : '' }}">
                      {!! Form::label('confirm_new_password','Confirm Password *', ['class' => 'control-label']) !!}
                      {!! Form::password('Password Confirmation', ['class' => 'form-control', 'placeholder' => '']) !!}
                       <em class="invalid-feedback" id="confirm_new_password-error"></em>
                  </div>
        
        
    
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  {!! Form::submit('Submit', ['class' => 'btn btn-danger']) !!}
                </div>
               
               {!! Form::close() !!}
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->

@stop
