# Neutria API - Production Deployment Guide

## CORS Configuration Fixed

The CORS (Cross-Origin Resource Sharing) issue has been resolved. The API now accepts requests from:
- `https://climesense.fr`
- `https://www.climesense.fr`
- `https://*.climesense.fr` (any subdomain)
- `http://localhost` (for local development)

## Deployment Steps for Production Server

### 1. Update Environment Configuration on Server

On your production server, create or update `.env.local` file:

```bash
cd /path/to/neutria/Back-end/api
cp .env.prod.example .env.local
```

Edit `.env.local` with production values:

```bash
APP_ENV=prod
APP_SECRET=<generate-a-strong-random-secret>
APP_DEBUG=0
CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1|climesense\.fr|.*\.climesense\.fr)(:[0-9]+)?$'
DATABASE_URL="mysql://user:password@host:3306/database?serverVersion=mariadb-10.11.2&charset=utf8mb4"
```

### 2. Clear Production Cache

After updating configuration, clear the cache on the server:

```bash
# SSH into your server
ssh user@api.climesense.fr

# Navigate to project
cd /path/to/neutria/Back-end

# Clear cache
docker compose exec php php bin/console cache:clear --env=prod

# Or if not using Docker
php bin/console cache:clear --env=prod
```

### 3. Restart Services

Restart your web server and PHP-FPM to apply changes:

```bash
# With Docker
docker compose restart nginx php

# Or with systemd
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

### 4. Verify CORS Headers

Test that CORS headers are properly set:

```bash
curl -I -X OPTIONS https://api.climesense.fr/api/rooms \
  -H "Origin: https://climesense.fr" \
  -H "Access-Control-Request-Method: GET"
```

You should see these headers in the response:
```
Access-Control-Allow-Origin: https://climesense.fr
Access-Control-Allow-Methods: GET, OPTIONS, POST, PUT, PATCH, DELETE
Access-Control-Allow-Headers: Content-Type, Authorization, Accept, X-Requested-With
```

## CORS Configuration Details

### File: `api/config/packages/nelmio_cors.yaml`

The configuration now includes:

```yaml
nelmio_cors:
    defaults:
        origin_regex: true
        allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
        allow_methods: ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE']
        allow_headers: ['Content-Type', 'Authorization', 'Accept', 'X-Requested-With']
        expose_headers: ['Link', 'Content-Type', 'Authorization']
        allow_credentials: false
        max_age: 3600
    paths:
        '^/api':
            allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
            allow_methods: ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE']
            allow_headers: ['Content-Type', 'Authorization', 'Accept', 'X-Requested-With']
            expose_headers: ['Link', 'Content-Type', 'Authorization']
            max_age: 3600
            origin_regex: true
```

### Environment Variable

In your `.env` or `.env.local`:

```bash
CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1|climesense\.fr|.*\.climesense\.fr)(:[0-9]+)?$'
```

This regex pattern allows:
- `http://localhost` or `https://localhost`
- `http://127.0.0.1` or `https://127.0.0.1`
- `https://climesense.fr`
- `https://www.climesense.fr`
- `https://app.climesense.fr`
- Any other subdomain of `climesense.fr`
- With or without port numbers

## Customizing CORS for Specific Domains

If you want to restrict to specific domains only:

```bash
# Allow only specific domains
CORS_ALLOW_ORIGIN='^https://(www\.climesense\.fr|app\.climesense\.fr)$'
```

If you want to allow all origins (NOT recommended for production):

```bash
CORS_ALLOW_ORIGIN='*'
```

## Troubleshooting

### Issue: Still Getting CORS Errors

1. **Check cache is cleared:**
   ```bash
   docker compose exec php php bin/console cache:clear --env=prod
   ```

2. **Verify environment variable is loaded:**
   ```bash
   docker compose exec php php bin/console debug:config nelmio_cors
   ```

3. **Check Nginx configuration** - ensure Nginx is not blocking CORS headers:
   ```nginx
   # In your Nginx config, ensure these are NOT set:
   # add_header 'Access-Control-Allow-Origin' should be handled by Symfony
   ```

4. **Check browser console** for the exact error and request headers

5. **Test with curl:**
   ```bash
   curl -v -H "Origin: https://climesense.fr" https://api.climesense.fr/api/rooms
   ```

### Issue: Preflight OPTIONS Requests Failing

Ensure your Nginx configuration passes OPTIONS requests to PHP:

```nginx
location ~ ^/api {
    try_files $uri /index.php$is_args$args;
}
```

## Additional Security Considerations

1. **Always use HTTPS in production** - the current config supports both HTTP and HTTPS, but you should enforce HTTPS

2. **Set specific origins** - instead of using wildcards, list specific domains:
   ```bash
   CORS_ALLOW_ORIGIN='^https://(climesense\.fr|app\.climesense\.fr)$'
   ```

3. **Enable credentials if needed** - if you're using cookies or authentication:
   ```yaml
   allow_credentials: true
   ```

4. **Monitor CORS requests** - check your logs for unauthorized origin attempts

## Testing After Deployment

1. Open your web app at `https://climesense.fr` or `https://app.climesense.fr`
2. Open browser DevTools (F12) → Network tab
3. Reload the page and watch API requests
4. Check that responses include proper CORS headers:
   - `Access-Control-Allow-Origin`
   - `Access-Control-Allow-Methods`
   - `Access-Control-Allow-Headers`

## Contact & Support

If you continue to experience CORS issues after following this guide:

1. Check browser console for the exact error message
2. Verify the `Origin` header in your request matches the allowed pattern
3. Ensure the server environment is loading the correct `.env.local` file
4. Restart all services after configuration changes

---

**Last Updated:** 2025-10-12
