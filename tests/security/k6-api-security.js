import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 30 },
    { duration: '2m', target: 30 },
    { duration: '1m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<3000'],
    http_req_failed: ['rate<0.1'],
  },
};

const BASE_URL = 'http://localhost:8000/api';

export default function() {
  // Test API security without authentication
  let unauthorizedTests = [
    {
      name: 'Unauthorized Chat Access',
      url: `${BASE_URL}/client/chats`,
      method: 'GET'
    },
    {
      name: 'Unauthorized Message Send',
      url: `${BASE_URL}/client/chats/send-message`,
      method: 'POST',
      payload: { sender_id: 1, receiver_id: 2, message: 'test' }
    },
    {
      name: 'Unauthorized User Data Access',
      url: `${BASE_URL}/client/users`,
      method: 'GET'
    }
  ];

  unauthorizedTests.forEach(test => {
    let response;
    if (test.method === 'GET') {
      response = http.get(test.url);
    } else {
      response = http.post(test.url, JSON.stringify(test.payload), {
        headers: { 'Content-Type': 'application/json' },
      });
    }

    check(response, {
      [`${test.name} - Unauthorized access blocked`]: (r) => r.status === 401,
      [`${test.name} - Security headers present`]: (r) => 
        r.headers['X-Content-Type-Options'] === 'nosniff',
      [`${test.name} - No sensitive data exposed`]: (r) => 
        !r.body.includes('password') && !r.body.includes('token'),
    });

    if (response.status !== 401) {
      console.log(`Security issue: ${test.name} returned ${response.status} instead of 401`);
    }
  });

  // Test request size limits
  let largePayload = 'x'.repeat(2 * 1024 * 1024); // 2MB payload
  let response = http.post(`${BASE_URL}/client/auth/login`, JSON.stringify({
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
