# Book Manager

A WordPress plugin to manage book information using custom tables and post types, built with Rabbit Framework.

## Features

- Custom post type for books
- Custom database table for book information
- Admin meta boxes for book details
- Taxonomy support for book categorization
- Database migrations system
- Full test coverage with PHPUnit

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Composer

## Installation

1. Clone or download this plugin to your WordPress plugins directory
2. Navigate to the plugin directory and install dependencies:
   ```bash
   composer install
   ```
3. Activate the plugin through WordPress admin panel

## Development

### Running Tests

```bash
composer test
```

### Project Structure

```
book-manager/
├── src/              # Plugin source code
│   ├── App/         # Application core
│   ├── Models/      # Eloquent models
│   ├── Services/    # Business logic
│   ├── Providers/   # Service providers
│   └── Migrations/  # Database migrations
├── tests/           # PHPUnit tests
├── views/           # Template files
└── config/          # Configuration files
```

## Dependencies

- **veronalabs/rabbit**: ^1.5 (Framework)
- **phpunit/phpunit**: 9.6.x-dev (Testing)
- **mockery/mockery**: ^1.5 (Testing)

## License

MIT

## Author

Amir Safari
