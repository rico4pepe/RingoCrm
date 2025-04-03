<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    //

    public function index()
    {
        return view('dashboard.index');
    }


    public function fetchKpiData(Request $request)
{
    $query = Ticket::query();

    // If no date range is provided, default to yesterday to today
    $startDate = Carbon::parse($request->start_date)->startOfDay()->toDateTimeString();
    $endDate = Carbon::parse($request->end_date)->endOfDay()->toDateTimeString();

    $query = Ticket::whereBetween('created_at', [$startDate, $endDate]);

    // Filter tickets based on date range
    $query = Ticket::whereBetween('created_at', [$startDate, $endDate]);





    // Calculate KPIs
    $totalTickets = $query->count();
   // Count tickets for each status
   $openTickets = (clone $query)->where('status', 'open')->count();
   $inProgressTickets = (clone $query)->where('status', 'in_progress')->count();
   $resolvedTickets = (clone $query)->where('status', 'resolved')->count();
   $slaBreaches = (clone $query)->where('sla_due_at', '<', now())->count();
   $closedTickets = (clone $query)->where('status', 'closed')->count();

   // Calculate average resolution time for resolved/closed tickets
   $averageResolutionTime = (clone $query)->whereIn('status', ['resolved', 'closed'])
                                         ->avg(DB::raw('TIMESTAMPDIFF(HOUR, created_at, updated_at)'));


    // Log queries for debugging
    Log::info('Ticket KPI Queries', [
        'total_tickets' => $totalTickets,
        'open_tickets' => $openTickets,
        'in_progress_tickets' => $inProgressTickets,
        'resolved_tickets' => $resolvedTickets,
        'sla_breaches' => $slaBreaches,
        'closed_tickets' => $closedTickets,
        'average_resolution_time' => $averageResolutionTime,
        'query' => $query->toSql(),
        'bindings' => $query->getBindings()
    ]);

    // Return KPI cards as a JSON response
    $kpiCards = view('dashboard.partials.kpi_cards', compact(
        'totalTickets', 'openTickets', 'inProgressTickets', 'resolvedTickets', 'slaBreaches', 'closedTickets', 'averageResolutionTime'
    ))->render();

    return response()->json(['kpi_cards' => $kpiCards]);
}



private function getTicketsPerStaff($query)
{
    // Example: Return the number of tickets per staff (can be adjusted based on your needs)
    return $query->select('assigned_user_id', DB::raw('count(*) as total_tickets'))
                 ->groupBy('assigned_user_id')
                 ->orderByDesc('total_tickets')
                 ->get();
}


public function fetchChartData(Request $request)
{
    // Set default date range (Last 30 days)
    $startDate = $request->start_date ?? now()->subDays(30)->format('Y-m-d');
    $endDate = $request->end_date ?? now()->format('Y-m-d');

    // Convert dates to proper format
    $startDateTime = Carbon::parse($startDate)->startOfDay()->toDateTimeString();
    $endDateTime = Carbon::parse($endDate)->endOfDay()->toDateTimeString();

    // 📈 Ticket Trends Over Time (Line Chart)
    $ticketTrends = Ticket::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total_tickets'))
        ->whereBetween('created_at', [$startDateTime, $endDateTime])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get();

    // 📊 Ticket Distribution by Status (Pie Chart) (Fixed: Added Date Filtering)
    $ticketStatusDistribution = [
        'open' => Ticket::whereBetween('created_at', [$startDateTime, $endDateTime])->where('status', 'open')->count(),
        'in_progress' => Ticket::whereBetween('created_at', [$startDateTime, $endDateTime])->where('status', 'in_progress')->count(),
        'resolved' => Ticket::whereBetween('created_at', [$startDateTime, $endDateTime])->where('status', 'resolved')->count(),
        'closed' => Ticket::whereBetween('created_at', [$startDateTime, $endDateTime])->where('status', 'closed')->count(),
        'overdue' => Ticket::whereBetween('created_at', [$startDateTime, $endDateTime])->where('sla_due_at', '<', now())->count(),
    ];

    // 📌 Tickets by Category (Bar Chart) (Fixed: Added Date Filtering)
    $ticketCategories = Ticket::select('categories', DB::raw('count(*) as total'))
        ->whereBetween('created_at', [$startDateTime, $endDateTime])
        ->groupBy('categories')
        ->get();

    // 👨‍💼 Workload by Staff (Bar Chart) (Fixed: Added Date Filtering)
    $workloadByStaff = Ticket::select('users.name as staff_name', DB::raw('count(*) as total_tickets'))
    ->join('users', 'tickets.assigned_user_id', '=', 'users.id') // Join with users table
    ->whereBetween('tickets.created_at', [$startDateTime, $endDateTime])
    ->groupBy('users.name')
    ->get();

   // ⏳ SLA Compliance (Gauge Chart) (Fixed: Consider Tickets within the Date Range)
$totalTickets = Ticket::whereBetween('created_at', [$startDateTime, $endDateTime])->count();

// Consider tickets where the status is 'Resolved' or 'Closed' and check if their SLA compliance
$resolvedWithinSLA = Ticket::whereBetween('created_at', [$startDateTime, $endDateTime])
    ->whereIn('status', ['resolved', 'closed'])  // Check status for resolved or closed
    ->whereColumn('sla_due_at', '>=', 'created_at') // Ensure the SLA due date was not exceeded
    ->count();

$slaCompliance = $totalTickets > 0 ? ($resolvedWithinSLA / $totalTickets) * 100 : 0;

    // 🔍 Log Queries for Debugging
    Log::info('Chart Data Queries', [
        'ticketTrends' => $ticketTrends,
        'ticketStatusDistribution' => $ticketStatusDistribution,
        'ticketCategories' => $ticketCategories,
        'workloadByStaff' => $workloadByStaff,
        'slaCompliance' => $slaCompliance
    ]);

    return response()->json([
        'ticketTrends' => $ticketTrends,
        'ticketStatusDistribution' => $ticketStatusDistribution,
        'ticketCategories' => $ticketCategories,
        'workloadByStaff' => $workloadByStaff,
        'slaCompliance' => $slaCompliance
    ]);
}


public function fetchTicketTables(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Recent Critical Tickets (High Priority & Nearing SLA Due)
        $recentCriticalTickets = Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'closed')
            ->orderBy('sla_due_at', 'asc')
            ->take(10)
            ->get(['id', 'title', 'assigned_user_id', 'status', 'sla_due_at']);

        //  Top SLA Violations (Tickets that have exceeded SLA)
        $topSLAViolations = Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'closed')
            ->whereColumn('sla_due_at', '<', 'created_at')
            ->orderBy('sla_due_at', 'asc')
            ->take(10)
            ->get(['id', 'title', 'assigned_user_id', 'status', 'sla_due_at']);

        // Supervisor Overview (Supervisors with team ticket counts)
        // $supervisorOverview = User::where('role', 'supervisor')
        //     ->with(['team' => function ($query) use ($startDate, $endDate) {
        //         $query->withCount(['tickets' => function ($query) use ($startDate, $endDate) {
        //             $query->whereBetween('created_at', [$startDate, $endDate]);
        //         }]);
        //     }])->get(['id', 'name']);


        // 3️⃣ Supervisor Overview (Supervisors with team ticket counts)
// $supervisorOverview = User::whereNotNull('supervisor_id') // Find all users who are assigned as supervisors
//     ->select('supervisor_id', 'supervisor_id') // Select the supervisor_id field to group by
//     ->withCount(['tickets' => function ($query) use ($startDate, $endDate) {
//         // Count the tickets assigned to users within the given date range
//         $query->whereBetween('created_at', [$startDate, $endDate]);
//     }])
//     ->groupBy('supervisor_id') // Group the results by supervisor_id
//     ->get();

// // Now, you can process $supervisorOverview to gather details about the supervisor and their team
// $supervisorDetails = $supervisorOverview->map(function ($supervisor) {
//     return [
//         'supervisor_id' => $supervisor->supervisor_id,
//         'ticket_count' => $supervisor->tickets_count,  // The number of tickets assigned to this supervisor's team
//         'supervisor_name' => User::find($supervisor->supervisor_id)->name,  // Get the supervisor's name
//         'team_members' => User::where('supervisor_id', $supervisor->supervisor_id)->pluck('name')  // Get the team members under the supervisor
//     ];
// });


    // 🔍 Log Queries for Debugging
    Log::info('Chart Data Queries', [
        'recentCriticalTickets' => $recentCriticalTickets,
        'topSLAViolations' => $topSLAViolations,
    ]);

return response()->json([
    'recentCriticalTickets' => $recentCriticalTickets,
    'topSLAViolations' => $topSLAViolations,
    //'supervisorOverview' => $supervisorDetails,  // Add the supervisor data here
]);

    }


}
