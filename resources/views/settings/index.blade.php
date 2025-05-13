@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Hello, {{ $user->name }}</h2>
    <p>Configure your account settings:</p>

    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="{{ route('settings.profile') }}" class="btn btn-outline-primary btn-block">Edit Profile</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('settings.password') }}" class="btn btn-outline-secondary btn-block">Change Password</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('settings.notifications') }}" class="btn btn-outline-success btn-block">Notification Settings</a>
        </div>
    </div>
</div>
@endsection
