@extends('Ragnarok::layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/alfredoem/ragnarok/authenticate-material.min.css')}}"/>
@endsection

<!-- Main Content -->
@section('content')

    <h1>Ragnarok Security</h1>

    <form role="form" method="POST" action="{{ url('/auth/login') }}">
        {!! csrf_field() !!}
        @include('Ragnarok::common.errors', ['form' => 'default'])
        <div class="group">
            <input type="text" name="email" id="email" placeholder="Email"><span class="highlight"></span><span class="bar"></span>
        </div>

        <div class="group">
            <input type="password" name="password" id="password" placeholder="Password"><span class="highlight"></span><span class="bar"></span>
        </div>

        <div>
            <p><label><input type="checkbox" name="remember"> Remember me</label></p>
        </div>

        <button type="submit" class="button buttonBlue">Login</button>
    </form>

    <footer>
        <p>Feel Good Inc.</p>
    </footer>
@endsection

@section('script')@endsection
