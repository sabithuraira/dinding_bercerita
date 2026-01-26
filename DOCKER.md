# Docker Setup Guide

This project uses Docker for containerized deployment with MySQL and PHPMyAdmin.

## Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+

## Quick Start

### 1. Environment Setup

Copy the Docker environment example file:
```bash
cp .env.docker.example .env
```

Edit `.env` and set secure passwords:
- `DB_PASSWORD` - Database user password
- `MYSQL_ROOT_PASSWORD` - MySQL root password
- `PHPMYADMIN_PASSWORD` - PHPMyAdmin password
- `APP_KEY` - Generate with: `php artisan key:generate`

### 2. Build and Start Containers

```bash
# Build and start all services
docker-compose up -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down

# Stop and remove volumes (WARNING: deletes database)
docker-compose down -v
```

### 3. Install Dependencies

```bash
# Install PHP dependencies
docker-compose exec app composer install

# Install Node dependencies
docker-compose exec app npm install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate
```

### 4. Access Services

- **Application**: http://localhost:8000 (or your configured APP_PORT)
- **PHPMyAdmin**: http://localhost:8080 (or your configured PHPMYADMIN_PORT)
- **MySQL**: localhost:3306 (only if MYSQL_PORT is exposed)

## Security Best Practices for VPS

### 1. Strong Passwords
- Use strong, unique passwords for all services
- Never commit `.env` file to version control
- Change default passwords immediately

### 2. MySQL Port Exposure
For production VPS, consider removing MySQL port exposure:
```yaml
# In docker-compose.yml, comment out or remove:
# ports:
#   - "${MYSQL_PORT:-3306}:3306"
```
Access MySQL only through PHPMyAdmin or from within the Docker network.

### 3. PHPMyAdmin Security
- Use strong `PHPMYADMIN_PASSWORD`
- Consider restricting PHPMyAdmin access via reverse proxy (Nginx/Apache)
- Use HTTPS for PHPMyAdmin access
- Restrict access by IP if possible

### 4. Firewall Configuration
On your VPS, configure firewall:
```bash
# Allow only necessary ports
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw allow 8000/tcp  # Your app port (if not using 80/443)
ufw allow 8080/tcp  # PHPMyAdmin (consider restricting to specific IPs)
ufw deny 3306/tcp   # Block direct MySQL access from outside
```

### 5. Reverse Proxy Setup
For production, use Nginx/Apache as reverse proxy:
- Route your domain to the application
- Use SSL/TLS certificates (Let's Encrypt)
- Restrict PHPMyAdmin access to specific paths or IPs

### 6. Regular Updates
```bash
# Update Docker images
docker-compose pull
docker-compose up -d

# Update application
docker-compose exec app composer update
docker-compose exec app npm update
```

## Common Commands

```bash
# Execute commands in containers
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
docker-compose exec app composer install

# View container status
docker-compose ps

# View logs
docker-compose logs app
docker-compose logs mysql_mading
docker-compose logs phpmyadmin

# Access container shell
docker-compose exec app bash
docker-compose exec mysql_mading mysql -u root -p

# Backup database
docker-compose exec mysql_mading mysqldump -u root -p mading_db > backup.sql

# Restore database
docker-compose exec -T mysql_mading mysql -u root -p mading_db < backup.sql
```

## Troubleshooting

### Permission Issues
If you encounter permission issues:
```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Issues
- Verify `.env` has correct database credentials
- Check if MySQL container is healthy: `docker-compose ps`
- Check MySQL logs: `docker-compose logs mysql_mading`

### Port Conflicts
If ports are already in use, change them in `.env`:
- `APP_PORT=8001`
- `PHPMYADMIN_PORT=8081`
- `MYSQL_PORT=3307`

## Production Deployment

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Remove or comment out MySQL port exposure
3. Set up SSL/TLS certificates
4. Configure reverse proxy
5. Set up regular backups
6. Monitor logs and container health
7. Use Docker secrets or environment variable management for sensitive data
