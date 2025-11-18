import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 5 },
    { duration: '2m', target: 5 },
    { duration: '1m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<5000'],
    http_req_failed: ['rate<0.05'],
  },
};

const BASE_URL = 'https://drveaestheticclinic.online';

export default function() {
  // Test admin panel access without authentication
  let unauthorizedTests = [
    {
      name: 'Unauthorized Admin Panel Access',
      url: `${BASE_URL}/admin`,
      method: 'GET'
    },
    {
      name: 'Unauthorized Admin Dashboard',
      url: `${BASE_URL}/admin/dashboard`,
      method: 'GET'
    },
    {
      name: 'Unauthorized Admin Users',
      url: `${BASE_URL}/admin/users`,
      method: 'GET'
    },
    {
      name: 'Unauthorized Admin Reports',
      url: `${BASE_URL}/admin/reports`,
      method: 'GET'
    },
    {
      name: 'Unauthorized Admin Settings',
      url: `${BASE_URL}/admin/settings`,
      method: 'GET'
    }
  ];

  unauthorizedTests.forEach(test => {
    let response = http.get(test.url);

    check(response, {
      [`${test.name} - Redirected to login`]: (r) => r.status === 302 || r.status === 401,
      [`${test.name} - Security headers present`]: (r) => 
        r.headers['X-Content-Type-Options'] === 'nosniff' &&
        r.headers['X-Frame-Options'] === 'DENY' &&
        r.headers['X-XSS-Protection'] === '1; mode=block',
      [`${test.name} - No sensitive data exposed`]: (r) => 
        !r.body.includes('password') && !r.body.includes('token') && !r.body.includes('secret'),
    });

    if (response.status !== 302 && response.status !== 401) {
      console.log(`Security issue: ${test.name} returned ${response.status} instead of redirect`);
    }
  });

  // Test role-based access control for admin panel
  let roleTests = [
    {
      name: 'Client Role Access to Admin Panel',
      url: `${BASE_URL}/admin`,
      headers: { 'X-Test-Role': 'Client' }
    },
    {
      name: 'Staff Role Access to Admin Panel',
      url: `${BASE_URL}/admin`,
      headers: { 'X-Test-Role': 'Staff' }
    },
    {
      name: 'Doctor Role Access to Admin Panel',
      url: `${BASE_URL}/admin`,
      headers: { 'X-Test-Role': 'Doctor' }
    }
  ];

  roleTests.forEach(test => {
    let response = http.get(test.url, { headers: test.headers });

    check(response, {
      [`${test.name} - Access denied`]: (r) => r.status === 403 || r.status === 401,
      [`${test.name} - Proper error message`]: (r) => 
        r.body.includes('Access denied') || r.body.includes('Admin privileges required'),
    });
  });

  // Test admin-specific security features
  let adminSecurityTests = [
    {
      name: 'Admin Two-Factor Authentication',
      url: `${BASE_URL}/admin/two-factor`,
      method: 'GET'
    },
    {
      name: 'Admin Audit Log Access',
      url: `${BASE_URL}/admin/audit-logs`,
      method: 'GET'
    },
    {
      name: 'Admin Security Settings',
      url: `${BASE_URL}/admin/security-settings`,
      method: 'GET'
    }
  ];

  adminSecurityTests.forEach(test => {
    let response = http.get(test.url);

    check(response, {
      [`${test.name} - Requires authentication`]: (r) => r.status === 302 || r.status === 401,
      [`${test.name} - Security headers present`]: (r) => 
        r.headers['X-Content-Type-Options'] === 'nosniff',
    });
  });

  // Test request size limits for admin panel
  let largePayload = 'x'.repeat(2 * 1024 * 1024); // 2MB payload
  let response = http.post(`${BASE_URL}/admin/users`, JSON.stringify({
    name: 'Test Admin',
    email: 'admin@example.com',
    password: largePayload,
    role: 'Admin'
  }), {
    headers: { 'Content-Type': 'application/json' },
  });

  check(response, {
    'Large request rejected': (r) => r.status === 413,
    'Request size limit enforced': (r) => r.status === 413,
  });

  // Test admin panel rate limiting
  for (let i = 0; i < 20; i++) {
    let response = http.get(`${BASE_URL}/admin`);
    
    if (i > 10) {
      check(response, {
        'Rate limiting enforced': (r) => r.status === 429,
      });
    }
    
    sleep(0.1);
  }

  sleep(2);
}
