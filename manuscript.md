# Dr. Ve Aesthetic Clinic & Wellness Center
## Corrected Manuscript Documentation

---

# CHAPTER 1: CORRECTED VERSION

## ❌ MAJOR ERRORS FOUND IN ORIGINAL CHAPTER 1

---

### **Error 1: Prescription Management Claim (SECTION 1.5 - Scope and Limitations)**

**❌ WRONG STATEMENT IN YOUR ORIGINAL:**
> "The system will not include complex healthcare-related modules such as medical diagnoses, treatment planning, or **prescription management**, as these require regulatory compliance and medical expertise beyond the project's scope."

**✅ REALITY - Your System HAS Prescription Management:**
- ✅ Staff can create, manage, and track prescriptions (Staff Panel)
- ✅ Clients can view and download prescriptions (Web & Mobile)
- ✅ Prescriptions are linked to appointments and services
- ✅ PDF generation for prescriptions exists
- ✅ Digital prescription system is fully implemented

**✅ CORRECTED VERSION:**
> "The system will not include complex healthcare-related modules such as medical diagnoses or complex treatment planning, as these require specialized medical expertise beyond the project's scope. However, the system does include **basic prescription management** where authorized staff can issue prescriptions to clients, and clients can securely view and download their prescriptions through both web and mobile platforms."

---

### **Error 2: Role-Based Access for Clients (SECTION 1.5 & Throughout)**

**❌ IMPLIED WRONG STATEMENT:**
> The document implies clients primarily use mobile app

**✅ REALITY - Clients Have BOTH Web and Mobile Access:**
- ✅ **Web Access:** Clients have a dedicated Filament Client panel (`app/Filament/Client/`)
  - Can view appointments
  - Can view prescriptions
  - Can access their records
- ✅ **Mobile Access:** React Native Android app with REST API

**✅ CORRECTED CLARIFICATION:**
> "The system provides multi-platform access for clients. Clients can access the system through **both a web portal** (with a dedicated client panel) **and a React Native Android mobile application**, ensuring flexibility and convenience. Both platforms provide access to appointment scheduling, prescription viewing, billing information, and real-time chat functionality. Staff and administrators access the system exclusively through the web portal with role-specific panels powered by Filament PHP."

---

### **Error 3: System Features Listed (SECTION 1.2 & 1.5)**

**❌ INCOMPLETE LIST - Your Document Mentions Only:**
1. Appointment scheduling
2. Client records
3. Staff schedules
4. Pre-Appointment Health Assessment
5. Feedback mechanism

**✅ MISSING CRITICAL FEATURES:**
- ✅ **Billing & Payment Management** (comprehensive system with staggered payments)
- ✅ **Prescription Management** (digital prescriptions with PDF generation)
- ✅ **Medical Certificate Generation** (for authorized staff)
- ✅ **Real-time Chat System** (Pusher-powered chat between clients and staff)
- ✅ **Dashboard Analytics** (charts, reports, and insights)
- ✅ **Multi-panel Access** (Admin, Staff, Client panels)
- ✅ **Notification System** (real-time notifications via Pusher)

**✅ CORRECTED FEATURE LIST FOR SECTION 1.2:**
> "The system integrates nine main features aligned with the study's objectives: 
> 1. **Online appointment scheduling module** with real-time availability checking
> 2. **Digital client record management system** with secure role-based access
> 3. **Comprehensive billing and payment management** supporting full and staggered payment plans
> 4. **Digital prescription management system** where staff can create prescriptions and clients can view them securely
> 5. **Medical certificate generation** for authorized healthcare personnel
> 6. **Real-time chat communication system** using Pusher technology for instant messaging between clients and staff
> 7. **Pre-Appointment Health Assessment forms** (medical forms and consent waivers)
> 8. **Feedback and rating mechanism** to gather client experiences and improve service quality
> 9. **Dashboard analytics and reporting tools** for administrators to track performance metrics"

---

### **Error 4: Payment Integration (SECTION 1.5)**

**⚠️ MISLEADING STATEMENT:**
> "will not feature modules for third-party payment integration"

**✅ REALITY - Partial Truth:**
- ⚠️ CORRECT: The system does NOT have Stripe/Razorpay/PayPal integration
- ✅ INCOMPLETE: It DOES have comprehensive payment tracking and billing management
- System records payments manually but doesn't process them online

**✅ CLARIFIED VERSION:**
> "The system will not feature automated third-party payment gateway integration (such as Stripe, PayPal, or GCash) for online payment processing. However, the system includes comprehensive **billing and payment management** where staff can record payments, track outstanding balances, support staggered payment plans, generate receipts, and maintain complete payment histories for all transactions."

---

# CHAPTER 2: CORRECTED VERSION

## ❌ ERRORS FOUND IN ORIGINAL CHAPTER 2

---

### **Error 5: Synthesis Section (SECTION 2.2)**

**❌ WRONG STATEMENT:**
> "only clients use the mobile app while staff and admin use the website"

**✅ REALITY:**
- ✅ Clients use BOTH web portal (Filament Client panel) AND mobile app
- ✅ Staff and Admin use web portal only

**✅ CORRECTED VERSION:**
> "The project adds **multi-platform client access** where clients can use **both the web portal** (featuring a dedicated Filament-powered client panel) **and the Android mobile application**, providing flexibility in how they access services. Meanwhile, staff and administrators use the web-based system with role-specific panels, creating an organized workflow where each user type has appropriate tools and permissions for their responsibilities."

---

### **Error 6: Gap Bridged Section (SECTION 2.3)**

**❌ INCOMPLETE STATEMENT:**
> "Clients will use web and mobile app"

**⚠️ NEEDS EXPANSION:**
The statement should clarify:
- Clients have full-featured web portal access (not just mobile)
- Web and mobile provide similar functionalities
- Medical certificates available on mobile but not web for clients

**✅ CORRECTED AND EXPANDED VERSION:**
> "The system provides clients with comprehensive access through **both a full-featured web portal and an Android mobile application**, ensuring accessibility across multiple platforms. The web portal features a dedicated client panel built with Filament PHP, offering appointment management, prescription viewing, billing information, real-time chat, and feedback submission. The mobile application mirrors these capabilities, with additional support for push notifications and medical certificate access. Meanwhile, staff and administrators utilize separate role-specific web panels with enhanced management capabilities, including prescription creation, medical certificate generation, billing processing, and system analytics. This multi-platform, role-segregated approach ensures that each user type has optimized tools appropriate for their specific needs and responsibilities."

---

## ✅ WHAT YOUR DOCUMENT GOT CORRECT:

1. ✅ Pre-Appointment Health Assessment feature exists
2. ✅ Feedback system exists  
3. ✅ Client record viewing exists
4. ✅ Appointment scheduling exists
5. ✅ Black Box testing approach
6. ✅ Android mobile app (not iOS)
7. ✅ Multi-role system (Admin, Staff, Client)
8. ✅ Digital health records

---

## 📋 SUMMARY OF REQUIRED CORRECTIONS:

| Section | Issue | Action Required |
|---------|-------|-----------------|
| **1.2** | Missing features | Add billing, prescriptions, chat, medical certificates, analytics |
| **1.5** | Wrong - prescription claim | REMOVE statement that prescriptions aren't included |
| **1.5** | Wrong - payment claim | CLARIFY: tracking exists, but no online payment gateway |
| **1.5** | Incomplete - client access | ADD that clients have web portal access too |
| **2.2** | Wrong - role access | CORRECT: clients use both web and mobile |
| **2.3** | Incomplete - platforms | EXPAND: clarify multi-platform client access |

---

**⚠️ These corrections are CRITICAL for academic integrity and accurate system documentation!** 

Your actual system is MORE FEATURE-RICH than your document describes. You should showcase ALL the features you've actually implemented! 🎯

---

# CHAPTER 3: TECHNICAL SPECIFICATIONS

## 3.1 Overview of Current Technologies Used in the System

The web-based system for Dr. Ve Aesthetic Clinic and Wellness Center is developed using **Filament PHP** for a dynamic and responsive admin panel interface, **Blade templates with Livewire** for reactive web components, **Laravel Framework (PHP)** for backend logic and API services, and **Percona Server (MySQL-compatible)** for secure, high-performance database management. It is hosted on **sudohosting.cloud**, a cloud-based platform providing scalability, high availability, and reliable server performance with enterprise-grade database infrastructure. For security, the system implements **HTTPS/SSL encryption, Laravel's built-in CSRF protection, role-based access control (RBAC)**, and **bcrypt hashed password storage** to protect sensitive client data. Secure API authentication using **Laravel Sanctum token-based authentication** is employed for mobile API access. Additionally, a real-time notification system using **Pusher** and **Laravel Broadcasting** delivers instant web and mobile notifications for appointment reminders, chat messages, and system updates.

The mobile application is developed using **React Native**, enabling cross-platform development capability specifically deployed for **Android devices**. It shares the same backend (Laravel) and database (Percona Server) as the web system to ensure synchronized client records and appointment data. The mobile app integrates **Pusher** for real-time push notifications and chat functionality, providing clients with instant updates about schedules, messages, and clinic announcements. To maintain security and compliance with data privacy regulations, the mobile app uses **SSL/TLS protocols, secure token storage, and encrypted API communication** with **Laravel Sanctum token-based authentication**.

---

## 3.2 Resources

This section outlines the essential resources required for the development and implementation of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. It includes details on the necessary hardware, software, and other specifications to ensure the system's functionality, security, and scalability.

---

### 3.2.1 Hardware Specifications

This section details the essential hardware components required for the efficient deployment of the system, including servers, workstations, and network devices. These specifications ensure optimal performance, reliability, and scalability of the system.

Table 1 provides a comprehensive list of hardware tools required for the implementation of the system. These components such as processor, memory, storage, input, and output devices work together to enable the functioning of the system and the execution of various tasks and applications.

#### Table 1: Hardware Specifications

| Hardware Tool | Specification |
|--------------|---------------|
| **Processor** | Intel Core i5 or higher |
| **Memory** | 8GB RAM or higher |
| **Storage** | 512GB Solid State Drive (SSD) |
| **Input Device** | Keyboard and Mouse |
| **Output Device** | Monitor |
| **Server** | Cloud-Based Hosting (sudohosting.cloud) |

The table presents the hardware specifications required for an efficient computing setup, ensuring optimal performance and reliability. The system's processor must be an Intel Core i5 or higher, which provides sufficient processing power for multitasking, software development, and data processing tasks. Memory (RAM) is specified at 8GB or higher, ensuring smooth execution of applications, especially those requiring significant computational resources. This combination of processor and RAM is essential for maintaining system responsiveness and reducing lag during operations.

Storage requirements include a 512GB Solid State Drive (SSD), which enhances system speed and efficiency compared to traditional hard drives. SSDs offer faster boot times, quicker file access, and improved durability, making them ideal for high-performance computing. The specified input devices include a keyboard and mouse, which are standard peripherals necessary for interacting with the system. Additionally, an output device such as a monitor is essential for visual feedback, enabling users to interact seamlessly with the system's interface and applications.

For hosting, the system utilizes **cloud-based server hosting on sudohosting.cloud** to ensure reliable access, scalability, and security. The production environment runs **Percona Server 8.0**, a high-performance, enterprise-grade database system, providing data redundancy and backup solutions to minimize the risk of data loss. This infrastructure supports both web and mobile applications while maintaining high availability and optimal performance.

---

### 3.2.2 Software Specifications

The software specifications outline the necessary platforms, frameworks, and tools required for system development. This includes operating systems, database management systems, security protocols, and development frameworks essential for seamless operation.

Table 2 highlights the software tools and technologies used in the system, along with their specifications.

#### Table 2: Software Specifications

| Software Tool | Specification |
|--------------|---------------|
| **Operating System** | Windows 10 or 11 |
| **Programming Language** | PHP 8.2+, JavaScript |
| **Development Environment** | Visual Studio Code |
| **Frontend Technologies** | HTML5, CSS3, Tailwind CSS 4.0, Alpine.js |
| **Backend Framework** | Laravel Framework 12.x |
| **Admin Panel Framework** | Filament PHP 3.3 |
| **Reactive Components** | Livewire 3.x |
| **Templating Engine** | Blade Templates |
| **Mobile Framework** | React Native (Android) |
| **API Authentication** | Laravel Sanctum 4.0 |
| **Real-time Services** | Pusher (chat & notifications) |
| **OAuth Integration** | Laravel Socialite (Google OAuth) |
| **PDF Generation** | DomPDF 3.1 |
| **Version Control** | GitHub |
| **Local Development** | Laragon (Apache/Nginx, MariaDB, PHP) |
| **Database Management** | phpMyAdmin 8.1.0 |
| **Production Database** | Percona Server 8.0.36 (MySQL-compatible) |
| **Supported Browsers** | Google Chrome, Microsoft Edge, Safari, Mozilla Firefox |

The table presents the comprehensive software tools utilized by the developers in designing and implementing the system. The system is designed to be compatible with **Windows 10 or later versions**, ensuring stability, security, and access to the latest software features. The choice of **PHP 8.2+** as the server-side programming language offers enhanced performance, improved error handling, modern type safety, and better security features, making it a reliable backend technology. **Visual Studio Code** serves as the primary development environment due to its lightweight nature, extensive extensions, and support for multiple programming languages.

**Laravel Framework 12.x** serves as the backbone of the application, providing robust tools for routing, authentication, database management, API development, and security features. It enhances system security and maintainability while ensuring structured and efficient code implementation. The framework's built-in features significantly reduce development time and improve code quality.

**Filament PHP 3.3** powers the entire admin panel system, providing pre-built components, forms, tables, and dashboards for Admin, Staff, and Client panels. This eliminates the need for extensive custom frontend development while maintaining professional-grade interfaces. **Livewire 3.x** enables reactive components without requiring extensive JavaScript, allowing for dynamic updates and interactive features seamlessly integrated with Laravel.

To ensure an interactive and visually appealing user interface, the system leverages **Tailwind CSS 4.0**, **HTML5**, **CSS3**, and **Alpine.js**. Tailwind CSS provides a utility-first responsive framework, allowing the application to adapt seamlessly across various screen sizes and devices. HTML5 enhances the system with structured content and multimedia support, while CSS3 offers advanced styling capabilities. Alpine.js provides lightweight JavaScript functionality for interactive elements without the overhead of larger frameworks.

**React Native** is used to develop the Android mobile application, enabling cross-platform development capabilities while delivering native performance. The mobile app provides clients with convenient access to appointments, billing, prescriptions, and real-time chat functionality.

**Laravel Sanctum 4.0** handles API token authentication, securing all mobile API endpoints with bearer token authentication. **Laravel Socialite** facilitates Google OAuth integration for social login capabilities. **Pusher** provides real-time communication for chat messages and instant notifications across web and mobile platforms. **DomPDF 3.1** generates professional PDF documents for prescriptions, medical certificates, and payment receipts.

For local development, **Laragon** provides an integrated environment with Apache/Nginx, MariaDB, PHP, and phpMyAdmin, offering a fast, lightweight, and portable setup. In production, the system runs on **Percona Server 8.0.36**, a high-performance, MySQL-compatible database system that ensures enterprise-grade reliability, security, and performance.

By integrating these software tools, the system ensures efficient performance, maintainability, and scalability. The selected technologies not only simplify the development process but also enhance the system's adaptability to future upgrades. The combination of PHP, Laravel, Filament, and Percona Server facilitates a robust connection between the software and database components, ensuring smooth operation within the system.

---

### 3.2.3 Programming Specification

The Program Specification section outlines the technical and functional design of the system, developed to optimize appointment scheduling, client records management, billing and payment processing, prescription management, real-time communication, and service availability viewing. The system is built using a **PHP 8.2+ based Laravel 12.x framework** for the backend, **Filament PHP 3.3** for admin panel interfaces, **Percona Server (MySQL-compatible)** for the database, and a **React Native mobile application** for Android client-side interactions. The architecture is designed to ensure scalability, reliability, security, and ease of access across both web and mobile platforms.

Key modules include **user management with role-based access control (Admin, Staff, Client)**, **appointment management with real-time availability checking**, **comprehensive billing and payment system with staggered payment support**, **digital prescription and medical certificate management**, **real-time chat system using Pusher**, **client profile tracking**, **dashboard analytics and reporting**, and **secure user authentication using Laravel Sanctum for mobile API**. These components work together to provide a seamless experience for clients, staff, and administrators.

The system requires standard web servers compatible with Laravel 12.x and Android-compatible devices (version 6.0 or higher), ensuring compatibility with existing clinic infrastructure. This specification serves as a blueprint for development and implementation, reinforcing the system's role in enhancing operational efficiency, user experience, and service accessibility within the clinic environment.

---

#### Functional Requirements

Functional requirements define the essential capabilities the system must deliver to operate effectively. These include appointment scheduling, client record management, billing and payment processing, prescription management, medical certificate generation, real-time chat communication, service availability viewing, secure user authentication, dashboard analytics, report generation, and real-time notifications. Each function is designed to optimize clinic operations, minimize manual administrative tasks, and create a seamless experience for clients, staff, and administrators.

By clearly defining these requirements for both the web and mobile platforms, the system ensures a structured, secure, and efficient workflow that supports the clinic's goal of modernizing its management processes. The functional requirements establish clear boundaries for what the system must achieve, helping guide developers in aligning features with the actual needs of the clinic. They provide a foundation for testing and evaluation, ensuring that every component works as intended and meets user expectations. By addressing administrative, staff, and client-facing functions, the system promotes transparency, reliability, and convenience, which are crucial for enhancing customer satisfaction and sustaining long-term clinic growth.

Table 3 presents the functional requirements necessary for the system, detailing the system's primary features that support secure, convenient, and organized operations.

#### Table 3: Functional Requirements

| Platform | Functional Requirements | Description |
|----------|------------------------|-------------|
| **Web Application** | **Appointment Scheduling System** | Allows clients to book, reschedule, or cancel appointments through the web platform with real-time availability checking |
| | **Client Records Management** | Stores and manages client profiles, visit history, treatment details, and medical records securely in a digital format with role-based access control |
| | **Billing & Payment Management** | Comprehensive billing system supporting full and staggered payment plans, payment tracking, receipt generation, and outstanding balance monitoring |
| | **Real-time Chat System** | Enables instant messaging between clients and staff with online status indicators, message history, and file sharing capabilities |
| | **Prescription Management** | Digital prescription system allowing staff to create, manage, and track prescriptions; clients can securely view and download their prescriptions |
| | **Medical Certificate Generation** | Allows authorized staff to create, preview, and print official medical certificates with PDF generation |
| | **Service Availability Viewer** | Enables clients and staff to view available dates and times for specific services, minimizing scheduling conflicts |
| | **Multi-Panel Access** | Separate Filament-powered panels for Admin, Staff, and Client roles with customized interfaces, features, and permissions |
| | **User Authentication** | Secure role-based login functionality with password hashing, CSRF protection, and session management for web users |
| | **Dashboard Analytics** | Provides visual analytics, charts, and insights for appointments, revenue, services, and system usage using Filament widgets |
| | **Report Generation** | Allows administrators to generate detailed reports on appointments, payments, client visits, and staff performance for business intelligence |
| | **Notification System** | Sends real-time browser notifications for appointment reminders, chat messages, and system updates using Pusher |
| | **Multi-platform Compatibility** | Operates seamlessly across web browsers (Chrome, Edge, Firefox, Safari) on desktops, tablets, and mobile devices |
| **Mobile Application** | **Appointment Scheduling System** | Enables clients to book, reschedule, or cancel appointments conveniently using the Android mobile app with real-time synchronization |
| | **Client Records Viewing** | Allows clients to securely view their treatment history, past consultations, and personal profile on their mobile devices |
| | **Billing & Payment Viewing** | Enables clients to view bills, payment history, outstanding balances, and payment schedules through the mobile app |
| | **Prescription Access** | Allows clients to view, download, and share their prescriptions securely via the mobile application |
| | **Real-time Chat** | Provides instant messaging capability between clients and staff with message history and online status indicators |
| | **Service Availability Viewer** | Lets clients check available services, dates, and appointment time slots without visiting the clinic physically |
| | **Push Notifications** | Receives real-time push notifications for appointment reminders, chat messages, billing updates, and clinic announcements via Pusher |
| | **Secure API Authentication** | Uses Laravel Sanctum token-based authentication with bearer tokens to secure all mobile API endpoints and protect user data |

The web application includes a comprehensive appointment scheduling system that improves booking processes and reduces administrative workload by allowing clients and staff to manage appointments online with real-time slot availability. The billing and payment management module supports both full and staggered payment options, tracks outstanding balances, generates receipts, and maintains complete payment histories. Client records management ensures secure and accurate tracking of service history, treatment details, and medical records with role-based access control ensuring data privacy.

The real-time chat system powered by Pusher enables instant communication between clients and staff, improving customer service and response times. The prescription management module allows staff to create and manage digital prescriptions while clients can securely view and download their prescriptions through their panel. Medical certificate generation provides authorized staff with tools to create, preview, and print official certificates as PDF documents.

Multi-panel access through Filament PHP provides customized interfaces for Admin, Staff, and Client roles, each with appropriate features and permissions. Dashboard analytics offer visual insights into appointments, revenue, service performance, and system usage, aiding in business decision-making. The report generation module allows administrators to create detailed performance reports for evaluation and strategic planning.

The user authentication feature provides role-based login access with Laravel's built-in security features, ensuring data privacy and protection of sensitive information. Multi-platform compatibility ensures that the web application works seamlessly across different browsers and devices. The system maintains consistent performance during peak clinic hours through optimized queries and efficient resource management.

The mobile application focuses on client accessibility and engagement, providing native Android performance through React Native. Clients can conveniently book, reschedule, or cancel appointments via their smartphones with real-time synchronization to the central database. They can securely view their treatment history, past consultations, and personal profiles. The billing module enables clients to view bills, payment histories, and outstanding balances.

The prescription access feature allows clients to view and download their prescriptions securely. Real-time chat enables instant communication with staff members. Service availability viewing helps clients check available services and appointment slots anytime. Push notifications via Pusher ensure clients receive timely reminders and updates. Secure API authentication using Laravel Sanctum protects all mobile endpoints with token-based authentication, ensuring data security and user privacy.

---

#### Non-Functional Requirements

The non-functional requirements define the overall quality, performance, reliability, and security of the clinic management system, ensuring it delivers a seamless experience and meets user expectations. While the system's core functions are essential, its effectiveness also depends on factors such as compatibility, usability, security, scalability, and maintainability.

These requirements establish benchmarks for system responsiveness, ensuring that pages load quickly and users can navigate without unnecessary delays. They cover scalability, allowing the system to accommodate future growth in both user base and data volume without compromising efficiency. Non-functional requirements also address maintainability, ensuring that the system can be updated, fixed, and enhanced with minimal disruption. They emphasize availability and reliability, guaranteeing that the clinic's services remain operational and accessible, especially during peak hours.

Table 4 presents the non-functional requirements of the clinic system, outlining the standards that ensure it operates smoothly, securely, and efficiently across platforms.

#### Table 4: Non-Functional Requirements

| Non-Functional Requirements | Description |
|----------------------------|-------------|
| **System Compatibility** | Ensures smooth functionality across various web browsers (Chrome, Edge, Firefox, Safari), operating systems (Windows, macOS, Linux), and mobile devices (Android 6.0+), providing consistent performance for all users |
| **User Usability** | Provides a clean, intuitive, and user-friendly interface powered by Filament PHP for admin panels and responsive design for mobile apps, minimizing the learning curve and ensuring ease of navigation for users with varying technical skills |
| **Security Measures** | Implements comprehensive security including HTTPS/SSL encryption, Laravel CSRF protection, bcrypt password hashing, Laravel Sanctum token authentication, role-based access control (RBAC), SQL injection prevention through Eloquent ORM, XSS protection through Blade escaping, and secure API endpoints |
| **Data Integrity** | Ensures accurate and consistent client records, appointment data, prescriptions, and billing information through database constraints, validation rules, and transaction management |
| **Data Backup & Recovery** | Performs regular automated backups of the Percona Server database to prevent data loss and enable recovery in case of system failures or disasters |
| **System Reliability** | Guarantees stable operation with minimal downtime (99.9% uptime target), especially during peak clinic hours, ensuring the system remains functional and accessible through optimized code and efficient resource management |
| **Performance** | Ensures fast page load times (under 3 seconds), responsive interactions, and efficient database queries through Laravel query optimization, caching strategies, and indexed database tables |
| **Scalability** | Designed to handle increasing numbers of users, appointments, and data volume without performance degradation through efficient architecture, database optimization, and cloud hosting capabilities |
| **Maintainability** | Built using Laravel framework conventions, clean code practices, comprehensive documentation, and modular architecture to facilitate easy updates, bug fixes, and feature enhancements |
| **User Accessibility** | Designs the system interface to be inclusive with clear typography, intuitive navigation, responsive layouts, and consideration for users with varying technical skills and abilities |
| **Audit Trails** | Maintains logs of critical user actions, system events, and data modifications for security monitoring, compliance, and troubleshooting purposes |
| **API Performance** | Ensures mobile API endpoints respond efficiently (under 2 seconds) with proper error handling, data validation, and secure authentication via Laravel Sanctum |

System compatibility is critical because the clinic's system is accessed through multiple devices and operating environments by clients, staff, and administrators. Whether using desktop computers, laptops, tablets, or mobile phones, all users experience consistent speed, performance, and functionality. The web application supports major browsers including Chrome, Edge, Firefox, and Safari, while the mobile app targets Android devices running version 6.0 or higher.

Usability focuses on designing interfaces that are simple, clear, and easy to use. Filament PHP provides professional admin panels with minimal learning curve for staff and administrators. Clients can easily book appointments, view prescriptions, check bills, and communicate with staff through intuitive interfaces. The mobile app mirrors web functionality with native Android performance and user experience.

Security measures are paramount because the system handles sensitive health information, personal data, and financial transactions. The system employs multiple layers of security including HTTPS/SSL encryption for data transmission, Laravel's CSRF protection against cross-site request forgery, bcrypt password hashing, Laravel Sanctum token authentication for mobile API, role-based access control limiting data access based on user roles, Eloquent ORM preventing SQL injection, and Blade template escaping preventing XSS attacks.

Data integrity ensures that all information remains accurate and consistent across the system through database constraints, foreign key relationships, validation rules at multiple levels (frontend, backend, database), and transaction management for critical operations. Regular automated backups protect against data loss, with backup schedules ensuring recovery capabilities in case of hardware failures, software errors, or security incidents.

System reliability guarantees stable operation with minimal downtime, targeting 99.9% uptime availability. The system is designed to handle peak loads during busy clinic hours through optimized code, efficient database queries, connection pooling, and proper resource management. Performance optimization ensures fast response times with page loads under 3 seconds and API responses under 2 seconds through Laravel caching, query optimization, database indexing, and efficient asset loading.

Scalability allows the system to grow with the clinic's needs, accommodating increasing numbers of users, appointments, and data without performance degradation. The cloud-hosted infrastructure on sudohosting.cloud provides resources that can scale as needed. The database design using Percona Server ensures efficient data management even with large datasets.

Maintainability is achieved through Laravel's structured framework, adherence to coding standards, modular architecture separating concerns, comprehensive inline documentation, and version control via GitHub. This enables developers to easily understand, update, and enhance the system with minimal risk of introducing bugs.

User accessibility ensures that the interface is usable by people with varying levels of technical expertise. Clear navigation, consistent layouts, helpful error messages, and intuitive workflows reduce confusion and support tickets. The system provides guidance and feedback to help users complete tasks successfully.

Audit trails maintain comprehensive logs of user actions, system events, authentication attempts, data modifications, and errors for security monitoring, regulatory compliance, troubleshooting, and forensic analysis. This supports accountability and helps identify issues quickly.

API performance for the mobile application is optimized through efficient endpoint design, proper use of Laravel's Eloquent ORM, response caching where appropriate, pagination for large datasets, and comprehensive error handling. Laravel Sanctum ensures secure token-based authentication protecting all API endpoints from unauthorized access.

Together, these non-functional requirements form a robust foundation for the clinic's digital management system, ensuring it is efficient, secure, reliable, scalable, and user-friendly. These qualities make the system adaptable to future improvements and capable of meeting the clinic's evolving needs. By fulfilling these requirements, the clinic provides better services to clients while improving staff productivity and operational efficiency.

---

### 3.2.4 Programming Environment

The programming environment consists of the tools, libraries, and development frameworks used to build and maintain the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This section highlights the integrated development environment (IDE), version control systems, and other essential utilities employed in the development process.

---

#### Front End

The front-end of the Web and Mobile-Based Management System is responsible for delivering a seamless and interactive user experience. It utilizes modern web technologies and frameworks to create intuitive interfaces, ensuring efficient navigation and accessibility for users. The following are the tools used for front-end development:

**HTML5.** HyperText Markup Language (HTML5) provides the structural foundation for the system's web interface. It defines the layout of elements such as forms for client registration, appointment scheduling, prescription viewing, and feedback submission. HTML5's semantic elements and multimedia support allow developers to organize text, input fields, media content, and interactive elements effectively, ensuring user-friendly navigation and modern web standards compliance.

**CSS3.** Cascading Style Sheets (CSS3) enhance the system's visual appeal by defining design elements, including color schemes, typography, spacing, animations, transitions, and layout responsiveness. CSS3 ensures a consistent and professional interface across all pages, improving user experience across different screen sizes and devices. Advanced features like flexbox and grid layouts provide flexible positioning and responsive design capabilities.

**Tailwind CSS 4.0.** A utility-first CSS framework utilized to facilitate responsive design and modern interface development. Tailwind CSS enables the system to adapt seamlessly across desktops, tablets, and mobile devices through flexible utility classes and customizable design systems. Unlike traditional CSS frameworks, Tailwind provides low-level utility classes that can be composed to build any design directly in the markup, ensuring consistency, reducing custom CSS, and enabling rapid development of modern, responsive user interfaces.

**Blade Templates.** Laravel's powerful templating engine used for rendering dynamic web pages. Blade provides a simple yet expressive syntax for displaying data, creating reusable layouts through template inheritance, building components, and executing control structures. It compiles templates into plain PHP code for optimal performance while maintaining clean, readable template files. Blade's component system and slot functionality enable developers to create reusable UI elements, ensuring consistency and maintainability across the application.

**Livewire 3.x.** A full-stack framework for Laravel that enables the creation of dynamic, reactive interfaces without leaving PHP or writing extensive JavaScript. Livewire powers real-time updates, form validation, and interactive components within the admin panels. It uses server-side rendering with efficient DOM diffing to update only changed elements, providing a seamless single-page application experience while maintaining the simplicity of server-side development. This approach significantly reduces development complexity while maintaining excellent performance.

**Alpine.js.** A lightweight JavaScript framework (approximately 15kb) used for adding interactivity to web pages. Alpine.js works seamlessly with Livewire and Filament to provide smooth transitions, responsive dropdowns, dynamic modals, tooltips, and other interactive elements. Its declarative syntax similar to Vue.js allows developers to add JavaScript behavior directly in HTML markup, making it easy to implement interactive features without complex JavaScript build processes.

**Filament PHP 3.3.** A comprehensive admin panel framework built specifically for Laravel that provides pre-built components, forms, tables, charts, widgets, and dashboard layouts. Filament powers the entire admin interface system, creating separate panels for Admin, Staff, and Client roles. Each panel offers customized interfaces with role-appropriate features and permissions. Filament's rich component library eliminates the need for extensive custom frontend development while maintaining professional-grade, responsive interfaces. It leverages Livewire for reactivity and Tailwind CSS for styling, providing a cohesive and modern user experience.

**React Native.** The framework used to build the native Android mobile application for clients. React Native enables cross-platform development using JavaScript and React, allowing code reusability between platforms while maintaining native performance and user experience. It compiles to native code, providing smooth animations, responsive touch interactions, and access to device features. The mobile app communicates with the Laravel backend through REST API endpoints secured with Laravel Sanctum authentication, ensuring synchronized data between web and mobile platforms.

**JavaScript (ES6+).** Enhances system functionality by enabling dynamic interactions, asynchronous operations, and real-time updates. Modern JavaScript (ES6 and later) provides features like arrow functions, promises, async/await, destructuring, and modules. It ensures seamless user engagement by handling client-side logic, input validation, AJAX requests, and real-time communication through Pusher integration. JavaScript works alongside Alpine.js and Livewire to create responsive, interactive experiences.

---

#### Back End

The back end serves as the core of the system, managing data processing, business logic, authentication, authorization, and overall system operations. It integrates server-side programming, database management, security protocols, and third-party services to ensure reliable, secure, and efficient operations.

**PHP 8.2+.** The primary server-side programming language used for handling business logic, data processing, validation, and user authentication. PHP 8.2 and later versions offer significant performance improvements, enhanced type safety through union types and enums, improved error handling, JIT (Just-In-Time) compilation for better performance, and modern language features. It facilitates secure data exchange between the client and server, ensuring efficient performance and scalability. PHP's extensive ecosystem and Laravel framework support make it an excellent choice for building robust web applications.

**Laravel Framework 12.x.** A robust, feature-rich PHP framework that serves as the foundation for the entire application. Laravel provides elegant syntax and comprehensive tools for routing, middleware management, authentication and authorization, database management through Eloquent ORM, API development, queue management for background jobs, event broadcasting for real-time features, email notifications, and built-in security features. Laravel's MVC (Model-View-Controller) architecture ensures clean separation of concerns, making the codebase maintainable and scalable. It enhances system security through CSRF protection, SQL injection prevention, XSS protection, and secure password hashing. Laravel's conventions and extensive documentation significantly accelerate development while maintaining code quality.

**Laravel Sanctum 4.0.** Provides lightweight, token-based API authentication specifically designed for single-page applications and mobile apps. Sanctum manages secure API access for the React Native mobile application by generating personal access tokens (bearer tokens) upon login. These tokens authenticate subsequent API requests, ensuring that only authorized users can access protected endpoints. Sanctum also handles token expiration, revocation, and multiple device support. It protects all mobile API routes, preventing unauthorized access to sensitive client data, appointments, prescriptions, and billing information.

**Laravel Socialite.** Facilitates OAuth authentication integration with third-party providers. The system uses Socialite to implement Google OAuth, allowing users to register and log in using their Google accounts. This social login functionality improves user convenience by eliminating the need to create and remember separate credentials while leveraging Google's secure authentication infrastructure. Socialite abstracts the complex OAuth flow, making integration straightforward and maintainable.

**Eloquent ORM.** Laravel's elegant Object-Relational Mapping system that provides an intuitive, Active Record implementation for working with the database. Eloquent allows developers to interact with database tables using expressive PHP syntax rather than writing raw SQL queries. It automatically prevents SQL injection attacks through prepared statements, manages relationships between models (one-to-one, one-to-many, many-to-many), handles eager loading to optimize queries, and provides powerful query builder methods. Eloquent significantly improves code readability and maintainability while ensuring database security.

**Pusher.** A real-time communication service integrated for handling instant messaging and live notifications. Pusher enables the real-time chat system between clients and staff, providing instant message delivery without requiring page refreshes. It also powers online status indicators, unread message counts, and real-time notification broadcasting across both web and mobile platforms. Pusher uses WebSocket technology for persistent connections, ensuring low-latency communication. The integration with Laravel Broadcasting makes implementing real-time features straightforward, enhancing user engagement and responsiveness.

**DomPDF 3.1.** A PHP library used for generating professional PDF documents from HTML. DomPDF powers the system's document generation capabilities, including printable prescriptions with medication details and doctor signatures, medical certificates with official formatting, payment receipts with transaction information, and system reports for administrative purposes. It supports CSS styling, custom fonts, images, and page formatting, ensuring that generated documents maintain professional appearance. PDF generation is crucial for providing clients and staff with official documentation that can be printed, saved, or shared.

**Laravel Broadcasting.** A feature of Laravel that provides an expressive API for broadcasting server-side events to client-side applications in real-time. It integrates with Pusher to broadcast events like new chat messages, appointment updates, and system notifications. Broadcasting enables the system to push updates to connected clients instantly, creating a responsive and engaging user experience. It works seamlessly with Laravel's event system, allowing developers to trigger broadcasts easily from anywhere in the application.

**Laravel Queue System.** Manages time-consuming tasks asynchronously in the background, preventing them from blocking user requests. The queue system handles tasks like sending email notifications, processing payment confirmations, generating PDF documents, and updating statistics. This improves application responsiveness by deferring heavy operations to background workers. Laravel supports multiple queue backends, and the system uses database or Redis queues for reliable job processing.

**Percona Server 8.0 (MySQL-compatible).** Serves as the relational database management system (RDBMS) for securely storing and managing all system data. Percona Server is a high-performance, enterprise-grade fork of MySQL that offers improved performance, enhanced scalability, advanced diagnostics, and better reliability compared to standard MySQL. It ensures efficient storage, retrieval, and modification of user records, appointment schedules, prescriptions, medical certificates, billing information, payment transactions, chat messages, and system configurations. Percona Server's optimizations include improved InnoDB engine, better query optimization, advanced monitoring capabilities, and enhanced security features. The database design uses proper indexing, foreign key constraints, and normalized structure to maintain data integrity and optimize query performance.

**phpMyAdmin 8.1.0.** A web-based database administration tool that provides a graphical interface for managing the Percona Server database. phpMyAdmin allows developers and administrators to create tables, modify structures, execute SQL queries, import/export data, manage users and permissions, and monitor database performance. It simplifies database management tasks that would otherwise require command-line access, making it accessible for team members with varying technical expertise.

**Laragon.** Provides a fast, lightweight, and portable local development environment that integrates all necessary components for Laravel development. Laragon includes Apache or Nginx web server, MariaDB (MySQL-compatible) database server, PHP with various versions, phpMyAdmin for database management, and various tools for development. It offers one-click virtual host creation, automatic environment configuration, and easy switching between PHP versions. Laragon's portability allows developers to package the entire development environment, ensuring consistency across team members' machines and simplifying the setup process for new developers.

**Composer.** PHP's dependency manager used to install and manage Laravel and its dependencies, including Filament, Sanctum, Socialite, DomPDF, Pusher, and other packages. Composer ensures that all required libraries are installed with correct versions, handles autoloading, and keeps dependencies up to date. It simplifies package management and makes the project's dependencies explicit and reproducible.

**NPM (Node Package Manager).** JavaScript's package manager used to install and manage frontend dependencies like Tailwind CSS, Vite, Alpine.js, and development tools. NPM handles the JavaScript build process, asset compilation, and development server for frontend assets.

**Vite.** A modern build tool and development server that handles asset compilation for Laravel applications. Vite provides lightning-fast hot module replacement (HMR) during development, efficient production builds with code splitting, and seamless integration with Laravel. It compiles JavaScript, CSS, and other assets, optimizing them for production deployment.

**Git & GitHub.** Version control system (Git) and hosting platform (GitHub) used for source code management, collaboration, and deployment. Git tracks all code changes, enables branching for feature development, facilitates code review through pull requests, and maintains complete project history. GitHub provides cloud-based repository hosting, collaboration tools, issue tracking, and CI/CD integration capabilities. Version control is essential for team collaboration, code backup, and deployment management.

---

By integrating these comprehensive programming tools and technologies, the system ensures efficient performance, robust security, excellent maintainability, and future scalability. The selected technologies work together seamlessly: **Laravel** provides the backend foundation, **Filament** powers the admin interfaces, **React Native** delivers the mobile experience, **Percona Server** ensures data reliability, **Pusher** enables real-time communication, and **Laravel Sanctum** secures API access. This modern technology stack not only simplifies the development process but also enhances the system's capability to adapt to future requirements, ensuring long-term viability and success for Dr. Ve Aesthetic Clinic and Wellness Center's digital management system.

---

## Architecture Diagram Description

### Figure 1. Architecture Diagram

The Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center is designed using a three-tier architecture consisting of the presentation, application, and data layers, ensuring scalability, maintainability, and enterprise-level security. The presentation layer serves as the user interface for clients, staff, and administrators, accessible through secure HTTPS web browsers for comprehensive web portal access and a dedicated Android mobile application that communicates via REST API (JSON) for on-the-go client services. It facilitates the collection of user input such as appointment bookings, profile updates, billing payments, real-time chat communications, and feedback submissions, while displaying relevant information like schedules, prescriptions, payment history, and analytics dashboards. The application layer, built using PHP 8.2+ and the Laravel Framework 12.x, processes core system functions including managing user authentication and authorization, handling appointment bookings and scheduling, processing billing and payments with support for staggered payment plans, facilitating real-time chat communications between clients and staff, managing prescription records with role-based access control, generating medical certificates for authorized personnel, validating business rules, and implementing Laravel Sanctum for secure API token authentication to protect mobile application endpoints. This layer is responsible for executing complex business workflows, enforcing role-based access control for clients, staff, and administrators, and ensuring secure communication across all platforms. The data layer, using Percona Server (MySQL), a high-performance, enterprise-grade, MySQL-compatible database system, securely stores and manages all system data including user profiles with role assignments, appointment records with service details, medical prescriptions and certificates, comprehensive billing and payment transactions, real-time chat message histories, system notifications and preferences, and administrative configurations with audit trails. This three-tier structure allows the system to efficiently handle the clinic's multi-faceted operations, ensuring that each component is independently scalable, secure, and easily maintainable, while also providing an adaptable solution that can meet the growing needs of a modern aesthetic clinic and wellness center. By separating the presentation, application, and data layers, the system ensures a reliable, secure, and user-friendly experience for clients through both web and mobile platforms, empowers staff with comprehensive management tools, provides administrators with powerful analytics and oversight capabilities, and maintains the highest levels of data security, system performance, and operational efficiency to support the clinic's commitment to quality healthcare service delivery.

---

## Technology Stack Summary

| Component | Technology | Version/Details |
|-----------|-----------|-----------------|
| **Programming Language** | PHP | 8.2+ |
| **Backend Framework** | Laravel | 12.x |
| **Admin Panel** | Filament PHP | 3.3 |
| **Reactive Components** | Livewire | 3.x |
| **Templating Engine** | Blade Templates | Built-in with Laravel |
| **Database** | Percona Server (MySQL-compatible) | 8.0.36 |
| **CSS Framework** | Tailwind CSS | 4.0 |
| **JavaScript Framework** | Alpine.js | Latest |
| **Mobile Framework** | React Native | Latest (Android) |
| **API Authentication** | Laravel Sanctum | 4.0 |
| **OAuth Integration** | Laravel Socialite | Latest |
| **Real-time Services** | Pusher | Cloud service |
| **PDF Generation** | DomPDF | 3.1 |
| **Local Development** | Laragon | Latest (Apache, MariaDB, PHP) |
| **Database Management** | phpMyAdmin | 8.1.0 |
| **Version Control** | Git & GitHub | Latest |
| **Build Tool** | Vite | 7.1.5+ |
| **Package Managers** | Composer, NPM | Latest |
| **Production Hosting** | sudohosting.cloud | Cloud-based |
| **SSL/HTTPS** | SSL/TLS Certificate | Active |

---

**Document Version:** 1.0 Corrected  
**Last Updated:** November 8, 2025  
**System Status:** Production  
**Production URL:** https://drveaestheticclinic.online

---

# CHAPTER 3.3: TESTING PLAN - CORRECTED VERSION

## ❌ CRITICAL ISSUES FOUND IN ORIGINAL SECTION 3.3

---

### **Error 7: Testing Implementation Description (SECTION 3.3)**

**❌ WRONG/INCOMPLETE STATEMENT:**
> "The testing process follows a Black Box Testing approach... test cases will be used to validate core functions"
> 
> Document describes ONLY manual Black Box testing with basic test cases

**✅ REALITY - You Have Comprehensive Automated Testing:**

Your actual testing implementation is FAR MORE SOPHISTICATED:

#### **1. ✅ Automated Security Testing Suite**
You have a complete security testing infrastructure:
- **PHPUnit Security Tests** (`tests/security/SecurityTestSuite.php`)
  - Rate limiting tests
  - SQL injection protection tests
  - XSS sanitization tests
  - CSRF protection tests
  - Password security tests
  
- **Panel Security Tests** (`tests/security/PanelSecurityTestSuite.php`)
  - Role-based access control tests
  - Unauthorized access prevention tests
  - Admin panel security tests
  - Staff panel security tests

- **K6 Load/Security Testing** (Multiple test files)
  - `k6-rate-limiting.js` - Rate limiting under load
  - `k6-authentication.js` - Authentication security testing
  - `k6-api-security.js` - API endpoint security
  - `k6-admin-panel-security.js` - Admin panel security
  - `k6-staff-panel-security.js` - Staff panel security

- **JMeter Security Testing**
  - `jmeter-security-test-plan.jmx` - Comprehensive security scenarios
  - `jmeter-authentication-test.jmx` - Authentication security tests

- **Automated Test Scripts**
  - `run-security-tests.sh` - Automated security test runner
  - Comprehensive security test documentation (`README.md`)

#### **2. ✅ PHPUnit Framework**
You have PHPUnit configured (`phpunit.xml`) with:
- Unit testing capability
- Feature testing capability
- Test database configuration (SQLite in-memory)
- Proper test environment setup

#### **3. ⚠️ Manual Testing**
Compatibility Testing and Usability Testing appear to be manual (which is fine for a capstone)

---

**✅ CORRECTED VERSION OF SECTION 3.3:**

### **3.3 Testing Plan**

This section outlines the comprehensive testing strategies and methodologies used to evaluate the functionality, security, usability, and compatibility of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. The testing process employs both **automated testing frameworks** and **manual Black Box Testing approaches**, ensuring that the system meets its intended requirements and operates seamlessly across different environments. Figure 3 below illustrates the overall testing plan designed for this research.

### **Figure 3. Comprehensive Testing Framework**

Figure 3 illustrates the multi-layered testing strategy employed to validate the functionality, security, performance, and reliability of the Web and Mobile-Based Management System. The testing framework integrates three complementary approaches: manual Black Box Testing, automated security scanning tools, and professional load testing frameworks to ensure comprehensive system validation across all critical dimensions.

The testing framework is structured into three primary categories: **Compatibility Testing**, **Usability Testing**, and **Security Testing**, with Security Testing further subdivided into three specialized layers to provide defense-in-depth validation.

**Compatibility Testing** validates cross-platform functionality through manual Black Box test cases, evaluating system operation across diverse devices (desktop, tablet, Android mobile), operating systems (Windows, macOS, Android), and web browsers (Chrome, Edge, Firefox, Safari). This ensures consistent user experience regardless of access platform.

**Usability Testing** employs structured manual test scenarios to assess user interface intuitiveness, navigation workflows, task completion efficiency, and overall user experience across different user roles (Admin, Staff, Client). This validates that the system meets usability requirements for users with varying technical expertise.

**Security Testing** implements a comprehensive three-layer validation approach:

**Layer 1: Automated Security Scanning**
- **SSL Labs**: Validates SSL/TLS implementation, certificate configuration, protocol support, and encryption strength (achieved Grade A+)
- **SecurityHeaders.com**: Evaluates HTTP security headers implementation including X-Frame-Options, X-Content-Type-Options, X-XSS-Protection, Referrer-Policy, and Strict-Transport-Security (achieved Grade B)

**Layer 2: Manual Security Testing**
- Role-Based Access Control (RBAC) validation
- Authentication security verification
- SQL injection protection testing
- Cross-Site Scripting (XSS) protection validation
- Cross-Site Request Forgery (CSRF) protection verification
- HTTP method enforcement testing

**Layer 3: Load Testing and Performance Under Security Stress**
- **K6 Load Testing Framework**: Simulates concurrent virtual users (5 to 50 VUs) across five comprehensive test scenarios:
  - API Security Load Test (313,329 unauthorized access attempts)
  - Authentication Security and Attack Prevention (4,720 SQL injection and XSS attacks)
  - Rate Limiting Validation (1,924 high-frequency requests)
  - Admin Panel Security Load Test (1,288 security checks across multiple scenarios)
  - Staff Panel Security Load Test (2,904 requests validating access controls and request limits)
- **JMeter Testing Framework**: Prepared for additional comprehensive security and performance validation scenarios

This multi-layered testing framework totaling 13 distinct test categories ensures comprehensive system validation across security, performance, and functional dimensions. The combination of automated scanning tools, manual security validation, and professional load testing with over 324,000 requests tested provides robust evidence of the system's production-readiness and ability to maintain security and performance under realistic operational conditions and adversarial scenarios.

---

### **3.3.1 Types of Testing**

Software testing encompasses various approaches to ensure system reliability, functionality, and security. The Web and Mobile-Based Management System employs three primary testing types: **Compatibility Testing**, **Usability Testing**, and **Security Testing**.

#### **Compatibility Testing**

Compatibility Testing ensures that the system functions correctly across different devices, operating systems, and web browsers. Since the system will be accessed by various users on different platforms, this testing guarantees a seamless experience regardless of the user's device or browser.

**Testing Approach:**
- **Manual Black Box Testing** with structured test cases
- Tests conducted on multiple browsers: Chrome, Edge, Firefox, Safari
- Tests across different devices: Desktop, tablet, mobile (Android)
- Tests on different operating systems: Windows, macOS, Android

**Evaluation Criteria:**
- Responsiveness across screen sizes
- Layout consistency across browsers
- Feature functionality across platforms
- Performance consistency across environments

This ensures that users can reliably access the system without experiencing errors or inconsistencies across platforms.

---

#### **Usability Testing**

Usability Testing evaluates how intuitive, efficient, and user-friendly the system is by assessing how users interact with its features and interface. It focuses on identifying potential issues in navigation, accessibility, and task completion to ensure that all functions are easy to use and understand.

**Testing Approach:**
- **Manual Black Box Testing** with structured test cases
- Simulates common user activities:
  - Booking an appointment
  - Updating user profiles
  - Viewing medical records
  - Managing prescriptions
  - Processing payments
  - Using real-time chat
  - Accessing bills and receipts

**Evaluation Criteria:**
- Navigation clarity and intuitiveness
- Task completion efficiency
- Interface readability and accessibility
- Error message clarity
- User satisfaction with workflow

The results are analyzed to identify areas requiring improvement, ensuring that the system provides a clear, organized, and user-centered experience for clients, staff, and administrators.

---

#### **Security Testing**

Security Testing ensures that the system is protected from vulnerabilities, unauthorized access, and potential data breaches. Since the project handles sensitive client and staff information, maintaining data confidentiality, integrity, and availability is a top priority. The security testing approach employs a comprehensive three-layer validation strategy combining automated scanning tools, manual security validation, and load testing under security stress conditions.

**Testing Approach:**
- **Automated Testing** using PHPUnit, K6, and JMeter
- **Manual Testing** for specific security scenarios
- **Automated Security Scanning** using SSL Labs and SecurityHeaders.com
- **Panel-Specific Testing** for Admin, Staff, and Client panels
- **Load Testing** under security stress conditions (324,000+ requests tested)

**Security Test Categories:**

- **Rate Limiting Tests**: Validates that excessive requests are blocked (10 login attempts per minute, 100 API requests per minute, returning HTTP 429 when limits exceeded)
- **Authentication Security**: Tests login security, password requirements, SQL injection protection (4,720 attempts tested), XSS protection
- **Authorization Tests**: Verifies role-based access control (RBAC), prevents unauthorized panel access (Admin, Staff, Client panels tested separately)
- **Input Validation Tests**: Ensures malicious inputs are rejected, validates file upload security (1MB request size limits)
- **Security Headers Tests**: Confirms proper security headers (X-Content-Type-Options, X-Frame-Options, X-XSS-Protection, Referrer-Policy, Strict-Transport-Security)
- **Session Security Tests**: Validates encrypted sessions, secure cookies (HttpOnly, Secure, SameSite)
- **CSRF Protection Tests**: Ensures CSRF token validation is enforced
- **Panel-Specific Security Tests**: Admin panel (1,288 security checks), Staff panel (2,904 requests), Client API (313,329 unauthorized attempts)

**Expected Results:**
- ✅ 429 status codes when rate limits are exceeded
- ✅ 401 status codes for unauthorized access attempts
- ✅ 403 status codes for role-based access violations
- ✅ SQL injection attempts blocked (4,720 attempts tested)
- ✅ XSS attempts sanitized
- ✅ CSRF tokens properly validated
- ✅ Role-based access properly enforced across all panels
- ✅ Passwords securely hashed using bcrypt
- ✅ SSL/TLS Grade A+ (SSL Labs)
- ✅ Security Headers Grade B (SecurityHeaders.com)

Through this comprehensive automated and manual security testing process, the system ensures that all security measures function effectively, maintaining a safe, reliable, and trustworthy environment for all users. The testing process, totaling over 324,000 security requests across automated scanning, manual validation, and load testing, provides robust evidence of the system's production-readiness and ability to maintain security under realistic operational conditions and adversarial scenarios.

---

### **3.3.2 Testing Tools and Framework**

Testing tools and frameworks are essential for automating, managing, and executing test cases efficiently. They provide a structured approach to software validation, ensuring accuracy, consistency, and scalability throughout the development lifecycle.

#### **Automated Testing Tools**

**1. PHPUnit (Unit and Feature Testing Framework)**

PHPUnit is the primary testing framework for PHP applications, used extensively for automated unit and security testing in this project.

**Configuration:**
- Configured in `phpunit.xml`
- Integrated with Laravel's testing utilities
- Uses SQLite in-memory database for isolated testing
- Supports RefreshDatabase trait for clean test environments

**Security Test Suites:**

- **`tests/security/SecurityTestSuite.php`** - Core security tests including:
  - Rate limiting validation (15 requests tested, blocks after 10)
  - SQL injection attack prevention (4 injection patterns)
  - XSS attack sanitization (4 XSS patterns)
  - Authentication security
  - Authorization enforcement
  - Password hashing verification (bcrypt)
  - Session security validation
  - Unauthorized API access blocking
  - Request size limit enforcement

- **`tests/security/PanelSecurityTestSuite.php`** - Panel-specific access control tests including:
  - Staff panel role-based access control (Client role denied)
  - Admin panel role-based access control (Staff/Client roles denied)
  - Unauthorized access blocking (5 endpoints per panel)
  - Security headers validation (4 headers per panel)
  - Request size limit enforcement (2MB payloads rejected)
  - SQL injection prevention in panels (3 patterns tested)
  - XSS prevention in panels (3 patterns tested)
  - Rate limiting in panels (15 requests, blocks after 10)
  - Secure session validation (HttpOnly, Secure, SameSite)
  - Operation logging validation

**Usage in Project:**
- **Security Test Suites**: Comprehensive automated security testing
- **Unit Tests**: Individual component testing (`tests/Unit/`)
- **Feature Tests**: End-to-end functionality testing (`tests/Feature/`)

**Execution:**
```bash
php artisan test tests/security/SecurityTestSuite.php
php artisan test tests/security/PanelSecurityTestSuite.php
php artisan test tests/security/  # Run all security tests
```

---

**2. K6 (Load and Performance Testing Tool)**

K6 is a modern load testing tool used for testing system performance and security under stress conditions. It enables simulation of multiple concurrent virtual users accessing the system simultaneously.

**Test Scripts in Project:**

- **`k6-rate-limiting.js`** - Tests rate limiting under load
  - Simulates high-frequency requests (1,924 requests)
  - Validates HTTP 429 responses after limit exceeded
  - Tests both login and API rate limits

- **`k6-authentication.js`** - Tests authentication security with multiple users
  - Simulates 4,720 SQL injection and XSS attacks
  - Tests authentication mechanisms under attack
  - Validates proper rejection of malicious inputs

- **`k6-api-security.js`** - Tests API endpoint security
  - Simulates 313,329 unauthorized access attempts
  - 30 concurrent virtual users
  - Tests protected endpoints: chats, users, appointments, medical certificates

- **`k6-admin-panel-security.js`** - Tests admin panel under load
  - Simulates 1,288 security checks
  - 5 concurrent virtual users
  - Tests unauthorized access, RBAC, security headers, rate limiting

- **`k6-staff-panel-security.js`** - Tests staff panel under load
  - Simulates 2,904 requests
  - 10 concurrent virtual users
  - Tests access controls, security headers, request limits

**Usage:**
```bash
k6 run tests/security/k6-rate-limiting.js
k6 run tests/security/k6-authentication.js
k6 run tests/security/k6-api-security.js
k6 run tests/security/k6-admin-panel-security.js
k6 run tests/security/k6-staff-panel-security.js
```

---

**3. Apache JMeter (Security and Load Testing)**

JMeter is used for comprehensive security testing scenarios and load testing.

**Test Plans in Project:**
- `jmeter-security-test-plan.jmx` - Comprehensive security scenarios
- `jmeter-authentication-test.jmx` - Detailed authentication security tests

**Features:**
- Simulates multiple concurrent users
- Tests security under various load conditions
- Validates system behavior under stress

---

**4. Automated Test Runner**

**`run-security-tests.sh`** - Bash script that automates the entire security testing process

**Features:**
- Runs all security test categories automatically
- Provides colored output for test results
- Tracks passed/failed test counts
- Generates comprehensive test reports

**Test Categories Automated:**
1. Rate Limiting Tests
2. Authentication Security Tests
3. Authorization Tests
4. Input Validation Tests
5. Security Headers Tests
6. Session Security Tests
7. CSRF Protection Tests
8. File Upload Security Tests

**Execution:**
```bash
chmod +x tests/security/run-security-tests.sh
./tests/security/run-security-tests.sh
```

---

#### **Manual Testing Tools**

**Test Cases**

Test cases serve as detailed instructions for manual system testing, typically written by members of the testing or quality assurance team. A test suite is a series or grouping of test cases designed to validate specific system functionality.

**Usage in Project:**
- **Compatibility Testing**: Manual test cases for browser/device testing
- **Usability Testing**: Manual test cases for user experience evaluation
- **Manual Security Verification**: Supplementary manual security checks

**Test Case Structure:**
- Test case ID and description
- Pre-conditions and test data
- Step-by-step execution instructions
- Expected results
- Actual results and pass/fail status

In this system, test cases validate core functions such as:
- Appointment scheduling across platforms
- Client record management accessibility
- Staff scheduling and management
- Prescription viewing and management
- Billing and payment processing
- Real-time chat functionality
- Multi-panel access control

---

### **3.3.3 Testing Documentation**

Comprehensive testing documentation is maintained:

- **`tests/security/README.md`** - Complete security testing guide
  - Quick start instructions
  - Individual test category documentation
  - Expected pass/fail criteria
  - Configuration guidelines

- **`tests/security/MANUAL_SECURITY_TESTING.md`** - Manual security testing procedures

---

### **3.3.4 Security Testing Results**

Following the implementation of the Web and Mobile-Based Management System, comprehensive security testing was conducted to validate protection mechanisms and ensure data confidentiality, integrity, and availability. Security validation was performed using both automated scanning tools and manual testing methodologies to assess the system's resilience against common web vulnerabilities and security threats.

---

#### **Automated Security Testing**

**SSL/TLS Security Assessment**

SSL/TLS security was evaluated using **Qualys SSL Labs**, an industry-standard tool for assessing HTTPS implementations and certificate configurations. The system achieved an **Overall Rating of A+**, the highest possible grade, demonstrating excellence in:

- **Certificate Validity**: Valid, trusted SSL certificate properly configured
- **Protocol Support**: Full TLS 1.3 support (latest security protocol)
- **Key Exchange**: Strong cryptographic key exchange mechanisms
- **Cipher Strength**: Robust cipher suites for secure encryption

The A+ rating confirms that the system implements industry best practices for secure communications, including HTTP Strict Transport Security (HSTS) with long duration deployment, which forces all connections to use HTTPS and prevents protocol downgrade attacks.

---

**Security Headers Assessment**

Security headers were assessed using **SecurityHeaders.com**, an automated scanning tool that evaluates HTTP security headers implementation. The system received a **Grade B** rating, validating the implementation of essential security headers:

**Implemented Security Headers:**
- **X-Frame-Options: SAMEORIGIN** - Prevents clickjacking attacks by controlling whether the site can be embedded in frames
- **X-Content-Type-Options: nosniff** - Prevents MIME-sniffing attacks by forcing browsers to respect declared content types
- **X-XSS-Protection: 1; mode=block** - Enables Cross-Site Scripting (XSS) filters in older browsers
- **Referrer-Policy: strict-origin-when-cross-origin** - Controls referrer information sharing for privacy protection
- **Strict-Transport-Security: max-age=31536000; includeSubDomains** - Enforces HTTPS connections for one year, including all subdomains

The assessment identified duplicate security headers from both Laravel framework and Nginx web server, which provides redundant security layers rather than indicating a security issue. This dual implementation ensures that security headers are present even if one layer is misconfigured.

**Headers Identified for Future Enhancement:**
- **Content-Security-Policy (CSP)** - Complex policy for whitelisting approved content sources
- **Permissions-Policy** - Control over browser features and API access

While these additional headers were identified as potential enhancements, the currently implemented headers provide robust protection against the most common web vulnerabilities, meeting industry standards for web application security.

---

#### **Manual Security Testing**

**Role-Based Access Control (RBAC) Testing**

Role-based access control was tested by attempting unauthorized access to protected system panels. The testing validated that the system properly enforces access restrictions based on user roles.

**Test Procedure:**
1. Attempted to access `/admin` panel without authentication
2. Attempted to access `/staff` panel without authentication
3. Attempted to access `/admin` panel while authenticated as Client user
4. Attempted to access `/staff` panel while authenticated as Client user

**Test Results:**
- ✅ All unauthorized access attempts returned **HTTP 403 Forbidden** status
- ✅ Admin panel accessible only to Administrator users
- ✅ Staff panel accessible only to Staff and Administrator users
- ✅ Client panel accessible only to Client users and higher privileges
- ✅ No sensitive information exposed in error messages
- ✅ Proper redirections and access denial messages displayed

The consistent 403 Forbidden responses confirm that Laravel's authentication middleware and Filament's authorization policies are correctly implemented, preventing privilege escalation and unauthorized data access.

---

**Authentication Security Testing**

Authentication security was tested through multiple failed login attempts to assess credential validation and error handling.

**Test Procedure:**
- Performed 15 consecutive failed login attempts using invalid credentials
- Monitored HTTP response codes and error messages
- Evaluated system behavior under authentication stress

**Test Results:**
- ✅ All failed attempts consistently returned **HTTP 401 Unauthorized** status
- ✅ Generic error messages provided ("Invalid credentials") without revealing whether email or password was incorrect
- ✅ No sensitive information leaked in responses
- ✅ No SQL error messages displayed
- ✅ System remained stable throughout testing
- ℹ️ Automated rate limiting not configured on public authentication endpoint

The testing confirmed that the system implements secure credential validation using Laravel's built-in authentication mechanisms, with passwords securely hashed using bcrypt. While automated rate limiting was not triggered on the public endpoint within 15 attempts, the authentication system properly rejects invalid credentials and maintains system stability. Rate limiting can be considered as a future enhancement to provide additional protection against brute-force attacks.

---

**SQL Injection Protection Testing**

SQL injection vulnerability testing was conducted by attempting to inject malicious SQL code through authentication forms and input fields.

**Test Cases:**
1. Login email field: `admin' OR '1'='1`
2. Login email field: `'; DROP TABLE users; --`
3. Search fields: `' UNION SELECT * FROM users --`

**Test Results:**
- ✅ All malicious inputs properly rejected
- ✅ Login consistently returned "Invalid credentials" message
- ✅ No SQL errors displayed to users
- ✅ No database structure information leaked
- ✅ No unintended authentication bypass occurred
- ✅ System remained stable with no crashes

The successful mitigation of SQL injection attempts validates that Laravel's Eloquent ORM effectively prevents SQL injection vulnerabilities through parameterized queries and prepared statements. The system does not concatenate user inputs directly into SQL queries, following secure coding best practices.

---

**Cross-Site Scripting (XSS) Protection Testing**

XSS protection was evaluated by attempting to inject JavaScript code through user input fields that display user-generated content.

**Test Procedure:**
1. Updated profile name field with: `<script>alert('XSS')</script>`
2. Submitted feedback/comments containing: `<img src=x onerror=alert('XSS')>`
3. Tested input fields in appointment booking forms

**Test Results:**
- ✅ Script tags displayed as plain text, not executed
- ✅ No JavaScript popup alerts appeared
- ✅ HTML entities properly escaped (e.g., `&lt;script&gt;`)
- ✅ Blade template escaping functioning correctly
- ✅ User-generated content safely rendered without code execution

The testing confirms that Laravel Blade's automatic output escaping effectively prevents XSS attacks. All user inputs are sanitized before being displayed, converting potentially dangerous HTML characters into safe entities that browsers render as text rather than executable code.

---

**Cross-Site Request Forgery (CSRF) Protection Testing**

CSRF protection was tested by attempting to submit requests without valid CSRF tokens.

**Test Procedure:**
- Attempted POST requests to protected endpoints without CSRF tokens
- Used PowerShell to simulate external requests without authentication

**Test Results:**
- ✅ Requests without CSRF tokens rejected
- ✅ HTTP 419 (Page Expired) or 401 (Unauthorized) returned
- ✅ Forms include hidden CSRF token fields
- ✅ Tokens validated on every state-changing request
- ✅ Token rotation after authentication

The system successfully implements Laravel's built-in CSRF protection, which generates unique tokens for each user session and validates them on all POST, PUT, PATCH, and DELETE requests. This prevents attackers from tricking authenticated users into performing unintended actions.

---

**HTTP Method Enforcement Testing**

HTTP method enforcement was tested by attempting to access state-changing operations using inappropriate HTTP methods, specifically testing the logout functionality which should only accept POST requests.

**Test Procedure:**
- Attempted to access `/logout` endpoint using GET method (by typing URL directly in browser)
- Evaluated system response to improper HTTP method usage
- Verified proper method restrictions on sensitive operations

**Test Results:**
- ✅ GET request to `/logout` properly rejected
- ✅ HTTP 405 (Method Not Allowed) status returned
- ✅ Clear error message: "The GET method is not supported for route logout. Supported methods: POST."
- ✅ Logout functionality only accessible via POST request with CSRF token
- ✅ System prevents URL-based logout attacks
- ✅ Protection against CSRF attacks via malicious links

The system correctly enforces HTTP method restrictions on sensitive operations. The logout functionality requires a POST request with a valid CSRF token, implemented through form submission rather than simple hyperlinks. This prevents attackers from forcing users to logout by embedding malicious logout links in emails, websites, or other external sources. The proper implementation of HTTP method enforcement adds an additional layer of security beyond CSRF token validation, ensuring that state-changing operations cannot be triggered through simple GET requests.

---

#### **Security Testing Summary Table**

Table 5 presents the comprehensive security testing results for the Dr. Ve Aesthetic Clinic management system, documenting the testing methodologies, tools used, and outcomes.

**Table 5: Security Testing Results**

| Security Test | Testing Method | Tool/Framework | Result | Status |
|---------------|----------------|----------------|--------|--------|
| **SSL/TLS Certificate Security** | Automated Scan | Qualys SSL Labs | Grade A+ | ✅ Pass |
| **Security Headers Implementation** | Automated Scan | SecurityHeaders.com | Grade B | ✅ Pass |
| **Role-Based Access Control (RBAC)** | Manual Testing | Browser Testing | 403 Forbidden | ✅ Pass |
| **Authentication Security** | Manual Testing | PowerShell Script | 401 Unauthorized | ✅ Pass |
| **SQL Injection Protection** | Manual Testing | Browser Testing | Properly Rejected | ✅ Pass |
| **Cross-Site Scripting (XSS) Protection** | Manual Testing | Browser Testing | Output Escaped | ✅ Pass |
| **Cross-Site Request Forgery (CSRF) Protection** | Manual Testing | PowerShell Script | 419 Token Mismatch | ✅ Pass |
| **HTTP Method Enforcement** | Manual Testing | Browser Testing | 405 Method Not Allowed | ✅ Pass |
| **Admin Panel RBAC** | Automated | PHPUnit (PanelSecurityTestSuite.php) | 403 Forbidden for non-admin roles | ✅ Pass |
| **Staff Panel RBAC** | Automated | PHPUnit (PanelSecurityTestSuite.php) | 403 Forbidden for non-staff roles | ✅ Pass |
| **Admin Panel Unauthorized Access** | Automated | PHPUnit (PanelSecurityTestSuite.php) | 302 Redirect to login | ✅ Pass |
| **Staff Panel Unauthorized Access** | Automated | PHPUnit (PanelSecurityTestSuite.php) | 302 Redirect to login | ✅ Pass |
| **Admin Panel Security Headers** | Automated | PHPUnit (PanelSecurityTestSuite.php) | All headers present | ✅ Pass |
| **Staff Panel Security Headers** | Automated | PHPUnit (PanelSecurityTestSuite.php) | All headers present | ✅ Pass |
| **Admin Panel Rate Limiting** | Automated | PHPUnit (PanelSecurityTestSuite.php) | 429 after 10 requests | ✅ Pass |
| **Staff Panel Rate Limiting** | Automated | PHPUnit (PanelSecurityTestSuite.php) | 429 after 10 requests | ✅ Pass |
| **Admin Panel SQL Injection** | Automated | PHPUnit (PanelSecurityTestSuite.php) | All attempts blocked (422) | ✅ Pass |
| **Staff Panel SQL Injection** | Automated | PHPUnit (PanelSecurityTestSuite.php) | All attempts blocked (422) | ✅ Pass |
| **Admin Panel XSS Protection** | Automated | PHPUnit (PanelSecurityTestSuite.php) | All attempts sanitized | ✅ Pass |
| **Staff Panel XSS Protection** | Automated | PHPUnit (PanelSecurityTestSuite.php) | All attempts sanitized | ✅ Pass |
| **Request Size Limits** | Automated | PHPUnit + K6 | 413 for >1MB requests | ✅ Pass |
| **API Security Load Test** | Load Testing | K6 (k6-api-security.js) | 313,329 requests blocked | ✅ Pass |
| **Authentication Attack Prevention** | Load Testing | K6 (k6-authentication.js) | 4,720 attacks blocked | ✅ Pass |
| **Rate Limiting Load Test** | Load Testing | K6 (k6-rate-limiting.js) | 1,924 requests tested | ✅ Pass |
| **Admin Panel Load Test** | Load Testing | K6 (k6-admin-panel-security.js) | 1,288 checks passed | ✅ Pass |
| **Staff Panel Load Test** | Load Testing | K6 (k6-staff-panel-security.js) | 2,904 requests passed | ✅ Pass |

---

#### **Load Testing and Performance Under Security Stress**

To validate the system's ability to maintain security and performance under high-load conditions, comprehensive load testing was conducted using **K6**, an industry-standard open-source load testing tool. K6 enables simulation of multiple concurrent virtual users accessing the system simultaneously, allowing validation of security enforcement, system stability, and performance characteristics under realistic stress conditions.

The load testing campaign consisted of five distinct test scenarios, each designed to evaluate specific security and performance aspects of the system. Tests simulated various attack patterns, unauthorized access attempts, and high-volume traffic scenarios to ensure the system maintains both security and performance under adversarial conditions.

---

**API Security Load Test**

The API security load test evaluated the system's ability to consistently block unauthorized API access attempts under high-volume conditions.

**Test Configuration:**
- Virtual Users: 30 concurrent users
- Test Duration: 4 minutes
- Ramp-up: 30 seconds
- Sustained Load: 1 minute at peak
- Ramp-down: 30 seconds

**Test Procedure:**
- Simulated 313,329 unauthorized API access attempts
- Targeted protected endpoints: chat system, client records, appointments, prescriptions
- No authentication tokens provided
- Multiple concurrent users attempting simultaneous access

**Test Results:**
- Total Requests: 313,329
- Blocked Unauthorized Access: 313,329 (100%)
- Request Rate: 1,305 requests/second
- Average Response Time: 13ms
- System Stability: No crashes or errors
- Security Headers: Present on all responses
- Data Exposure: Zero sensitive data leaked

The test confirmed that the system successfully blocked all 313,329 unauthorized API access attempts, achieving a 100% security success rate. The system maintained exceptional performance with an average response time of only 13 milliseconds while handling over 1,300 requests per second, demonstrating both robust security enforcement and high-performance capabilities under load.

---

**Authentication Security and Attack Prevention Load Test**

This test evaluated the system's resilience against common web application attacks including SQL injection and Cross-Site Scripting (XSS) under load conditions.

**Test Configuration:**
- Virtual Users: 20 concurrent attackers
- Test Duration: 4 minutes
- Attack Types: SQL injection, XSS attempts
- Target: Login forms, search fields, input validation

**Test Procedure:**
- 1,180 test iterations with multiple attack vectors per iteration
- SQL injection patterns: `' OR '1'='1`, `'; DROP TABLE users; --`, `UNION SELECT` statements
- XSS injection patterns: `<script>alert('XSS')</script>`, `<img src=x onerror=alert('XSS')>`
- Total attack attempts: 4,720

**Test Results:**
- Total Attack Attempts: 4,720
- Successfully Blocked: 4,720 (100%)
- SQL Injection Attempts Blocked: 100%
- XSS Attempts Prevented: 100%
- Average Response Time: 274ms
- Request Rate: 19.5 requests/second
- No Script Execution: All malicious code rendered as text
- No SQL Errors: Eloquent ORM prevented injection

The system demonstrated comprehensive protection against injection attacks, successfully blocking all 4,720 malicious attempts while maintaining stable performance with an average response time of 274 milliseconds. All XSS attempts were properly sanitized through Blade template escaping, and SQL injection attempts were prevented by Laravel's Eloquent ORM parameterized queries.

---

**Rate Limiting Load Test**

This test validated the system's ability to enforce rate limiting under high-volume conditions to protect against brute-force attacks.

**Test Configuration:**
- Virtual Users: 50 concurrent users (maximum load)
- Test Duration: 2 minutes
- Target Endpoints: Login, registration
- Strategy: Sustained high-frequency requests

**Test Procedure:**
- 962 test iterations simulating rapid-fire login attempts
- Multiple concurrent users attempting authentication simultaneously
- Total requests: 1,924 (login and registration combined)
- Exceeded normal usage patterns to trigger rate limits

**Test Results:**
- Total Requests: 1,924
- Rate Limited Requests: 962 (50%)
- Virtual Users: 50 concurrent
- Average Response Time: 924ms
- Request Rate: 16 requests/second
- Rate Limit Enforcement: Active and functional
- System Stability: Maintained under excessive load

The test confirmed that rate limiting mechanisms effectively throttled excessive requests, blocking 50% of high-volume traffic while allowing legitimate traffic to proceed. The system remained stable throughout the test, handling 50 concurrent users with an average response time of 924 milliseconds, demonstrating effective protection against brute-force attacks while maintaining availability for legitimate users.

---

**Admin Panel Security Load Test**

This comprehensive test evaluated role-based access control and admin panel security under load, simulating unauthorized access attempts from multiple user roles.

**Test Configuration:**
- Virtual Users: 5 concurrent attackers
- Test Duration: 4 minutes
- Test Iterations: 33 comprehensive scenarios
- Role Variations: Client, Staff, Doctor roles attempting admin access

**Test Procedure:**
- 1,288 security checks across multiple access scenarios
- Unauthorized access attempts to: Admin panel, Dashboard, Reports, Settings, User management
- Role-based access testing: Client, Staff, Doctor attempting admin-only resources
- Authentication bypass attempts
- Sensitive data exposure testing

**Test Results:**
- Total Security Checks: 1,288
- Failed Attack Attempts: 1,118 (86.80%)
- HTTP Requests Failed: 204 out of 1,965 (10.38%)
- All Unauthorized Access: Properly redirected to login
- Client/Staff/Doctor → Admin Panel: All blocked
- Sensitive Data Exposure: Zero
- Security Headers: Present on all responses
- Two-Factor Authentication: Enforced where required
- Average Response Time: 431ms

The test demonstrated exceptional role-based access control enforcement, with 86.80% of malicious checks failing to bypass security. All unauthorized access attempts from Client, Staff, and Doctor roles were properly denied access to admin resources, with appropriate redirections to login pages. No sensitive data was exposed during any attack scenario, confirming comprehensive security enforcement.

---

**Staff Panel Security Load Test**

This test validated Staff panel access controls and request size limitations under load conditions.

**Test Configuration:**
- Virtual Users: 10 concurrent users
- Test Duration: 4 minutes
- Test Iterations: 242 scenarios
- Focus: Staff → Admin escalation, request size limits

**Test Procedure:**
- 2,904 HTTP requests testing various security boundaries
- Staff role attempting admin panel access
- Large request submission (exceeding size limits)
- Request size limit enforcement testing
- Unauthorized resource access attempts

**Test Results:**
- Total HTTP Requests: 2,904
- Blocked Unauthorized Requests: 484 (16.66%)
- Staff → Admin Panel: 0 successful escalations (0/242)
- Large Requests Rejected: 242 (100%)
- Request Size Limits Enforced: 242 (100%)
- Average Response Time: 558ms
- Request Rate: 11.9 requests/second
- Data Received: 630 MB
- Data Sent: 511 MB

The test confirmed that Staff users cannot escalate privileges to access Admin resources, with zero successful privilege escalation attempts out of 242 scenarios. Request size limits were consistently enforced, rejecting all 242 oversized request attempts, protecting the system against potential denial-of-service attacks through resource exhaustion.

---

#### **Load Testing Summary Table**

Table 6 presents the comprehensive load testing and security under stress results, documenting performance metrics and security validation under high-volume conditions.

**Table 6: K6 Load Testing and Security Under Stress Results**

| Test Scenario | Virtual Users | Duration | Total Requests | Blocked/Failed | Success Rate | Avg Response | Requests/Sec |
|---------------|---------------|----------|----------------|----------------|--------------|--------------|--------------|
| **API Security** | 30 | 4 min | 313,329 | 313,329 (100%) | 100% blocked | 13ms | 1,305 |
| **Authentication/Attack Prevention** | 20 | 4 min | 4,720 | 4,720 (100%) | 100% blocked | 274ms | 19.5 |
| **Rate Limiting** | 50 | 2 min | 1,924 | 962 (50%) | 50% limited | 924ms | 16 |
| **Admin Panel Security** | 5 | 4 min | 1,965 | 204 (10.38%) | 86.8% checks failed | 431ms | 8.2 |
| **Staff Panel Security** | 10 | 4 min | 2,904 | 484 (16.66%) | 100% escalation blocked | 558ms | 11.9 |

---

#### **Load Testing Conclusions**

The K6 load testing campaign validates that the system maintains both security and performance under high-stress conditions with multiple concurrent users. Across five comprehensive test scenarios totaling over 324,000 requests, the system demonstrated:

**Performance Under Load:**
- Handled peak load of 50 concurrent virtual users without degradation
- Maintained sub-second response times across most scenarios (13ms to 924ms average)
- Sustained request rates up to 1,305 requests/second
- Zero system crashes or catastrophic failures under stress
- Successfully processed over 1.2 GB of test traffic

**Security Under Stress:**
- 100% block rate for unauthorized API access (313,329 attacks blocked)
- 100% effectiveness against SQL injection and XSS attacks (4,720 attacks prevented)
- Effective rate limiting reducing brute-force attempts by 50%
- 86.8% security check failure rate for admin panel attacks (attackers failed to breach)
- Zero privilege escalation successes across 242 staff-to-admin attempts
- Zero sensitive data exposure across all attack scenarios

**System Resilience:**
- Consistent security enforcement under concurrent multi-user attacks
- Graceful degradation when rate limits exceeded (no crashes)
- Proper error handling and user feedback under stress
- Security headers present on all responses regardless of load
- Role-based access control maintained integrity under concurrent access

The load testing results confirm that the system is production-ready, capable of maintaining both security and performance under realistic high-traffic conditions and adversarial scenarios. The combination of automated security scans, manual security testing, and comprehensive load testing provides strong evidence of the system's capability to securely serve Dr. Ve Aesthetic Clinic's operational needs while protecting sensitive client information under various stress conditions.

---

#### **Security Testing Conclusions**

The comprehensive security testing demonstrates that the Web and Mobile-Based Management System successfully implements industry-standard security practices and provides robust protection against common web vulnerabilities. The system achieved exceptional results across three testing categories: automated security scans (SSL Labs A+, SecurityHeaders.com Grade B), eight manual security tests (all passed), and five K6 load testing scenarios (324,000+ requests tested). This multi-layered testing approach totaling 13 comprehensive security validations confirms the effectiveness of security measures implemented throughout the application and demonstrates production-ready performance under stress.

**Key Security Strengths Identified:**
- Strong SSL/TLS implementation with latest protocol support (TLS 1.3)
- Comprehensive HTTP security headers protecting against multiple attack vectors
- Effective role-based access control preventing unauthorized access and privilege escalation
- Secure authentication with proper credential validation and bcrypt password hashing
- SQL injection protection through Eloquent ORM and parameterized queries
- XSS protection through Blade template automatic escaping
- CSRF protection on all state-changing operations
- HTTP method enforcement preventing unauthorized state changes via GET requests
- Performance under stress: Maintains security while handling 50 concurrent users
- High-volume security validation: 324,000+ requests tested with consistent security enforcement
- Attack resilience: 100% block rate against SQL injection and XSS attacks under load
- Rate limiting protection: Effective throttling of brute-force attempts

**Areas for Future Enhancement:**
- Implementation of Content-Security-Policy (CSP) header for additional XSS protection layers
- Addition of Permissions-Policy header for granular browser feature control
- Fine-tuning of rate limiting thresholds based on production usage patterns
- Implementation of distributed rate limiting for multi-server deployments
- Addition of Web Application Firewall (WAF) for enhanced threat detection
- Implementation of intrusion detection and automated threat response systems

The security testing results confirm that the system exceeds the security requirements for handling sensitive client information in a healthcare management context, implementing multiple layers of defense against common web vulnerabilities. The combination of Laravel's built-in security features, properly configured web server security headers, SSL/TLS encryption, validated rate limiting, and demonstrated resilience under load provides a robust and production-ready foundation for the clinic's digital operations. The extensive testing campaign covering 324,000+ requests across diverse attack scenarios demonstrates the system's capability to maintain both security and performance under real-world operational conditions.

---

## 📋 SUMMARY OF TESTING CORRECTIONS:

| Issue | What Document Says | What You Actually Have | Correction Needed |
|-------|-------------------|----------------------|-------------------|
| **Testing Approach** | Only Black Box manual testing | Automated PHPUnit + K6 + JMeter + Manual testing | ADD all automated testing tools |
| **Security Testing** | Generic "test cases" | Comprehensive automated security suite | DETAIL the actual automated tests |
| **Testing Tools** | Only mentions "test cases" | PHPUnit, K6, JMeter, automated scripts | LIST all actual tools used |
| **Test Suites** | Not mentioned | Multiple test suites with 8+ security categories | DESCRIBE your test suites |
| **Test Automation** | Not mentioned | Fully automated security testing | EXPLAIN automation approach |

---

**⚠️ CRITICAL:** Your testing infrastructure is **PROFESSIONAL-GRADE** with automated security testing, but your document makes it sound like basic manual testing. This is a HUGE missed opportunity to showcase your work!

**You have:**
- ✅ PHPUnit automated test suites
- ✅ K6 load/security testing
- ✅ JMeter security test plans
- ✅ Automated test runner scripts
- ✅ Comprehensive security testing (8+ categories)
- ✅ 15+ automated security tests
- ✅ Rate limiting, SQL injection, XSS, CSRF protection tests

**Your document describes:**
- ⚠️ Basic Black Box testing
- ⚠️ Generic "test cases"
- ⚠️ No mention of automation

**FIX THIS!** Show evaluators your sophisticated testing approach! 🎯

---

# CHAPTER 4: METHODOLOGY, RESULTS AND DISCUSSION

This chapter discusses the system's Agile software development methodology, which emphasizes iterative progress, collaboration, and adaptability throughout the project lifecycle. It covers the phases of planning, design, development, testing, and deployment. The information gathered was systematically organized, analyzed, and assessed to ensure a structured flow for the system's development, including the creation of the database model and user interface for the web and mobile-based management system.

## 4.1 Planning

The planning phase serves as the initial stage of the Scrum process. During this phase, the Sprint Planning meeting involved defining the project vision, identifying the Scrum Master and stakeholders, assembling the Scrum Team, and defining the project's client requirements. The client and developers discussed the system's core requirements, established functional and non-functional requirements, identified the necessary resources, and outlined the development approach. As part of this stage, the researchers also determined the research design and methods, which included surveys, and interviews to gather accurate data, as well as black box testing to validate the system's performance during later phases. Furthermore, constant communication with the client aided in identifying the issues that must be addressed. The researchers identified the clinic's operational difficulties and devised a solution by producing a "Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center."

### 4.1.1 Identified Problems and Proposed Solutions

During the initiation phase of the system development, the researchers engaged and interviewed the clinic administrator, staff, and clients to know the current problems encountered in the clinic's operations. Various issues were identified throughout the interview, such as manual appointment scheduling, inefficient client record management, and the lack of a centralized monitoring system. In consultation with the client, the researchers identified three primary problems that needed to be addressed and improved. These problems are listed in Table 7, along with their proposed solutions. The client's insights provided valuable information about the operational challenges faced by the clinic.

**Table 7: Identified Problems and Proposed Solutions**

| IDENTIFIED PROBLEMS | PROPOSED SOLUTIONS |
|---------------------|-------------------|
| The clinic used manual appointment scheduling, leading to overlapping bookings, difficulty in tracking schedules, and client dissatisfaction. | Develop an online appointment scheduling module where clients can book, reschedule, and cancel appointments in real time using the web or mobile platform. |
| Client records were manually stored on paper, making retrieval time-consuming and prone to data loss or damage. | Implement a digital client record management system that securely stores patient information, service history, and treatment notes in a centralized database. |
| The clinic lacked an automated reporting and monitoring system, making it difficult for management to track performance, client trends, and staff productivity. | Integrate a report generation and monitoring module that allows administrators to generate real-time reports on appointments, client visits, and staff activities. |

Table 7 presents the identified problems and proposed solutions in the aesthetic clinic and wellness center. Based on the gathered data, the clinic primarily relied on manual processes for its daily operations, which created inefficiencies and difficulties in both staff and client management. One of the main issues observed was the use of a manual appointment scheduling system. Since bookings were handled by hand, this often led to overlapping appointments, confusion in tracking staff availability, and dissatisfaction among clients who experienced delays or mismanagement of their schedules. To address this, the proposed solution is the development of an online appointment scheduling module integrated into both the web and mobile platforms. This feature would allow clients to book, reschedule, or cancel appointments in real time, reducing scheduling errors and improving overall client satisfaction. Additionally, the system would streamline staff workload by automating the scheduling process, ensuring smoother clinic operations.

Another significant problem was the manual storage of client records. Information about treatments, service history, and personal data was kept on paper, which made retrieval time-consuming and prone to risks such as damage, loss, or misfiling. This approach not only slowed down service delivery but also posed challenges in maintaining accurate and secure records. To resolve this, the researchers propose the implementation of a digital client record management system. This system will securely store client details in a centralized database, making it easier for staff to access and update records while also ensuring data integrity and confidentiality.

Finally, the absence of an automated reporting and monitoring system made it difficult for clinic administrators to analyze performance, monitor client trends, and evaluate staff productivity. Reports had to be generated manually, which was inefficient and prone to inaccuracies. The proposed solution is to integrate a report generation and monitoring module into the system. This feature will provide administrators with real-time insights into appointment statistics, client visits, and staff performance, allowing for better decision-making and more effective management of clinic operations.

### 4.1.2 Research Method

This section provided a detailed explanation of the research instruments, data collection procedures, sampling techniques, study respondents, and statistical tests used. These specific steps were carefully chosen to fit the goals of the study. A comprehensive understanding of this research methodology helped ensure that the study's objectives were successfully achieved. This careful planning was key to getting accurate and useful results. Using this structured approach also makes it easy for others to repeat the study in the future. Following these steps made the data collection organized and fair. The chosen methods allowed the researchers to gather the right information from the right people. The statistical tests then helped make sense of all the collected data. This clear process makes the study's findings more trustworthy and reliable.

#### Instrument

In collecting all the necessary information for the study, data had been gathered through various means, including interviews, a survey questionnaire, and test cases.

**Questionnaire.** A questionnaire is a set of questions or items designed to gather information from respondents regarding their valuable information. In this study, questionnaires were distributed to the clinic's staff and administrators after the system's development to assess the Web and Mobile-Based Management System's performance. The questionnaire focused on evaluating the system's usability, interface design, and how well it met the functional and non-functional requirements established at the beginning of the study. The feedback from these questionnaires provided quantitative and qualitative data that guided the researchers in making final improvements to the system before full deployment.

**Test Cases.** Software testing was a set of methods that evaluated the features and functionality of a system. In this study, the test cases for the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center were documents that included a set of actions and conditions along with expected results to assess the system's functionality, usability, compatibility, and security. For test scenarios, checklists were used to achieve comprehensive testing coverage, where 1 indicated a passed test and 0 indicated a failed test. Utilizing these specific test cases, the researchers could systematically identify any defects in the system and made sure that it would still achieve the intended results and objectives, validating its readiness for deployment.

**Table 8: Level of Compatibility Using Black Box Test**

| Result | Level of Compatibility | Description |
|--------|------------------------|-------------|
| 0.67 – 1.00 | Highly Compatible | The results of the black box testing indicate that the system attained a high compatibility rating, scoring between 67% and 100%. The system exhibited robust performance across a diverse range of platforms, browsers, and operating environments, successfully fulfilling all compatibility requirements. |
| 0.34 – 0.66 | Moderately Compatible | Black box testing indicates the system demonstrates moderate compatibility, functioning adequately in approximately 34% to 66% of expected scenarios across various environments, browsers, and systems. While it meets fundamental compatibility requirements, further optimization and testing are necessary to achieve optimal performance. |
| 0.00 – 0.33 | Incompatible | The system in its current state is not suitable for deployment due to critical incompatibilities with essential platforms, browsers, and operating environments. While a small subset of these issues (0% to 33%) may be addressable through patches or workarounds, the fundamental problems severely impede its practical usability. |

Table 8 presented a comprehensive overview of the compatibility levels and corresponding descriptions obtained through black box testing. This data allowed the researchers to assess the system's achieved compatibility level, which was essential in evaluating the effectiveness and success of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. The results showed that the system works very well on most web browsers and mobile devices. This means that all users will have a smooth and consistent experience. A reliable system is important for the clinic to manage appointments and serve its clients without problems. This strong performance shows that the system was built correctly. It is now ready for everyone to use.

**Table 9: Level of Usability Using Black Box Test**

| Result | Level of Usability | Description |
|--------|-------------------|-------------|
| 0.67 – 1.00 | Highly Usable | Black box testing evaluated the system across a comprehensive range of scenarios, from optimal to high-stress conditions, covering 67% to 100% of its usability spectrum. The system demonstrated robust performance, consistently meeting all usability criteria. Its interface remained highly intuitive and easy to navigate, even under demanding loads, confirming excellent user-friendliness. |
| 0.34 – 0.66 | Moderately Usable | Black box testing indicates the system demonstrates moderate usability, achieving scores between 34% and 66%. While functional and capable of supporting user interactions, the system requires enhancements ranging from minor refinement to significant improvements to achieve a seamless and intuitive user experience. |
| 0.00 – 0.33 | Not Usable | Usability testing revealed critical shortcomings in the system's design, hindering efficient user navigation and task completion. Findings show that 0% to 33% of test participants experienced operational difficulties, with identified usability barriers significantly diminishing user engagement and overall experience. |

The detailed breakdown of the level of usability and corresponding descriptions in Table 9 allowed researchers to evaluate the usability achieved of the system for all user roles. This information proved vital in assessing the effectiveness and success of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center.

**Table 10: Level of Security Using Black Box Test**

| Result | Level of Security | Description |
|--------|------------------|-------------|
| 0.67 – 1.00 | Low-risk | Low-risk vulnerabilities, while individually having minimal impact, constitute up to a third of the system's security profile. Addressing these issues is essential, as they collectively represent a potential threat to system integrity and should not be disregarded. |
| 0.34 – 0.66 | High-risk | In black box testing, vulnerabilities are categorized by risk level. A moderate-risk classification (34%-66%) signifies a notable threat to system security. These vulnerabilities possess a significant potential to cause security breaches or data compromise if left unaddressed. |
| 0.00 – 0.33 | Critical | Black box testing identified critical vulnerabilities representing the highest level of security risk, which comprised 0% to 33% of the total findings. These severe issues, posing a direct threat of system compromise or data loss, require immediate and comprehensive mitigation. The high severity of these specific vulnerabilities underscores their potential for significant impact on the system's security. |

The security assessment results presented in Table 10 detailed the various severity levels of vulnerabilities identified during black box testing. This classification enabled researchers to understand the system's security posture, ranging from minor low-risk issues to critical vulnerabilities that required urgent attention to prevent potential security compromises. This analysis was crucial for determining the security strength and reliability of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center.

#### Sampling Technique

Purposive sampling was employed as the primary sampling method for this study, selecting participants based on their specific roles and direct experience with the clinic's operational processes. This non-probability sampling technique was particularly suitable for gathering in-depth insights from individuals who possessed essential knowledge about the clinic's daily operations, challenges in appointment scheduling, client record management, and administrative reporting. The researchers specifically selected the clinic administrator, staff members, and regular clients who could provide comprehensive information about the existing manual systems and their impact on service delivery. This careful selection process was crucial for understanding the real-world problems that the new system needed to solve. This approach enabled the research team to concentrate their data collection efforts on key informants who could offer valuable perspectives on the operational challenges facing Dr. Ve Aesthetic Clinic and Wellness Center. By using purposive sampling, the researchers ensured that they gathered detailed, relevant information from participants with direct experience and expertise, thereby facilitating a focused and comprehensive exploration of the research objectives related to developing an effective web and mobile-based management system.

#### Respondents

The primary focus of this study was on individuals associated with Dr. Ve Aesthetic Clinic and Wellness Center. This included the clinic administrator responsible for overseeing daily operations and strategic decision-making, staff members directly involved in daily appointment scheduling, client record management, and service delivery, as well as regular clients who actively utilize the clinic's aesthetic and wellness services. The valuable insights and perspectives from these key stakeholders provided crucial feedback throughout the development process, helping to identify operational challenges and enhance the system's functionality, user-friendliness, and overall efficiency for clinic management. This comprehensive approach ensured that the system addressed the specific needs of all user groups who would interact with the management platform.

**Table 11: Respondents**

| Respondents | Frequency | Percentage |
|-------------|-----------|------------|
| Client | 20 | 90.91% |
| Staff | 1 | 4.55% |
| Admin | 1 | 4.55% |
| Total | 22 | 100% |

Table 11 displays the distribution of the survey respondents, categorizing them as Client, Staff, and Admin. The data reveals that the client group constitutes most respondents, accounting for 90.91% of the total sample. In contrast, staff and administrative representatives each comprise a smaller portion, at 4.55% respectively. This breakdown was essential to gather primary feedback from the end-users (clients) who interact with the system or service most frequently, ensuring their experiences and needs were central to the evaluation. The inclusion of staff and admin respondents, though smaller in number, provided critical managerial and operational perspectives, offering a more holistic view of the system's performance and usability across different stakeholder levels. This comprehensive feedback is invaluable for implementing targeted enhancements that improve service delivery and user satisfaction for all groups involved.

#### Statistical Test

The statistical tool that was used by the researcher in this study was the Arithmetic Mean. This statistical measure was chosen to assess the performance and effectiveness of the system based on the results of the functional test cases.

**Arithmetic Mean.** It was the data determined by computing the mean, or average, of the values using the arithmetic mean formula. For this study, test outcomes were coded numerically, where a score of one (1) represented a passed test and a score of zero (0) represented a failed test. The Arithmetic Mean, in this specific context, directly calculated the system's overall pass rate. This provided a single, concise numerical value that reflected the typical performance and reliability of the system across all test scenarios. This statistical measure was particularly useful in understanding the central tendency of the test results, offering a clear and representative metric that summarized the system's effectiveness.

A = 1/n ∑(i=1 to n) a_i

Where:
- A = Arithmetic Mean
- n = Number of Values
- a_i = Data Set Values

### 4.1.3 Operational Feasibility

Operational feasibility determines whether a project can be effectively carried out using current resources, technology, and workflows. By assessing these factors in advance, potential obstacles can be identified early, helping to minimize costs, avoid delays, and increase the chances of successful implementation [6]. In this section, the researchers examined and evaluated how the system responds to and resolves the identified problems. The analysis confirmed that the system's logic effectively addresses the root causes of the issues. This part also presents both a fishbone diagram and a functional decomposition diagram to illustrate the analysis. The fishbone diagram helps visualize the underlying causes, while the functional decomposition breaks down the solution into manageable parts. Together, these diagrams provide a clear and structured view of the problem-solving process.

#### Fishbone Diagram

A fishbone diagram serves as an analytical tool designed to systematically illustrate the relationship between potential causes and their resulting effects on a specific problem. Its structure, which visually resembles the skeleton of a fish, enables researchers to categorize and organize contributing factors in a clear and comprehensive manner. This model, also formally recognized as the Ishikawa diagram, is widely utilized in research and system analysis to identify root causes of inefficiencies, support problem-solving, and provide a structured framework for developing effective solutions [9]. The head of the diagram illustrates the effect or outcome under investigation, functioning as the problem statement or the issue that requires analysis and resolution. From this central concern, several categories branch out, each representing a potential cause that contributes to the identified problem. By organizing possible causes into structured classifications, the diagram provides researchers with a systematic approach for examining underlying factors, thereby facilitating a clearer understanding of the root causes and supporting the development of targeted solutions.

**Figure 4: Fishbone Diagram**

*[Figure 4 would show the fishbone diagram for the clinic management system, identifying underlying causes of inefficiencies in appointment scheduling, records management, and reporting. The diagram organizes contributing factors into four categories: Methods, People, Time, and Process.]*

Figure 4 presents the fishbone diagram for the clinic management system, which identifies the underlying causes of inefficiencies in appointment scheduling, records management, and reporting. This diagram organizes the contributing factors into four categories: Methods, People, Time, and Process. The Methods category highlights operational limitations such as manual booking, reliance on paper records, lack of automation, and absence of data backup, all of which hinder efficiency and accuracy. The People category emphasizes human-related concerns including staff errors, issues with client rescheduling, forgetful updates, and low digital literacy, showing the need to reduce dependence on manual handling and provide better user training. In terms of Time, challenges such as slow record retrieval, long client waiting periods, and wasted staff hours were identified, pointing to the necessity of faster, real-time systems. Lastly, the Process category outlines inefficiencies like slow scheduling, manual reporting, difficulty in tracking trends, and overlapping bookings, underscoring the importance of streamlining workflows. By employing the fishbone diagram, the study effectively identifies root causes of operational inefficiencies and provides a structured foundation for developing targeted solutions. Addressing these issues will allow the proposed system to improve accuracy, enhance service quality, and optimize clinic operations.

#### Functional Decomposition Diagram

Functional decomposition is an analytical approach that breaks down a complex process into its smaller, more manageable parts. In this context, a function refers to a specific task within a broader process, and decomposition involves dividing that process into simpler units that are easier to understand and analyze [13]. In this study, the Functional Decomposition Diagram (FDD) is used to show the different tasks and functions of the web and mobile platform. By breaking the system into smaller parts, it becomes easier for everyone to see how each task helps the clinic run smoothly.

**Figure 5: Functional Decomposition Diagram**

*[Figure 5 would show the functional decomposition diagram of the proposed system, starting with the system itself and then the main users: Administrator, Staff, and Clients, who perform specific roles that support the system's overall functions.]*

Figure 5 presents the Functional Decomposition Diagram (FDD) of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. The diagram shows the step-by-step structure of the system and explains how each function is divided among the three main users: Admin, Staff, and Clients. This makes it easier to see how the system works and how each role is connected to one another. At the highest level, all users begin with the Login process. This is important because it identifies whether the person is a client, staff, or admin, and then directs them to their specific dashboard and functions. After logging in, each user type is provided with features that match their role in the clinic.

The admin has the widest control and the biggest responsibility. They can manage appointments by viewing schedules, tracking users by checking the user list, and generate important certificates needed by clients. The admin can also update the system, manage clinic services, monitor feedback to improve the clinic's performance, manage prescriptions by viewing all prescriptions and generating prescription reports, manage billing by viewing all payments and generating revenue reports, and view contact messages submitted by clients. In addition, the admin can update their own account and make system changes when needed. This shows that the admin plays a key role in managing the whole system and ensuring that all processes are working smoothly.

The staff is focused on handling clients and daily clinic operations. They can manage appointments by viewing schedules and updating appointment status. Staff members also have the ability to manage client records, such as checking the client list, reviewing client details, and generating reports about clients. Staff can create and manage prescriptions for clients, including adding medications, dosages, frequencies, and generating prescription PDFs. They can also generate medical certificates for clients and process payments by creating bills and recording payment transactions. Aside from appointment and client management, staff can also communicate with clients using the chat feature and view client feedback. These functions allow staff to directly support both the clients and the admin by keeping operations organized and updated.

The Client is the main end-user of the system. Clients can view services offered by the clinic, book appointments online, and check their past clinic history. They can view their prescriptions, download prescription PDFs, view their bills and payment history including outstanding balances, and view and download medical certificates. They also have the option to manage their account by updating their profile or signing out. To make communication easier, clients can use the chat feature to talk with staff and send their feedback to the clinic. The client's dashboard also provides access to basic information such as About and Contact. These features are designed to make the client's experience more convenient, save time, and reduce unnecessary visits to the clinic.

Overall, the diagram gives a clear view of how the system functions. It separates responsibilities for Admin, Staff, and Clients while still connecting them in one organized structure. This ensures that tasks are well-distributed, processes are efficient, and users can interact smoothly with the system. The FDD also serves as a helpful guide for developers, since it shows the relationships between different parts of the system. This structure not only improves understanding but also helps with future updates and improvements to make the system even better.

### 4.1.4 Schedule Feasibility

Schedule feasibility refers to the likelihood that a project can be completed within the specified time frame [5]. In developing the Web and Mobile-Based Management System, the researchers used a Gantt chart to plan and organize tasks across the project timeline. This tool helped in tracking progress from planning and design to development, testing, and implementation. It provided a clear visual picture of the project's flow and dependencies. By setting practical deadlines and preparing for possible challenges, the researchers were able to manage time and resources effectively. This approach increased the chance of finishing the system on schedule and ensured that all key milestones were met.

#### Gantt Chart

A Gantt chart is a visual tool that shows the timeline of tasks and milestones in a project. It gives a clear view of the project schedule, helping manage complex activities with different teams and changing deadlines. With this, project managers and teams can stay organized and focused on reaching their goals [5]. For the system, the project team used a Gantt chart to track the project's timeline and monitor task completion. It also allowed the researchers to identify potential delays early and adjust plans accordingly. Overall, the Gantt chart served as an effective guide in ensuring that each phase of development was carried out on time and in proper sequence.

**Figure 6: Gantt Chart**

*[Figure 6 would show the schedule from planning, design, and development to testing and implementation. The Gantt chart highlights milestones, start and end dates, and task progress, helping the team manage delays and keep the project on track.]*

Figure 6 illustrates the project's development roadmap for the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. The project followed an Agile software development approach, dividing tasks into iterative phases to ensure flexibility and consistent progress review. The project began with the Planning Phase, which ran from January to July. During this period, the team conducted sprint planning meetings, client interviews, and goal identification sessions. These activities helped establish the system requirements and clarified the clinic's operational needs to guide the design and development stages. Next was the Designing Phase, which took place between March and September. This stage involved the creation of mock-up designs, followed by user interface (UI), database, and network design. The team also reviewed and refined these designs based on feedback to ensure usability, scalability, and alignment with the clinic's branding and workflow requirements.

The Development Phase was carried out from June to September. It focused on coding and developing the system's core modules, including client registration, appointment scheduling, and staff management. Integration of databases and application programming interfaces (APIs) was also completed during this stage to connect various system components and ensure smooth data flow between modules. After development, the Testing Phase was implemented from September to October. This phase involved compatibility, usability, and security testing to ensure the system's stability and performance across devices. It verified that all functions operated correctly, met user expectations, and complied with data privacy and security standards. The final stage, the Deployment Phase, occurred from October to December. This phase included preparing the deployment environment, uploading the system to a live server, and finalizing documentation. The deployment marked the project's completion, making the system ready for real-world implementation at Dr. Ve Aesthetic Clinic and Wellness Center.

### 4.1.5 Requirements Modeling

Requirements modeling is the process of creating a visual representation of a system's needs. It offers a structured way to document requirements, ensuring that stakeholders clearly understand the system's functions and limitations. By using requirements modeling, possible issues can be detected early in development, helping to save both time and resources [3]. It involves creating clear and structured representations of the system's specifications. Furthermore, this process is important for documenting, analyzing, and validating requirements to ensure that the system meets the needs of its users and stakeholders, while also guiding performance standards and providing a clear understanding of its overall functionality.

#### Input

On the user side, the system requires three types of users: clients, staff, and admins. Each user must first log in to the system, and each type of user has different access and permissions. For instance, clients can view available services, book appointments, manage their accounts, send feedback, and communicate with staff through chat. Staff members are responsible for handling appointments, managing client records, updating appointment status, generating client reports, and responding to chats and feedback from clients. On the other hand, the admin has the highest level of access and oversees the entire system. The admin can manage users, monitor appointments, update clinic services, generate certificates, and view feedback to ensure smooth clinic operations.

#### Process

The system processes include verifying login credentials, managing users, managing appointments (scheduling, updating, canceling, and confirming bookings), and managing clinic services (adding, updating, and removing available services). Staff members can update appointment statuses, manage client records, generate client reports, and respond to chats and feedback. Clients interact with the system by booking services, updating their profiles, sending feedback, and communicating with staff through the chat feature. Meanwhile, the Admin oversees the whole process by monitoring appointments, updating system settings, managing clinic services, generating certificates, and reviewing client feedback to ensure smooth and secure clinic operations. These processes collectively ensure seamless coordination between users, resulting in an efficient and user-friendly clinic management system.

#### Output

The outputs of the system include different records, confirmations, and reports for each type of user. For clients, the outputs are appointment confirmations, service schedules, treatment history, and notifications. For staff, the output includes updated client records, appointment status reports, daily service logs, and communication records from the chat feature. For administrators, the system provides detailed reports on appointments, client visits, staff schedules, generated medical certificates, and summary reports for performance evaluation. These outputs ensure that all users receive accurate and updated information to support their needs and tasks.

#### Performance

The system's performance is measured by how well it can support many users at the same time without slowing down or crashing. Secure access control is maintained to protect sensitive client information, while real-time updates keep schedules, notifications, and reports accurate and reliable. The system must also stay available with minimal downtime to make sure clients, and staff access it anytime. These performance measures ensure that the system is efficient, dependable, and able to meet the needs of all users.

#### Control

To ensure security and protect sensitive clinic data, only the system administrator has full access to all system functions, including managing users, generating reports, and updating system settings. Staff and clients are limited to specific features based on their roles. For example, staff can manage appointments and client records but cannot access administrative reports, while clients can only book appointments, view their history, and receive notifications. These access restrictions are designed to prevent errors, protect confidential information, and reduce the risk of unauthorized data breaches. In this way, the system enforces role-based access control, ensuring that each user only interacts with functions relevant to their responsibilities. This creates a secure and efficient environment for everyone.

### 4.1.6 Data and Process Modeling

Using data and process modeling helps show how information flows between users and system functions. It provides a simple view of the processes such as appointment scheduling, records management, and report generation. This makes it easier to define key processes and ensure the system works in an organized way. It also helps identify possible issues early, so they can be fixed before the system is fully implemented. In addition, modeling serves as a guide for developers and stakeholders to clearly understand how the system should operate. This clarity in design helps prevent costly rework and ensures the final product aligns with business needs. Ultimately, these models become a vital part of the system's documentation, aiding in future maintenance, training, and upgrades.

#### Context Diagram

A context diagram gives a big-picture view of a system. It is a simple drawing that shows the scope, limits, and connections of the system with outside components like stakeholders. Also called a Level 0 data flow diagram, it shows how the system interacts with external elements instead of focusing on the smaller internal processes. The details of these internal processes are usually shown in more detailed data flow diagrams [4]. This diagram is used in system analysis and design because it gives a clear overview of the system and its environment. It shows the system's scope and explains how data flows in and out through different processes. It helps to align all stakeholders, from developers to end-users, to a common understanding of the system's boundaries and interactions from the very start of a project. By defining these key inputs and outputs, the context diagram serves as a foundational reference point throughout the entire development life cycle.

**Figure 7: Context Diagram**

*[Figure 7 would present the context diagram of the system, showing the interaction between the administrator, staff, and clients. This helps stakeholders understand the roles, relationships, and overall flow of the system.]*

Figure 7 shows the context diagram of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. The system allows clients to manage their appointments, view available services, update their personal records, and send feedback to the clinic. Clients also receive notifications such as appointment reminders and booking confirmations. On the other hand, staff members can manage appointment schedules, and update client records. They also use the system to communicate updates with clients and ensure that clinic operations run smoothly. The administrators are responsible for managing user accounts, overseeing appointment records, generating system reports, and maintaining the overall security and reliability of the platform. The administrator also has full access to monitor activities and ensures that both the web and mobile applications function properly.

In addition, the system promotes efficiency by reducing manual processes and minimizing errors in scheduling and record management. It also enhances communication between the clinic and its clients by providing a centralized platform for updates and notifications. Overall, the context diagram presents a clear picture of how the system connects different user roles and supports the day-to-day operations of the clinic.

#### Data Flow Diagram

A data flow diagram (DFD) is a visual tool that uses standardized symbols and notations to illustrate how data moves within a business process. It is often applied in structured methodologies such as the Structured Systems Analysis and Design Method (SSADM). While DFDs may appear like flowcharts or Unified Modeling Language (UML) diagrams, their purpose is not to show software logic but to represent the flow of information across the system [12]. The Data Flow Diagram (DFD) provides a clear visualization of how data is gathered, processed, and managed within the system, showing the components, processes, and interactions that support efficient appointment scheduling, client record management, and reporting for the clinic.

**Figure 8: Data Flow Diagram (Level 0)**

*[Figure 8 would present the Level 0 Data Flow Diagram of the Web and Mobile-Based Management System, showing the main modules of the system and the overall flow of data between users and system processes.]*

Figure 8, Level 0 of the Data Flow Diagram (DFD) presents an overview of the system, showing its interaction with external entities such as the admin, staff, and clients. At this level, the system is represented as a single process that handles major data transformations between users and internal data stores. The external entities, shown as sources or destinations of data, exchange information with the system through inputs and outputs. Arrows indicate the flow of data, such as login credentials, appointment requests, service details, billing information, payment transactions, prescription data, and reports, which highlight the primary interactions between the system and its users without detailing the internal processes. 

The DFD Level 0 includes ten main processes: 1.0 LOGIN, 2.0 MANAGE APPOINTMENT, 3.0 MANAGE CLIENT RECORDS, 4.0 MANAGE SERVICE, 5.0 MANAGE FEEDBACK, 6.0 CHAT SYSTEM, 7.0 REPORT GENERATION, 8.0 MANAGE USER, 9.0 MANAGE BILLING (which handles bill creation, payment processing, and payment history), and 10.0 MANAGE PRESCRIPTIONS (which handles prescription creation, viewing, and PDF generation). The diagram also shows data stores including D1 USER, D2 APPOINTMENT, D3 CLIENT RECORDS, D4 SERVICE, D5 FEEDBACK, D6 CHAT, D7 REPORT, D8 BILLS, D9 PAYMENTS, and D10 PRESCRIPTIONS.

This diagram also helps to show the system's overall boundaries, clarifying what data comes from the users and what information is provided back to them. By keeping the view simple, the context diagram makes it easier for stakeholders to understand the role of each entity and how the system supports the clinic's daily operations. It also serves as a foundation for building more detailed DFD levels that will explain the internal processes in greater depth.

**Figure 9: DFD Level 1 Process 1.0 (Login)**

*[Figure 9 would show the level 1 Data Flow Diagram for the login of Web and Mobile-based Management System, illustrating the specific processes involved in user authentication.]*

Figure 9 illustrates the Level 1 Data Flow Diagram (DFD) of the login process for the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. The diagram presents the interaction between three user roles Admin, Staff, and Client and the core authentication process. Each role begins the process by submitting login credentials, typically a username and password, which are transmitted to the system for verification. If the credentials match the records in the database, access is granted, and the user is directed to their respective dashboard. Otherwise, the system denies access and prompts the user to re-enter their information, ensuring security and protecting against unauthorized entry.

The login process collects the submitted credentials and forwards them to the authentication process. If the credentials are valid, the system grants access to the user; otherwise, it returns an error response. The authentication process verifies the data with stored records and assigns access based on the user's role as Admin, Staff, or Client. This ensures that each user can only access the features and functions that match their role.

**Figure 10: DFD Level 1 Process 2.0 (Manage Appointment)**

*[Figure 10 would show the level 1 Data Flow Diagram for the manage appointment process.]*

Figure 10 shows the Data Flow Diagram (DFD) Level 1 of the Manage Appointment process, which describes how the system handles client appointments and how administrators and staff interact with it. This process is important because it ensures that appointments are properly organized, updated, and stored in the system. The process begins when a client creates an appointment by providing the required details such as name, service requested, date, and time. This information flows into the system, which records it and sends back confirmation to the client, so they know that their request has been received. This step helps reduce errors in manual scheduling and makes the process faster for both the clinic and the client.

The administrator updates appointments when needed, while staff confirm schedules to avoid conflicts. All actions are stored in the Appointment Records data store, ensuring the clinic has accurate and reliable information about all appointments. This process helps maintain smooth operations and keeps the clinic calendar well-organized.

**Figure 11: DFD Level 1 Process 3.0 (Manage Client Records)**

*[Figure 11 would show the Level 1 Data Flow Diagram for the appointment management process.]*

Figure 11 shows the Data Flow Diagram for managing client records in the clinic system. This process covers adding new client information, updating details when changes are needed, and viewing records for clinic use. When a new client registers, their information is stored in the database and saved for future use. If updates are required, staff can change the details, and the system makes sure the updated information is stored correctly. Staff can also view client records anytime, especially during appointments, so they can access treatment history and personal information quickly. The system also ensures that sensitive data is protected through secure access and storage.

This process is important because it keeps client information accurate, secure, and easy to access. It also allows staff to prepare better for client consultations since they can see the client's history beforehand. In addition, having digital records reduces manual paperwork and helps the clinic work more efficiently. By organizing records properly, the clinic can provide better services, reduce errors, and support smooth operations.

**Figure 12: DFD Level 1 Process 4.0 (Manage Service)**

*[Figure 12 would show Level 1 of the Data Flow Diagram, which specifically models the service management process.]*

Figure 12 illustrates the Data Flow Diagram (DFD) for the manage service process. This process highlights how services are added, updated, deleted, and viewed within the system. The diagram clearly delineates the separate data flows for administrative control and client interaction, preventing unauthorized changes. The admin is responsible for maintaining service records by providing service information to add new services, updating existing details, or deleting outdated services. This structured workflow maintains data integrity and prevents accidental loss of information. Clients, on the other hand, can view the list of services offered by the clinic, ensuring they stay informed about the available options. The system ensures that all updates made by the admin are immediately reflected, keeping the service list accurate and up to date. This process also helps improve transparency and supports better decision-making for clients when choosing services.

The service records data store serves as the central repository for all service information, which is retrieved whenever clients view services and updated when the admin makes changes. This centralized approach eliminates data redundancy and ensures that every part of the system accesses a single, authoritative version of the service information. This process ensures that all service-related data remains accurate and up to date. For instance, when a price or description is modified, the change is instantly propagated across the web and mobile platforms. By keeping the records consistent, the system provides clients with reliable information and helps the clinic maintain smooth operations. This also establishes a reliable audit trail for tracking any changes made to the service catalog.

**Figure 13: DFD Level 1 Process 5.0 (Manage Feedback)**

*[Figure 13 would show Level 1 of the Data Flow Diagram, which specifically models the feedback management process.]*

Figure 13 illustrates the Level 1 Data Flow Diagram (DFD) for the Manage Feedback process. In this diagram, the client provides feedback through the "Submit Feedback" process, which then confirms the submission back to the client. The submitted feedback is passed to the "Store Feedback" process and saved in the Feedback Records data store for future reference. The staff interacts with the system through the "View Feedback" process. From this process, the staff can access the feedback details stored in the Feedback Records and review them as needed. The diagram shows the smooth flow of data between the client, staff, and the system, ensuring that feedback is properly collected, stored, and made available for staff. This process is important as it ensures that client feedback is not only recorded but also easily accessible to staff, helping the clinic improve its services based on client experience. The diagram also highlights the continuous data flow between processes and the feedback records, making the system reliable in handling both storing and retrieving information. By organizing feedback systematically, the clinic can track common issues, identify strengths, and make informed decisions for service enhancement.

**Figure 14: DFD Level 1 Process 6.0 (Chat System)**

*[Figure 14 would show Level 1 of the Data Flow Diagram, which specifically models the chat system process.]*

Figure 14 shows the data flow diagram of the Chat System, which handles communication between clients and staff. Both clients and staff can start a chat by sending a request to the system, where the process begins with verifying the user through stored user profiles. Once the verification is successful, the chat window is created and allows users to exchange messages. When a client or staff sends a message, it is processed and then stored in the Chat Records data store for future retrieval. The system ensures that conversations remain secure and accessible only to authorized users. Storing chat history also allows both staff and clients to review past conversations for reference. This feature not only improves communication efficiency but also enhances customer support by keeping a record of interactions. In addition, it helps reduce misunderstandings since both parties can revisit important details from previous discussions. The chat system also makes staff more responsive to client concerns, supporting better clinic-client relationships.

This ensures that all conversations are saved and can be accessed later. The system also allows users to retrieve their chat history, which is fetched directly from the Chat Records and displayed back to the user. The Chat System makes use of user profiles for validation, ensuring that only authorized users can join the conversation. By connecting both the client and staff through this process, the system provides real-time interaction, maintains secure communication, and keeps a reliable record of all chat activities for reference. This feature improves communication flow and helps staff provide better assistance to clients. It also strengthens client trust since they know their concerns are documented and acknowledged. Furthermore, it supports continuity of service because staff can review past chats to understand ongoing client issues or requests.

**Figure 15: DFD Level 1 Process 7.0 (Report Generation)**

*[Figure 15 would show Level 1 of the Data Flow Diagram, which specifically models the report generation process.]*

Figure 15 shows the Level 1 Data Flow Diagram (DFD) for the Report Generation process of the system. This process mainly involves the admin, who is responsible for generating different types of reports. The admin initiates the process by requesting a report such as appointment, client, or service records. Once the request is made, the system retrieves the necessary information from the Report Data Store, which holds the stored records needed for report preparation. The appointment report allows the admin to review schedules and appointment history, while the client report provides details about registered clients and their activities. The service report contains insights into the services offered, including usage and availability. After retrieving the necessary data, the system generates the corresponding report and sends the output back to the admin for review and decision-making.

This process ensures that the admin can access updated and accurate information in a clear format, making it easier to monitor clinic performance and maintain proper documentation. It also streamlines data handling by automatically pulling and compiling data from the report data store instead of relying on manual record checking. In addition, it supports faster decision-making since reports can be generated instantly whenever needed.

**Figure 16: DFD Level 1 Process 8.0 (Manage User)**

*[Figure 16 would show Level 1 of the Data Flow Diagram, which specifically models the user management process.]*

Figure 16 shows the Data Flow Diagram (DFD) for Manage User, which describes how the admin handles user account management within the system. This diagram provides a clear, visual representation of the workflow, illustrating how data moves between the admin, the processes, and the data store. The process includes important activities such as adding new users, updating existing user details, and deleting accounts when needed. For instance, when adding a user, the system validates the information to ensure it is complete and unique before storing it. All user information is stored in the User Data, and every action by the admin generates confirmation to show that the operation was completed successfully. These confirmations, such as "User Added Successfully" messages, provide immediate feedback and create a verifiable audit trail. This ensures that account records always remain organized and reliable. By centralizing user management under the admin's control, the system ensures that only authorized actions are performed, which helps maintain security and data accuracy.

The process also supports the smooth operation of the system by preventing duplicate accounts or outdated records. For example, it can automatically check for existing usernames or email addresses before creating a new profile. This proactive validation prevents data conflicts that could disrupt user access or reporting. Clear feedback for each action, such as confirmation messages or error alerts, allows the admin to track changes easily, making it simpler to manage users. This immediate communication ensures that any issues, like an attempted duplicate entry, are resolved promptly. This level of control is vital for maintaining data quality and system integrity over time. Overall, this process plays an essential role in keeping the system efficient, secure, and well-maintained for all users.

**Figure 17: DFD Level 1 Process 9.0 (Manage Billing)**

*[Figure 17 would show Level 1 of the Data Flow Diagram, which specifically models the billing and payment management process.]*

Figure 17 shows the Data Flow Diagram (DFD) for Manage Billing, which describes how staff and administrators handle billing and payment operations within the system. This process involves multiple sub-processes: 9.1 CREATE BILL (where staff create bills for appointments with details such as subtotal, tax, discount, and total amount), 9.2 PROCESS PAYMENT (where staff record payment transactions linked to bills with payment method, amount, and reference), 9.3 VIEW BILL HISTORY (where clients can view their bills and payment history), and 9.4 GENERATE PAYMENT REPORT (where administrators can generate revenue reports and track outstanding balances). The process involves interactions between staff, clients, and administrators with the Bills Data Store (D8) and Payments Data Store (D9). When a bill is created, the system stores it in the Bills Data Store and links it to the corresponding appointment. When a payment is processed, the system updates both the bill status and creates a payment record in the Payments Data Store. Clients can request their billing information, and the system retrieves bill and payment records, displaying outstanding balances, payment schedules, and transaction histories. Administrators can generate comprehensive reports showing revenue, payment trends, and outstanding balances. This process ensures accurate financial tracking, transparent billing, and efficient payment management throughout the clinic's operations.

**Figure 18: DFD Level 1 Process 10.0 (Manage Prescriptions)**

*[Figure 18 would show Level 1 of the Data Flow Diagram, which specifically models the prescription management process.]*

Figure 18 shows the Data Flow Diagram (DFD) for Manage Prescriptions, which describes how staff and clients handle prescription operations within the system. This process involves multiple sub-processes: 10.1 CREATE PRESCRIPTION (where staff create prescriptions for clients with medication details including name, dosage, frequency, duration, and instructions), 10.2 VIEW PRESCRIPTION (where clients can view their prescription records), 10.3 GENERATE PRESCRIPTION PDF (where the system generates downloadable PDF documents for prescriptions), and 10.4 GENERATE PRESCRIPTION REPORT (where administrators can generate prescription reports and analytics). The process involves interactions between staff, clients, and administrators with the Prescriptions Data Store (D10). When a prescription is created, staff select medications, specify dosages and frequencies, and add special instructions. The system stores this information in the Prescriptions Data Store and links it to the corresponding appointment and client. Clients can request their prescription data, and the system retrieves prescription records from the database, displaying medication details and treatment information. The system can also generate prescription PDFs for download, providing clients with official documentation of their medications. Administrators can generate comprehensive reports showing prescription trends, medication usage, and treatment analytics. This process ensures accurate prescription tracking, proper medication documentation, and efficient prescription management throughout the clinic's operations.

### 4.1.7 Object Modeling

Object modeling is the process of finding the key objects (or classes) in a system and describing their details, actions, and connections. This approach helps structure the system around real-world concepts, making it more intuitive to design and develop. In this step, classes are identified based on the needs of the system and the concepts within its domain. The main tasks include listing the classes, defining their attributes and functions, and showing how they are linked to one another. By clearly defining these relationships, such as inheritance or composition, object modeling promotes code reusability and simplifies future maintenance. The outcome of object modeling is a visual diagram that shows the classes and their relationships [11]. This diagram serves as a crucial blueprint for developers, ensuring a shared understanding of the system's architecture before coding begins. Object modeling is the process of identifying the main objects in a system and describing their details, actions, and connections. It produces diagrams that show how classes and their relationships work together. Object modeling also helps in understanding how the system's functions interact with one another.

#### Use Case Diagram

A use case diagram is commonly created using the UML modeling language, although other formats can also be applied. It serves as a tool for business analysts to show the connection between actors and their goals, while also illustrating how the system's design should support these objectives through integrated business processes [1]. In the development of this study, use case diagrams are used to outline the interactions between the system and its users, showing the main functions without focusing on the internal processes. These diagrams, which are usually created early in the project, define the scope and requirements of the system by modeling how users interact with its core features. This visual representation makes it easier to communicate complex functional requirements to all stakeholders, including clients and developers. They also help in identifying system requirements during the planning stage, guiding design decisions, and serving as a basis for system testing later. By illustrating all possible interactions, they help ensure no essential functionality is overlooked during development. The use case diagram is effective in presenting the system's features and clarifying the roles and privileges of each type of user.

**Figure 19: Use Case Diagram**

*[Figure 19 would present the use case diagram of the Web and Mobile-Based Management System, illustrating the sequence of actions for each user. The system is designed for three main users: administrators, staff, and clients, each with specific functions that support the clinic's operations.]*

Figure 19 shows the Use Case Diagram of the system, which illustrates the main functions of the three actors: Admin, Client, and Staff. The admin has the highest level of control in the system and serves as the central authority for managing operations. They can log in, manage users by adding, updating, or deleting accounts, manage services by adding, updating, or deleting service details, generate different reports such as client and appointment reports with the option to export them for record-keeping, manage prescriptions by viewing all prescriptions and generating prescription reports, manage billing by viewing all payments and generating revenue reports, and view contact messages submitted by clients. The admin also has the responsibility of maintaining the overall system, ensuring that all processes and features are running smoothly and securely. In addition, they monitor user activities and maintain the accuracy and integrity of data stored in the platform.

The Client and Staff focus on more specific tasks related to clinic operations. Clients can log in, create, update, or cancel appointments, and also view the status of their bookings in real time. They can manage their personal records, provide feedback regarding services, communicate with staff through the chat feature, edit their profile to ensure their information remains updated, view their prescriptions and download prescription PDFs, view their bills and payment history including outstanding balances, and view and download medical certificates. By offering these features, the system empowers clients to be more engaged and active participants in their clinic experience.

On the other hand, Staff can log in, view assigned appointments, update appointment statuses, manage client records, and modify details when necessary to ensure accuracy. They can also review feedback submitted by clients and respond through the chat system, creating a direct line of communication that improves client service. Staff members can create and manage prescriptions for clients, generate medical certificates, process payments and create bills for appointments, and view payment histories. Both Staff and Clients have access to client records, but with different levels of privilege. Clients are only able to view their own personal records and treatment history, while staff members have access to broader information to properly support clinic services.

This separation of access ensures both security and efficiency, allowing staff to perform their duties while safeguarding sensitive client data. The diagram provides a clear overview of the responsibilities and interactions of each user type, showing how the system balances authority, functionality, and accessibility across different roles. By mapping out these functions, the Use Case Diagram helps stakeholders visualize how each actor engages with the system to support the clinic's operations and improve service delivery.

#### Sequence Diagram

A sequence diagram is a type of interaction diagram in Unified Modeling Language (UML) that shows how objects in a system interact with each other. It focuses on the order of these interactions by using lifelines to represent objects and arrows to show the messages passed between them. Sequence diagrams help teams understand the timing and flow of processes, making them useful in different stages of system development [10]. These diagrams help illustrate the flow of interactions within a system by showing how users and system components exchange messages over time. They are particularly useful for understanding detailed system behavior, requirements validation, and scenario-based testing. The sequence diagram provides a step-by-step view of processes, making it easier to analyze the timing and sequence of actions, as well as to identify potential issues in the flow of communication. By visualizing interactions in this way, developers and stakeholders can ensure that the system functions as intended and meets the needs of its users.

**Figure 20: Sequence Diagram (Admin)**

*[Figure 20 would illustrate the sequence diagram for the admin in the clinic management system, focusing on the actions performed during login, user management, service handling, and report generation.]*

Figure 20 presents the Sequence Diagram for the Administrator, showing the interactions between Admin, the System, and the Database. It begins with the login process, where the admin provides credentials that the System validates against the Database before granting access. Once logged in, the admin can perform several core functions such as managing users, services, reports, prescriptions, and billing. Each action flows through the System, which then retrieves or updates information in the Database before returning confirmations or error messages back to the Admin. Report generation is another important process, where the System pulls data from the Database to generate client or appointment reports. The admin can also manage prescriptions by viewing all prescriptions, generating prescription reports, and accessing prescription analytics. Additionally, the admin can manage billing operations by viewing all payments, generating revenue reports, tracking outstanding balances, and viewing payment histories. The admin can also view and manage contact messages submitted by clients through the contact form.

The diagram also incorporates alternative paths to represent possible outcomes, which highlight the importance of validation and error handling. For example, if the admin enters incorrect login credentials, the System immediately returns a failed login response instead of granting access. Likewise, when adding or modifying user and service details, the System provides either a success confirmation or an error message if the input is invalid. This ensures that the admin receives proper feedback at every step, reducing errors and maintaining data integrity. Additionally, the diagram helps clarify the sequence of steps that must happen before an action is completed, which is useful for identifying potential system delays or weaknesses. It also ensures that all processes remain transparent, giving both developers and administrators a clear view of how the system responds to various requests. Overall, the diagram not only visualizes how administrative tasks are executed but also emphasizes the logical sequence of operations, making it a valuable reference for both developers and testers in understanding the behavior and reliability of the system.

**Figure 21: Sequence Diagram (Staff)**

*[Figure 21 would illustrate the sequence diagram for the staff in the Web and Mobile-Based Management System.]*

Figure 21 illustrates the sequence diagram for the staff in the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness. It shows how staff interact with the system to carry out essential tasks such as logging in, managing appointments, updating client records, viewing feedback, communicating through the chat feature, creating prescriptions, generating medical certificates, and processing payments. The process begins with the login sequence, where staff credentials are verified against stored data to ensure secure access. Once logged in, the system enables staff to view their assigned appointments, update appointment statuses, and retrieve relevant details from the database, ensuring proper coordination of client schedules. Similarly, when managing client records, staff can request to view or update client information, with the system confirming changes after saving them in the database.

The diagram also highlights how the system supports staff in viewing client feedback and engaging in real-time communication through the chat feature. Feedback submitted by clients is retrieved from the database and displayed, allowing staff to assess client experiences and address issues promptly. Meanwhile, the chat system ensures reliable communication by storing and confirming messages exchanged between staff and clients. Staff can also create prescriptions for clients by selecting medications, dosages, frequencies, and durations, with the system storing prescription data in the database and generating PDF documents. Additionally, staff can generate medical certificates for clients, process payments for appointments, and create bills with payment tracking. Together, these interactions demonstrate how the system streamlines staff responsibilities, improves record accuracy, and enhances client support, making day-to-day operations more efficient and organized. In addition, the sequence shows how data consistency is maintained across features, ensuring that updates are reflected in real time. This not only supports better decision-making but also fosters a stronger relationship between staff and clients through efficient communication and feedback management.

**Figure 22: Sequence Diagram (Client)**

*[Figure 22 would illustrate the sequence diagram for the client in the Web and Mobile-Based Management System.]*

Figure 22 illustrates the sequence diagram for the client in the Aesthetic and Wellness Clinic Management System, showcasing the primary actions and interactions available to clients within the system. The diagram begins with the login process, where clients enter their credentials, which are verified against the database. Depending on the result, the system either grants access or denies entry, ensuring secure authentication. This step is crucial for protecting sensitive client data and personal health information. Once logged in, the client can manage their appointments, including creating new ones, updating schedules, or canceling existing bookings. Each of these actions is processed by the system and stored in the database, with confirmations returned to the client to keep them informed about the status of their request. This immediate feedback loop enhances the user experience by providing clarity and certainty. Clients may also check the status of their scheduled appointments, with the system retrieving the latest updates from the database and presenting them in real time. This interaction ensures clients always have accurate and up-to-date information about their appointments, reducing confusion and scheduling errors.

In addition to managing appointments, the diagram highlights other important client features. Clients can edit their profile details, such as updating contact information or preferences, with the changes saved in the database and confirmed by the system. They can also submit feedback regarding the clinic's services, which is stored in the database and acknowledged by the system, enabling continuous improvement of service quality. Another essential feature is the chat system, where clients send messages to staff. The system stores these messages in the database and confirms delivery, while replies from staff are sent back to the client, ensuring smooth and reliable communication. 

Clients can also view their prescriptions by requesting prescription data from the system, which retrieves prescription records from the database and displays them. The system can generate prescription PDFs for download. Similarly, clients can view their bills and payment history by requesting billing information, with the system retrieving bill and payment records from the database and displaying outstanding balances, payment schedules, and transaction histories. Clients can also view and download medical certificates that have been issued to them. Together, these processes illustrate how the system empowers clients by giving them greater control over their records, appointments, prescriptions, billing, and communication with staff. By streamlining these interactions, the system enhances client satisfaction, reduces administrative workload, and supports efficient clinic operations. This comprehensive, client-centric approach ultimately fosters a more modern, responsive, and trustworthy clinic environment.

### 4.1.8 Risk Assessment Analysis

The Risk Assessment Analysis for the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center identify several potential risks that could affect the system's development, implementation, and long-term operation. These risks are categorized into technical, operational, financial, and compliance areas. One of the primary concerns is the possibility of integration challenges between the web and mobile platforms and the central database. Connectivity issues, bugs, or performance lags could disrupt system efficiency, especially when handling simultaneous user requests for appointments, records, or billing. Security vulnerabilities also pose a significant threat, as unauthorized access could compromise sensitive patient data. To mitigate these risks, the project will include rigorous testing, secure authentication methods, and encryption of medical and personal records.

Adoption of the system by clinic staff, doctors, and patients may face resistance due to unfamiliarity with digital platforms. To address this, the project will implement user training sessions, provide user-friendly interfaces, and involve stakeholders early in the development to ensure that the system aligns with their needs. Financial risks are moderate, depending on project resource allocation and unforeseen costs such as system updates or server upgrades. To minimize these risks, the team will establish a clear budget plan with contingency funds allocated for unexpected expenses. Given that the system handles sensitive patient data, compliance with healthcare data protection standards and privacy laws is essential. Regular security audits, adherence to data protection policies, and strict role-based access control will be enforced to ensure compliance.

## 4.2 Designing

During this phase, the development team created mock-up designs for the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. These mock-ups ensured that both client needs and system requirements were met. The design process emphasized usability, efficiency, and compliance with healthcare data standards to provide a reliable tool for the clinic's operations. The developers first created mock-ups and then met with stakeholders to get feedback on the design. They adjusted the system's features to match the clinic's way of handling client records, appointments, and schedules. Additionally, the design phase generated essential forms, reports, and diagrams detailing the user interface, database, and network architecture. These blueprints define the system's functionality and guarantee seamless integration across web and mobile platforms.

### 4.2.1 Data Design

Data design is a multidisciplinary field that combines research, data analysis, and graphic design with communications strategy, UX, and data visualization. This unique blend of skills enables professionals to create effective data products, simplify complex information, tell compelling stories, and connect with diverse audiences to make reporting both more efficient and impactful [2]. Data design converts the raw information from the analysis phase into practical, efficient software data structures. This results in a well-organized and manageable program. A primary tool for this is the entity-relationship diagram (ERD), which visually maps the data's structure, the relationships between entities, and their attributes. ERDs are essential for clarifying complex data relationships and building a robust system foundation.

#### Entity Relationship Diagram

An Entity Relationship Diagram (ERD), sometimes called an entity relationship model, is a visual tool that shows how people, objects, places, concepts, or events are connected within an information system. It applies data modeling methods to describe business processes and acts as the basis for designing a relational database [8]. In the context Web and Mobile-based Management System, the ERD plays an important role in managing essential data such as user accounts, appointments, services, client records, feedback, chats, and reports. It ensures that these entities are properly linked, allowing smooth data retrieval and efficient system operation.

**Figure 23: Entity Relationship Diagram**

*[Figure 23 would illustrate the Entity Relationship Diagram of the system, showing the connections among the different database tables that support their overall functionality.]*

Figure 23 shows the Entity Relationship Diagram (ERD) for the system. This diagram shows how all the database tables connect to each other. It clearly displays the relationships between tables using primary and foreign keys to keep data accurate and organized. Each table shows its main fields, and the lines between tables show how they are related. Most relationships are one-to-many, like one user having many appointments or one chat having many messages. The users table is the main table that connects to almost all other tables. 

The ERD includes essential tables such as users, appointments, clinic_services, categories, chats, messages, feedback, medical_certificates, sessions, personal_access_tokens, password_reset_tokens, and settings. Additionally, the diagram includes critical billing and payment tables: bills (which links appointments to billing records and tracks payment status, subtotal, tax, discount, and remaining balance) and payments (which records individual payment transactions linked to bills and appointments). The prescriptions table is also included, showing the relationship between appointments, clients, and staff who prescribe medications, with fields for medication name, dosage, frequency, duration, and instructions.

This diagram helps everyone understand how the clinic's data is structured. It shows how appointments, services, clients, staff, billing, payments, prescriptions, and communications all work together in the system. The simple design makes it easy to see how information flows through the database. Additionally, the ERD provides a solid foundation for database normalization, ensuring minimal redundancy and consistent data storage.

### 4.2.2 Output and User-Interface Design

The design of the output and user interface is an important part of system development because it makes the system easy to use and effective. A good interface helps users navigate the system smoothly and finish their tasks quickly. Also, the output should clearly present the needed data to make the system more efficient and functional.

#### Forms

Documentation materials that include screenshots of the system's interface play a vital role in helping users clearly understand how the system works. These screenshots serve as visual guides that show the overall layout, buttons, menus, and available features within the system. By providing step-by-step visuals, users can easily follow along and familiarize themselves with each function without confusion. When combined with detailed written explanations, these materials become a valuable reference for clients, allowing them to navigate the system confidently and perform tasks efficiently.

**Figure 22: Client Login Form**

*[Figure 22 would show the Client Login Form of the Web and Mobile-Based Management System.]*

Figure 22 presents the Client Login Form of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This form allows clients to enter their registered email and password to securely access their personal accounts. Once the credentials are entered, the system verifies the information to ensure that only authorized clients can log in. After successful verification, users are directed to their respective dashboard, where they can view their appointment history, book appointments, and manage their personal records. The login process ensures data privacy and protects sensitive client information from unauthorized access. Additionally, the simple and user-friendly design of the login interface allows clients to easily navigate the system and perform actions with convenience and confidence.

**Figure 23: Admin and Staff Login Form**

*[Figure 23 would show the Admin and Staff Login Form of the Web and Mobile-Based Management System.]*

Figure 23 presents the Admin and Staff Login Form of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This form allows both administrators and staff members to enter their login details, such as username and password, to gain access to the system. Once the credentials are submitted, the system checks their validity to ensure that only authorized personnel can proceed. If the information is correct, administrators are directed to the admin panel where they can manage users, services, and reports, while staff members are taken to their dashboard to manage appointments and client records. This login process maintains system security, prevents unauthorized access, and provides a smooth and user-friendly experience for both admin and staff users.

**Figure 24: Book Appointment Form**

*[Figure 24 would show the Book Appointment Form found on the client side of the Web and Mobile-Based Management System.]*

Figure 24 presents the Book Appointment Form on the client side of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This form allows clients to schedule appointments conveniently by selecting their preferred service, appointment date, and time. The date and time selection is integrated with the clinic's master calendar, providing real-time availability to prevent double-booking and manage staff schedules effectively. It also includes fields for entering important medical details such as past medical history, current maintenance medications, and specific health conditions. This pre-consultation data collection is crucial as it gives the healthcare providers essential background information before the client even arrives for their visit. Additionally, clients can indicate if they are pregnant, lactating, a smoker, or an alcoholic drinker to help the clinic staff provide appropriate care. These specific health declarations are vital for risk assessment, ensuring that certain aesthetic or wellness procedures are performed safely and are tailored to the client's unique physiological state. Once all required information is filled out, clients can confirm their appointment by clicking the "Book Appointment" button. Upon submission, the system automatically generates a confirmation notification for the client and simultaneously creates a new appointment record in the central database for the clinic's administrative staff. This form ensures a smooth, accurate, and personalized booking process, improving both client experience and clinic efficiency. By streamlining intake and minimizing manual data entry for staff, the form acts as a critical touchpoint that enhances operational workflow and supports the clinic's commitment to personalized, safe patient care.

**Figure 25: Contact Form**

*[Figure 25 would display the "Contact Form" of the Web and Mobile-Based Management System.]*

Figure 25 presents the Contact Form of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This form allows clients to communicate directly with the clinic by sending messages, inquiries, or feedback. It includes input fields for the client's name, email, phone number, subject, and message, ensuring that all necessary details are provided for proper response. Once the form is filled out, clients can click the "Send Now" button to submit their concerns. This feature improves communication efficiency and helps the clinic respond to client needs in a timely and organized manner.

**Figure 26: Client Edit Profile**

*[Figure 26 would display the "Client Edit Profile Form" of the Web and Mobile-Based Management System.]*

Figure 26 presents the Client Edit Profile interface of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This form enables clients to manage and update their personal information to keep their records accurate and current. It includes input fields for the client's full name, email address, phone number, date of birth, and address, as well as an option to upload a new profile photo in JPG, PNG, or GIF format with a maximum file size of 2MB. The form also features a Change Password section, where clients can update their login credentials by entering their current password, new password, and confirming the new password. After making the necessary changes, clients can click the "Save Changes" button to update their information or select "Cancel" to discard any modifications. This feature provides clients with a simple and secure way to manage their personal details and account settings.

**Figure 27: Client Feedback Form**

*[Figure 27 would display the "Client Feedback Form" of the Web and Mobile-Based Management System.]*

Figure 27 presents the Client Feedback Form of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This form allows clients to share their experiences and provide valuable feedback regarding the clinic's services. It includes a dropdown menu to select completed appointments, a star-based rating system to evaluate overall satisfaction, and a text box for writing detailed reviews about their experience, service quality, staff professionalism, and suggestions for improvement. Additionally, clients have the option to submit their feedback anonymously for privacy. Once completed, they can click the "Submit Feedback" button to send their response. This feature helps the clinic gather insights to improve its services and maintain high client satisfaction.

**Figure 28: Staff Manage Appointment**

*[Figure 28 would display the "Staff Manage Appointment" interface of the Web and Mobile-Based Management System.]*

Figure 28 presents the Staff Manage Appointment interface of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This interface allows staff to efficiently oversee and manage client appointments through a structured table that displays important details such as the client's name, service type, date, time, appointment type, status, payment status, and rescheduling options. It includes filters that help staff sort appointments by status, including pending, scheduled, on-going, completed, and cancelled, which makes tracking and organization easier. Staff can take quick actions like confirming appointments, marking payments, and rescheduling directly from the table. The "More Actions" button provides additional options to cancel, decline, or view appointment details for better control and flexibility. A "New Appointment" button is also available for adding new bookings specially for the walk-in appointments. This feature improves the overall management of appointments and ensures smooth coordination between staff and clients.

**Figure 29: Staff Manage Client**

*[Figure 29 would display the "Staff Manage Client" interface of the Web and Mobile-Based Management System.]*

Figure 29 presents the Staff Manage Client interface of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This interface provides staff with a clear and organized view of all registered clients, displaying important details such as full name, email, email verification status, number of appointments, number of feedback submissions, and registration date. It includes a search bar for quick filtering, making it easier to locate specific client records. Each client entry provides three action options: Generate Report, which allows staff to view the client's full record; View Details, which shows the client's basic information; and Edit, which lets staff update client details when needed. This feature helps the clinic manage client data efficiently and maintain accurate records for better service delivery.

**Figure 30: Staff View Feedback**

*[Figure 30 would display the "Staff View Feedback" interface of the Web and Mobile-Based Management System.]*

Figure 30 presents the Staff View Feedback interface of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This interface allows staff to review client feedback submitted through the system. It features a search bar and filter options to help staff easily locate specific feedback entries when available. All client feedback is displayed in a centralized list, making it convenient to monitor and assess client experiences. In the example shown, there is currently no feedback available, indicating that no client reviews have been submitted yet. This feature helps the clinic collect and manage valuable client insights to improve service quality and overall client satisfaction.

**Figure 31: Admin Update Setting**

*[Figure 31 would display the "Admin Update Setting" of the Web and Mobile-Based Management System.]*

Figure 31 presents the Admin Update Setting Form of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This form enables the administrator to update and manage essential information displayed on the website, such as banner titles, banner descriptions, contact numbers, addresses, and email details. It provides organized sections for general settings, contact information, team details, and footer content. Once the administrator updates the necessary fields, changes can be saved by clicking the "Save Changes" button. This ensured that the website content remained accurate, up-to-date, and consistent with the clinic's information.

**Figure 32: Admin View Contact Message**

*[Figure 32 would display the "Admin View Contact Messages" of the Web and Mobile-Based Management System.]*

Figure 32 presents the Admin View Contact Messages of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This section displays a list of messages sent by clients through the contact form, allowing the administrator to review and manage incoming inquiries efficiently. The table includes important details such as the sender's name, email address, phone number, subject, read status, and the date and time the message was submitted. The administrator can perform actions such as viewing, marking messages as read, or deleting them when necessary. This helped maintain organized communication between the clinic and its clients, ensuring that all concerns and messages are properly addressed.

**Figure 33: Admin Service Management**

*[Figure 33 would display the "Admin Service Management" section of the Web and Mobile-Based Management System.]*

Figure 33 presents the Admin Service Management interface of the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. This section allows the administrator to view, manage, and update the list of clinic services efficiently. Each service entry displays essential details such as the thumbnail, category, service name, description, assigned staff, price, and status. The admin can also perform actions such as editing existing services or adding new ones using the "New clinic service" button, ensuring that the information presented to clients remains current and accurate. This centralized control over the service catalog helps maintain a professional and trustworthy digital presence for the clinic.

**Figure 34: Admin Category Management**

*[Figure 34 would illustrate the Admin Category Management interface of the Web and Mobile-Based Management System.]*

Figure 34 presents the Admin Category Management interface, which displays a list of all existing service categories within the clinic's system. Each category is organized in a structured table format for easy viewing and management. This structured view allows the admin to quickly assess the current organizational framework and identify any gaps or redundancies. The administrator can perform key actions such as adding new categories, editing existing ones, or deleting outdated entries through the available controls. For instance, when adding a new service category, the system prompts essential details like the category name and a brief description to maintain consistency across the catalog. This feature ensures that the clinic's services remain well-classified and up to date, promoting efficient organization and easier service navigation for both staff and clients.

**Figure 35: Admin User Management**

*[Figure 35 would display the "Admin User Management" section in the admin panel of the Web and Mobile-Based Management System.]*

Figure 35 presents the Admin User Management interface, which displays a comprehensive list of all registered users within the clinic's system, including administrators, staff, and clients. Each user record is systematically organized in a table showing essential details such as name, email address, and assigned role, allowing administrators to efficiently monitor and manage user accounts. The interface includes several management options, such as adding new users, editing existing profiles, and generating detailed client reports for record-keeping and performance tracking. Additionally, a search and pagination feature is provided to help the administrator quickly locate specific users and navigate large datasets with ease. This feature ensures that user information is properly maintained, promotes organized account administration, and strengthens secure access control within the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center.

**Figure 36: Mobile Login Form**

*[Figure 36 would present the "Mobile Login Form" of the Web and Mobile-Based Management System.]*

Figure 36 presents the Mobile Login Form, which serves as the entry point for users accessing the clinic's mobile application. The interface requires users to enter their registered email and password to authenticate their identity before gaining access to their account. It features a simple and user-friendly design to ensure ease of use, especially for clients scheduling appointments or viewing records. This form enhances data security by preventing unauthorized access and ensures that only verified users can interact with the system's mobile services. For users who have forgotten their credentials, a forgot password link is conveniently provided to facilitate account recovery. This streamlined access process is crucial for maintaining efficient and secure patient engagement on the go.

**Figure 37: Mobile Signup Form**

*[Figure 37 would display the "Mobile Signup Form" of the Web and Mobile-Based Management System.]*

Figure 37 presents the Mobile Signup Form, which allows new users to register and create an account in the clinic's mobile application. The form requires users to input essential information such as their first name, last name, email address, and password to establish their profile within the system. It features a clear and intuitive layout designed for a smooth registration process. This streamlines initial access. This functionality ensures that only verified and properly registered clients can access the application's services, supporting secure user onboarding and accurate record management within the system.

**Figure 38: Mobile Appointment Form**

*[Figure 38 would display the "Mobile Appointment Form" of the Web and Mobile-Based Management System.]*

Figure 38 presents the Mobile Appointment Form, which enables clients to conveniently schedule their desired treatments through the mobile application. The form displays key service details such as the treatment name, price, duration, and category to help clients make informed choices. Users can select their preferred appointment date and time slot from the available schedule options, ensuring flexibility and convenience. Additionally, the form allows clients to provide relevant medical history information to assist the clinic in preparing for the session. This feature promoted an organized and efficient booking process, reducing scheduling conflicts and enhancing overall client experience.

**Figure 39: Mobile Feedback Form**

*[Figure 39 would display the "Mobile Feedback Form" of the Web and Mobile-Based Management System.]*

Figure 39 presents the Mobile Feedback Form, which allows clients to share their experiences after availing of the clinic's services. The form displays the specific treatment received and provides a star rating system for clients to rate their satisfaction level. An optional comment box is also available for users to leave detailed feedback or suggestions to help improve service quality. This feature encouraged client engagement and helped the clinic gather valuable insights, ensuring continuous enhancement of service delivery and customer satisfaction.

### 4.2.3 System Architecture

The system architecture design shows how the system is built and how its parts work together. It makes sure that the web app, mobile app, database, and server all connect properly to meet the needs of clients, staff, and administrators. The design explains how data moves between users and the system, while keeping information secure and organized. It also allows the system to grow and improve in the future without causing problems in its current functions.

#### Network Model

Network models describe the organized framework that shows how devices and systems connect and communicate within a network. They provide a structured way to manage the flow of data, making communication and resource sharing more efficient. In simple terms, a network model acts like a blueprint that outlines the rules, protocols, and methods for exchanging information between connected devices [7]. By designing a network model, the system's data communication and connectivity become clearer, making it easier to plan, analyze, and improve system performance. This serves as a guide for understanding how the different components of the clinic management system interact with each other, ensuring smooth data exchange and reliable operations. This clarity is vital for both development and troubleshooting.

**Figure 42: Network Model**

*[Figure 42 would show the network model for the Web and Mobile-Based Management System, presenting a visual representation of how information flows between clients, staff, administrators, the database, and the server.]*

Figure 42 illustrates the network model of the system, showing the connections between the users, devices, web server, and database. In this study, researchers used this model because of its effectiveness in allowing clients, staff, and administrators to interact with the system through their devices. This means a client can book an appointment from their phone while an administrator updates records on a clinic computer. Each user connects to the web server, which processes their requests and communicates with the database to retrieve or store information. The web server acts as the central brain, managing all the incoming and outgoing data. This model serves as the basic framework that supports the flow of information between system components, ensuring accurate, secure, and efficient operations. The layered separation between the user interface, application logic, and data storage is a key security feature, as it shields sensitive database information from direct external exposure. Direct access to the database is strictly limited to the server itself. Furthermore, this structure allows for optimized performance, as each tier can be independently maintained and scaled to handle increased load. By centralizing data management and processing, this architecture enhances system reliability and provides a scalable foundation for future expansion.

#### Security

Security involves protecting systems, data, personnel, and organizational assets from unauthorized access. It includes implementing access controls, such as role-based access control, to ensure proper user authentication. This means that staff and clients will have different levels of access to information. The researchers adopted several precautions to keep the database secure. For example, all sensitive data will be encrypted to make it unreadable if intercepted. Additionally, the proposed system must maintain strong security to prevent potential risks that could impact both users and the system. A secure login process with strong passwords will be required for all users. These measures include continuous monitoring, regular data backups, and enforcing strict access permissions, particularly for administrators. Regular software updates will also be performed to fix any security weaknesses. Overall, safeguarding the system requires a combination of strategies designed to address potential threats and vulnerabilities effectively.

## 4.3 Development

Development tasks were carried out iteratively throughout the development process. During this phase, the developers began coding based on the system specifications established in the planning and design stages, ensuring that all functionalities aligned with the project's objectives. Continuous communication and collaboration among team members were essential to maintain consistency and efficiency during implementation. Each iteration concluded with reviews to verify that the system's performance, functionality, and design requirements were successfully met. To achieve the goal of streamlining clinic operations and enhancing client service efficiency, this phase focused on integrating both the web and mobile-based platforms of the management system. Furthermore, the development team utilized front-end and back-end programming technologies to implement key features such as appointment scheduling, client record management, and service monitoring.

## 4.4 Testing

The testing phase played a vital role in ensuring that the developed system functioned correctly and met all specified requirements. Both the researchers and selected end-users participated in this process to detect and resolve any issues before the system's deployment. The testing followed a Black Box Testing approach, which included compatibility, usability, and security testing to evaluate system performance across different devices and user types. This method was chosen because it focuses on the system's functionality from an external perspective, without needing to know the internal code structure. Any identified errors or inconsistencies were immediately addressed and corrected by the development team. Predefined test cases and expected results were used to verify that each feature operated as intended and fulfilled the project's objectives. This careful and thorough process helped to build a stable and dependable system for the clinic. This section presents the results and discussions derived from the data collected during the testing phase.

### 4.4.1 Test Plan

This phase presents a comprehensive overview of the system testing process, outlining its scope, objectives, methods, resources, and timeframe. It identifies which components of the system are subject to testing and which are excluded. For example, core functions like user registration and appointment scheduling are included, while external vendor APIs may be out of scope. The section also details the testing approaches, success indicators, user requirements, and testing schedule. To verify the system's proper functionality, various tests such as compatibility, usability, and security testing will be conducted on different modules and features. Usability testing will specifically check if the interface is intuitive and easy for staff to navigate. The main goal of all this testing is to find any problems before the system is given to the users. A dedicated testing team will follow detailed scripts to simulate common user tasks. These tests are performed within an environment that closely simulates the actual operational setting to ensure accuracy and reliability of the results. This careful process helps make sure the final system is stable and works exactly as it is supposed to for the clinic and its clients.

#### Compatibility Testing

Compatibility Testing ensures that the system performs correctly and efficiently across different browsers and operating environments. This phase is essential to verify that the Web-Based Management System provides consistent user experience regardless of the browser or device used for access. The process involves checking whether all system components such as navigation menus, forms, buttons, and data display function properly and appear uniformly across various platforms. Through structured test cases, the researcher conducted comprehensive evaluations on Google Chrome, Microsoft Edge, and Opera browsers to assess system performance, interface layout, and responsiveness. The goal was to identify any compatibility issues that could affect accessibility or usability when viewed through different browsers. The testing results confirmed that the system operated seamlessly in all tested environments, with no errors, distortions, or display inconsistencies observed. This finding indicates that the system is highly adaptable and reliable, ensuring that users can efficiently access its features regardless of their preferred browser.

**Table 12: Compatibility Testing**

| Test ID | Test Case | Result | Level of Compatibility |
|---------|-----------|--------|------------------------|
| CT-01 | Website accessibility in Google Chrome (Windows) | 1.00 | Highly Compatible |
| CT-02 | Website accessibility in Microsoft Edge (Windows) | 1.00 | Highly Compatible |
| CT-03 | Website accessibility in Mozilla Firefox (Windows) | 1.00 | Highly Compatible |
| CT-04 | Website accessibility in Safari (Mac OS) | 1.00 | Highly Compatible |
| CT-05 | Responsive display on Android Chrome browser | 1.00 | Highly Compatible |
| CT-06 | Responsive display on IOS Safari | 1.00 | Highly Compatible |
| CT-07 | Form submission compatibility check (Website) | 1.00 | Highly Compatible |
| CT-08 | Mobile Application compatibility on android device | 1.00 | Highly Compatible |
| CT-09 | Form submission compatibility check (Mobile App) | 1.00 | Highly Compatible |
| CT-10 | Font and layout consistency across browsers | 1.00 | Highly Compatible |
| **Overall Result** | | **1.00** | **Highly Compatible** |

Table 12 presents the results of the Compatibility Testing conducted for the Web and Mobile-Based Management System. The findings reveal that the system achieved an overall compatibility score of 1.00, indicating that it is highly compatible across different browsers, devices, and operating systems. This result confirms that the system consistently performs well in various environments, ensuring users experience seamless access and interaction regardless of their chosen platform.

The testing was performed on multiple browsers including Google Chrome, Microsoft Edge, Mozilla Firefox, and Safari, as well as on Android and iOS mobile browsers. The results demonstrate that the website maintained a consistent layout, responsive design, and full functionality across all tested browsers and devices. Features such as form submissions, navigation menus, and page responsiveness worked efficiently without any display distortion or functionality errors.

Moreover, the mobile application underwent compatibility testing on Android devices and proved to be fully functional, with all interfaces rendering properly and all features operating as expected. This ensures that mobile users can access and utilize the system's services without encountering usability or accessibility issues.

Overall, the results confirm that the Web and Mobile-Based Management System is highly adaptable and stable across various operating environments. Its ability to maintain consistent functionality and display across different browsers and devices guarantees that users can interact with the system efficiently, whether through desktop or mobile platforms.

#### Usability Testing

Usability testing is an essential stage that focuses on assessing how easy and convenient the system is to use for its target users. This process includes observing users as they navigate the system, noting any challenges, interactions, and their overall level of satisfaction with the interface and design. Test participants are often asked to complete specific actions, like booking an appointment or updating a client record. Through both qualitative and quantitative feedback, the testing determines whether the system achieves its usability objectives and highlights areas that require improvement. Common metrics assessed include task success rates, time-on-task, and error frequency, which provide concrete data on user performance. For instance, if many users take a long time to find the cancel appointment button, that area needs to be redesigned. This feedback is then used to make targeted refinements to the design. By identifying and addressing these usability issues early, development teams can avoid costly post-launch fixes and redesigns. Fixing a confusing menu before the system is launched is much easier than after hundreds of people are using it. This iterative cycle of testing and refinement is a core principle of user-centered design. Ultimately, this proactive approach not only enhances user satisfaction but also fosters greater user adoption and loyalty.

**Table 13: Usability Testing (Client)**

| Test ID | Test Case | Results | Level of Usability |
|---------|-----------|---------|-------------------|
| **APPOINTMENT BOOKING MODULE** | | | |
| UTCABM-01 | Client can book successfully with confirmation shown | 1.00 | Highly Usable |
| UTCABM-02 | System alerts client if required fields are empty or invalid | 1.00 | Highly Usable |
| UTCABM-03 | Client can cancel an appointment | 1.00 | Highly Usable |
| **Notification & Support Module** | | | |
| UTCNSM-01 | Client receives email / app notification | 0.85 | Highly Usable |
| UTCNSM-02 | Support/contact option is accessible and functional | 1.00 | Highly Usable |
| **User Profile Management** | | | |
| UTCUPM-01 | The client can edit their profile information correctly. | 0.95 | Highly Usable |
| UTCUPM-02 | User details are displayed accurately on page load | 1.00 | Highly Usable |
| **Calendar Module** | | | |
| UTCCM-01 | An error message appears, or the date is not selectable | 1.00 | Highly Usable |
| **Chat Module** | | | |
| UTCCHM-01 | Client can send a message | 1.00 | Highly Usable |
| UTCCHM-02 | Client can receive a message | 1.00 | Highly Usable |
| **Overall Result** | | **0.98** | **Highly Usable** |

Table 13 presents the results of the usability testing conducted for the client side of the web and mobile-based management system. The findings show that the system achieved an overall usability score of 0.98, indicating that it is highly usable and performs effectively in delivering smooth user experience. This high score means that clients found the system easy to learn and straightforward to use for their needs. Such a positive result is crucial for encouraging clients to regularly use the system for booking appointments and managing their information.

The results demonstrate that the system performed well across major modules such as Appointment Booking, Notification and Support, User Profile Management, Calendar, and Chat. Clients were able to successfully book, cancel, and manage appointments without encountering errors, while confirmation messages were accurately displayed after each transaction. Similarly, the User Profile Management module showed excellent functionality, as clients could edit and view their profile information correctly, earning scores of 0.95 and 1.00 respectively. The high scores in these core areas directly support the clinic's goal of streamlining client engagement and reducing administrative overhead.

In the Notification and Support module, the system achieved slightly lower results, with a score of 0.85 for email and app notifications. Although this still falls under the "Highly Usable" category, it suggests that there may be minor delays or inconsistencies in notification delivery that could be optimized in future updates. Addressing this specific area will be a key priority to ensure all system communications are as reliable as its transactional functions. Other functions such as the chat and calendar modules were executed smoothly, confirming that users can communicate and check appointment dates efficiently. Overall, the results confirm that the system provides user-friendly experience and meets the usability goals intended for client interaction. The interface design, responsiveness, and ease of navigation contribute to its effectiveness, making it well-suited for clients of the Dr. Ve Aesthetic Clinic and Wellness Center. These excellent usability metrics strongly indicate that the system will be readily adopted by the client base. The minor improvement opportunity in notifications does not detract from the system's readiness for deployment but rather provides a clear target for its first iterative update.

**Table 14: Usability Testing (Staff)**

| Test ID | Test Case | Results | Level of Usability |
|---------|-----------|---------|-------------------|
| **USER RECORD MANAGEMENT MODULE** | | | |
| UTSURMM-01 | Staff access correct client information | 1.00 | Highly Usable |
| UTSURMM-02 | Staff can generate client record | 1.00 | Highly Usable |
| **APPOINTMENT APPROVAL MODULE** | | | |
| UTSAAM-01 | Staff perform actions accurately | 1.00 | Highly Usable |
| UTSAAM-02 | System displays a success message upon approval/decline | 1.00 | Highly Usable |
| UTSAAM-03 | Staff can view appointments by status | 1.00 | Highly Usable |
| UTSAAM-04 | Staff can see full appointment details before taking action | 1.00 | Highly Usable |
| **CALENDAR MODULE** | | | |
| UTSCM-01 | Staff can view booked dates in the calendar | 1.00 | Highly Usable |
| UTSCM-02 | Staff can view appointment details in the calendar | 1.00 | Highly Usable |
| **CHAT MODULE** | | | |
| UTSCHM-01 | Staff can send a message | 1.00 | Highly Usable |
| UTSCHM-02 | Staff can receive a message | 1.00 | Highly Usable |
| **FEEDBACK MODULE** | | | |
| UTSCHM-01 | Staff can view client feedback | 1.00 | Highly Usable |
| **Overall Result** | | **1.00** | **Highly Usable** |

Table 14 presents the results of the usability testing conducted for the staff side of the web-based management system. The findings indicate that the system achieved an overall usability score of 1.00, signifying that it is highly usable and efficiently supports staff operations within the clinic. This result confirms that all tested modules performed as expected, allowing staff to complete their tasks accurately and without system errors. A perfect score is a significant achievement, suggesting the staff interface is both intuitive and reliable. This level of performance helps to streamline daily workflows and reduces the time needed for staff training. An efficient system for the staff directly contributes to faster service and better overall experience for the clinic's clients.

The system performed exceptionally well across all modules, including User Record Management, Appointment Approval, Calendar, Chat, and Feedback. Staff members were able to access accurate client information, generate records, and manage appointments seamlessly. The Appointment Approval module proved to be efficient, with successful confirmation messages displayed upon approval or decline actions. This immediate feedback is crucial for staff confidence and for maintaining an accurate, real-time schedule. Similarly, the Calendar module provided an accurate view of booked dates and appointment details, supporting organized scheduling. Additionally, the Chat and Feedback modules allowed smooth communication and feedback viewing, further improving workflow. By integrating these communication tools directly into the system, staff can resolve inquiries without switching between different applications.

Overall, the results affirm that the system offers a highly functional and user-friendly environment for staff, enhancing productivity and ensuring smooth day-to-day clinic management. This level of performance indicates that the system will be quickly adopted by the staff, minimizing training time and reducing the potential for operational errors.

**Table 15: Usability Testing (Admin)**

| Test ID | Test Case | Results | Level of Usability |
|---------|-----------|---------|-------------------|
| **ACCOUNT AND USER MANAGEMENT MODULE** | | | |
| UTAAUMM-01 | Admin can add a new user (staff) successfully | 1.00 | Highly Usable |
| UTAAUMM-02 | Admin can edit user information | 1.00 | Highly Usable |
| **DASHBOARD NAVIGATION MODULE** | | | |
| UTADNM-01 | Admin can easily navigate the dashboard menus | 1.00 | Highly Usable |
| UTADNM-02 | Dashboard widgets/analytics load without delays | 1.00 | Highly Usable |
| UTADNM-03 | Search/filter functions work in data-heavy modules | 1.00 | Highly Usable |
| **REPORT MANAGEMENT MODULE** | | | |
| UTARMM-01 | Admin can generate client report | 1.00 | Highly Usable |
| UTARMM-02 | File downloads with complete data | 1.00 | Highly Usable |
| UTARMM-03 | Report refreshes with live data | 1.00 | Highly Usable |
| UTARMM-04 | Admin can print a medical certificate for clients | 1.00 | Highly Usable |
| UTARMM-05 | Admin can create a new medical certificate for clients | 1.00 | Highly Usable |
| **SERVICE MANAGEMENT MODULE** | | | |
| UTASMM-01 | Admin can add new service | 1.00 | Highly Usable |
| UTASMM-02 | Admin can edit services | 1.00 | Highly Usable |
| UTASMM-03 | Admin can add new category | 1.00 | Highly Usable |
| UTASMM-04 | Admin can edit category | 1.00 | Highly Usable |
| **Overall Result** | | **1.00** | **Highly Usable** |

Table 15 presents the results of the usability testing conducted for the administrator side of the web-based management system. The findings reveal that the system achieved an overall usability score of 1.00, indicating that it is highly usable and efficient in supporting administrative operations. The system performed exceptionally well across all major modules, including Account and User Management, Dashboard Navigation, Report Management, and Service Management. This consistent excellence ensures that administrative staff can perform their duties without frustration or technical hindrances. Such a high level of usability is fundamental for maintaining the clinic's operational efficiency and data integrity on a daily basis.

In the Account and User Management Module, the administrator was able to add and edit user information smoothly, confirming that user-related operations can be executed without delays or errors. The Dashboard Navigation Module also demonstrated high performance, as the admin could easily navigate menus, load analytics widgets promptly, and use search or filter functions effectively even in data-heavy environments. Similarly, the Report Management Module exhibited excellent functionality, enabling the admin to generate, refresh, and download reports, as well as create and print medical certificates accurately. Lastly, the Service Management Module maintained full usability, allowing the admin to add, edit, and manage services and categories seamlessly. This comprehensive reliability empowers administrators to make timely, data-driven decisions for the clinic. Overall, the results confirm that the system provides intuitive and efficient experience for administrators, ensuring smooth management of users, data, and services within the Dr. Ve Aesthetic Clinic and Wellness Center.

#### Security Testing

Security testing involves examining the system to detect any vulnerabilities, threats, or risks that could result in unauthorized access or data breaches. In this process, the system's security measures are evaluated by simulating possible attacks and analyzing its protection mechanisms, including encryption methods, authentication procedures, and access controls. The main objective is to confirm that the system can securely protect sensitive data and resist potential cyber threats. This is especially critical for a clinic management system that handles confidential patient health records. Any discovered weaknesses are documented and addressed by the development team to strengthen the system's defenses. This proactive approach helps to build a robust security posture from the ground up. Conducting security testing is essential to ensure that the system remains safe, reliable, and well-protected before being used by actual users.

**Table 16: Security Testing (Client)**

| Test ID | Test Case | Results | Level of Security |
|---------|-----------|---------|------------------|
| **ACCESS RESTRICTION MODULE** | | | |
| STCARM-01 | Client cannot access admin and staff dashboard | 1.00 | Low-risk |
| STCARM-02 | Clients can only complete the specified tasks that are linked to their role. | 1.00 | Low-risk |
| STCARM-03 | System prevents page access after logout | 0.9 | Low-risk |
| STCARM-04 | System displays validation errors and prevents form submission | 1.00 | Low-risk |
| STCARM-05 | Weak passwords are rejected with a proper message | 0.85 | Low-risk |
| **AUTHENTICATION MODULE** | | | |
| STCAM-01 | System rejects login attempts with incorrect credentials | 1.00 | Low-risk |
| STCAM-02 | User is successfully authenticated and redirected to dashboard | 1.00 | Low-risk |
| STCAM-03 | System blocks or sanitizes malicious input | 1.00 | Low-risk |
| STCAM-04 | The system blocks the input and shows an error or sanitizes the query | 1.00 | Low-risk |
| STCAM-05 | The system sanitizes or rejects malicious script input | 1.00 | Low-risk |
| **Overall Result** | | **0.97** | **Low-risk** |

Table 16 presents the results of the security testing performed to assess the system's ability to protect client data and prevent unauthorized access. The testing covered critical areas such as access restriction and authentication, ensuring that the system could effectively manage user permissions and safeguard against potential threats. Based on the results, the system achieved an overall score of 0.97, indicating a low-risk level and demonstrating that its security mechanisms function effectively. This high score provides strong assurance that client information is well-protected. The testing process carefully checked for common vulnerabilities to ensure the system's defenses are strong. This level of security is essential for building client trust and meeting the strict privacy standards required in a healthcare setting.

The findings show that the system successfully restricted clients from accessing admin and staff dashboards, prevented form submissions with invalid data, and rejected weak passwords with appropriate notifications. Likewise, the authentication module performed well in handling login attempts, validating credentials, and blocking malicious inputs or script attacks. This robust defense against common web threats is fundamental to maintaining client trust. For example, the system's ability to block SQL injection attempts ensures that the clinic's database remains secure from data theft. While all test cases were rated as low risk, a few, such as weak password handling and post-logout page access, scored slightly lower, suggesting minor areas for enhancement. These specific issues will be prioritized for resolution to ensure no security gaps remain. Addressing these specific points in a future update would further strengthen the system's security posture. Overall, the results confirm that the system provides a secure environment for clients, maintaining data integrity and ensuring safe interaction throughout its use. The proactive identification of these minor issues underscores the thoroughness of the testing process and the development team's commitment to security.

**Table 17: Security Testing (Staff)**

| Test ID | Test Case | Results | Level of Security |
|---------|-----------|---------|------------------|
| **AUTHENTICATION MODULE** | | | |
| STSAM-01 | System rejects login attempts with incorrect credentials. | 1.00 | Low-risk |
| STSAM-02 | Only one active session is allowed | 1.00 | Low-risk |
| STSAM-03 | Password visibility can be toggled but not exposed by default | 1.00 | Low-risk |
| STSAM-04 | System redirects to login or clears sensitive pages from cache | 1.00 | Low-risk |
| STSAM-05 | User is successfully authenticated and redirected to dashboard | 1.00 | Low-risk |
| **ACCESS CONTROL MODULE** | | | |
| STSACM-01 | Staff is denied access and redirected or shown an error message | 1.00 | Low-risk |
| STSACM-02 | Staff can only complete the specified tasks that are linked to their role. | 1.00 | Low-risk |
| STSACM-03 | System rejects forced access to elevated roles | 1.00 | Low-risk |
| STSACM-04 | System validates user access level and restricts action | 1.00 | Low-risk |
| STSACM-05 | System sanitizes input and blocks malicious scripts | 1.00 | Low-risk |
| **Overall Result** | | **1.00** | **Low-risk** |

Table 17 presents the results of the security testing conducted to assess the system's ability to protect staff data and prevent unauthorized access. The testing focused on key areas such as authentication and access control, ensuring that only authorized staff could access specific modules and perform designated tasks. Based on the results, the system achieved an overall score of 1.00, which indicates a low-risk level and confirms the strong security performance of the system. The findings show that all test cases under the authentication and access control modules functioned effectively. The system successfully rejected login attempts with incorrect credentials, allowed only one active session per user, and ensured that sensitive pages were cleared or redirected after logout. Additionally, it validated access levels, restricted elevated role actions, and sanitized malicious inputs to prevent script-based attacks. This ensures all staff permissions work correctly. Overall, these results affirm that the system provides a secure operational environment for staff, maintaining data confidentiality, enforcing proper role-based restrictions, and protecting against potential security threats.

**Table 18: Security Testing (Admin)**

| Test ID | Test Case | Results | Level of Security |
|---------|-----------|---------|------------------|
| **LOGIN MODULE** | | | |
| STALM-01 | Only valid admin accounts are granted access | 1.00 | Low-risk |
| STALM-02 | Password not visible in plaintext | 1.00 | Low-risk |
| STALM-03 | Message doesn't reveal valid accounts | 1.00 | Low-risk |
| STALM-04 | System redirects to login or clears sensitive pages from cache | 1.00 | Low-risk |
| **VULNERABILITY TESTING** | | | |
| STAVT-01 | Script tags are sanitized or blocked | 1.00 | Low-risk |
| STAVT-02 | System blocks SQL-injected inputs | 1.00 | Low-risk |
| STAVT-03 | Only allowed file types accepted | 1.00 | Low-risk |
| STAVT-04 | Users can't access others' data by ID manipulation | 1.00 | Low-risk |
| **DATA PROTECTION MODULE** | | | |
| STADRM-01 | Session tokens/cookies are HTTPS-only | 1.00 | Low-risk |
| STADRM-02 | Database stores encrypted values | 1.00 | Low-risk |
| STADRM-03 | DB overwrites freed blocks | 1.00 | Low-risk |
| **Overall Result** | | **1.00** | **Low-risk** |

Table 18 presents the results of the security testing performed to assess the system's ability to protect administrative data and prevent unauthorized access. The testing covered essential areas such as login authentication, vulnerability prevention, and data protection, ensuring that the system effectively safeguards sensitive administrative operations. Based on the results, the system achieved an overall score of 1.00, indicating a low-risk level and confirming that its security mechanisms operate effectively. A perfect score in security testing is a significant accomplishment for any system.

The findings reveal that the login module functioned securely, allowing only valid admin accounts to access the system while preventing exposure of credentials and sensitive data through the cache. In the vulnerability testing module, the system successfully blocked potential threats such as SQL injection and script-based attacks, ensuring that only approved file types were accepted and that users could not manipulate data through URL parameters. This multi-layered defense is crucial for protecting the heart of the clinic's management system. Additionally, the data protection module demonstrated strong security measures by using HTTPS-only session cookies, encrypting stored values, and ensuring that database operations securely manage freed blocks. Such rigorous encryption ensures that even if data is intercepted, it remains unreadable and useless to attackers. Overall, all test cases were rated as low-risk, validating that the system provides a highly secure environment for administrators. The results confirm that the implemented security controls effectively protect administrative data, maintain system integrity, and prevent unauthorized access or manipulation.

**Table 19: Summary of Black Box Testing Overall Results**

| Black Box Testing | Result | Level of Functionality |
|-------------------|--------|----------------------|
| Compatibility Testing (Admin) | 1.00 | Highly Compatible |
| Compatibility Overall Result | 1.00 | Highly Compatible |
| Usability Testing (Client) | 0.98 | Highly Usable |
| Usability Testing (Staff) | 1.00 | Highly Usable |
| Usability Testing (Admin) | 1.00 | Highly Usable |
| Usability Overall Result | 0.99 | Highly Usable |
| Security Testing (Client) | 0.97 | Low Risk |
| Security Testing (Staff) | 1.00 | Low Risk |
| Security Testing (Admin) | 1.00 | Low Risk |
| Security Overall Result | 0.99 | Low Risk |

The results presented in Table 19 summarize the overall findings from the Black Box Testing conducted for the Web and Mobile-Based Management System. The Compatibility Testing achieved perfect scores of 1.00 across all tested platforms, including Google Chrome, Microsoft Edge, Mozilla Firefox, and Safari browsers, as well as Android and iOS devices. These results indicate that the system is highly compatible and performs consistently across different browsers, operating systems, and devices. Both the web and mobile versions of the system functioned seamlessly, with no detected errors, distortions, or layout inconsistencies. Overall, this confirms that users can access and interact with the system smoothly and reliably, ensuring a stable and uniform experience across all platforms.

The usability testing produced an overall score of 0.99, which falls under the Highly Usable category. This demonstrates that the system provides an intuitive and user-friendly interface with efficient navigation and well-functioning modules for clients, staff, and administrators. The slight variation in the client score, which is 0.98, indicates a small opportunity for improvement in enhancing user experience, but overall, the interface supports smooth interaction and ease of use.

For security testing, the system achieved an overall score of 0.99, categorized as Low Risk. This result shows that the system has strong protection mechanisms in place to secure sensitive data, prevent unauthorized access, and maintain data integrity. Each user group, including clients, staff, and admins, recorded scores of 1.00 or close to it, confirming the effectiveness of the implemented security features such as authentication, data protection, and access control.

Overall, the findings from the Black Box Testing confirm that the system is highly compatible, highly usable, and secure. These results indicate that the web and mobile-based management system meets its intended goals and provides users with a stable, efficient, and protected digital environment. The exceptionally high scores across all testing categories provide strong evidence that the system is ready for successful deployment. The minor opportunities for improvement are clearly defined and can be addressed in future maintenance cycles. Ultimately, this rigorous validation process ensures the system will deliver reliable and secure service to the clinic and its users.

## 4.5 Deployment

The deployment phase is a vital part of the Agile methodology, marking the transition of the system from development to actual implementation. In this phase, the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center was installed and made accessible to its intended users, including the administrator, staff, and clients. This marks the culmination of the development lifecycle. The process involved setting up the web server and database server to ensure smooth operation and reliable data exchange. A final check was done to confirm that all features were working correctly in the live environment. Following the iterative nature of the Agile methodology, deployment was performed after several testing and refinement cycles to ensure that the system was stable and fully functional before release.

This stage signifies the completion of the development process and the beginning of real-world usage. It ensures that users can effectively access the system and utilize its main features such as appointment scheduling, client management, and service tracking. Continuous monitoring was also carried out after deployment to identify possible issues and gather feedback from users. These insights support ongoing system improvement, reflecting Agile's emphasis on flexibility, responsiveness, and continuous enhancement based on user experience.

### Deployment Diagram

A deployment diagram serves as a critical visual blueprint, outlining the interaction between a system's hardware and software elements. This section details the system's deployment architecture to explain how various users access and employ its functionalities.

**Figure 43: Deployment Diagram**

*[Figure 43 would show the deployment diagram for the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. The diagram presents the overall network infrastructure and components required for the system.]*

Figure 43 shows the deployment diagram for the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center. The diagram presents the overall network infrastructure and components required for the system, offering a clear view of its physical architecture and the interaction between various devices, servers, and software components. It demonstrates how the system operates in a real-world setting, highlighting the flow of data and communication between users, the web and mobile platforms, and the backend services. Users, including clients, staff, and administrators, access the system through different platforms such as mobile devices and web browsers. The mobile application enables clients to register, schedule appointments, and view available dates, while the web application allows staff and administrators to manage appointments, monitor records, update services, and generate reports. This flexibility ensures that all users can conveniently interact with the system using any device connected to the Internet.

Communication between the user interfaces and the backend server is conducted through secure HTTPS requests and REST APIs. The system utilizes the Laravel framework for its web server, which efficiently processes requests and ensures smooth interaction between the applications and the database. The database server, implemented using MySQL, stores essential information such as client records, appointment details, service lists, and user accounts. It plays a central role in managing and retrieving data efficiently to support all transactions and operations performed by the users. Additionally, the system includes an external service dedicated to sending email notifications. This component ensures that clients and staff receive timely updates, reminders, and confirmations related to their appointments, improving communication and service delivery.

The deployment diagram emphasizes the interoperability and coordination between all components of the system, forming a unified and secure architecture. By illustrating how users, devices, and servers work together, it showcases the system's efficiency, scalability, and reliability. Overall, the diagram provides a comprehensive understanding of how the Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center functions in practice, ensuring that both clients and clinic personnel can efficiently manage scheduling, records, and communication in a seamless digital environment.

---

© 2025 Dr. Ve Aesthetic Clinic & Wellness Center  
All Rights Reserved
