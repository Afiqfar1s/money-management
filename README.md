# Money Management System

A Laravel-based debt management and payment tracking system with multi-tenant company support and role-based access control.

## Overview

This application helps manage debtor information, track payments, record balance adjustments, and maintain detailed financial records for individuals and companies. Built with multi-tenancy in mind, users can be assigned to multiple companies with isolated data access.

## 🌐 Live Demo

**Production URL:** [https://money-management-ask7.onrender.com](https://money-management-ask7.onrender.com)

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
- **Database:** PostgreSQL (Supabase)
- **Authentication:** Laravel Breeze
- **Build Tool:** Vite 7
- **Hosting:** Render (Docker-based)
- **Security:** Supabase Row Level Security (RLS)

## Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- PostgreSQL database (or Supabase account)

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
   
   For local development with SQLite:
   \`\`\`bash
   touch database/database.sqlite
   \`\`\`
   
   For Supabase PostgreSQL, update \`.env\`:
   \`\`\`env
   DB_CONNECTION=pgsql
   DB_HOST=your-supabase-host.supabase.com
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres.your-project-id
   DB_PASSWORD=your-password
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

7. **Start the server**
   \`\`\`bash
   php artisan serve --port=8001
   \`\`\`

8. **Access the application**
   \`\`\`
   http://127.0.0.1:8001
   \`\`\`

### Default Login Credentials

**Super Admin:**
- Email: \`admin@example.com\`
- Password: \`admin123\`

**Test User:**
- Email: \`test@example.com\`
- Password: \`test123\`

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

## Multi-Tenancy & Row Level Security

This application implements database-level security using Supabase Row Level Security (RLS):

- **Company Isolation** - Users can only see data belonging to their assigned companies
- **Admin Bypass** - Administrators have full access to all data
- **Session-Based Context** - Laravel middleware passes user context to PostgreSQL for RLS enforcement

### RLS Setup (for Supabase)

After deployment, run the SQL script in Supabase SQL Editor:
\`\`\`sql
-- Located at: database/supabase_rls_setup.sql
\`\`\`

See \`docs/RLS_IMPLEMENTATION.md\` for detailed documentation.

## Deployment

### Render Deployment

This project is configured for deployment on Render using Docker:

1. Connect your GitHub repository to Render
2. Set environment variables in Render dashboard
3. Deploy - Render will use the \`Dockerfile\` automatically

Required environment variables:
- \`APP_KEY\` - Laravel application key
- \`APP_ENV\` - Set to \`production\`
- \`DB_CONNECTION\`, \`DB_HOST\`, \`DB_DATABASE\`, \`DB_USERNAME\`, \`DB_PASSWORD\`
- \`SESSION_DRIVER\` - Recommend \`file\` for free tier
- \`CACHE_STORE\` - Recommend \`file\` for free tier

### Configuration Files
- \`Dockerfile\` - Docker build configuration
- \`docker-entrypoint.sh\` - Container startup script
- \`render.yaml\` - Render deployment configuration

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
│   ├── Http/Middleware/      # Custom middleware (incl. RLS context)
│   ├── Models/               # Eloquent models
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/              # Database seeders
│   └── supabase_rls_setup.sql # RLS policies for Supabase
├── docs/
│   └── RLS_IMPLEMENTATION.md # RLS documentation
├── resources/
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Web routes
├── Dockerfile                # Docker configuration
├── docker-entrypoint.sh      # Container startup script
└── render.yaml               # Render deployment config
\`\`\`

## Troubleshooting

### Common Issues

**Slow loading on first visit (Render free tier):**
- Free tier instances spin down after inactivity
- First request after idle period takes 20-60 seconds (cold start)
- Subsequent requests are fast

**CSS not loading / Mixed content errors:**
- Ensure \`APP_URL\` uses \`https://\` in production
- The app forces HTTPS in production automatically

**Database connection issues:**
- Verify Supabase connection string format
- Use Session Pooler connection for serverless environments
- Check firewall/network settings

**Port already in use (local development):**
\`\`\`bash
php artisan serve --port=8002
\`\`\`

**Assets not loading:**
\`\`\`bash
npm run build
php artisan view:clear
\`\`\`

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
- CSRF protection on all forms
- Password hashing with bcrypt
- Input validation and sanitization
- Row Level Security at database level
- HTTPS enforced in production

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

**Last Updated:** 19 February 2026  
**Version:** 2.0.0
