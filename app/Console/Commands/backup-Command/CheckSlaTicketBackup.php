<?php
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use App\Mail\SlaExceededMail;
use Illuminate\Support\Facades\Log;


class CheckSlaTickets extends Command
{
    protected $signature = 'sla:check';
    protected $description = 'Check tickets that have exceeded SLA and notify the support team.';

    public function handle()
    {
        $now = Carbon::now();

        Ticket::whereIn('status', ['open', 'in_progress'])
            ->where('sla_due_at', '<', $now)
            ->chunk(50, function ($tickets) {
                foreach ($tickets as $ticket) {
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

                    try {
                        if (!empty($emails)) {
                            $firstEmail = array_shift($emails);
                            Mail::to($firstEmail)->cc($emails)->send(new SlaExceededMail($ticket));
                        }
                    } catch (\Exception $e) {
                        Log::error("SLA Notification Failed for Ticket {$ticket->id}: " . $e->getMessage());
                    }
                }
            });
    }
}
