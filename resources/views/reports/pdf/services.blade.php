<div class="section">
    <div class="section-title">Services Summary</div>

    <div class="summary-box">
        <div class="summary-label">Total Services</div>
        <div class="summary-value">{{ number_format($reportData['summary']['total_services']) }}</div>
    </div>

    <div class="summary-box">
        <div class="summary-label">Services Used</div>
        <div class="summary-value">{{ number_format($reportData['summary']['used_services']) }}</div>
    </div>

    <div class="summary-box" style="margin-right: 0;">
        <div class="summary-label">Utilization Rate</div>
        <div class="summary-value">{{ number_format($reportData['summary']['utilization_rate'], 1) }}%</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Service Performance</div>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th class="text-right">Price</th>
                <th class="text-right">Bookings</th>
                <th class="text-right">Completed</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['service_performance'] as $service)
                <tr>
                    <td>{{ $service->service_name }}</td>
                    <td class="text-right">₱{{ number_format($service->price, 2) }}</td>
                    <td class="text-right">{{ number_format($service->bookings) }}</td>
                    <td class="text-right">{{ number_format($service->completed) }}</td>
                    <td class="text-right">₱{{ number_format($service->revenue ?? 0, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section page-break">
    <div class="section-title">Top 5 Most Popular Services</div>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Service</th>
                <th class="text-right">Bookings</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['popular_services']->take(5) as $index => $service)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $service->service_name }}</td>
                    <td class="text-right">{{ $service->bookings }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

