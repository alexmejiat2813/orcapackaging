@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="pagetitle">
        <h1>Tools</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Tools</li>
            </ol>
        </nav>
        <div class="mb-4">
      <button id="openPopup1" class="btn btn-primary me-2">Open Popup 1</button>
      <button id="openPopup2" class="btn btn-secondary me-2">Open Popup 2</button>
      <button id="openPopup3" class="btn btn-success me-2">Open Popup 3</button>
      <button id="openPopup4" class="btn btn-danger">Open Popup 4</button>
    </div>
    </div>
      <!-- Popups -->
  <div id="popup1" style="display: none;">
    <div>
      <h4>Content for Popup 1</h4>
      <p>This is the content of the first popup.</p>
    </div>
  </div>

  <div id="popup2" style="display: none;">
    <div>
      <h4>Content for Popup 2</h4>
      <p>This is the content of the second popup.</p>
    </div>
  </div>

  <div id="popup3" style="display: none;">
    <div>
      <h4>Content for Popup 3</h4>
      <p>This is the content of the third popup.</p>
    </div>
  </div>

  <div id="popup4" style="display: none;">
    <div>
      <h4>Content for Popup 4</h4>
      <p>This is the content of the fourth popup.</p>
    </div>
  </div>
@endsection
