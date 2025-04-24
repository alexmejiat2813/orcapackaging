@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="container">
        @if(Auth::check())
            <h1>Welcome {{ Auth::user()->Users_Name }}</h1>
            <p>Administrative tools panel.</p>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endif
    </div>
@endsection
