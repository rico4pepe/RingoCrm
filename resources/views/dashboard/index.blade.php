@extends('layouts/layout')
@section('title', 'Dashboard')
@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Dahboard Management</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item text-muted">Executive Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <!--begin::Card for KPI Cards-->
                <div class="card mb-4">
                    <div class="card-header border-0 pt-6">
                        <div class="d-flex align-items-center">
                            <label for="kt_dashboard_date_range" class="me-2">Select Date Range:</label>
                            <input type="text" id="kt_dashboard_date_range" class="form-control form-control-sm" placeholder="Pick a date range" />
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="kpi-cards-container" class="row">
                            <!-- Dynamically generated KPI cards will be injected here -->
                        </div>
                    </div>


                     <!--begin::Charts Section-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Ticket Trends</div>
                            <div class="card-body">
                                <canvas id="ticketTrendsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Ticket Status Distribution</div>
                            <div class="card-body">
                                <canvas id="ticketStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Tickets by Category</div>
                            <div class="card-body">
                                <canvas id="ticketCategoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Workload by Staff</div>
                            <div class="card-body">
                                <canvas id="workloadByStaffChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">SLA compliance</div>
                            <div class="card-body">
                                <canvas id="slaComplianceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Charts Section-->

                <!-- Recent Critical Tickets Table -->
<div class="card mt-5">
    <div class="card-header">
        <h3 class="card-title">Recent Critical Tickets</h3>
    </div>
    <div class="card-body">
        <table id="recentCriticalTickets" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Title</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>SLA Due</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be injected dynamically -->
            </tbody>
        </table>
    </div>
</div>

                <!-- Top SLA Violations Table -->
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="card-title">Top SLA Violations</h3>
                    </div>
                    <div class="card-body">
                        <table id="topSlaViolations" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Title</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>SLA Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be injected dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Supervisor Overview Table -->
<div class="card mt-5">
    <div class="card-header">
        <h3 class="card-title">Supervisor Overview</h3>
    </div>
    <div class="card-body">
        <table id="supervisorOverview" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Supervisor</th>
                    <th>Team</th>
                    <th>Total Tickets</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be injected dynamically -->
            </tbody>
        </table>
    </div>
</div>


                </div>
                <!--end::Card-->


            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection

@push('scripts')
<!-- Include Metronic Date Picker Script -->
<script>
    $(document).ready(function() {
    const today = new Date().toLocaleDateString('en-CA');
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    const yesterdayDate = yesterday.toLocaleDateString('en-CA');
    var chartInstances = {};

    // Initialize Metronic date picker for selecting date range
    $('#kt_dashboard_date_range').flatpickr({
        mode: 'range',
        dateFormat: 'Y-m-d',
        defaultDate: [yesterdayDate, today],
        onChange: function(selectedDates) {
            const startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : '';
            const endDate = selectedDates[1] ? selectedDates[1].toLocaleDateString('en-CA') : '';

            if (startDate && endDate) {
                fetchFilteredData(startDate, endDate);
                fetchChartData(startDate, endDate);
            }
        }
    });

    function fetchFilteredData(startDate, endDate) {
        let adjustedEndDate = new Date(endDate);
        adjustedEndDate.setHours(23, 59, 59, 999);
        let formattedEndDate = adjustedEndDate.toISOString().split('T')[0];

        $.ajax({
            url: '{{ route("dashboard.fetchKPIData") }}',
            method: 'GET',
            data: { start_date: startDate, end_date: formattedEndDate },
            success: function(response) {
                $('#kpi-cards-container').html(response.kpi_cards);
            },
            error: function() {
                alert('Error fetching KPI data.');
            }
        });
    }

    function fetchChartData(startDate, endDate) {
        $.ajax({
            url: '{{ route("dashboard.fetchChartData") }}',
            method: 'GET',
            data: { start_date: startDate, end_date: endDate },
            success: function(response) {
                console.log(response); // Log the response data
                updateCharts(response);
            },
            error: function() {
                alert('Error fetching chart data.');
            }
        });
    }


    function fetchTicketTables(startDate, endDate) {
    $.ajax({
        url: '{{ route("dashboard.fetchTicketTables") }}',  // Ensure you have the correct route
        method: 'GET',
        data: { start_date: startDate, end_date: endDate },
        success: function(response) {
            console.log(response); // Log the response data for debugging

            // Initialize DataTables for recentCriticalTickets
            $('#recentCriticalTicketsTable').DataTable({
                data: response.recentCriticalTickets.data,  // Paginated data from backend
                columns: [
                    { data: 'ticket_id' },
                    { data: 'title' },
                    { data: 'assigned_to' },
                    { data: 'status' },
                    { data: 'sla_due' }
                ],
                searching: true,
                ordering: true,
                paging: true,
                info: true,
            });

            // Initialize DataTables for topSLAViolations
            $('#topSLAViolationsTable').DataTable({
                data: response.topSLAViolations.data,  // Paginated data from backend
                columns: [
                    { data: 'ticket_id' },
                    { data: 'title' },
                    { data: 'assigned_to' },
                    { data: 'status' },
                    { data: 'sla_due' }
                ],
                searching: true,
                ordering: true,
                paging: true,
                info: true,
            });

            // Initialize DataTables for supervisorOverview
            // $('#supervisorOverviewTable').DataTable({
            //     data: response.supervisorOverview,  // Data from backend response
            //     columns: [
            //         { data: 'supervisor_name' },
            //         { data: 'team_members' },
            //         { data: 'ticket_count' }
            //     ],
            //     searching: true,
            //     ordering: true,
            //     paging: true,
            //     info: true,
            // });
        },
        error: function() {
            alert('Error fetching ticket tables data.');
        }
    });
}


    // Store chart instances globally to track them
var chartInstances = {};

function updateCharts(data) {
    // Function to destroy existing chart before creating a new one
    function destroyChart(chartId) {
        if (chartInstances[chartId]) {
            chartInstances[chartId].destroy();
        }
    }

    // 🎟️ Ticket Trends (Line Chart)
    var ticketTrendsCtx = document.getElementById('ticketTrendsChart').getContext('2d');
    destroyChart('ticketTrendsChart'); // Destroy if it exists
    chartInstances['ticketTrendsChart'] = new Chart(ticketTrendsCtx, {
        type: 'line',
        data: {
            labels: data.ticketTrends.map(item => item.date),
            datasets: [{
                label: 'Tickets',
                data: data.ticketTrends.map(item => item.total_tickets),
                borderColor: 'blue',
                fill: false
            }]
        }
    });

    // 📊 Ticket Status Distribution (Pie Chart)
    var statusCtx = document.getElementById('ticketStatusChart').getContext('2d');
    destroyChart('ticketStatusChart');
    chartInstances['ticketStatusChart'] = new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: ['Open', 'In Progress', 'Resolved', 'Closed', 'Overdue'],
            datasets: [{
                data: Object.values(data.ticketStatusDistribution),
                backgroundColor: ['#f39c12', '#3498db', '#2ecc71', '#e74c3c', '#e67e22']
            }]
        }
    });

    // 📌 Tickets by Category (Bar Chart)
    var categoryCtx = document.getElementById('ticketCategoryChart').getContext('2d');
    destroyChart('ticketCategoryChart');
    chartInstances['ticketCategoryChart'] = new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: data.ticketCategories.map(item => item.category),
            datasets: [{
                label: 'Tickets',
                data: data.ticketCategories.map(item => item.total),
                backgroundColor: 'purple'
            }]
        }
    });

    // 👨‍💼 Workload by Staff (Bar Chart)
    var staffCtx = document.getElementById('workloadByStaffChart').getContext('2d');
    destroyChart('workloadByStaffChart');
    chartInstances['workloadByStaffChart'] = new Chart(staffCtx, {
        type: 'bar',
        data: {
            labels: data.workloadByStaff.map(item => item.staff_name),
            datasets: [{
                label: 'Tickets',
                data: data.workloadByStaff.map(item => item.total_tickets),
                backgroundColor: 'green'
            }]
        }
    });

    // ⏳ SLA Compliance (Gauge Chart)
var slaCtx = document.getElementById('slaComplianceChart').getContext('2d');
destroyChart('slaComplianceChart');
chartInstances['slaComplianceChart'] = new Chart(slaCtx, {
    type: 'doughnut',  // Using doughnut as a gauge
    data: {
        labels: ['Compliance', 'Non-Compliance'],
        datasets: [{
            data: [data.slaCompliance, 100 - data.slaCompliance], // SLA %
            backgroundColor: ['#2ecc71', '#e74c3c']
        }]
    },
    options: {
        rotation: 1 * Math.PI,  // Start from bottom
        circumference: 1 * Math.PI,  // Half-circle effect
        cutout: '80%',  // Makes it look like a gauge
        plugins: {
            tooltip: {
                enabled: false // Hides tooltip for cleaner look
            }
        }
    }
});

}


    // Fetch data for the default date range (Yesterday to Today) on page load
    fetchTicketTables(yesterdayDate, today);
    fetchFilteredData(yesterdayDate, today);
    fetchChartData(yesterdayDate, today);
});

</script>
@endpush
