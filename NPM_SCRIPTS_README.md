# DRC Embassy Management System - NPM Scripts

This document explains the available npm scripts for managing the DRC Embassy Management System.

## Quick Start

```bash
# Start the development server
npm run dev

# Or simply
npm start
```

## Available Commands

### Development
- `npm run dev` - Start development server and open in browser
- `npm start` - Alias for `npm run dev`

### Testing
- `npm run test` - Run comprehensive system tests
- `npm run test:config` - Test configuration settings
- `npm run test:db` - Test database connection
- `npm run test:php` - Test PHP configuration

### Setup & Maintenance
- `npm run setup` - Setup database and tables
- `npm run status` - Check system status
- `npm run validate` - Validate configuration
- `npm run backup` - Create project backup
- `npm run clean` - Clean temporary files

### Debugging
- `npm run debug:path` - Debug PHP executable path
- `npm run fix:path` - Open PHP path troubleshooting guide

### XAMPP Management
- `npm run xampp` - Open XAMPP Control Panel
- `npm run phpmyadmin` - Open phpMyAdmin
- `npm run apache` - Open Apache configuration
- `npm run php:config` - Open PHP configuration
- `npm run mysql:config` - Open MySQL configuration
- `npm run check:services` - Check XAMPP services status
- `npm run restart:apache` - Restart Apache service
- `npm run restart:mysql` - Restart MySQL service
- `npm run restart:all` - Restart all XAMPP services

### Monitoring & Documentation
- `npm run monitor` - Open system monitor
- `npm run logs` - Open logs directory
- `npm run docs` - Open documentation
- `npm run help` - Show available commands

## Usage Examples

### Starting Development
```bash
npm run dev
```
This will:
1. Check if XAMPP is running
2. Open the application in your browser
3. Display login credentials

### Running Tests
```bash
npm run test
```
This runs all system tests to ensure everything is working correctly.

### Creating Backup
```bash
npm run backup
```
This creates a timestamped backup of the entire project.

### Checking System Status
```bash
npm run status
```
This provides a comprehensive system status report.

## Prerequisites

- Node.js (>=14.0.0)
- npm (>=6.0.0)
- XAMPP installed and configured
- PHP executable in system PATH

## Troubleshooting

If you encounter issues:

1. **PHP not found**: Run `npm run debug:path` then `npm run fix:path`
2. **Database issues**: Run `npm run setup` to recreate database
3. **XAMPP services**: Run `npm run check:services` to verify services
4. **Configuration**: Run `npm run validate` to check configuration

## Configuration

The project configuration is stored in `settings.json` and can be validated using:
```bash
npm run validate
```

## Backup

Backups are stored in the `backups/` directory with timestamped names:
```
backups/
├── bomoko_backup_2025-01-15_14-30-25/
├── bomoko_backup_2025-01-15_16-45-12/
└── ...
```

## Support

For additional help, run:
```bash
npm run help
```

This will display all available commands with descriptions. 