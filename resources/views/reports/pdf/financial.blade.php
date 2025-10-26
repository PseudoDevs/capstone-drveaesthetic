<div class="section">
    <div class="section-title">Financial Summary</div>

    <div class="summary-box">
        <div class="summary-label">Total Revenue</div>
        <div class="summary-value">₱{{ number_format($reportData['summary']['total_revenue'], 2) }}</div>
    </div>

    <div class="summary-box">
        <div class="summary-label">Outstanding Balance</div>
        <div class="summary-value">₱{{ number_format($reportData['summary']['outstanding_balance'], 2) }}</div>
    </div>

    <div class="summary-box" style="margin-right: 0;">
        <div class="summary-label">Total Bills</div>
        <div class="summary-value">{{ number_format($reportData['summary']['total_bills']) }}</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Revenue by Service</div>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['revenue_by_service'] as $item)
                <tr>
                    <td>{{ $item->service_name }}</td>
                    <td class="text-right">₱{{ number_format($item->revenue, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Payment Methods Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Payment Method</th>
                <th class="text-center">Count</th>
                <th class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['payment_methods'] as $method)
                <tr>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $method->payment_method) }}</td>
                    <td class="text-center">{{ $method->count }}</td>
                    <td class="text-right">₱{{ number_format($method->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Bills by Status</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th class="text-right">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['bills_by_status'] as $status)
                <tr>
                    <td style="text-transform: capitalize;">{{ $status->status }}</td>
                    <td class="text-right">{{ $status->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

