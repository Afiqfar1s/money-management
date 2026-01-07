# Money Management System

A Laravel-based debt management and payment tracking system with role-based access control.

## Overview

This application helps manage debtor information, track payments, record balance adjustments, and maintain detailed financial records for individuals and companies.

## Features

- 💰 **Debtor Management** - Track individual and company debtors
- 💵 **Payment Recording** - Record and manage payment transactions
- 📊 **Balance Adjustments** - Add additional debts or adjustments
- 🎫 **Voucher System** - Generate and view payment vouchers
- 👥 **User Management** - Admin panel for managing system users
- 🔐 **Role-Based Access** - Admin and regular user roles with custom permissions
- 📱 **Responsive Design** - Clean, professional UI that works on all devices
- 🔍 **Search & Filter** - Advanced filtering capabilities for debtors
- 📈 **Financial Overview** - Dashboard with key metrics and statistics

## Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Blade Templates, TailwindCSS, Alpine.js
- **Database:** SQLite (easily configurable for MySQL/PostgreSQL)
- **Authentication:** Laravel Breeze
- **Build Tool:** Vite

## Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd money-management
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Build assets**
   ```bash
   npm run build
   # Or for development with hot reload:
   npm run dev
   ```

6. **Start the server**
   ```bash
   ./dev-server.sh
   # Or manually:
   php artisan serve --port=8001
   ```

7. **Access the application**
   ```
   http://127.0.0.1:8001
   ```

### Default Login Credentials

**Super Admin:**
- Email: `admin@example.com`
- Password: `admin123`

**Test User:**
- Email: `test@example.com`
- Password: `test123`

## User Roles & Permissions

### Admin Role
- Full access to all features
- User management capabilities
- Can view/edit/delete all debtors
- Session management access

### Regular User Role
Customizable permissions include:
- `create_debtors` - Create new debtor records
- `view_all_debtors` - View all debtors in the system
- `view_own_debtors` - View only their own debtors
- `edit_all_debtors` - Edit any debtor record
- `edit_own_debtors` - Edit only their own debtors
- `delete_all_debtors` - Delete any debtor record
- `delete_own_debtors` - Delete only their own debtors

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

### Session Management (Admin Only)
- View active user sessions
- Force logout capabilities
- Session activity tracking
- Security monitoring

## Documentation

Detailed guides available:
- 📖 [Development Guide](DEVELOPMENT_GUIDE.md) - Setup and development workflow
- 👥 [User Management Guide](USER_MANAGEMENT_GUIDE.md) - Managing users and permissions
- 🔐 [Admin Session Guide](ADMIN_SESSION_GUIDE.md) - Session management features
- 📝 [Session Management](SESSION_MANAGEMENT.md) - Technical session implementation
- 🎨 [UI Simplification Summary](UI_SIMPLIFICATION_SUMMARY.md) - Recent UI improvements

## Troubleshooting

### Blank Page or Access Issues

If you see a blank page when accessing protected routes (like /users):

1. **Make sure you're logged in:**
   - Go to http://127.0.0.1:8001/login
   - Use admin credentials
   - Then access the protected route

2. **Clear caches if needed:**
   ```bash
   ./clear-cache.sh
   # Or manually:
   php artisan cache:clear
   php artisan view:clear
   php artisan config:clear
   ```

3. **Check permissions:**
   - Admin users have full access
   - Regular users need specific permissions set

### Common Issues

**Port already in use:**
```bash
# Use a different port
php artisan serve --port=8002
```

**Database locked errors:**
- Stop any running servers accessing the database
- Check for SQLite Browser or other tools

**Assets not loading:**
```bash
npm run build
php artisan view:clear
```

## Development Utilities

### Clear Cache Script
```bash
./clear-cache.sh
```
Clears all Laravel caches (config, routes, views, application).

### Development Server Script
```bash
./dev-server.sh
```
Starts server with optimized settings for development.

## Project Structure

```
money-management/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/               # Eloquent models
│   └── Http/Middleware/      # Custom middleware
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/              # Database seeders
│   └── database.sqlite       # SQLite database file
├── resources/
│   └── views/               # Blade templates
├── routes/
│   └── web.php              # Web routes
└── public/                  # Public assets
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## Security

- All routes protected with authentication middleware
- Admin middleware for administrative functions
- CSRF protection on all forms
- Password hashing with bcrypt
- Input validation and sanitization

## License

This project is open-sourced software.

## Support

For issues, questions, or contributions, please refer to the documentation files or open an issue in the repository.

---

**Last Updated:** 6 January 2026
**Version:** 1.0.0
