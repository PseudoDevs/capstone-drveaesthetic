<div class="section">
    <div class="section-title">Clients Summary</div>

    <div class="summary-box">
        <div class="summary-label">Total Clients</div>
        <div class="summary-value">{{ number_format($reportData['summary']['total_clients']) }}</div>
    </div>

    <div class="summary-box">
        <div class="summary-label">New Clients</div>
        <div class="summary-value">{{ number_format($reportData['summary']['new_clients']) }}</div>
    </div>

    <div class="summary-box" style="margin-right: 0;">
        <div class="summary-label">Retention Rate</div>
        <div class="summary-value">{{ number_format($reportData['summary']['retention_rate'], 1) }}%</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Top 10 Clients by Revenue</div>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Client Name</th>
                <th>Email</th>
                <th class="text-right">Total Spent</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['top_clients'] as $index => $client)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td class="text-right">₱{{ number_format($client->total_spent, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Client Demographics</div>
    
    <table style="width: 48%; display: inline-table; margin-right: 4%;">
        <thead>
            <tr>
                <th>Gender</th>
                <th class="text-right">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['by_gender'] as $gender)
                <tr>
                    <td style="text-transform: capitalize;">{{ $gender->gender }}</td>
                    <td class="text-right">{{ $gender->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 48%; display: inline-table;">
        <thead>
            <tr>
                <th>Age Group</th>
                <th class="text-right">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['by_age'] as $age)
                <tr>
                    <td>{{ $age->age_group }}</td>
                    <td class="text-right">{{ $age->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

