<div class="section">
    <div class="section-title">Appointments Summary</div>

    <div class="summary-box">
        <div class="summary-label">Total Appointments</div>
        <div class="summary-value">{{ number_format($reportData['summary']['total_appointments']) }}</div>
    </div>

    <div class="summary-box">
        <div class="summary-label">Completed</div>
        <div class="summary-value">{{ number_format($reportData['summary']['completed']) }}</div>
    </div>

    <div class="summary-box" style="margin-right: 0;">
        <div class="summary-label">Completion Rate</div>
        <div class="summary-value">{{ number_format($reportData['summary']['completion_rate'], 1) }}%</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Appointments by Status</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th class="text-right">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['by_status'] as $status)
                <tr>
                    <td>{{ $status->status }}</td>
                    <td class="text-right">{{ $status->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Appointments by Service</div>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th class="text-right">Bookings</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['by_service'] as $item)
                <tr>
                    <td>{{ $item->service_name }}</td>
                    <td class="text-right">{{ number_format($item->count) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section page-break">
    <div class="section-title">Appointments by Staff</div>
    <table>
        <thead>
            <tr>
                <th>Staff Member</th>
                <th class="text-right">Appointments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['by_staff'] as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-right">{{ number_format($item->count) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

