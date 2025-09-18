# Digital Time Capsule

A beautiful Laravel application that allows users to create digital time capsules - messages, photos, and memories that unlock at future dates they specify.

## Features

- 🔐 **Secure Storage**: All messages are encrypted and stored securely
- ⏰ **Future Unlock**: Set any future date for your capsules to unlock
- 💌 **Rich Content**: Support for text, photos, and file attachments
- 📧 **Email Reminders**: Optional notifications before capsules unlock
- 🎨 **Beautiful UI**: Modern, emotional design with smooth animations
- 📱 **Responsive**: Works perfectly on all devices
- 🐳 **Docker Ready**: Complete Docker setup for easy deployment

## Requirements

- PHP 8.2+
- PostgreSQL 12+
- Node.js 18+
- Composer
- Docker & Docker Compose (for containerized setup)

## Installation

### Local Development

1. Clone the repository:
```bash
git clone https://github.com/maikuamx/timecapsule.git
cd time-machine
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=time_capsule
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

6. Generate encryption key for time capsules:
```bash
php artisan tinker
# In tinker console:
echo \Defuse\Crypto\Key::createNewRandomKey()->saveToAsciiSafeString();
# Copy the output to ENCRYPTION_KEY in .env
```

7. Run migrations:
```bash
php artisan migrate
```

8. Build assets:
```bash
npm run build
```

9. Start the development server:
```bash
php artisan serve
```

### Docker Development

1. Clone and navigate to the project:
```bash
git clone <repository-url>
cd digital-time-capsule
```

2. Start with Docker Compose:
```bash
docker-compose up -d
```

3. Install dependencies:
```bash
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build
```

4. Set up the application:
```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

5. Generate encryption key:
```bash
docker-compose exec app php artisan tinker
# Follow the same process as local development
```

The application will be available at `http://localhost:8080`

## Usage

### Creating Time Capsules

1. Register for an account or sign in
2. Click "Create Capsule" from your dashboard
3. Write your message to the future
4. Set an unlock date (can be days, months, or years in the future)
5. Optionally attach photos or documents
6. Click "Seal Time Capsule"

Your capsule will remain locked until the unlock date arrives!

### Managing Capsules

- **Dashboard**: View all your capsules organized by status
- **Ready to Open**: Capsules that have reached their unlock date
- **Waiting**: Capsules still locked with countdown timers
- **Opened**: Previously opened capsules you can revisit

## Security Features

- All time capsule content is encrypted using industry-standard encryption
- Row-level security prevents users from accessing others' capsules
- Secure file upload handling for attachments
- CSRF protection on all forms
- SQL injection prevention through Laravel's query builder

## Scheduled Tasks

Set up a cron job to send reminder emails:

```bash
# Add to crontab
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Or run manually:
```bash
php artisan capsules:send-reminders
```

## File Structure

```
├── app/
│   ├── Console/Commands/     # Artisan commands
│   ├── Http/Controllers/     # Web controllers  
│   ├── Models/              # Eloquent models
│   └── Policies/            # Authorization policies
├── database/
│   └── migrations/          # Database migrations
├── docker/                  # Docker configuration
├── resources/
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   └── views/              # Blade templates
└── routes/                 # Application routes
```

## API Endpoints

The application uses web routes with session authentication:

- `GET /` - Welcome page
- `GET /login` - Login form
- `POST /login` - Process login
- `GET /register` - Registration form
- `POST /register` - Process registration
- `GET /dashboard` - User dashboard
- `GET /capsules/create` - Create capsule form
- `POST /capsules` - Store new capsule
- `GET /capsules/{id}` - View capsule (if unlocked)
- `DELETE /capsules/{id}` - Delete capsule

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support or questions, please create an issue in the repository.

---

**Note**: This application is designed to run in a PHP environment with Laravel. The code provided here creates the complete structure but requires proper PHP/Laravel setup to execute.
