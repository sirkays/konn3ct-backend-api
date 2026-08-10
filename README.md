# Konn3ct Backend

Konn3ct Backend is a robust API built with Laravel 8, powering the Konn3ct platform for virtual meetings, webinars, and online classrooms. It handles user authentication, room management, real-time communications, and payment integrations.

## Features

- **Virtual Meetings & Rooms:** Integration with BigBlueButton for high-quality audio, video, and screen sharing.
- **Real-time Communication:** WebSockets using Laravel WebSockets and Pusher for live chat, presence, and event broadcasting.
- **Authentication:** Secure API authentication powered by Laravel Sanctum and scaffolding via Jetstream.
- **Payments & Subscriptions:** Seamless payment processing using Stripe and Paystack integrations.
- **Meeting Management:** Create, join, and manage virtual rooms, track attendance, and handle meeting histories.
- **Recordings:** Fetch and manage recordings of past meetings.
- **Pre-registration & Invites:** Event pre-registration, automated email invites, and attendee management.
- **Reseller System:** Specialized endpoints for reseller operations and pricing plans.

## Tech Stack

- **Framework:** [Laravel 8](https://laravel.com)
- **PHP Version:** ^7.3 | ^8.0
- **Database:** MySQL / MariaDB (Eloquent ORM)
- **Authentication:** Laravel Sanctum & Jetstream
- **WebSockets:** BeyondCode Laravel WebSockets & Pusher
- **Video Conferencing API:** BigBlueButton
- **Payments:** Stripe PHP, Paystack PHP SDK

## Requirements

- PHP >= 7.3
- Composer
- Node.js & NPM
- MySQL or compatible database
- BigBlueButton Server (for video conferencing functionality)
- Pusher / local WebSocket server configured

## Installation

1. **Clone the repository**
   (Assuming you have access to the repository)

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install NPM Dependencies**
   ```bash
   npm install
   npm run dev
   ```

4. **Environment Setup**
   Copy `.env.example` to `.env` and configure your local settings:
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your Database, BigBlueButton, Stripe/Paystack, and WebSocket credentials.

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations**
   ```bash
   php artisan migrate
   ```

## Development Server

Start the local development server:
```bash
php artisan serve
```

For real-time features, start the WebSocket server (if configured locally):
```bash
php artisan websockets:serve
```

## API Routes

The main API endpoints are defined in `routes/api.php` and include:
- `POST /api/create-room` - Create a new meeting room
- `POST /api/start-room` - Start an existing room
- `POST /api/join-room` - Join a room
- `GET /api/list-rooms` - List available rooms
- Payment and Webhook routes for Donatations/Subscriptions
- Preregistration handling

For full API documentation, refer to the postman collection or integrated API docs if available.
