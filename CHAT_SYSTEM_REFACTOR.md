# Chat System Refactor

## Overview

This refactor addresses an issue where the application was using two separate models for chat messages:

1. `Message` model (table: `messages`)
2. `ChatMessage` model (table: `chat_messages`)

The issue was that messages were being stored in the `chat_messages` table, but some parts of the code were trying to fetch from the `messages` table, causing bugs.

## Changes Made

1. Updated `Conversation` model to use `ChatMessage` instead of `Message` for the `latestMessage` relationship
2. Updated `MessageController` to use `ChatMessage` instead of `Message`, including:
   - Changed import statement
   - Updated field names (`recipient_id` → `receiver_id`, `content` → `message`)
   - Fixed all queries to use the correct model and field names
3. Created a migration to drop the unused `messages` table

## How to Apply Changes

1. Run the migration to drop the unused `messages` table:

```bash
php artisan migrate
```

2. Clear application cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

3. Restart the application server

## Verification

After applying these changes, verify that:

1. Admin users can send and receive messages
2. Regular users can send and receive messages
3. Message history is displayed correctly
4. Unread message counts are accurate
5. Real-time messaging works as expected

If any issues are encountered, check the Laravel logs for errors.