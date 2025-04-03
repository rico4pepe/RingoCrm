<!DOCTYPE html>
<html>
<head>
    <title>Ticket Assigned</title>
</head>
<body>
    <p>Hello,</p>

    <p>A new ticket has been assigned to you.</p>

    <p><strong>Title:</strong> {{ $ticket->title }}</p>
    <p><strong>Reference ID:</strong> {{ $ticket->reference_id }}</p>
    <p><strong>Severity:</strong> {{ $ticket->severity }}</p>
    <p><strong>Status:</strong> {{ $ticket->status }}</p>
    <p><strong>Message:</strong> {{ $ticket->message }}</p>

    <p>Please log in to the system to take the necessary action.</p>

    <p>Thank you.</p>
</body>
</html>
