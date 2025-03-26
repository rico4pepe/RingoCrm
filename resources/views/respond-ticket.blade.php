@extends('layouts/layout')
@section('title', 'Response Ticket')
@section('content')
<div class="container">
    <h3>Ticket: {{ $ticket->title }}</h3>
    
    <!-- Ticket Details -->
    <div class="mb-4">
        <label class="fs-6 fw-semibold mb-2">Subject</label>
        <input type="text" class="form-control form-control-solid w-100" value="{{ $ticket->title }}" readonly />
    </div>

    <div class="mb-4">
        <label class="fs-6 fw-semibold mb-2">Description</label>
        <textarea class="form-control form-control-solid w-100" rows="4" readonly>{{ $ticket->mesasge }}</textarea>
    </div>

   


    <hr>

    <!-- Display Replies -->
    <h4>Replies</h4>
    <div class="replies">
        @foreach($ticket->replies as $reply)
            <div class="border p-3 mb-2">
                <strong>{{ $reply->user->name }}</strong> ({{ $reply->created_at->diffForHumans() }})
                <p>{{ $reply->message }}</p>
            </div>
        @endforeach
    </div>

    <hr>

    <!-- Reply Form -->
    <h4>Reply to Ticket</h4>
    <form action="{{ route('tickets.reply', $ticket->id) }}" method="POST">
        @csrf

        @if(str_starts_with(Auth::user()->name, 'Ringo-'))
        <div class="mb-4">
            <label class="fs-6 fw-semibold mb-2">Status</label>
            <select class="form-select form-select-solid" name="status">
                <option value="open" @if($ticket->status == 'open') selected @endif>Open</option>
                <option value="in_progress" @if($ticket->status == 'in_progress') selected @endif>In Progress</option>
                <option value="resolved" @if($ticket->status == 'resolved') selected @endif>Resolved</option>
                <option value="closed" @if($ticket->status == 'closed') selected @endif>Closed</option>
            </select>
        </div>
        @endif
        <div class="mb-3">
            <textarea name="message" class="form-control" rows="3" placeholder="Type your reply here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Reply</button>
    </form>
</div>
@endsection
