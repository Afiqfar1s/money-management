# Money Management System

A Laravel-based debt management and payment tracking system with multi-tenant company support and role-based access control.

## Overview

This application helps manage debtor information, track payments, record balance adjustments, and maintain detailed financial records for individuals and companies. Built with multi-tenancy in mind, users can be assigned to multiple companies with isolated data access.

## Features

- 💰 **Debtor Management** - Track individual and company debtors
- 💵 **Payment Recording** - Record and manage payment transactions
- 📊 **Balance Adjustments** - Add additional debts or adjustments
- 🎫 **Voucher System** - Generate and view payment vouchers
- 👥 **User Management** - Admin panel for managing system users
- 🏢 **Multi-Company Support** - Users can belong to multiple companies
- 🔐 **Role-Based Access** - Admin and regular user roles with custom permissions
- 🛡️ **Row Level Security** - Database-level security with Supabase RLS
- 📱 **Responsive Design** - Clean, professional UI that works on all devices
- 🔍 **Search & Filter** - Advanced filtering capabilities for debtors
- 📈 **Financial Overview** - Dashboard with key metrics and statistics

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** Blade Templates, TailwindCSS, Alpine.js
- **Database:** MySQL/MariaDB
- **Authentication:** Laravel Breeze
- **Build Tool:** Vite 7
- **Hosting:** XAMPP (Local/Network Deployment)
- **Security:** Role-based access control, session encryption

## Quick Start

### Prerequisites
- XAMPP (includes PHP 8.2+, MySQL/MariaDB, Apache)
- Composer
- Node.js & npm

### Installation

1. **Clone the repository**
   \`\`\`bash
   git clone https://github.com/Afiqfar1s/money-management.git
   cd money-management
   \`\`\`

2. **Install dependencies**
   \`\`\`bash
   composer install
   npm install
   \`\`\`

3. **Environment setup**
   \`\`\`bash
   cp .env.example .env
   php artisan key:generate
   \`\`\`

4. **Configure database**
   
   Create database in MySQL:
   \`\`\`bash
   mysql -u root -p
   CREATE DATABASE money_management;
   exit
   \`\`\`
   
   Update \`.env\`:
   \`\`\`env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=money_management
   DB_USERNAME=moneymanager
   DB_PASSWORD=your_secure_password
   \`\`\`

5. **Run migrations**
   \`\`\`bash
   php artisan migrate --seed
   \`\`\`

6. **Build assets**
   \`\`\`bash
   npm run build
   # Or for development with hot reload:
   npm run dev
   \`\`\`

7. **Start Apache in XAMPP**
   - Open XAMPP Control Panel
   - Start Apache and MySQL modules

8. **Access the application**
   \`\`\`
   http://localhost/money-management/public
   # Or if using virtual host:
   http://moneymanagement.com
   \`\`\`

### Default Login Credentials

**Admin:**
- Email: \`admin@test.com\`
- Password: \`admin123\`

**Test User:**
- Email: \`user@test.com\`
- Password: \`user123\`

## User Roles & Permissions

### Admin Role
- Full access to all features
- User management capabilities
- Can view/edit/delete all debtors across all companies
- Company management access
- Bypass Row Level Security restrictions

### Regular User Role
- Access scoped to assigned companies
- Customizable permissions include:
  - \`create_debtors\` - Create new debtor records
  - \`view_all_debtors\` - View all debtors in current company
  - \`edit_all_debtors\` - Edit any debtor record in current company
  - \`delete_all_debtors\` - Delete any debtor record in current company

## Multi-Tenancy & Company Isolation

This application implements multi-tenant architecture with company-level data isolation:

- **Company Isolation** - Users can only see data belonging to their assigned companies
- **Admin Bypass** - Administrators have full access to all data across all companies
- **Middleware-Based Security** - Laravel middleware enforces company context on all queries

### Company Assignment

Users can be assigned to multiple companies through the admin panel. Regular users must select a company before accessing the system, while admins can view and manage all company data.

## Network Deployment

### XAMPP Network Configuration

To make the application accessible on your local network:

To make the application accessible on your local network:

1. **Configure Apache Virtual Host**
   - Edit \`httpd-vhosts.conf\` in XAMPP
   - Add virtual host configuration
   - Point DocumentRoot to \`public/\` folder

2. **Configure Windows Firewall**
   \`\`\`powershell
   New-NetFirewallRule -DisplayName "Apache HTTP Server" -Direction Inbound -Protocol TCP -LocalPort 80 -Action Allow
   \`\`\`

3. **Update APP_URL in .env**
   \`\`\`env
   APP_URL=http://your-ip-address
   \`\`\`

4. **Access from network devices**
   \`\`\`
   http://your-ip-address
   \`\`\`

See \`docs/XAMPP_MYSQL_DEPLOYMENT.md\` for detailed deployment instructions.

## Key Features Details

### Debtor Management
- Support for both individuals/staff and companies
- Individual: Staff number, IC, phone, position, working dates
- Company: SSM number, office phone, company address
- Track starting outstanding balance
- Automatic balance calculation

### Payment Tracking
- Record payments with voucher numbers
- Date/time stamping
- Optional notes for each payment
- View/print payment vouchers
- Payment history with full audit trail

### Balance Adjustments
- Add additional debt amounts
- Optional voucher tracking
- Adjustment history with timestamps
- Separate from payment records for clarity

### Search & Filtering
- Live search by name, IC, phone
- Filter by payment status (owing/settled)
- Sort and pagination

## Project Structure

\`\`\`
money-management/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Http/Middleware/      # Custom middleware (admin, company context)
│   ├── Models/               # Eloquent models
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── docs/
│   ├── RLS_IMPLEMENTATION.md      # Row Level Security docs (legacy)
│   ├── TROUBLESHOOTING_LOGIN.md   # Login troubleshooting guide
│   └── XAMPP_MYSQL_DEPLOYMENT.md  # XAMPP deployment guide
├── resources/
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Web routes
├── public/
│   └── storage/              # Public storage (symlinked)
└── SECURITY_AUDIT_REPORT.md  # Security audit findings
\`\`\`

## Troubleshooting

### Common Issues

**Port already in use:**
- Check if another application is using port 80
- Stop other web servers or change Apache port in XAMPP

**Database connection issues:**
- Verify MySQL is running in XAMPP
- Check database credentials in \`.env\`
- Ensure database \`money_management\` exists

**Assets not loading:**
\`\`\`bash
npm run build
php artisan storage:link
php artisan view:clear
\`\`\`

**Cannot login / Session issues:**
- Ensure session tables exist: \`php artisan migrate\`
- Check \`SESSION_DRIVER=database\` in \`.env\`
- Clear cache: \`php artisan config:clear\`

### Clear Cache
\`\`\`bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
\`\`\`

## Security

- All routes protected with authentication middleware
- Admin middleware for administrative functions
- Company context middleware for data isolation
- CSRF protection on all forms
- Password hashing with bcrypt
- Input validation and sanitization
- Session encryption enabled
- Limited database user (not root)
- Debug mode disabled in production

**Security Audit:** See \`SECURITY_AUDIT_REPORT.md\` for comprehensive security review and recommendations.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open-sourced software.

## Support

For issues, questions, or contributions, please open an issue in the repository.

---

**Last Updated:** 24 February 2026  
**Version:** 2.1.0  
**Deployment:** XAMPP Production (Security Hardened)
