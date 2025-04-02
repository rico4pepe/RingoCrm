<!DOCTYPE html>
<html>
<head>
    <title>SLA Violation Alert</title>
</head>
<body>
    <p>Hello,</p>

    <p>The following ticket has exceeded its SLA time and is still unresolved.</p>

    <p><strong>Title:</strong> {{ $ticket->title }}</p>
    <p><strong>Reference ID:</strong> {{ $ticket->reference_id }}</p>
    <p><strong>Severity:</strong> {{ $ticket->severity }}</p>
    <p><strong>Status:</strong> {{ $ticket->status }}</p>
    <p><strong>Message:</strong> {{ $ticket->message }}</p>

    <p>Please take immediate action.</p>

    <p>Thank you.</p>
</body>
</html>
