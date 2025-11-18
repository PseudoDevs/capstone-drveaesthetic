import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 20 },
    { duration: '2m', target: 20 },
    { duration: '1m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<2000'],
    http_req_failed: ['rate<0.05'],
  },
};

const BASE_URL = 'https://drveaestheticclinic.online/api';

export default function() {
  // Test authentication security
  let authTests = [
    {
      name: 'Valid Login',
      url: `${BASE_URL}/client/auth/login`,
      payload: { email: 'client@example.com', password: 'password123' },
      expectedStatus: 200
    },
    {
      name: 'Invalid Login',
      url: `${BASE_URL}/client/auth/login`,
      payload: { email: 'invalid@example.com', password: 'wrongpassword' },
      expectedStatus: 401
    },
    {
      name: 'SQL Injection Attempt',
      url: `${BASE_URL}/client/auth/login`,
      payload: { email: "admin' OR '1'='1", password: 'anything' },
      expectedStatus: 401
    },
    {
      name: 'XSS Attempt',
      url: `${BASE_URL}/client/auth/login`,
      payload: { email: '<script>alert("xss")</script>', password: 'test' },
      expectedStatus: 401
    }
  ];

  authTests.forEach(test => {
    let response = http.post(test.url, JSON.stringify(test.payload), {
      headers: { 'Content-Type': 'application/json' },
    });

    check(response, {
      [`${test.name} - Correct status`]: (r) => r.status === test.expectedStatus,
      [`${test.name} - No XSS in response`]: (r) => !r.body.includes('<script>'),
      [`${test.name} - Security headers present`]: (r) => 
        r.headers['X-Content-Type-Options'] === 'nosniff' &&
        r.headers['X-Frame-Options'] === 'DENY',
      [`${test.name} - Response time acceptable`]: (r) => r.timings.duration < 3000,
    });

    if (response.status !== test.expectedStatus) {
      console.log(`Unexpected status for ${test.name}: ${response.status}`);
    }
  });

  sleep(2);
}
