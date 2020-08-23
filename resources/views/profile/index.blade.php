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
                <h3 class="card-title">Update Profile</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
               {!! Form::model($user, ['method' => 'POST', 'route' => ['admin.profile.update', $user->id]]) !!}
              
              <div class="card-body">
                 
              <div class="col-xs-12 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name','Name *', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   
                    <em class="invalid-feedback" id="name-error">

                    </em>
                </div>


                <div class="col-xs-12 form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                    {!! Form::label('username','Username *', ['class' => 'control-label']) !!}
                    {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <em class="invalid-feedback" id="username-error"></em>
                </div>
       
                <div class="col-xs-12 form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    {!! Form::label('email','Email *', ['class' => 'control-label']) !!}
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <em class="invalid-feedback" id="email-error"></em>
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
