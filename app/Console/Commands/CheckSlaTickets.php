<?php

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SlaExceededMail;

class CheckSlaTickets extends Command
{
    protected $signature = 'sla:check';
    protected $description = 'Check tickets that have exceeded SLA and notify the support team.';

    public function handle()
    {
        $now = Carbon::now();
        $tickets = Ticket::whereIn('status', ['open', 'in_progress'])
            ->where('sla_due_at', '<', $now)
            ->get();

        if ($tickets->isEmpty()) {
            Log::info('SLA Check: No overdue tickets found.');
            return;
        }

        foreach ($tickets as $ticket) {
            try {
                $emails = [];

                // Notify assigned staff
                if ($ticket->assignedUser) {
                    $emails[] = $ticket->assignedUser->email;
                }

                // Notify supervisor
                if ($ticket->supervisor) {
                    $emails[] = $ticket->supervisor->email;
                }

                // Notify dedicated support email
                $emails[] = 'support@company.com';

                // Ensure there are valid email addresses before sending
                $emails = array_filter($emails);

                if (!empty($emails)) {
                    Mail::to($emails)->send(new SlaExceededMail($ticket));

                    // Log successful email sending
                    Log::info("SLA Notification sent for Ticket #{$ticket->id} to: " . implode(', ', $emails));
                } else {
                    Log::warning("SLA Notification skipped for Ticket #{$ticket->id}: No valid email found.");
                }

            } catch (\Exception $e) {
                Log::error("SLA Notification failed for Ticket #{$ticket->id}: " . $e->getMessage());
            }
        }
    }
}
