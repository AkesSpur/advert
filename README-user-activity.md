# User Activity Tracking System

## Overview
This system tracks when a profile user was last active on the site and displays this information in a user-friendly format on the profile page.

## Implementation Details

### Database Changes
- Added `last_active` timestamp field to the `users` table via migration
- The field is nullable and added to the fillable attributes in the User model

### Middleware
Created a `TrackUserActivity` middleware that:
- Runs on every web request for authenticated users
- Updates the user's `last_active` timestamp to the current time
- Is registered in the web middleware group in `Kernel.php`

### Helper Class
Created an `ActivityHelper` class with a `formatLastActive` method that formats the last active time in a user-friendly way:
- "онлайн" if active within the last 5 minutes
- "сегодня, HH:MM" if active today
- "вчера, HH:MM" if active yesterday
- Day name and time if active within the last week
- Full date (DD.MM.YYYY) otherwise

### Display
Updated the profile.blade.php template to use the ActivityHelper for formatting the last active time in three different locations:
- For small screens (mobile)
- For medium screens (tablet)
- For large screens (desktop)

### Command for Existing Users
Created an Artisan command `users:update-last-active` that:
- Finds all users with null `last_active` values
- Sets their `last_active` to either their `updated_at` or `created_at` timestamp
- Can be run with: `php artisan users:update-last-active`

## Usage
No manual intervention is needed. The system automatically:
1. Updates the `last_active` timestamp whenever a user performs an action while logged in
2. Displays the formatted last active time on profile pages

For existing profiles, run the command mentioned above to initialize their `last_active` values.