@extends('Ragnarok::layouts.auth')

@section('style')
    <link rel="stylesheet" href="{{asset('css/alfredoem/ragnarok/authenticate.css')}}"/>
@endsection

<!-- Main Content -->
@section('content')
<div class="container">
    <form class="form-signin" role="form" method="POST" action="{{ url('/login') }}">
        {!! csrf_field() !!}
        <h2 class="form-signin-heading">Please sign in</h2>
        @include('Ragnarok::common.errors', ['form' => 'default'])
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email address" autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="remember"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
</div>
@endsection

@section('script')@endsection
