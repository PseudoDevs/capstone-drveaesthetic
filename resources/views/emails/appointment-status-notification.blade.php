<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Status Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .status-update {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .status-change {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            font-size: 16px;
            font-weight: 600;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-scheduled { background-color: #d1ecf1; color: #0c5460; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-declined { background-color: #f8d7da; color: #721c24; }
        .arrow {
            margin: 0 15px;
            font-size: 20px;
            color: #007bff;
        }
        .appointment-details {
            background-color: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f4;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        .detail-value {
            color: #6c757d;
            text-align: right;
        }
        .message {
            background-color: #e7f3ff;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #007bff;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
        .clinic-info {
            margin-top: 20px;
        }
        .clinic-info h3 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 18px;
        }
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                width: 100%;
            }
            .content, .header, .footer {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-value {
                text-align: left;
                margin-top: 5px;
                font-weight: 500;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ setting('general.site_name', 'Capstone Aesthetic') }}</h1>
            <p>Appointment Status Update</p>
        </div>

        <div class="content">
            <div class="greeting">
                Hello {{ $clientName }},
            </div>

            <p>We wanted to notify you that your appointment status has been updated.</p>

            @if($oldStatus)
            <div class="status-change">
                <span class="status-badge status-{{ strtolower($oldStatus) }}">{{ ucfirst(strtolower($oldStatus)) }}</span>
                <span class="arrow">→</span>
                <span class="status-badge status-{{ strtolower($status) }}">{{ ucfirst(strtolower($status)) }}</span>
            </div>
            @else
            <div class="status-update">
                <p><strong>Current Status:</strong> <span class="status-badge status-{{ strtolower($status) }}">{{ ucfirst(strtolower($status)) }}</span></p>
            </div>
            @endif

            <div class="appointment-details">
                <h3 style="margin-top: 0; color: #2c3e50;">Appointment Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Service:</span>
                    <span class="detail-value">{{ $serviceName }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Staff Member:</span>
                    <span class="detail-value">{{ $staffName }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($appointmentDate)->format('F j, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($appointmentTime)->format('g:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ strtolower($status) }}">{{ ucfirst(strtolower($status)) }}</span>
                    </span>
                </div>
            </div>

            <div class="message">
                @switch($status)
                    @case('SCHEDULED')
                        <strong>Great news!</strong> Your appointment has been confirmed and scheduled. Please arrive 10 minutes early for your appointment.
                        @break
                    @case('COMPLETED')
                        <strong>Thank you!</strong> Your appointment has been completed. We hope you had a great experience with us.
                        @break
                    @case('CANCELLED')
                        <strong>Appointment Cancelled:</strong> Your appointment has been cancelled. If you need to reschedule, please contact us.
                        @break
                    @case('DECLINED')
                        <strong>Appointment Declined:</strong> Unfortunately, we cannot accommodate your appointment request. Please contact us to discuss alternative options.
                        @break
                    @default
                        <strong>Status Update:</strong> Your appointment status has been updated to {{ ucfirst(strtolower($status)) }}.
                @endswitch
            </div>

            <p>If you have any questions or need to make changes to your appointment, please don't hesitate to contact us.</p>

            <div class="clinic-info">
                <h3>Contact Information</h3>
                <p><strong>Phone:</strong> {{ setting('contact.phone_1', '+00 569 846 358') }}</p>
                <p><strong>Email:</strong> {{ setting('contact.email_1', 'support@gmail.com') }}</p>
                <p><strong>Address:</strong> {{ setting('contact.address_line_1', '1569 Davis Place,') }}<br>
                {{ setting('contact.address_line_2', 'Filkon, USA.') }}</p>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply directly to this email.</p>
            <p>&copy; {{ date('Y') }} {{ setting('general.site_name', 'Capstone Aesthetic') }}. All rights reserved.</p>
            <p>{{ setting('footer.about_description', 'Professional aesthetic and dermatological care.') }}</p>
        </div>
    </div>
</body>
</html>