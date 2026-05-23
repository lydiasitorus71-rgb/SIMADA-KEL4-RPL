# SIMADA (Sistem Informasi Manajemen Pengadaan)

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4.svg?style=flat-square&logo=php)](#)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20.svg?style=flat-square&logo=laravel)](#)
[![Status](https://img.shields.io/badge/Status-Development-yellow.svg?style=flat-square)](#)

### Overview
SIMADA is a web-based application built with Laravel to manage and monitor the procurement process (Pengadaan) effectively. 

This project includes a built-in mock for the SPSE Integrasi endpoint. Local API calls are routed internally to serve JSON data directly from `database/data/spse_paket.json` without requiring external network calls.

### Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

#### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL, SQLite, etc.)

#### Installing

1. **Clone the repository:**
   ```bash
   git clone https://github.com/lydiasitorus71-rgb/SIMADA-KEL4-RPL.git
   cd SIMADA-KEL4-RPL/SIMADA_Project/simada-project
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup:**
   Copy the example environment file to create your own `.env` file:
   ```bash
   cp .env.example .env
   ```
   **Important Database Setup:** Open the `.env` file and verify your `DB_*` configuration. For example, if you are using MySQL, make sure you have created an empty database (e.g., `simada`) and updated the credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=simada
   DB_USERNAME=root
   DB_PASSWORD=<your_password>
   ```

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Database Migration & Seeding:**
   This command will create all the necessary tables and populate them with default static and dummy data.
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Run the Development Server:**
   ```bash
   php artisan serve
   ```
   You can now access the application at `http://localhost:8000`.

#### Testing the Application (Default Credentials)
After running the database seeders, the following default accounts are available for testing different user roles. All accounts use the same default password:

**Password:** `password123`

| Role | Username | Description |
| :--- | :--- | :--- |
| **Admin** | `admin` | Administrator SIMADA |
| **PA/KPA** | `pa_kpa_user` | Pengguna Anggaran / Kuasa Pengguna Anggaran |
| **PPK** | `ppk_user` | Pejabat Pembuat Komitmen |
| **Pokja** | `pokja_user` | Anggota Pokja Pemilihan |
| **Pejabat Pengadaan** | `pejabat_pengadaan` | Pejabat Pengadaan |

### Tests

Tests can be run using PHPUnit or Artisan test.
```bash
php artisan test
```

### Deployment

This project is currently scoped for local development and academic purposes. Production deployment is out of scope for this phase, but the application can be fully operated locally by following the **Getting Started** steps.


### Release History
- 0.1
  - Initial structure and models.

### Team Members

This system is developed as a group project. The role of each member is as follows:

| Name | Role |
| :--- | :--- |
| **Daffa Ibnu Abdillah** | Project Manager |
| **Muhammad Rafli Pradana** | Software Designer |
| **Muhammad Alfi Anfahsa** | Vibe Coder |
| **Lidya Stephanie Arthania Sitorus** | Version Controller |
| **Ilham Buwana Putra** | Software Tester |
