<div class="section">
    <div class="section-title">Staff Summary</div>

    <div class="summary-box">
        <div class="summary-label">Total Staff</div>
        <div class="summary-value">{{ number_format($reportData['summary']['total_staff']) }}</div>
    </div>

    <div class="summary-box">
        <div class="summary-label">Active Staff</div>
        <div class="summary-value">{{ number_format($reportData['summary']['active_staff']) }}</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Staff Performance Overview</div>
    <table>
        <thead>
            <tr>
                <th>Staff Member</th>
                <th class="text-right">Appointments</th>
                <th class="text-right">Completed</th>
                <th class="text-right">Revenue</th>
                <th class="text-right">Avg Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['staff_performance'] as $staff)
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td class="text-right">{{ number_format($staff->total_appointments) }}</td>
                    <td class="text-right">{{ number_format($staff->completed) }}</td>
                    <td class="text-right">₱{{ number_format($staff->revenue ?? 0, 2) }}</td>
                    <td class="text-right">{{ $staff->avg_rating ? number_format($staff->avg_rating, 1) : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($reportData['prescriptions_by_staff']->count() > 0)
<div class="section">
    <div class="section-title">Prescriptions Issued</div>
    <table>
        <thead>
            <tr>
                <th>Staff Member</th>
                <th class="text-right">Prescriptions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['prescriptions_by_staff'] as $staff)
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td class="text-right">{{ number_format($staff->count) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($reportData['certificates_by_staff']->count() > 0)
<div class="section">
    <div class="section-title">Medical Certificates Issued</div>
    <table>
        <thead>
            <tr>
                <th>Staff Member</th>
                <th class="text-right">Certificates</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['certificates_by_staff'] as $staff)
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td class="text-right">{{ number_format($staff->count) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

