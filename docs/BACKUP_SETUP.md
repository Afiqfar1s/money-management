# Automated Backup Setup for Windows

## Overview
The system is configured to automatically create database backups daily at midnight (00:00). To enable this automation, you need to set up Windows Task Scheduler to run Laravel's task scheduler.

## Prerequisites
- XAMPP with PHP installed at `C:\xampp\php\php.exe`
- Money Management application at `C:\xampp\htdocs\money-management`
- Administrator access to Windows

## Setup Instructions

### Option 1: Using Task Scheduler GUI (Recommended)

1. **Open Task Scheduler**
   - Press `Win + R`
   - Type `taskschd.msc` and press Enter

2. **Create New Task**
   - Click "Create Task" (not "Create Basic Task") in the right panel
   - Give it a name: `Laravel Money Management Scheduler`

3. **General Tab**
   - ✅ Check "Run whether user is logged on or not"
   - ✅ Check "Run with highest privileges"
   - Select your Windows user account

4. **Triggers Tab**
   - Click "New..."
   - Begin the task: "On a schedule"
   - Settings: Daily, Start at 00:00
   - ✅ Check "Repeat task every: 1 minute"
   - Duration: Indefinitely
   - ✅ Check "Enabled"
   - Click OK

5. **Actions Tab**
   - Click "New..."
   - Action: "Start a program"
   - Program/script: `C:\xampp\htdocs\money-management\run-scheduler.bat`
   - Start in: `C:\xampp\htdocs\money-management`
   - Click OK

6. **Conditions Tab**
   - ✅ UNCHECK "Start the task only if the computer is on AC power"
   - ✅ CHECK "Wake the computer to run this task" (optional)

7. **Settings Tab**
   - ✅ CHECK "Allow task to be run on demand"
   - ✅ CHECK "Run task as soon as possible after a scheduled start is missed"
   - ✅ CHECK "If the task fails, restart every: 1 minute"
   - Attempt to restart up to: 3 times
   - ✅ UNCHECK "Stop the task if it runs longer than:"

8. **Save**
   - Click OK
   - Enter your Windows password if prompted

### Option 2: Using PowerShell (Advanced)

Run PowerShell as Administrator and execute:

```powershell
$action = New-ScheduledTaskAction -Execute "C:\xampp\htdocs\money-management\run-scheduler.bat" -WorkingDirectory "C:\xampp\htdocs\money-management"

$trigger = New-ScheduledTaskTrigger -Daily -At "00:00"
$trigger.Repetition = (New-ScheduledTaskTrigger -Once -At "00:00" -RepetitionInterval (New-TimeSpan -Minutes 1) -RepetitionDuration (New-TimeSpan -Days 9999)).Repetition

$settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable -RunOnlyIfNetworkAvailable:$false

Register-ScheduledTask -TaskName "Laravel Money Management Scheduler" -Action $action -Trigger $trigger -Settings $settings -RunLevel Highest -User $env:USERNAME
```

## Verification

### Test the Setup

1. **Manual Test**
   - Run the batch file manually: Double-click `run-scheduler.bat`
   - Check if it executes without errors

2. **Check Task Scheduler Status**
   - Open Task Scheduler
   - Find your task in the Task Scheduler Library
   - Right-click → Run
   - Check "Last Run Result" should be "The operation completed successfully (0x0)"

3. **Verify Backup Creation**
   - Open: http://10.10.210.112/admin/backups
   - Click "Create Backup Now"
   - Verify backup file appears in the list

4. **Check Logs**
   - Scheduler logs: `storage/logs/scheduler.log`
   - Laravel logs: `storage/logs/laravel.log`

### Monitor Automated Backups

After midnight (00:00), check:
- Backup page shows new backup file dated today
- File size is reasonable (typically a few MB compressed)
- Scheduler log shows successful execution

## Backup Schedule

- **Frequency:** Daily at 00:00 (midnight)
- **Retention:** Last 30 days automatically kept
- **Location:** `storage/app/backups/backup_YYYY-MM-DD_HHMMSS.sql.gz`
- **Format:** Compressed SQL dump (.gz)

## Troubleshooting

### Task doesn't run
- Check Task Scheduler → Task Status
- Verify user account has permissions
- Check "Last Run Result" for error codes

### Backup files not created
- Check `storage/logs/laravel.log` for errors
- Verify MySQL/XAMPP is running
- Ensure database credentials in `.env` are correct
- Check disk space availability

### Permission issues
- Run `run-scheduler.bat` manually to see errors
- Ensure storage directory is writable
- Check `storage/app/backups` folder permissions

### Scheduler not executing at midnight
- Verify Task Scheduler trigger is set to 00:00
- Check computer is on or set to wake for task
- Review Task Scheduler History tab

## Manual Backup

If automated backup fails, you can always create backups manually:

1. Go to http://10.10.210.112/admin/backups
2. Click "Create Backup Now"
3. Wait for success message
4. Download the backup file using the download button

## Disabling Automated Backups

To disable:
1. Open Task Scheduler
2. Find "Laravel Money Management Scheduler"
3. Right-click → Disable

Or delete the task completely:
- Right-click → Delete

Manual backups will still work through the admin interface.

## Important Notes

- ⚠️ Keep your XAMPP/MySQL running for scheduled backups to work
- 💡 Test the setup during daytime before relying on midnight automation
- 🔐 Backup files are stored locally - consider additional offsite backup
- 📅 Old backups (>30 days) are automatically deleted
- 🔒 Only Admin users can access backup management page
