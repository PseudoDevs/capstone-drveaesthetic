import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 10 },
    { duration: '2m', target: 10 },
    { duration: '1m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<3000'],
    http_req_failed: ['rate<0.1'],
  },
};

const BASE_URL = 'http://localhost:8000';

export default function() {
  // Test staff panel access without authentication
  let unauthorizedTests = [
    {
      name: 'Unauthorized Staff Panel Access',
      url: `${BASE_URL}/staff`,
      method: 'GET'
    },
    {
      name: 'Unauthorized Staff Dashboard',
      url: `${BASE_URL}/staff/dashboard`,
      method: 'GET'
    },
    {
      name: 'Unauthorized Staff Users',
      url: `${BASE_URL}/staff/users`,
      method: 'GET'
    },
    {
      name: 'Unauthorized Staff Appointments',
      url: `${BASE_URL}/staff/appointments`,
      method: 'GET'
    }
  ];

  unauthorizedTests.forEach(test => {
    let response = http.get(test.url);

    check(response, {
      [`${test.name} - Redirected to login`]: (r) => r.status === 302 || r.status === 401,
      [`${test.name} - Security headers present`]: (r) => 
        r.headers['X-Content-Type-Options'] === 'nosniff' &&
        r.headers['X-Frame-Options'] === 'DENY',
      [`${test.name} - No sensitive data exposed`]: (r) => 
        !r.body.includes('password') && !r.body.includes('token'),
    });

    if (response.status !== 302 && response.status !== 401) {
      console.log(`Security issue: ${test.name} returned ${response.status} instead of redirect`);
    }
  });

  // Test role-based access control
  let roleTests = [
    {
      name: 'Client Role Access to Staff Panel',
      url: `${BASE_URL}/staff`,
      headers: { 'X-Test-Role': 'Client' }
    },
    {
      name: 'Staff Role Access to Admin Panel',
      url: `${BASE_URL}/admin`,
      headers: { 'X-Test-Role': 'Staff' }
    }
  ];

  roleTests.forEach(test => {
    let response = http.get(test.url, { headers: test.headers });

    check(response, {
      [`${test.name} - Access denied`]: (r) => r.status === 403 || r.status === 401,
      [`${test.name} - Proper error message`]: (r) => 
        r.body.includes('Access denied') || r.body.includes('Insufficient privileges'),
    });
  });

  // Test request size limits
  let largePayload = 'x'.repeat(2 * 1024 * 1024); // 2MB payload
  let response = http.post(`${BASE_URL}/staff/users`, JSON.stringify({
    name: 'Test User',
    email: 'test@example.com',
    password: largePayload
  }), {
    headers: { 'Content-Type': 'application/json' },
  });

  check(response, {
    'Large request rejected': (r) => r.status === 413,
    'Request size limit enforced': (r) => r.status === 413,
  });

  sleep(1);
}
