# Session Management System

## Overview
The Money Management system now includes a comprehensive PHP session management system built on Laravel's database session driver. This allows users and administrators to view, monitor, and control active login sessions.

## Features

### For Regular Users
1. **View Active Sessions** - See all devices and browsers where you're currently logged in
2. **Session Details** - View device type, browser, IP address, and last activity time
3. **Revoke Sessions** - Terminate individual sessions remotely
4. **Revoke All Sessions** - Log out from all other devices except the current one
5. **Current Session Indicator** - Clearly see which session is your current one

### For Administrators
1. **View All Sessions** - Monitor all active user sessions across the system
2. **User Information** - See which user owns each session
3. **Revoke Any Session** - Terminate suspicious sessions for security
4. **Session Analytics** - Track user activity and device usage

## How to Access

### User Session Management
1. Click on your profile dropdown (top-right corner)
2. Select **"My Sessions"**
3. View and manage your active sessions

### Admin Session Management
1. Click on your profile dropdown (top-right corner)
2. Select **"All Sessions (Admin)"** (only visible to administrators)
3. Monitor all active sessions in the system

## Session Information Displayed

Each session shows:
- **Device & Browser**: Chrome on macOS, Firefox on Windows, etc.
- **IP Address**: The internet address from which the session originated
- **Last Activity**: Timestamp of the last action in that session
- **Status**: Current Session or Active

## Security Features

### User Security
- **Session Revocation**: Instantly terminate suspicious sessions
- **Device Tracking**: Know exactly which devices are logged in
- **Security Alerts**: Visual warnings if sessions look suspicious
- **Remote Logout**: Log out from devices even when not physically present

### Administrator Security
- **System-wide Monitoring**: View all active sessions
- **User Activity Tracking**: Monitor login patterns
- **Emergency Revocation**: Terminate any user's session if compromised
- **IP Address Logging**: Track access locations

## Session Configuration

The system uses Laravel's database session driver with the following settings:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

### Session Lifetime
- Sessions expire after **120 minutes** (2 hours) of inactivity
- Active sessions automatically extend the lifetime
- Expired sessions are automatically cleaned up

### Session Storage
- Sessions are stored in the `sessions` table in the database
- Each session includes:
  - Session ID (unique identifier)
  - User ID (for authenticated sessions)
  - IP Address (for security tracking)
  - User Agent (browser and device information)
  - Payload (encrypted session data)
  - Last Activity timestamp

## Routes

### User Routes (Authenticated)
- `GET /sessions` - View my active sessions
- `DELETE /sessions/{sessionId}` - Revoke a specific session
- `POST /sessions/destroy-all` - Revoke all other sessions

### Admin Routes (Admin Only)
- `GET /admin/sessions` - View all active sessions

## Database Schema

The `sessions` table structure:

```sql
- id (string, primary key) - Unique session identifier
- user_id (bigint, nullable) - Associated user ID
- ip_address (string, nullable) - IP address of the client
- user_agent (text, nullable) - Browser and device information
- payload (longtext) - Encrypted session data
- last_activity (integer) - Unix timestamp of last activity
```

## Usage Examples

### Viewing Your Sessions
1. Navigate to **Profile → My Sessions**
2. See all devices where you're logged in
3. Current session is highlighted in green

### Revoking a Single Session
1. Find the session you want to revoke
2. Click the **"Revoke"** button
3. Confirm the action
4. The session is immediately terminated

### Revoking All Sessions (Security Measure)
1. Click **"Revoke All Other Sessions"** button
2. Confirm the action
3. All sessions except your current one are terminated
4. Use this if you suspect unauthorized access

### Admin Monitoring (Administrators Only)
1. Navigate to **Profile → All Sessions (Admin)**
2. View comprehensive list of all active sessions
3. See user information for each session
4. Revoke suspicious sessions instantly

## Security Best Practices

### For Users
1. **Regular Check**: Review your active sessions regularly
2. **Unrecognized Sessions**: Immediately revoke any unfamiliar sessions
3. **Change Password**: If you find suspicious activity, change your password
4. **Use Revoke All**: When traveling or using public computers

### For Administrators
1. **Monitor Regularly**: Check the admin sessions page periodically
2. **Unusual Patterns**: Look for suspicious IP addresses or unusual login times
3. **Geographic Anomalies**: Be alert to logins from unexpected locations
4. **Multiple Sessions**: Investigate users with many concurrent sessions

## Technical Implementation

### Controller: SessionController
Located at `app/Http/Controllers/SessionController.php`

**Methods:**
- `index()` - Display user's own sessions
- `adminIndex()` - Display all sessions (admin only)
- `destroy($sessionId)` - Revoke a specific session
- `destroyAll()` - Revoke all user's sessions except current
- `parseUserAgent($userAgent)` - Parse browser and device information

### Views
- `resources/views/sessions/index.blade.php` - User sessions page
- `resources/views/sessions/admin.blade.php` - Admin sessions page

### Middleware
- `auth` - Requires authentication for all session routes
- `admin` - Requires administrator role for admin routes

## Troubleshooting

### Sessions Not Showing
**Problem**: No sessions appear on the page
**Solution**: 
- Ensure you're logged in
- Check that SESSION_DRIVER=database in .env
- Verify the sessions table exists

### Can't Revoke Sessions
**Problem**: Revoke button doesn't work
**Solution**:
- You cannot revoke your current session (use logout instead)
- Check that you have permission to revoke that session
- Admins can revoke any session, users can only revoke their own

### Old Sessions Not Clearing
**Problem**: Expired sessions still showing
**Solution**:
- Laravel automatically cleans expired sessions via garbage collection
- Run `php artisan schedule:work` to enable automatic cleanup
- Or manually: `php artisan session:gc`

## Admin Credentials

**Super Admin Account:**
- Email: admin@example.com
- Password: admin123

**Regular User Account:**
- Email: test@example.com
- Password: password

## Important Notes

1. **Current Session Protection**: You cannot revoke your own current session through the session manager. Use the logout button instead.

2. **Admin Override**: Administrators can revoke any user's session for security purposes.

3. **Session Security**: All session data is encrypted in the database using Laravel's encryption.

4. **Activity Tracking**: Last activity time updates with every page request.

5. **Automatic Cleanup**: Expired sessions are automatically removed by Laravel's garbage collection.

## Future Enhancements

Potential improvements for the session management system:

1. **Email Notifications**: Alert users when new sessions are created
2. **Geographic Location**: Show city/country for each IP address
3. **Session Analytics**: Charts showing login patterns
4. **Two-Factor Authentication**: Require 2FA for new device logins
5. **Session History**: Keep logs of past sessions
6. **Trusted Devices**: Mark devices as trusted to skip 2FA

## Support

For issues or questions about session management:
1. Check this documentation first
2. Review Laravel's session documentation
3. Check application logs at `storage/logs/laravel.log`
4. Contact your system administrator

---

**Last Updated**: January 5, 2026
**Version**: 1.0
**Laravel Version**: 11.x
