@extends('Ragnarok::layouts.auth')

@section('style')
    <link rel="stylesheet" href="{{asset('css/alfredoem/ragnarok/register.css')}}"/>
@endsection

<!-- Main Content -->
@section('content')
<div class="container">
  <div class="row">
  	<div class="col-md-6 col-md-offset-3">
          <form class="form-horizontal" method="POST" action="{{url('/register')}}">
          {!! csrf_field() !!}
          <fieldset>
            <div id="legend">
              <h2 class="form-signin-heading">Register</h2>
            </div>
            @include('Ragnarok::common.errors', ['form' => 'default'])
            <div class="control-group">
              <label class="control-label" for="firstName">First name</label>
              <div class="controls">
                <input type="text" id="firstName" name="firstName" class="form-control input-lg" value="{{ old('firstName') }}">
                <p class="help-block">Please provide your First name</p>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="lastName">Last name</label>
              <div class="controls">
                <input type="text" id="lastName" name="lastName" class="form-control input-lg" value="{{ old('lastName') }}">
                <p class="help-block">Please provide your Last name</p>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="email">E-mail</label>
              <div class="controls">
                <input type="email" id="email" name="email" class="form-control input-lg" value="{{ old('email') }}">
                <p class="help-block">Please provide your E-mail</p>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="password">Password</label>
              <div class="controls">
                <input type="password" id="password" name="password" class="form-control input-lg">
                <p class="help-block">Password should be at least 6 characters</p>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="password_confirm">Password (Confirm)</label>
              <div class="controls">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control input-lg">
                <p class="help-block">Please confirm password</p>
              </div>
            </div>

            <div class="control-group">
              <!-- Button -->
              <div class="controls">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
              </div>
            </div>
          </fieldset>
        </form>

    </div>
  </div>
</div>
@endsection

@section('script')@endsection