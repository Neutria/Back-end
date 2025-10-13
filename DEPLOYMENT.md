# Neutria API - Production Deployment Guide (Docker)

## Prerequisites

- Docker and Docker Compose installed on your production server
- External MySQL/MariaDB database accessible from your server
- Domain name configured (api.neutria.fr)
- SSL certificates (recommended: use a reverse proxy like Traefik or Nginx Proxy Manager)

## Files Overview

Production deployment uses separate files from development:

- `build/php/Dockerfile.prod` - Production PHP-FPM container with optimizations
- `build/nginx/Dockerfile.prod` - Production Nginx container
- `build/nginx/api.prod.conf` - Production Nginx configuration with security headers
- `compose.prod.yaml` - Production docker-compose file (no database)

## Deployment Steps

### 1. Prepare your environment file

Copy and configure your production environment:

```bash
cp api/.env.prod.example api/.env
```

Edit `api/.env` and update:
- `APP_SECRET` - Generate a strong random secret (use `php bin/console secrets:generate-keys`)
- `DATABASE_URL` - Update with your external database host/credentials
- `JWT_PASSPHRASE` - Set your JWT passphrase
- `CORS_ALLOW_ORIGIN` - Configure allowed origins

Example production `.env`:
```bash
APP_ENV=prod
APP_SECRET=your-generated-secret-key-here
APP_DEBUG=0
DATABASE_URL="mysql://username:password@external-db-host:3306/dbneutria?serverVersion=mariadb-10.11.2&charset=utf8mb4"
CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1|climesense\.fr|.*\.climesense\.fr)(:[0-9]+)?$'
```

### 2. Generate JWT keys (if not already done)

```bash
# Create JWT directory
mkdir -p api/config/jwt

# Generate private key (it will ask for a passphrase - use the same as JWT_PASSPHRASE in .env)
openssl genpkey -out api/config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096

# Generate public key
openssl pkey -in api/config/jwt/private.pem -out api/config/jwt/public.pem -pubout

# Set proper permissions
chmod 600 api/config/jwt/private.pem
chmod 644 api/config/jwt/public.pem
```

### 3. Configure external database connection

Your `DATABASE_URL` in `api/.env` should point to your external database:

```env
# Example with external database
DATABASE_URL="mysql://userneutria:password@192.168.1.100:3306/dbneutria?serverVersion=mariadb-10.11.2&charset=utf8mb4"

# Or with domain name
DATABASE_URL="mysql://userneutria:password@db.neutria.fr:3306/dbneutria?serverVersion=mariadb-10.11.2&charset=utf8mb4"
```

### 4. Build and start containers

```bash
# Build production images
docker compose -f compose.prod.yaml build --no-cache

# Start services in detached mode
docker compose -f compose.prod.yaml up -d

# Check containers are running
docker compose -f compose.prod.yaml ps
```

### 5. Run database migrations (first deployment only)

```bash
# Create database tables
docker compose -f compose.prod.yaml exec php php bin/console doctrine:migrations:migrate --no-interaction

# Or if migrations don't exist yet
docker compose -f compose.prod.yaml exec php php bin/console doctrine:schema:update --force
```

### 6. Verify deployment

```bash
# Check containers status
docker compose -f compose.prod.yaml ps

# View logs
docker compose -f compose.prod.yaml logs -f

# Test API endpoint
curl http://localhost:8000/

# Check PHP-FPM status
docker compose -f compose.prod.yaml exec php php-fpm -t
```

## Updating the Application

To deploy a new version:

```bash
# Pull latest code
git pull

# Rebuild images with no cache
docker compose -f compose.prod.yaml build --no-cache

# Stop old containers and start new ones
docker compose -f compose.prod.yaml up -d

# Run migrations if needed
docker compose -f compose.prod.yaml exec php php bin/console doctrine:migrations:migrate --no-interaction

# Verify update
docker compose -f compose.prod.yaml logs -f
```

## SSL/HTTPS Configuration

For production, you should use a reverse proxy for SSL termination. Options:

### Option 1: Nginx Reverse Proxy on Host

Create `/etc/nginx/sites-available/api.neutria.fr`:

```nginx
server {
    listen 443 ssl http2;
    server_name api.neutria.fr;

    ssl_certificate /etc/letsencrypt/live/api.neutria.fr/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.neutria.fr/privkey.pem;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name api.neutria.fr;
    return 301 https://$server_name$request_uri;
}
```

### Option 2: Traefik (Docker-based)

Update `compose.prod.yaml` nginx service with Traefik labels:

```yaml
nginx:
  # ... existing config ...
  labels:
    - "traefik.enable=true"
    - "traefik.http.routers.neutria-api.rule=Host(`api.neutria.fr`)"
    - "traefik.http.routers.neutria-api.entrypoints=websecure"
    - "traefik.http.routers.neutria-api.tls.certresolver=letsencrypt"
    - "traefik.http.services.neutria-api.loadbalancer.server.port=80"
```

## Monitoring and Logs

```bash
# View all logs
docker compose -f compose.prod.yaml logs -f

# View specific service logs
docker compose -f compose.prod.yaml logs -f nginx
docker compose -f compose.prod.yaml logs -f php

# Monitor resource usage
docker stats

# Check container health
docker compose -f compose.prod.yaml ps
```

## Backup Strategy

Since the database is external, ensure you have backups for:

1. **Database** - Handled by your external DB server
2. **Uploaded files** - Back up the shared volume:
   ```bash
   docker run --rm -v neutria-backend-prod_public-files:/data -v $(pwd):/backup alpine tar czf /backup/public-files-backup.tar.gz -C /data .
   ```
3. **JWT keys** - `api/config/jwt/`
4. **Environment file** - `api/.env`
5. **Application code** - Git repository

## Performance Optimization

The production setup includes:

- **OPcache enabled** - PHP bytecode caching
- **Composer optimized** - Class map optimized for production
- **No dev dependencies** - Smaller image size
- **Symfony cache warmed** - Faster first requests
- **Static file caching** - 1 year cache for assets
- **Health checks** - Automatic container monitoring

## Troubleshooting

### Container won't start

```bash
# Check logs for errors
docker compose -f compose.prod.yaml logs

# Check specific service
docker compose -f compose.prod.yaml logs php
```

### Database connection issues

```bash
# Test database connection from PHP container
docker compose -f compose.prod.yaml exec php php bin/console doctrine:query:sql "SELECT 1"

# Check DATABASE_URL is correct
docker compose -f compose.prod.yaml exec php php bin/console debug:container --env-vars
```

### Permission issues

```bash
# Fix permissions in container
docker compose -f compose.prod.yaml exec php chown -R www-data:www-data /app/api/var /app/api/public
```

### Clear opcache after code changes

```bash
# Restart PHP container to clear opcache
docker compose -f compose.prod.yaml restart php
```

### High memory usage

```bash
# Check resource usage
docker stats

# Adjust PHP memory limit in Dockerfile.prod if needed
# memory_limit=512M can be increased
```

## Security Checklist

- [ ] `.env` file has strong `APP_SECRET`
- [ ] JWT keys are generated with strong passphrase
- [ ] Database uses strong passwords
- [ ] CORS is configured for specific domains (not `*`)
- [ ] SSL/TLS is enabled (HTTPS)
- [ ] `APP_DEBUG=0` in production
- [ ] File permissions are correct (JWT keys are 600)
- [ ] Firewall rules allow only necessary ports
- [ ] Regular backups are scheduled
- [ ] Monitoring is set up for errors and performance

## Production vs Development

| Feature | Development | Production |
|---------|-------------|------------|
| Environment | `APP_ENV=dev` | `APP_ENV=prod` |
| Debug | Enabled | Disabled |
| OPcache | Disabled | Enabled with no validation |
| Composer | With dev deps | No dev deps, optimized |
| Cache warmup | No | Yes |
| Volumes | Bind mounts | Named volumes for uploads |
| Database | Containerized | External |
| HTTPS | Optional | Required (reverse proxy) |
| Health checks | No | Yes |

---

**Last Updated:** 2025-10-13
