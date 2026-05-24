# ⚡ Web PLN UP3 Gresik – Monthly Invoice Delivery System

Web PLN UP3 Gresik is a web-based monthly invoice delivery system designed to assist PLN staff in managing customer data, uploading electricity invoices, and sending invoices automatically via email in a more structured, efficient, and paperless way.

This system provides authentication for admins and staff, customer data management, invoice upload functionality, automatic email delivery using SMTP, and activity logging for monitoring and audit purposes.

---

# ✨ Main Features

## 🔐 Authentication System
- User Login
- User Registration
- Session-based authentication
- Admin & Staff SAR access control

---

## 👥 Customer Data Management
- Add customer data
- Edit customer data
- Delete customer data
- Search customer data
- Customer information includes:
  - IDPEL
  - Customer Name
  - Address
  - Phone Number
  - Active Email List

---

## 📄 Monthly Invoice Upload
- Upload invoice files (PDF / Image)
- Invoice linked automatically to customer data
- File stored securely on server directory

---

## 📧 Automatic Invoice Delivery
- Send invoice directly to customer email
- Email contains:
  - Billing notification
  - Payment details
  - Invoice attachment
- SMTP integration using PHPMailer

---

## 🔔 Delivery Notification
- System displays:
  - Successful delivery notification
  - Failed delivery notification

---

## 📝 Activity Log
System records all admin activities including:
- Invoice upload
- Invoice sending
- User activities
- Login actions

---

# 🛠️ Technologies Used

| Technology | Description |
|---|---|
| PHP | Backend Programming Language |
| HTML, CSS, JavaScript | Frontend Development |
| Bootstrap | Responsive UI Framework |
| MySQL | Database Management |
| PHPMailer | Email Sending Library |
| SMTP Gmail | Email Delivery Service |
| XAMPP | Local Development Server |
| MVC Architecture | Separation of Logic, View, and Data |

---

# 🗄️ Database Design

## 📌 Entity Relationship Diagram (ERD)

The system database is designed using ERD to model relationships between entities such as:
- Admin
- Customer Data
- Customer Emails
- Invoice Logs
- Activity Logs

The ERD helps ensure:
- Structured database design
- Efficient data processing
- Better system scalability
- Easier maintenance

---

# 📂 Main Entities

## 1. Admin
Stores administrator or staff data with:
- ID
- Username
- Encrypted Password
- Created At

Admins can:
- Upload invoices
- Send invoices
- Manage customer data
- Monitor activity logs

---

## 2. Customer Data (`data_pelanggan`)
Stores PLN customer information:
- IDPEL (Primary Key)
- Customer Name
- Address
- Phone Number

---

## 3. Customer Emails (`emails_pelanggan`)
Stores one or more customer email addresses:
- ID
- IDPEL (Foreign Key)
- Email Address

This allows one customer to receive invoices through multiple emails.

---

## 4. Activity Log (`log_aktivitas`)
Stores admin activities such as:
- Uploading invoice
- Sending email
- Managing customer data

Attributes:
- ID
- Admin ID
- Activity
- Timestamp

---

# 🔄 Entity Relationships

- One admin can perform many activities (1:N)
- One customer can have multiple email addresses (1:N)

---

# 📌 Use Case Overview

## 👨‍💼 Staff SAR Capabilities
- Register account
- Login
- Add customer data
- Edit customer data
- Delete customer data
- Search customer data
- Upload invoice
- Send invoice to customer email
- View activity logs

---

# 🔄 Activity Flow

1. Staff checks account availability
2. Register account (if needed)
3. Login into system
4. Access dashboard
5. Manage customer data
6. Upload customer invoice
7. System stores invoice
8. Send invoice via email
9. System records activity log
10. Logout

---

# 🖥️ System Pages

## 🔑 Login Page
User authentication page with session validation.

---

## 📝 Register Page
Allows new staff/admin account creation.

---

## 📊 Dashboard
Displays:
- Customer count
- Sent invoice count
- Navigation menus
- PLN profile summary

---

## 👥 Customer Data Page
Features:
- Add customer
- Edit customer
- Delete customer
- Search customer
- Upload invoice
- Send invoice email

---

## 📂 Activity Log Page
Displays:
- Activity date & time
- Activity type
- Admin username

Useful for:
- Tracking
- Monitoring
- Audit trail

---

# 🚀 Installation Guide

## 1. Clone Repository

```bash
git clone https://github.com/ririsariii/Web-PLN-UP3-Gresik.git
```

---

## 2. Move Project Folder

Move the project folder into:

```bash
C:/xampp/htdocs/
```

---

## 3. Import Database

- Open phpMyAdmin
- Create a new database
- Import the provided `.sql` file

---

## 4. Configure Database

Edit configuration file:

```bash
/config/db.php
```

Example:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pln_up3_gresik";
```

---

## 5. Configure PHPMailer SMTP

Configure SMTP email settings inside:

```bash
/config/mail.php
```

Example:

```php
$mail->Host = 'smtp.gmail.com';
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
```

---

## 6. Run Project

Open browser:

```bash
http://localhost/Web-PLN-UP3-Gresik
```

---

# 🌱 Benefits of the System

✅ Faster invoice delivery process  
✅ Better operational efficiency  
✅ Transparent activity tracking  
✅ Reduced human error  
✅ Paperless invoice distribution  
✅ Centralized customer management  

---

# 👩‍💻 Developer

**Yuliani Purwitasari**  

GitHub:  
https://github.com/ririsariii

---

# 📄 License

This project is developed for educational, internship, and portfolio purposes.
