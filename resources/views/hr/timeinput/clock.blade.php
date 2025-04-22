@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px">
    <h3 class="mb-4 text-center">🕒 Employee Clock In / Out</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('hr.clock.process') }}">
        @csrf

        <div class="mb-3">
            <label for="barcode" class="form-label">Scan Your Badge</label>
            <input type="text" name="barcode" id="barcode" class="form-control" autofocus required>
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Comment (optional)</label>
            <textarea name="note" id="note" class="form-control" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">📍 Register Entry/Exit</button>
    </form>
</div>
@endsection
