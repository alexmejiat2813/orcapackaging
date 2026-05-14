@props(['type', 'id', 'notes'])

<div class="card shadow-sm mb-4">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span>Notes ({{ $notes->count() }})</span>
    </div>
    <div class="card-body">

        @if(session('note_success'))
            <div class="alert alert-success py-1 small">{{ session('note_success') }}</div>
        @endif

        {{-- Existing notes --}}
        @forelse($notes as $note)
        <div class="border rounded p-2 mb-2 bg-light small">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="fw-bold">{{ $note->user->Users_Name ?? 'User #'.$note->user_id }}</span>
                    <span class="text-muted ms-2">{{ $note->created_at->format('Y-m-d H:i') }}</span>
                </div>
                @if(Auth::id() == $note->user_id)
                <form method="POST" action="{{ route('notes.destroy', $note->id) }}" onsubmit="return confirm('Delete this note?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-link btn-sm text-danger p-0">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @endif
            </div>
            <p class="mb-0 mt-1">{{ $note->body }}</p>
        </div>
        @empty
            <p class="text-muted small mb-3">No notes yet.</p>
        @endforelse

        {{-- Add note form --}}
        <form method="POST" action="{{ route('notes.store') }}" class="mt-3">
            @csrf
            <input type="hidden" name="notable_type" value="{{ $type }}">
            <input type="hidden" name="notable_id" value="{{ $id }}">
            <div class="mb-2">
                <textarea name="body" class="form-control form-control-sm @error('body') is-invalid @enderror"
                    rows="2" placeholder="Add a note..." maxlength="2000" required>{{ old('body') }}</textarea>
                @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-plus"></i> Add Note
            </button>
        </form>
    </div>
</div>
