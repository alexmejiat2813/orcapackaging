@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 500px">
        <h3 class="mb-4 text-center">🕒 Employee Clock In / Out</h3>

        <!-- Display success or error messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Clock In / Out Form -->
        <form method="POST" action="{{ route('hr.clock.process') }}">
            @csrf

            <!-- Employee Badge Field -->
            <div class="mb-3">
                <label for="barcode" class="form-label">Scan Your Badge</label>
                <input type="text" name="barcode" id="barcode" class="form-control" autofocus required>
            </div>

            <!-- Optional Comment Field -->
            <div class="mb-3">
                <label for="note" class="form-label">Comment (optional)</label>
                <textarea name="note" id="note" class="form-control" rows="2"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">📍 Register Entry/Exit</button>
        </form>
    </div>
@endsection
