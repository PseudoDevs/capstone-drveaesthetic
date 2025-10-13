# Database Architecture & Data Flow

## Database Schema Diagram

```mermaid
erDiagram
    USERS {
        int id PK
        string name
        string email UK
        string password
        enum role
        string google_id
        string avatar
        string phone
        date date_of_birth
        text address
        timestamp email_verified_at
        timestamp last_activity_at
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        int id PK
        string category_name
        timestamp created_at
        timestamp updated_at
    }

    CLINIC_SERVICES {
        int id PK
        int category_id FK
        int staff_id FK
        string service_name
        text description
        string thumbnail
        int duration
        decimal price
        enum status
        timestamp created_at
        timestamp updated_at
    }

    APPOINTMENTS {
        int id PK
        int client_id FK
        int service_id FK
        int staff_id FK
        datetime appointment_date
        enum status
        text notes
        enum appointment_type
        timestamp created_at
        timestamp updated_at
    }

    MEDICAL_CERTIFICATES {
        int id PK
        int staff_id FK
        int client_id FK
        text purpose
        decimal amount
        boolean is_issued
        timestamp created_at
        timestamp updated_at
    }

    FEEDBACKS {
        int id PK
        int client_id FK
        int appointment_id FK
        int rating
        text comment
        enum status
        timestamp created_at
        timestamp updated_at
    }

    CHATS {
        int id PK
        int client_id FK
        int staff_id FK
        enum status
        timestamp created_at
        timestamp updated_at
    }

    MESSAGES {
        int id PK
        int chat_id FK
        int sender_id FK
        text content
        string message_type
        timestamp created_at
        timestamp updated_at
    }

    TRAININGS {
        int id PK
        string title
        text description
        string type
        string thumbnail
        text content
        enum status
        timestamp created_at
        timestamp updated_at
    }

    TIME_LOGS {
        int id PK
        int user_id FK
        datetime check_in
        datetime check_out
        text notes
        timestamp created_at
        timestamp updated_at
    }

    NOTIFICATION_PREFERENCES {
        int id PK
        int user_id FK
        boolean email_notifications
        boolean sms_notifications
        boolean push_notifications
        boolean appointment_reminders
        boolean feedback_requests
        timestamp created_at
        timestamp updated_at
    }

    CONTACT_SUBMISSIONS {
        int id PK
        string name
        string email
        string phone
        text message
        enum status
        timestamp created_at
        timestamp updated_at
    }

    %% Relationships
    USERS ||--o{ APPOINTMENTS : "client_id"
    USERS ||--o{ APPOINTMENTS : "staff_id"
    USERS ||--o{ CLINIC_SERVICES : "staff_id"
    USERS ||--o{ MEDICAL_CERTIFICATES : "client_id"
    USERS ||--o{ MEDICAL_CERTIFICATES : "staff_id"
    USERS ||--o{ FEEDBACKS : "client_id"
    USERS ||--o{ CHATS : "client_id"
    USERS ||--o{ CHATS : "staff_id"
    USERS ||--o{ MESSAGES : "sender_id"
    USERS ||--o{ TIME_LOGS : "user_id"
    USERS ||--o| NOTIFICATION_PREFERENCES : "user_id"

    CATEGORIES ||--o{ CLINIC_SERVICES : "category_id"
    CLINIC_SERVICES ||--o{ APPOINTMENTS : "service_id"
    APPOINTMENTS ||--o{ FEEDBACKS : "appointment_id"
    CHATS ||--o{ MESSAGES : "chat_id"
```

## System Data Flow Diagram

```mermaid
flowchart TD
    %% User Interactions
    subgraph "User Interactions"
        ClientLogin[👤 Client Login]
        StaffLogin[👩‍⚕️ Staff Login]
        AdminLogin[👨‍💼 Admin Login]
    end

    %% Authentication Flow
    subgraph "Authentication Flow"
        AuthCheck{🔐 Authentication Check}
        GoogleAuth[🔑 Google OAuth]
        SanctumToken[🎫 Sanctum Token]
        RoleCheck{👥 Role Verification}
    end

    %% Core Business Processes
    subgraph "Core Business Processes"
        AppointmentBooking[📅 Appointment Booking]
        ServiceManagement[🏥 Service Management]
        ChatSystem[💬 Chat System]
        MedicalCertificates[📄 Medical Certificates]
        TrainingSystem[📚 Training System]
        FeedbackSystem[⭐ Feedback System]
    end

    %% Data Processing
    subgraph "Data Processing"
        Validation[✅ Input Validation]
        BusinessLogic[⚙️ Business Logic]
        DatabaseOps[🗄️ Database Operations]
        FileProcessing[📁 File Processing]
        PDFGeneration[📄 PDF Generation]
    end

    %% Real-time Features
    subgraph "Real-time Features"
        EventBroadcasting[📡 Event Broadcasting]
        SSENotifications[🔄 SSE Notifications]
        WebSocketChat[💬 WebSocket Chat]
        PushNotifications[🔔 Push Notifications]
    end

    %% External Integrations
    subgraph "External Integrations"
        EmailService[📧 Email Service]
        StorageService[☁️ File Storage]
        QueueService[📬 Queue Service]
    end

    %% User Flow
    ClientLogin --> AuthCheck
    StaffLogin --> AuthCheck
    AdminLogin --> AuthCheck

    AuthCheck -->|Google Login| GoogleAuth
    AuthCheck -->|Email/Password| SanctumToken
    GoogleAuth --> RoleCheck
    SanctumToken --> RoleCheck

    RoleCheck -->|Client| AppointmentBooking
    RoleCheck -->|Staff| ServiceManagement
    RoleCheck -->|Admin| ServiceManagement

    %% Business Process Flow
    AppointmentBooking --> Validation
    ServiceManagement --> Validation
    ChatSystem --> Validation
    MedicalCertificates --> Validation
    TrainingSystem --> Validation
    FeedbackSystem --> Validation

    Validation --> BusinessLogic
    BusinessLogic --> DatabaseOps
    BusinessLogic --> FileProcessing
    BusinessLogic --> PDFGeneration

    %% Real-time Flow
    DatabaseOps --> EventBroadcasting
    EventBroadcasting --> SSENotifications
    EventBroadcasting --> WebSocketChat
    EventBroadcasting --> PushNotifications

    %% External Service Flow
    FileProcessing --> StorageService
    PDFGeneration --> EmailService
    BusinessLogic --> QueueService

    %% Styling
    classDef userClass fill:#e1f5fe,stroke:#01579b,stroke-width:2px
    classDef authClass fill:#f3e5f5,stroke:#4a148c,stroke-width:2px
    classDef businessClass fill:#e8f5e8,stroke:#1b5e20,stroke-width:2px
    classDef dataClass fill:#fff3e0,stroke:#e65100,stroke-width:2px
    classDef realtimeClass fill:#fce4ec,stroke:#880e4f,stroke-width:2px
    classDef externalClass fill:#f1f8e9,stroke:#33691e,stroke-width:2px

    class ClientLogin,StaffLogin,AdminLogin userClass
    class AuthCheck,GoogleAuth,SanctumToken,RoleCheck authClass
    class AppointmentBooking,ServiceManagement,ChatSystem,MedicalCertificates,TrainingSystem,FeedbackSystem businessClass
    class Validation,BusinessLogic,DatabaseOps,FileProcessing,PDFGeneration dataClass
    class EventBroadcasting,SSENotifications,WebSocketChat,PushNotifications realtimeClass
    class EmailService,StorageService,QueueService externalClass
```

## API Endpoints Architecture

```mermaid
graph LR
    subgraph "Client API Endpoints"
        ClientAuth[🔐 /api/client/auth]
        ClientAppointments[📅 /api/client/appointments]
        ClientChats[💬 /api/client/chats]
        ClientServices[🏥 /api/client/services]
        ClientProfile[👤 /api/client/profile]
    end

    subgraph "Staff API Endpoints"
        StaffAppointments[📅 /api/staff/appointments]
        StaffChats[💬 /api/staff/chats]
        StaffServices[🏥 /api/staff/services]
        StaffCertificates[📄 /api/staff/certificates]
    end

    subgraph "Admin API Endpoints"
        AdminUsers[👥 /api/admin/users]
        AdminAnalytics[📊 /api/admin/analytics]
        AdminSettings[⚙️ /api/admin/settings]
        AdminReports[📈 /api/admin/reports]
    end

    subgraph "Web Routes"
        WebHome[🏠 /]
        WebServices[🏥 /services]
        WebContact[📞 /contact]
        WebFeedback[⭐ /feedback]
        WebForms[📋 /appointments/{id}/form]
    end

    subgraph "Panel Routes"
        AdminPanel[⚙️ /admin]
        StaffPanel[👥 /staff]
        ClientPanel[🤝 /client]
    end

    %% API Connections
    ClientAuth --> ClientAppointments
    ClientAuth --> ClientChats
    ClientAuth --> ClientServices
    ClientAuth --> ClientProfile

    StaffAppointments --> StaffChats
    StaffAppointments --> StaffServices
    StaffAppointments --> StaffCertificates

    AdminUsers --> AdminAnalytics
    AdminUsers --> AdminSettings
    AdminUsers --> AdminReports

    %% Web Connections
    WebHome --> WebServices
    WebServices --> WebContact
    WebContact --> WebFeedback
    WebFeedback --> WebForms

    %% Panel Connections
    AdminPanel --> AdminUsers
    StaffPanel --> StaffAppointments
    ClientPanel --> ClientAppointments
```

## Security Architecture

```mermaid
graph TD
    subgraph "Security Layers"
        CSRF[🛡️ CSRF Protection]
        Auth[🔐 Authentication]
        Authorization[👥 Authorization]
        Validation[✅ Input Validation]
        Sanitization[🧹 Data Sanitization]
        Encryption[🔒 Data Encryption]
    end

    subgraph "Access Control"
        RoleBased[👤 Role-Based Access]
        PanelAccess[🎛️ Panel Access Control]
        APIAccess[🔌 API Access Control]
        ResourceAccess[📁 Resource Access Control]
    end

    subgraph "Monitoring"
        ActivityTracking[📊 Activity Tracking]
        Logging[📝 Comprehensive Logging]
        AuditTrail[🔍 Audit Trail]
        ErrorHandling[⚠️ Error Handling]
    end

    %% Security Flow
    CSRF --> Auth
    Auth --> Authorization
    Authorization --> Validation
    Validation --> Sanitization
    Sanitization --> Encryption

    %% Access Control Flow
    RoleBased --> PanelAccess
    PanelAccess --> APIAccess
    APIAccess --> ResourceAccess

    %% Monitoring Flow
    ActivityTracking --> Logging
    Logging --> AuditTrail
    AuditTrail --> ErrorHandling

    %% Styling
    classDef securityClass fill:#ffebee,stroke:#c62828,stroke-width:2px
    classDef accessClass fill:#e8f5e8,stroke:#2e7d32,stroke-width:2px
    classDef monitorClass fill:#fff3e0,stroke:#ef6c00,stroke-width:2px

    class CSRF,Auth,Authorization,Validation,Sanitization,Encryption securityClass
    class RoleBased,PanelAccess,APIAccess,ResourceAccess accessClass
    class ActivityTracking,Logging,AuditTrail,ErrorHandling monitorClass
```

