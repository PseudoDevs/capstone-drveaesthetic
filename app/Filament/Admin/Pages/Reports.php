<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\User;
use App\Models\ClinicService;
use App\Models\Prescription;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Reports extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.admin.pages.reports';

    protected static ?string $navigationGroup = 'Reports & Analytics';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    
    public $reportType = 'financial';
    public $startDate;
    public $endDate;
    public $reportData = null;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->form->fill([
            'report_type' => 'financial',
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('report_type')
                    ->label('Report Type')
                    ->options([
                        'financial' => 'Financial Report',
                        'appointments' => 'Appointments Report',
                        'services' => 'Services Report',
                        'clients' => 'Clients Report',
                        'staff' => 'Staff Performance Report',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->reportData = null),
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->native(false)
                    ->maxDate(now()),
                DatePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->native(false)
                    ->maxDate(now())
                    ->after('start_date'),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function generateReport()
    {
        $data = $this->form->getState();
        $this->reportType = $data['report_type'];
        $this->startDate = $data['start_date'];
        $this->endDate = $data['end_date'];

        $method = 'generate' . ucfirst($this->reportType) . 'Report';
        $this->reportData = $this->$method();
    }

    protected function generateFinancialReport(): array
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // Total Revenue
        $totalRevenue = Bill::whereBetween('bill_date', [$start, $end])
            ->sum('paid_amount');

        // Outstanding Balance
        $outstandingBalance = Bill::whereBetween('bill_date', [$start, $end])
            ->sum('remaining_balance');

        // Total Bills
        $totalBills = Bill::whereBetween('bill_date', [$start, $end])->count();

        // Total Payments
        $totalPayments = Payment::whereBetween('payment_date', [$start, $end])
            ->where('status', 'completed')
            ->count();

        // Revenue by Service
        $revenueByService = Bill::whereBetween('bill_date', [$start, $end])
            ->join('appointments', 'bills.appointment_id', '=', 'appointments.id')
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->select('clinic_services.service_name', DB::raw('SUM(bills.paid_amount) as revenue'))
            ->groupBy('clinic_services.id', 'clinic_services.service_name')
            ->orderByDesc('revenue')
            ->get();

        // Payment Methods Breakdown
        $paymentMethods = Payment::whereBetween('payment_date', [$start, $end])
            ->where('status', 'completed')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Revenue by Month (for chart)
        $revenueByMonth = Bill::whereBetween('bill_date', [$start, $end])
            ->select(
                DB::raw('DATE_FORMAT(bill_date, "%Y-%m") as month'),
                DB::raw('SUM(paid_amount) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Payment Type Distribution
        $paymentTypes = Bill::whereBetween('bill_date', [$start, $end])
            ->select('payment_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_type')
            ->get();

        // Bills by Status
        $billsByStatus = Bill::whereBetween('bill_date', [$start, $end])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'summary' => [
                'total_revenue' => $totalRevenue,
                'outstanding_balance' => $outstandingBalance,
                'total_bills' => $totalBills,
                'total_payments' => $totalPayments,
                'average_bill' => $totalBills > 0 ? $totalRevenue / $totalBills : 0,
            ],
            'revenue_by_service' => $revenueByService,
            'payment_methods' => $paymentMethods,
            'revenue_by_month' => $revenueByMonth,
            'payment_types' => $paymentTypes,
            'bills_by_status' => $billsByStatus,
        ];
    }

    protected function generateAppointmentsReport(): array
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // Total Appointments
        $totalAppointments = Appointment::whereBetween('appointment_date', [$start, $end])->count();

        // Appointments by Status
        $appointmentsByStatus = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Appointments by Service
        $appointmentsByService = Appointment::whereBetween('appointment_date', [$start, $end])
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->select('clinic_services.service_name', DB::raw('COUNT(*) as count'))
            ->groupBy('clinic_services.id', 'clinic_services.service_name')
            ->orderByDesc('count')
            ->get();

        // Appointments by Staff
        $appointmentsByStaff = Appointment::whereBetween('appointment_date', [$start, $end])
            ->join('users', 'appointments.staff_id', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(*) as count'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('count')
            ->get();

        // Appointments by Day of Week
        $appointmentsByDay = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select(
                DB::raw('DAYNAME(appointment_date) as day'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day')
            ->get();

        // Daily Appointments (for chart)
        $dailyAppointments = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Appointment Type Distribution
        $appointmentTypes = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select('appointment_type', DB::raw('COUNT(*) as count'))
            ->groupBy('appointment_type')
            ->get();

        // Calculate rates
        $completed = $appointmentsByStatus->where('status', 'COMPLETED')->first()->count ?? 0;
        $cancelled = $appointmentsByStatus->where('status', 'CANCELLED')->first()->count ?? 0;
        $noShow = $appointmentsByStatus->where('status', 'NO_SHOW')->first()->count ?? 0;

        return [
            'summary' => [
                'total_appointments' => $totalAppointments,
                'completed' => $completed,
                'cancelled' => $cancelled,
                'no_show' => $noShow,
                'completion_rate' => $totalAppointments > 0 ? round(($completed / $totalAppointments) * 100, 2) : 0,
                'cancellation_rate' => $totalAppointments > 0 ? round(($cancelled / $totalAppointments) * 100, 2) : 0,
            ],
            'by_status' => $appointmentsByStatus,
            'by_service' => $appointmentsByService,
            'by_staff' => $appointmentsByStaff,
            'by_day' => $appointmentsByDay,
            'daily_appointments' => $dailyAppointments,
            'by_type' => $appointmentTypes,
        ];
    }

    protected function generateServicesReport(): array
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // Service Performance
        $servicePerformance = Appointment::whereBetween('appointment_date', [$start, $end])
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->leftJoin('bills', 'appointments.id', '=', 'bills.appointment_id')
            ->select(
                'clinic_services.service_name',
                'clinic_services.price',
                DB::raw('COUNT(appointments.id) as bookings'),
                DB::raw('SUM(CASE WHEN appointments.status = "COMPLETED" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(bills.paid_amount) as revenue')
            )
            ->groupBy('clinic_services.id', 'clinic_services.service_name', 'clinic_services.price')
            ->orderByDesc('bookings')
            ->get();

        // Most Popular Services
        $popularServices = $servicePerformance->sortByDesc('bookings')->take(5);

        // Highest Revenue Services
        $highestRevenue = $servicePerformance->sortByDesc('revenue')->take(5);

        // Service Utilization
        $totalServices = ClinicService::where('status', 'active')->count();
        $usedServices = Appointment::whereBetween('appointment_date', [$start, $end])
            ->distinct('service_id')
            ->count('service_id');

        // Service Categories (if you have categories)
        $servicesByCategory = Appointment::whereBetween('appointment_date', [$start, $end])
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->leftJoin('categories', 'clinic_services.category_id', '=', 'categories.id')
            ->select('categories.category_name as category', DB::raw('COUNT(*) as count'))
            ->groupBy('categories.id', 'categories.category_name')
            ->orderByDesc('count')
            ->get();

        return [
            'summary' => [
                'total_services' => $totalServices,
                'used_services' => $usedServices,
                'utilization_rate' => $totalServices > 0 ? round(($usedServices / $totalServices) * 100, 2) : 0,
            ],
            'service_performance' => $servicePerformance,
            'popular_services' => $popularServices,
            'highest_revenue' => $highestRevenue,
            'by_category' => $servicesByCategory,
        ];
    }

    protected function generateClientsReport(): array
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // New Clients
        $newClients = User::where('role', 'Client')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Total Active Clients
        $activeClients = Appointment::whereBetween('appointment_date', [$start, $end])
            ->distinct('client_id')
            ->count('client_id');

        // Total Clients
        $totalClients = User::where('role', 'Client')->count();

        // Client Demographics - Gender
        $clientsByGender = User::where('role', 'Client')
            ->whereNotNull('gender')
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->get();

        // Client Demographics - Age Groups
        $clientsByAge = User::where('role', 'Client')
            ->whereNotNull('date_of_birth')
            ->selectRaw('
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18 THEN "Under 18"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 25 THEN "18-25"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 35 THEN "26-35"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 45 THEN "36-45"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 46 AND 55 THEN "46-55"
                    ELSE "56+"
                END as age_group,
                COUNT(*) as count
            ')
            ->groupBy('age_group')
            ->get();

        // Top Clients by Revenue
        $topClients = Bill::whereBetween('bill_date', [$start, $end])
            ->join('users', 'bills.client_id', '=', 'users.id')
            ->select('users.name', 'users.email', DB::raw('SUM(bills.paid_amount) as total_spent'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->take(10)
            ->get();

        // Client Acquisition by Month
        $clientAcquisition = User::where('role', 'Client')
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Client Retention (clients who had appointments in date range)
        $returningClients = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select('client_id')
            ->groupBy('client_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        return [
            'summary' => [
                'total_clients' => $totalClients,
                'new_clients' => $newClients,
                'active_clients' => $activeClients,
                'returning_clients' => $returningClients,
                'retention_rate' => $activeClients > 0 ? round(($returningClients / $activeClients) * 100, 2) : 0,
            ],
            'by_gender' => $clientsByGender,
            'by_age' => $clientsByAge,
            'top_clients' => $topClients,
            'acquisition' => $clientAcquisition,
        ];
    }

    protected function generateStaffReport(): array
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // Staff Performance
        $staffPerformance = Appointment::whereBetween('appointment_date', [$start, $end])
            ->join('users', 'appointments.staff_id', '=', 'users.id')
            ->leftJoin('bills', 'appointments.id', '=', 'bills.appointment_id')
            ->leftJoin('feedback', 'appointments.id', '=', 'feedback.appointment_id')
            ->select(
                'users.name',
                'users.email',
                DB::raw('COUNT(DISTINCT appointments.id) as total_appointments'),
                DB::raw('SUM(CASE WHEN appointments.status = "COMPLETED" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(bills.paid_amount) as revenue'),
                DB::raw('AVG(feedback.rating) as avg_rating')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_appointments')
            ->get();

        // Prescriptions Issued by Staff
        $prescriptionsByStaff = Prescription::whereBetween('prescribed_date', [$start, $end])
            ->join('users', 'prescriptions.prescribed_by', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(*) as count'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('count')
            ->get();

        // Medical Certificates Issued
        $certificatesByStaff = DB::table('medical_certificates')
            ->whereBetween('medical_certificates.created_at', [$start, $end])
            ->join('users', 'medical_certificates.issued_by', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(*) as count'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('count')
            ->get();

        // Total Staff
        $totalStaff = User::whereIn('role', ['Staff', 'Doctor'])->count();

        return [
            'summary' => [
                'total_staff' => $totalStaff,
                'active_staff' => $staffPerformance->count(),
            ],
            'staff_performance' => $staffPerformance,
            'prescriptions_by_staff' => $prescriptionsByStaff,
            'certificates_by_staff' => $certificatesByStaff,
        ];
    }

    public function exportPDF()
    {
        if (!$this->reportData) {
            $this->generateReport();
        }

        $html = view('reports.pdf', [
            'reportType' => $this->reportType,
            'reportData' => $this->reportData,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ])->render();

        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, ucfirst($this->reportType) . '_Report_' . now()->format('Y-m-d') . '.pdf');
    }

}

