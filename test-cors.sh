#!/bin/bash

# CORS Testing Script for Neutria API
# This script tests CORS configuration on your API server

API_URL="${1:-https://api.climesense.fr}"
ORIGIN="${2:-https://climesense.fr}"

echo "======================================"
echo "Testing CORS Configuration"
echo "======================================"
echo "API URL: $API_URL"
echo "Origin: $ORIGIN"
echo ""

echo "1. Testing OPTIONS preflight request..."
echo "--------------------------------------"
curl -i -X OPTIONS "$API_URL/api/rooms" \
  -H "Origin: $ORIGIN" \
  -H "Access-Control-Request-Method: GET" \
  -H "Access-Control-Request-Headers: Content-Type, Authorization"
echo ""
echo ""

echo "2. Testing GET request with Origin header..."
echo "--------------------------------------"
curl -i -X GET "$API_URL/api/rooms" \
  -H "Origin: $ORIGIN" \
  -H "Accept: application/json"
echo ""
echo ""

echo "3. Expected CORS Headers:"
echo "--------------------------------------"
echo "✓ Access-Control-Allow-Origin: $ORIGIN"
echo "✓ Access-Control-Allow-Methods: GET, OPTIONS, POST, PUT, PATCH, DELETE"
echo "✓ Access-Control-Allow-Headers: Content-Type, Authorization, Accept, X-Requested-With"
echo "✓ Access-Control-Max-Age: 3600"
echo ""

echo "======================================"
echo "Test Complete"
echo "======================================"
echo ""
echo "If you don't see the expected headers above:"
echo "1. Make sure you've cleared the cache on the server"
echo "2. Verify .env.local has the correct CORS_ALLOW_ORIGIN"
echo "3. Restart nginx and php services"
echo ""
echo "Usage: $0 [API_URL] [ORIGIN]"
echo "Example: $0 https://api.climesense.fr https://app.climesense.fr"
