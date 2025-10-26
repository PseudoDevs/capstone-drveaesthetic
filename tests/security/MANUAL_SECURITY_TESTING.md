# Manual Security Testing Guide

## 🔒 Security Testing Checklist

### 1. Authentication Security Tests

#### Test Rate Limiting
```bash
# Test login rate limiting (should get 429 after 10 attempts)
for i in {1..15}; do
  curl -X POST http://localhost:8000/api/client/auth/login \
    -H "Content-Type: application/json" \
    -d '{"email":"test@example.com","password":"wrongpassword"}' \
    -w "Status: %{http_code}\n"
  sleep 1
done
```

#### Test SQL Injection Protection
```bash
# Test SQL injection attempts
curl -X POST http://localhost:8000/api/client/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin'\'' OR '\''1'\''='\''1","password":"anything"}' \
  -w "Status: %{http_code}\n"
```

#### Test XSS Protection
```bash
# Test XSS attempts
curl -X POST http://localhost:8000/api/client/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"<script>alert('\''xss'\'')</script>","password":"test"}' \
  -w "Status: %{http_code}\n"
```

### 2. Authorization Tests

#### Test Unauthorized Access
```bash
# Test accessing protected endpoints without auth
curl -X GET http://localhost:8000/api/client/chats \
  -w "Status: %{http_code}\n"

curl -X GET http://localhost:8000/api/client/users \
  -w "Status: %{http_code}\n"
```

#### Test Chat Security
```bash
# Test chat endpoint security
curl -X POST http://localhost:8000/api/client/chats/send-message \
  -H "Content-Type: application/json" \
  -d '{"sender_id":1,"receiver_id":2,"message":"test"}' \
  -w "Status: %{http_code}\n"
```

### 3. Request Size Limit Tests

#### Test Large Request Rejection
```bash
# Create a large payload (2MB)
dd if=/dev/zero bs=1024 count=2048 | base64 > large_payload.txt

# Test large request
curl -X POST http://localhost:8000/api/client/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"test@example.com\",\"password\":\"$(cat large_payload.txt)\"}" \
  -w "Status: %{http_code}\n"
```

### 4. Security Headers Tests

#### Check Security Headers
```bash
# Test security headers
curl -I http://localhost:8000/api/client/auth/login

# Expected headers:
# X-Content-Type-Options: nosniff
# X-Frame-Options: DENY
# X-XSS-Protection: 1; mode=block
# Referrer-Policy: strict-origin-when-cross-origin
```

### 5. Session Security Tests

#### Test Session Configuration
```bash
# Check session cookie settings
curl -c cookies.txt -b cookies.txt http://localhost:8000/client/login

# Check cookie attributes:
# HttpOnly: true
# Secure: true (if HTTPS)
# SameSite: Strict
```

### 6. CSRF Protection Tests

#### Test CSRF Token Requirement
```bash
# Test CSRF protection on web routes
curl -X POST http://localhost:8000/client/appointments \
  -H "Content-Type: application/json" \
  -d '{"service_id":1,"appointment_date":"2024-01-01"}' \
  -w "Status: %{http_code}\n"
```

## 🧪 Automated Testing Commands

### Run K6 Tests
```bash
# Install K6
# Windows: choco install k6
# macOS: brew install k6
# Linux: sudo apt-get install k6

# Run rate limiting test
k6 run tests/security/k6-rate-limiting.js

# Run authentication test
k6 run tests/security/k6-authentication.js

# Run API security test
k6 run tests/security/k6-api-security.js
```

### Run JMeter Tests
```bash
# Install JMeter
# Download from https://jmeter.apache.org/download_jmeter.cgi

# Run security test plan
jmeter -n -t tests/security/jmeter-security-test-plan.jmx -l results.jtl

# Run authentication test
jmeter -n -t tests/security/jmeter-authentication-test.jmx -l auth-results.jtl
```

## 📊 Expected Results

### ✅ Pass Criteria
- **Rate Limiting**: 429 status codes after limit exceeded
- **Authentication**: 401 for invalid credentials, 200 for valid
- **Authorization**: 401 for unauthorized access
- **Request Size**: 413 for oversized requests
- **Security Headers**: All required headers present
- **Session Security**: Secure cookie attributes
- **CSRF Protection**: 419 for missing CSRF tokens

### ❌ Fail Criteria
- **No Rate Limiting**: No 429 responses
- **SQL Injection**: Successful injection attempts
- **XSS**: Script execution in responses
- **Information Disclosure**: Sensitive data in responses
- **Missing Headers**: Required security headers absent
- **Insecure Cookies**: Non-secure session cookies

## 🔍 Security Monitoring

### Check Application Logs
```bash
# Monitor security events
tail -f storage/logs/laravel.log | grep -i "security\|rate\|limit\|blocked"

# Check failed login attempts
grep "Failed login" storage/logs/laravel.log

# Monitor rate limiting
grep "Rate limit" storage/logs/laravel.log
```

### Database Security Checks
```sql
-- Check for failed login attempts
SELECT * FROM failed_jobs WHERE payload LIKE '%login%';

-- Check session security
SELECT * FROM sessions WHERE user_id IS NOT NULL;

-- Check rate limiting
SELECT * FROM cache WHERE key LIKE '%rate%';
```

## 🚨 Security Incident Response

### If Tests Fail
1. **Check middleware registration** in `bootstrap/app.php`
2. **Verify route protection** in `routes/api.php`
3. **Check configuration** in `config/security.php`
4. **Review application logs** for errors
5. **Test individual components** separately

### Common Issues
- **Rate limiting not working**: Check throttle middleware
- **Headers missing**: Verify SecurityHeaders middleware
- **Auth bypass**: Check route middleware configuration
- **Size limits ignored**: Verify RequestSizeLimit middleware

## 📈 Performance Impact Testing

### Load Testing with Security
```bash
# Test performance under security constraints
k6 run --vus 100 --duration 5m tests/security/k6-rate-limiting.js

# Monitor resource usage
htop
# or
top -p $(pgrep -f "php artisan serve")
```

### Memory and CPU Monitoring
```bash
# Monitor PHP memory usage
ps aux | grep php

# Monitor database connections
mysql -e "SHOW PROCESSLIST;"

# Monitor rate limiting cache
redis-cli monitor
```

This comprehensive testing approach ensures your security implementations are working correctly and can handle real-world attack scenarios.
