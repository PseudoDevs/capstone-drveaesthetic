# RSC ACTION TAKEN - COMPLETE VERSION

---

## PART 1: MANUSCRIPT SECTION

### 1. Title and Literature Requirements
**Recommendation:** Remove the Client and Staff from the title of the study. Minimum number of literature is 5 literature per topic.

**Action Taken:** 
The title was revised to "Web and Mobile-Based Management System for Dr. Ve Aesthetic Clinic and Wellness Center" by removing "Client and Staff" as recommended. All literature review topics now contain a minimum of 5 related studies per topic to meet the academic standard.

---

### 2. Project Context (Page 3-4)
**Recommendation:** 
- Establish the need for the proposed project, how many client records need to be managed, and what the services are
- Move the 6th paragraph after the 3rd paragraph

**Action Taken:** 
The Project Context section was enhanced to establish the need for the proposed project, specifying the volume of client records to be managed and detailing the clinic's services. The sixth paragraph was repositioned after the third paragraph as recommended.

---

### 3. Purpose and Description of the Project (Page 6)
**Recommendation:** 
- Revise the 2nd and 3rd paragraphs. In the 2nd paragraph, discuss what is good in the project, and in the 3rd paragraph, discuss what is unique in the project
- Merge the 4th and 5th paragraph

**Action Taken:** 
The 2nd paragraph was revised to highlight the project's strengths and advantages, while the 3rd paragraph was restructured to emphasize the unique features and innovations of the system. The 4th and 5th paragraphs were merged into a single cohesive paragraph as recommended.

---

### 4. Scope and Limitations of the Project (Page 6)
**Recommendation:** 
- Please revise the discussion, do not focus only on the system, focus on the study as a whole
- Please consider the entire SOs
- Redundant discussion in the 2nd paragraph

**Action Taken:** 
The Scope and Limitations section was comprehensively revised to focus on the study as a whole rather than solely on the system. All specific objectives (SOs) were considered and integrated into the discussion. Redundant content in the 2nd paragraph was removed to improve clarity and conciseness.

---

### 5. Chapter 2 Revision (Page 18)
**Recommendation:** 
- Revise the entire Chapter 2 based on the suggested topics
- RRLs must have at least 5 topics to discuss
- Suggested Topics:
  - Current Procedures in Aesthetic and Wellness Clinic Management
  - Digital Health Record Management System
  - Web and Mobile Application Development Methodologies
  - System testing through Black Box Testing

**Action Taken:** 
Chapter 2 was completely revised following the suggested topic structure. The Review of Related Literature now includes at least five major topics: Current Procedures in Aesthetic and Wellness Clinic Management, Digital Health Record Management System, Web and Mobile Application Development Methodologies, System Testing through Black Box Testing, and additional relevant topics. Each topic contains a minimum of 5 literature sources as required.

---

### 6. Synthesis of the State-of-the-Art (Page 19)
**Recommendation:** Update this section based on the changes in the RRL

**Action Taken:** 
The Synthesis of the State-of-the-Art section was updated to align with the revised Review of Related Literature content, ensuring consistency and proper integration of all discussed topics and findings.

---

### 7. Gap Bridged by the Study (Page 19)
**Recommendation:** 
- Revise this section, 1st paragraph, what is the main gap, and in the 2nd paragraph, how this Gap will be bridged by the current study

**Action Taken:** 
The Gap Bridged by the Study section was revised. The first paragraph now clearly identifies the main research gap in existing literature and systems, while the second paragraph explicitly explains how the current study addresses and bridges this gap through its proposed solution and methodology.

---

### 8. Hanging Page Fix (Page 19)
**Recommendation:** Fix the hanging page

**Action Taken:** 
The hanging page issue was corrected, ensuring proper page breaks and formatting consistency throughout the document.

---

### 9. Overview of Current Technologies (Page 25)
**Recommendation:** Add about the technologies to be used for web hosting, notification, and security protocols

**Action Taken:** 
The Overview of Current Technologies section was expanded to include detailed information about web hosting technologies (sudohosting.cloud with Percona Server), notification systems (Pusher for real-time notifications), and security protocols (HTTPS/SSL encryption, Laravel Sanctum token authentication, CSRF protection, bcrypt password hashing).

---

### 10. Table 3 - Functional Requirements (Page 29)
**Recommendation:** 
- Separate the functional requirements for the mobile application and web
- Add report generation

**Action Taken:** 
Table 3 was restructured to clearly separate functional requirements for the mobile application and web platform into distinct sections. The report generation functionality was added to the functional requirements for administrators, enabling them to generate detailed reports on appointments, payments, client visits, and staff performance.

---

### 11. Table 4 - Non-Functional Requirements (Page 30)
**Recommendation:** Must be consistent with the SO3

**Action Taken:** 
Table 4 (Non-Functional Requirements) was revised to ensure consistency with Specific Objective 3 (SO3), aligning all non-functional requirements with the stated objectives of the study.

---

### 12. Figure 1 - System Flowchart (Page 31)
**Recommendation:** 
- Add an introduction statement for the figure
- Revise based on the suggested modules
- Must include all processes of the clinic

**Action Taken:** 
An introduction statement was added for Figure 1 to provide context before the diagram. The system flowchart was revised to include all suggested modules: appointment scheduling, client record management, billing and payment processing, prescription management, medical certificate generation, real-time chat, feedback system, and report generation. All clinic processes are now represented in the flowchart.

---

### 13. Figure 3 (Page 33)
**Recommendation:** Add an introduction statement for the figure

**Action Taken:** 
An introduction statement was added for Figure 3 to provide context and explanation before the diagram presentation.

---

## PART 2: SOFTWARE PROGRAM SECTION

### 1. Client Management Module
**Recommendation:** Add, adding of records and updating of records of the client

**Action Taken:** 
The client management module was implemented with full CRUD (Create, Read, Update, Delete) functionality. Staff and administrators can add new client records through the registration system, and both staff and clients can update client information through their respective panels. The system maintains a complete audit trail of all record modifications.

---

### 2. Pre-Screening Forms Module
**Recommendation:** Same content as pre-screening in the mobile app and web

**Action Taken:** 
The pre-screening forms (Pre-Appointment Health Assessment) were standardized to ensure identical content and structure across both the web application and mobile application. The medical information form and consent waiver form contain the same fields, validation rules, and data structure on both platforms, ensuring consistency in data collection.

---

### 3. Appointment Scheduling Module
**Recommendation:** In booking for a schedule, if the same time but different service, it will be approved; same time and same service will not be approved, but it will depend on the availability of the doctor and other employees

**Action Taken:** 
The appointment scheduling module was implemented with intelligent conflict detection. The system allows appointments at the same time slot for different services, but prevents double-booking for the same service at the same time. The system checks staff availability and service capacity before approving appointments, ensuring optimal resource utilization while preventing scheduling conflicts.

---

### 4. Forms Consistency
**Recommendation:** The forms of the clinic should be the same forms in the web and mobile app

**Action Taken:** 
All clinic forms, including the Pre-Appointment Health Assessment (medical information form and consent waiver), appointment booking forms, and feedback forms, were standardized to have identical structure, fields, and validation rules across both the web application and mobile application platforms.

---

### 5. Module Separation
**Recommendation:** Separate modules for the web and mobile app

**Action Taken:** 
The system architecture was designed with clear separation between web and mobile modules. The web application uses Filament PHP panels (Admin, Staff, Client) with Blade templates, while the mobile application uses React Native with REST API endpoints. Both platforms share the same Laravel backend and database, but have distinct frontend implementations tailored to their respective platforms.

---

### 6. Billing and Payment Module
**Recommendation:** Add manage bills, payments, and online payments

**Action Taken:** 
A comprehensive billing and payment management module was implemented. Staff can create bills linked to appointments, manage payment records, track outstanding balances, support both full and staggered payment plans, and generate professional payment receipts. The system maintains complete payment histories and automatically updates bill balances. Note: Online payment gateway integration (Stripe, PayPal, GCash) was not implemented as per project scope; payments are recorded manually by staff.

---

### 7. Reports and Records Module
**Recommendation:** Add reports and records of the client, and in the records of every client, it should include their past appointment and their prescription

**Action Taken:** 
The system includes a comprehensive client records module that displays complete client information including personal details, service history, past appointments with dates and services, and all prescriptions issued to the client. Additionally, a report generation module was implemented for administrators to generate detailed reports on appointments, payments, client visits, and staff performance. Client records are accessible through both web and mobile platforms.

---

### 8. Recurring Appointments
**Recommendation:** No clear appointment for repetitive customers

**Action Taken:** 
The appointment scheduling system allows clients to book multiple appointments for the same service or different services. While there is no dedicated "recurring appointment" feature that automatically schedules future appointments, clients can manually book follow-up appointments through the same booking interface. The system maintains a complete history of all past appointments for each client, making it easy to track treatment progress and schedule subsequent visits.

---

### 9. Medical Certificate Module
**Recommendation:** Add certification for the client and generate the medical certificate for the client

**Action Taken:** 
A medical certificate generation module was implemented for authorized staff members. Staff can create, preview, and print official medical certificates as PDF documents. The certificates include client information, appointment details, and certification statements. Clients can access and download their medical certificates through the mobile application.

---

### 10. Report Generation Module
**Recommendation:** Add the generation of reports

**Action Taken:** 
A comprehensive report generation module was implemented for administrators. The system can generate detailed reports on: appointment statistics (booked, confirmed, cancelled, completed), payment summaries and revenue analysis, client visit history and trends, staff performance and productivity metrics, service utilization statistics, and system usage analytics. Reports can be exported and are accessible through the admin dashboard.

---

## NOTES:

1. **Appointment Conflict Logic**: The system checks for time conflicts and service availability. Same time + different service = allowed; same time + same service = blocked based on staff/service capacity.

2. **Recurring Appointments**: The system doesn't have automatic recurring appointment scheduling, but clients can manually book follow-up appointments. Complete appointment history is maintained for easy tracking.

3. **Online Payments**: The system includes comprehensive payment tracking and management, but does not include automated payment gateway integration (Stripe, PayPal, GCash) as per project scope. Payments are recorded manually by staff.
