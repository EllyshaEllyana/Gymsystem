# Gym Membership Management System
# Lecturer Feedback & System Improvements

---

# Introduction

This document contains the lecturer feedback received during the system review session for the Gym Membership Management System project.

The purpose of this document is to identify the weaknesses of the current system and list the improvements required to make the system more professional, realistic, and suitable for final project presentation.

---

# 1. Admin Permission Control

## Lecturer Feedback

```text
Admin doesn’t have permission to edit member
Admin can see details but not edit
Edit package can
```

---

## Current Problem

The current system allows admin to access member information, but the permission logic is unclear.

Personal member information should not be fully editable by admin because it belongs to the member.

---

## Required Improvement

The system should implement proper role-based access control.

---

## Recommended Permission Structure

### Member Personal Information

Only members can edit:

- Full Name
- Email
- Phone Number
- Gender
- Password

### Admin Permissions

Admin can:

- View member details
- Update membership package
- Update membership status
- Update expiry date

Admin cannot edit personal member information.

---

## Suggested UI Changes

Replace:

```text
Edit Member
```

With:

```text
View Details
Edit Membership
Update Package
```

---

# 2. Payment Status Management

## Lecturer Feedback

```text
Payment (admin) missing - pending, paid, cancelled
```

---

## Current Problem

The payment system currently lacks payment status tracking.

Admin cannot properly monitor whether payments are:

- Pending
- Paid
- Cancelled

---

## Required Improvement

Add payment status management into the system.

---

## Suggested Database Update

### payments table

```sql
payment_status VARCHAR(20)
```

---

## Suggested Payment Status Options

- Pending
- Paid
- Cancelled

---

## Suggested Features

Admin can:

- Approve payment
- Mark payment as paid
- Cancel payment
- Filter payments by status

---

# 3. Booking Management Connection

## Lecturer Feedback

```text
Booking management delete admin - tak connected
```

---

## Current Problem

The booking module is not properly connected with other modules.

Example:

- Booking deleted
- Timetable still shows booking
- Dashboard statistics not updated
- Trainer schedule not updated

This causes inconsistent system data.

---

## Required Improvement

The booking system must synchronize automatically with:

- Timetable
- Dashboard
- Trainer schedule
- Reports

---

## Correct Booking Workflow

```text
Member books session
↓
Booking saved in database
↓
Trainer timetable updated
↓
Dashboard statistics updated
↓
Admin approves/cancels/deletes booking
↓
All modules update automatically
```

---

# 4. Dashboard Navigation Improvement

## Lecturer Feedback

```text
Booking back to dashboard
```

---

## Current Problem

Navigation flow between pages is inconsistent.

Users may not have easy access back to the dashboard.

---

## Required Improvement

Every page should include:

- Sidebar navigation
- Dashboard shortcut
- Back button
- Breadcrumb navigation

---

## Suggested Sidebar Structure

```text
Dashboard
Members
Packages
Payments
Bookings
Reports
Profile
Logout
```

---

# 5. Report Performance Enhancement

## Lecturer Feedback

```text
Report - performance
```

---

## Current Problem

Current reports are too basic.

The system only shows:

- Total members
- Total income
- Total bookings

---

## Required Improvement

Add performance and analytical reports.

---

## Suggested Performance Reports

### Most Active Members

Show members with highest attendance or bookings.

| Member | Sessions |
|---|---|
| John Lee | 15 |
| Sarah Lim | 12 |

---

### Top Personal Trainers

| Trainer | Total Sessions |
|---|---|
| Jason Wong | 25 |

---

### Monthly Revenue Performance

| Month | Revenue |
|---|---|
| January | RM5000 |
| February | RM7200 |

---

### Package Popularity Report

| Package | Total Members |
|---|---|
| Premium | 40 |

---

## Suggested Dashboard Analytics

Add:

- Top Trainer
- Most Active Member
- Most Popular Package
- Monthly Growth Statistics

---

# 6. Weight Tracking Feature

## Lecturer Feedback

```text
Can add weight
```

---

## Current Problem

The system currently only focuses on membership management.

It lacks fitness-related functionality.

---

## Required Improvement

Add a weight tracking feature for members.

---

## Suggested Member Features

Members can:

- Add current weight
- Set target weight
- View weight history
- Monitor progress

---

## Suggested Database Table

### weight_progress

```sql
CREATE TABLE weight_progress (
    weight_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT,
    weight DECIMAL(5,2),
    record_date DATE
);
```

---

## Example Weight Progress Table

| Date | Weight |
|---|---|
| 1 May | 85kg |
| 15 May | 82kg |

---

## Suggested Reports

Admin can view:

- Average weight loss
- Member fitness progress
- Weight tracking statistics

---

# 7. Improve UI with Images

## Lecturer Feedback

```text
Insert picture in about and package page
```

---

## Current Problem

The current UI design may appear:

- Too plain
- Too text-heavy
- Too basic

---

## Required Improvement

Add images and visual components to improve presentation quality.

---

# About Page Improvements

Add:

- Gym banner image
- Fitness equipment pictures
- Trainer images
- Gym environment photos

---

## Suggested About Sections

```text
About Our Gym
Our Mission
Why Choose Us
Our Facilities
Meet Our Trainers
```

---

# Package Page Improvements

Display packages using image cards.

---

## Example Layout

```text
[ Package Image ]
Premium Package
6 Months
RM450
[ Select Package ]
```

---

## Suggested UI Components

- Bootstrap cards
- Hero banners
- Icons
- Fitness images
- Package thumbnails

---

# 8. Role-Based Access Control

## Recommended Final Permission Structure

| Feature | Admin | Member |
|---|---|---|
| View Profile | ✅ | ✅ |
| Edit Personal Information | ❌ | ✅ |
| Manage Membership Package | ✅ | ❌ |
| Manage Payments | ✅ | ❌ |
| Book Trainer Session | ❌ | ✅ |
| View Reports | ✅ | ❌ |
| Manage Trainers | ✅ | ❌ |

---

# 9. Recommended Final Modules

## Admin Side

### Dashboard

- Statistics cards
- Analytics overview

### Member Management

- View details
- Membership management

### Package Management

- CRUD packages

### Payment Management

- Payment status control

### Trainer Management

- Add/Edit/Delete trainers

### Booking Management

- Approve/cancel bookings

### Reports

- Revenue reports
- Performance reports
- Fitness reports

---

## Member Side

### Profile Management

- Edit personal information

### Membership Details

- View package
- Renew membership

### Weight Tracking

- Add weight progress
- View fitness history

### Personal Trainer Booking

- Book session
- View timetable
- Cancel/reschedule session

### Payment History

- View payments

---

# Conclusion

The lecturer feedback mainly focuses on improving:

- System workflow
- User permissions
- Data synchronization
- Report quality
- Realistic gym functionality
- UI/UX design

These improvements will help transform the system from a basic CRUD project into a more realistic and professional Gym Membership Management System.

Implementing these features will improve:

- System usability
- Database design quality
- User experience
- Project presentation quality
- Final evaluation marks

The project is now moving toward a more commercial-style gym management web application.

