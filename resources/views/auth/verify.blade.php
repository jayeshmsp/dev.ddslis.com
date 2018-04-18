@extends('layouts.auth')
@section('content')
<section id="wrapper" class="new-login-register">
    <div class="new-login-box">
        <div class="white-box">
            @include('layouts.partials.notifications')
            <h3 class="box-title m-b-0">Company verification </h3>
            <small>Enter your details below</small>
            <form class="form-horizontal new-lg-form" role="form" id="loginform" method="POST" action="{{ url("register/company_verify_store/$user->id") }}">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }} m-t-20">
                    <div class="col-xs-4">
                        <label>First Name</label>
                    </div>
                    <div class="col-xs-8">
                        <input placeholder="First name" id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name',$user->first_name) }}" autofocus>
                        @if ($errors->has('first_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }} m-t-20">
                    <div class="col-xs-4">
                        <label>Last Name</label>
                    </div>
                    <div class="col-xs-8">
                        <input placeholder="Last name" id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name',$user->last_name) }}" autofocus>
                        @if ($errors->has('last_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} m-t-20">
                    <div class="col-xs-4">
                        <label>Email Address</label>
                    </div>
                    <div class="col-xs-8">
                        <input placeholder="Email address" id="email" type="text" class="form-control" name="email" value="{{ old('email',$user->email) }}" disabled autofocus>
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }} m-t-20">
                    <div class="col-xs-4">
                        <label>Username</label>
                    </div>
                    <div class="col-xs-8">
                        <input placeholder="Username" id="username" type="text" class="form-control" name="username" value="{{ old('username',!empty($user->username) ? $user->username : $user->email) }}" required autofocus>
                        @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="col-xs-4">
                        <label>Password</label>
                    </div>
                    <div class="col-xs-8">
                        <input placeholder="Password" id="password" type="password" class="form-control" name="password" required>
                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-4">
                        <label>Confirm Password</label>
                    </div>
                    <div class="col-xs-8">
                        <input placeholder="Confirm Password" id="password" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection