@extends('layouts.app')

@section('content')
  <div class="container">
    <h1>Bienvenido Admin</h1>
    <p>Panel con herramientas administrativas.</p>
    @if(Auth::check())
    Bienvenido {{ Auth::user()->Users_Name }}
@else
    <a href="/login">Login</a>
@endif
  </div>
@endsection
