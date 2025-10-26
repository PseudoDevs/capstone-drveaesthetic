#!/bin/bash

# Security Testing Script
# This script runs comprehensive security tests for the application

echo "🔒 Starting Security Test Suite"
echo "================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    if [ $2 -eq 0 ]; then
        echo -e "${GREEN}✅ $1${NC}"
    else
        echo -e "${RED}❌ $1${NC}"
    fi
}

# Function to run test and capture result
run_test() {
    local test_name="$1"
    local test_command="$2"
    
    echo -e "${YELLOW}Running: $test_name${NC}"
    
    if eval "$test_command" > /dev/null 2>&1; then
        print_status "$test_name - PASSED" 0
        return 0
    else
        print_status "$test_name - FAILED" 1
        return 1
    fi
}

# Initialize counters
total_tests=0
passed_tests=0
failed_tests=0

echo "📋 Test Categories:"
echo "1. Rate Limiting Tests"
echo "2. Authentication Security"
echo "3. Authorization Tests"
echo "4. Input Validation Tests"
echo "5. Security Headers Tests"
echo "6. Session Security Tests"
echo "7. CSRF Protection Tests"
echo "8. File Upload Security Tests"
echo ""

# 1. Rate Limiting Tests
echo "🚦 Rate Limiting Tests"
echo "----------------------"

# Test login rate limiting
run_test "Login Rate Limiting" "
    for i in {1..15}; do
        response=\$(curl -s -o /dev/null -w '%{http_code}' -X POST http://localhost:8000/api/client/auth/login \
            -H 'Content-Type: application/json' \
            -d '{\"email\":\"test@example.com\",\"password\":\"wrongpassword\"}')
        if [ \$i -gt 10 ] && [ \$response -ne 429 ]; then
            exit 1
        fi
    done
    exit 0
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# 2. Authentication Security Tests
echo ""
echo "🔐 Authentication Security Tests"
echo "-------------------------------"

# Test SQL injection protection
run_test "SQL Injection Protection" "
    response=\$(curl -s -o /dev/null -w '%{http_code}' -X POST http://localhost:8000/api/client/auth/login \
        -H 'Content-Type: application/json' \
        -d '{\"email\":\"admin'\'' OR '\''1'\''='\''1\",\"password\":\"anything\"}')
    [ \$response -eq 401 ]
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# Test XSS protection
run_test "XSS Protection" "
    response=\$(curl -s -o /dev/null -w '%{http_code}' -X POST http://localhost:8000/api/client/auth/login \
        -H 'Content-Type: application/json' \
        -d '{\"email\":\"<script>alert('\''xss'\'')</script>\",\"password\":\"test\"}')
    [ \$response -eq 401 ]
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# 3. Authorization Tests
echo ""
echo "🛡️ Authorization Tests"
echo "---------------------"

# Test unauthorized access
run_test "Unauthorized Access Blocked" "
    response=\$(curl -s -o /dev/null -w '%{http_code}' -X GET http://localhost:8000/api/client/chats)
    [ \$response -eq 401 ]
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# 4. Input Validation Tests
echo ""
echo "📝 Input Validation Tests"
echo "-------------------------"

# Test large request rejection
run_test "Large Request Rejection" "
    large_payload=\$(printf 'x%.0s' {1..2097152})
    response=\$(curl -s -o /dev/null -w '%{http_code}' -X POST http://localhost:8000/api/client/auth/login \
        -H 'Content-Type: application/json' \
        -d \"{\\\"email\\\":\\\"test@example.com\\\",\\\"password\\\":\\\"\$large_payload\\\"}\")
    [ \$response -eq 413 ]
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# 5. Security Headers Tests
echo ""
echo "🔒 Security Headers Tests"
echo "------------------------"

# Test security headers
run_test "Security Headers Present" "
    headers=\$(curl -s -I http://localhost:8000/api/client/auth/login)
    echo \$headers | grep -q 'X-Content-Type-Options: nosniff' && \
    echo \$headers | grep -q 'X-Frame-Options: DENY' && \
    echo \$headers | grep -q 'X-XSS-Protection: 1; mode=block'
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# 6. Session Security Tests
echo ""
echo "🍪 Session Security Tests"
echo "------------------------"

# Test session cookie security
run_test "Session Cookie Security" "
    cookies=\$(curl -s -c - -b - http://localhost:8000/client/login | grep -E 'laravel_session|XSRF-TOKEN')
    echo \$cookies | grep -q 'HttpOnly' && echo \$cookies | grep -q 'Secure'
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# 7. CSRF Protection Tests
echo ""
echo "🛡️ CSRF Protection Tests"
echo "-----------------------"

# Test CSRF protection
run_test "CSRF Protection" "
    response=\$(curl -s -o /dev/null -w '%{http_code}' -X POST http://localhost:8000/client/appointments \
        -H 'Content-Type: application/json' \
        -d '{\"service_id\":1,\"appointment_date\":\"2024-01-01\"}')
    [ \$response -eq 419 ]
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# 8. File Upload Security Tests
echo ""
echo "📁 File Upload Security Tests"
echo "-----------------------------"

# Test file type validation
run_test "File Type Validation" "
    response=\$(curl -s -o /dev/null -w '%{http_code}' -X POST http://localhost:8000/api/client/users/1/upload-avatar \
        -F 'avatar=@/dev/null;type=text/plain')
    [ \$response -eq 422 ]
"
if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
((total_tests++))

# Run K6 Tests if available
echo ""
echo "⚡ K6 Load Testing"
echo "-----------------"

if command -v k6 &> /dev/null; then
    echo "Running K6 security tests..."
    
    if [ -f "tests/security/k6-rate-limiting.js" ]; then
        run_test "K6 Rate Limiting Test" "k6 run tests/security/k6-rate-limiting.js"
        if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
        ((total_tests++))
    fi
    
    if [ -f "tests/security/k6-authentication.js" ]; then
        run_test "K6 Authentication Test" "k6 run tests/security/k6-authentication.js"
        if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
        ((total_tests++))
    fi
    
    if [ -f "tests/security/k6-api-security.js" ]; then
        run_test "K6 API Security Test" "k6 run tests/security/k6-api-security.js"
        if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
        ((total_tests++))
    fi
else
    echo -e "${YELLOW}K6 not installed. Install with: brew install k6 (macOS) or choco install k6 (Windows)${NC}"
fi

# Run PHPUnit Tests
echo ""
echo "🧪 PHPUnit Security Tests"
echo "-------------------------"

if command -v php &> /dev/null; then
    if [ -f "tests/security/SecurityTestSuite.php" ]; then
        run_test "PHPUnit Security Test Suite" "php artisan test tests/security/SecurityTestSuite.php"
        if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
        ((total_tests++))
    fi
    
    if [ -f "tests/security/PanelSecurityTestSuite.php" ]; then
        run_test "PHPUnit Panel Security Test Suite" "php artisan test tests/security/PanelSecurityTestSuite.php"
        if [ $? -eq 0 ]; then ((passed_tests++)); else ((failed_tests++)); fi
        ((total_tests++))
    fi
else
    echo -e "${YELLOW}PHP not found in PATH${NC}"
fi

# Final Results
echo ""
echo "📊 Security Test Results"
echo "========================"
echo -e "Total Tests: ${YELLOW}$total_tests${NC}"
echo -e "Passed: ${GREEN}$passed_tests${NC}"
echo -e "Failed: ${RED}$failed_tests${NC}"

if [ $failed_tests -eq 0 ]; then
    echo ""
    echo -e "${GREEN}🎉 All security tests passed! Your application is secure.${NC}"
    exit 0
else
    echo ""
    echo -e "${RED}⚠️  $failed_tests security test(s) failed. Please review and fix the issues.${NC}"
    exit 1
fi
