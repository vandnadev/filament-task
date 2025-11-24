# Laravel FilamentPHP Setup

## Requirements
- PHP >= 8.1, Composer, Laravel 10


## Setup in One Go
```bash
# 1. Clone Laravel project
git clone https://github.com/vandnadev/filament-task.git

# 2. Copy env & set DB
cp .env.example .env
php artisan key:generate
# edit .env with DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 3. Run migrations
php artisan migrate

# 4. Install Composer
composer Install

# 5. Create admin user
php artisan make:filament-user

# 6. Serve app
php artisan serve

#7. Url
http://127.0.0.1:8000/admin/login
