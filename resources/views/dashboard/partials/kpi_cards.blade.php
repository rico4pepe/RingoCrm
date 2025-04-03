<div class="col-xl-3 col-sm-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-gray-600">Total Tickets</div>
                <div class="ms-auto fs-2 fw-bolder text-primary">{{ $totalTickets }}</div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-sm-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-gray-600">Open Tickets</div>
                <div class="ms-auto fs-2 fw-bolder text-warning">{{ $openTickets }}</div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-sm-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-gray-600">In-Progress Tickets</div>
                <div class="ms-auto fs-2 fw-bolder text-info">{{ $inProgressTickets }}</div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-sm-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-gray-600">Resolved Tickets</div>
                <div class="ms-auto fs-2 fw-bolder text-success">{{ $resolvedTickets }}</div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-sm-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-gray-600">Closed Tickets</div>
                <div class="ms-auto fs-2 fw-bolder text-success">{{ $closedTickets }}</div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-sm-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-gray-600">SLA Breaches</div>
                <div class="ms-auto fs-2 fw-bolder text-danger">{{ $slaBreaches }}</div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-sm-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-gray-600">Average Resolution Time</div>
                <div class="ms-auto fs-2 fw-bolder text-danger">{{ $averageResolutionTime }}</div>
            </div>
        </div>
    </div>
</div>
