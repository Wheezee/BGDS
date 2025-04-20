# Barangay Governance and Development System (BGDS) 

Web Based Laravel System

##Screenshots
![Alt text](https://i.imgur.com/ki1PmBD.png)


## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL/MariaDB (If you want to use MySQL)

## Installation Steps

1. Clone the repository:
```bash
git clone https://github.com/Wheezee/BGDS.git
cd [your-project-folder]
```

2. Install PHP dependencies using Composer:
```bash
composer install
```

3. Create a copy of the `.env` file:

4. Configure your `.env` file:
- Set your database connection details:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run database migrations:
```bash
php artisan migrate
```

7. Seed the database with default super admin:
```bash
php artisan db:seed
```

8. Start the development server:
```bash
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`

## Default Super Admin Credentials

- Email: superadmin@gmail.com
- Password: superadmin123

## Database Management Commands

- To fresh install database (warning: this will delete all data):
```bash
php artisan migrate:fresh
```

- To fresh install database and add default super admin:
```bash
php artisan migrate:fresh --seed
```

## System Requirements

- PHP >= 8.1
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- And many other stuff... I dunno
