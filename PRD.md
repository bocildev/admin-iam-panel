# Product Requirement Document (PRD): Multi-Tenant SaaS & Portfolio Management Platform

## 1. Overview & Objectives
This project aims to upgrade an existing portfolio administration dashboard into a comprehensive **Multi-Tenant SaaS Management Platform**. The upgraded system will allow the Super Admin to:
1. Securely authenticate and manage admin access.
2. Manage a portfolio of internal/external projects/applications.
3. Provision and manage isolated databases (MySQL/PostgreSQL) dynamically (creating DB name, username, and password) for registered applications.
4. Manage content dynamically for each registered application.
5. Control application-level and feature-level access (IAM / RBAC).

---

## 2. Architecture & Tech Stack Guidelines
*Please adjust this section based on your actual tech stack (e.g., Laravel, Node.js, Vue/Nuxt, etc.)*
* **Architecture:** Modular Monolith or Service-Oriented Architecture with clean separation of concerns (Controllers, Services, Repositories/Models).
* **Database Strategy (Multi-Tenancy):** Database-per-tenant or Schema-per-tenant approach for application data isolation.
* **Security:** Password hashing (bcrypt/argon2), JWT / Session-based authentication, SQL injection prevention, and secure connection string handling.

---

## 3. Core Features & Requirements

### 3.1. Authentication & Admin Management (IAM Foundation)
* **Requirement:** Secure login system for the platform administrators.
* **User Stories:**
  * As an admin, I want to log in using an email and password to access the control panel.
  * As a super admin, I want to manage admin accounts (Create, Read, Update, Delete / Active & Deactive).
* **Acceptance Criteria:**
  * Password must be securely hashed.
  * Session timeout / token expiration implemented.
  * Role-Based Access Control (RBAC) for Super Admin vs. Regular Admin (if applicable).

### 3.2. Portfolio & Application Registry Management
* **Requirement:** Extend the existing portfolio system to register external/internal projects as "tenants" or managed applications.
* **User Stories:**
  * As an admin, I want to view a list of all registered applications/projects alongside my portfolio items.
  * As an admin, I want to register a new application by providing basic metadata (App Name, Slug, Description, Status).
* **Acceptance Criteria:**
  * CRUD operations for registered applications.
  * Each registered app acts as a distinct entity linked to its own configuration and database.

### 3.3. Dynamic Database Provisioning
* **Requirement:** Automate the creation and management of databases for each registered application.
* **User Stories:**
  * As an admin, when I register or configure an application, the system should automatically generate a dedicated database, a unique database username, and a secure password on the database server.
  * As an admin, I want to view or reset database credentials for a specific application.
* **Acceptance Criteria:**
  * Programmatic execution of SQL commands to create databases and grant user privileges (e.g., `CREATE DATABASE`, `CREATE USER`, `GRANT ALL PRIVILEGES`).
  * Secure encryption of database credentials stored in the main system configuration table.

### 3.4. Dynamic Content Management System (CMS)
* **Requirement:** A modular content management interface tailored for each registered application outside the core portfolio.
* **User Stories:**
  * As an admin, I want to select a registered application and manage its content dynamically based on its schema or content types.
* **Acceptance Criteria:**
  * Flexible data entry forms or JSON-based content handlers mapped to the application's isolated database.

### 3.5. Application Access & User Management (IAM)
* **Requirement:** Manage users and access permissions for each individual application registered in the platform.
* **User Stories:**
  * As an admin, I want to manage who can access a specific registered application and what roles they hold within that app.
* **Acceptance Criteria:**
  * User assignment mapping table (Admin/App User <-> Application ID).

---

## 4. Database Schema Guidelines (Suggested Entities)

1. **`admins`**: `id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`
2. **`applications`**: `id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`
3. **`app_databases`**: `id`, `application_id`, `db_host`, `db_port`, `db_name`, `db_user`, `db_password_encrypted`, `created_at`, `updated_at`
4. **`app_contents`**: `id`, `application_id`, `content_key`, `content_value` (or structured JSON), `created_at`, `updated_at`

---

## 5. Non-Functional Requirements
* **Code Structure:** Maintain clean folder organization, follow MVC/Service pattern strictly, and avoid tight coupling between modules.
* **Error Handling:** Implement robust try-catch blocks, especially for database provisioning operations, returning clear error messages if DB creation fails.
* **Logging:** Log critical admin actions (app creation, credential generation, deletions) for auditing purposes.