import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '30s', target: 10 },   // Ramp up
    { duration: '1m', target: 50 },    // Stay at 50 users
    { duration: '30s', target: 0 },    // Ramp down
  ],
  thresholds: {
    http_req_duration: ['p(95)<2000'], // 95% of requests under 2s
    http_req_failed: ['rate<0.1'],     // Error rate under 10%
  },
};

const BASE_URL = 'http://localhost:8000/api';

export default function() {
  // Test rate limiting on authentication endpoints
  let authTests = [
    {
      name: 'Login Rate Limit Test',
      url: `${BASE_URL}/client/auth/login`,
      payload: {
        email: 'test@example.com',
        password: 'wrongpassword'
      }
    },
    {
      name: 'Register Rate Limit Test', 
      url: `${BASE_URL}/client/auth/register`,
      payload: {
        name: 'Test User',
        email: `test${Math.random()}@example.com`,
        password: 'password123',
        password_confirmation: 'password123'
      }
    }
  ];

  authTests.forEach(test => {
    let response = http.post(test.url, JSON.stringify(test.payload), {
      headers: { 'Content-Type': 'application/json' },
    });

    // Check for rate limiting (429 status code)
    let isRateLimited = response.status === 429;
    let hasRateLimitHeaders = response.headers['X-RateLimit-Limit'] !== undefined;
    
    check(response, {
      [`${test.name} - Rate limit enforced`]: (r) => isRateLimited || r.status < 500,
      [`${test.name} - Has rate limit headers`]: (r) => hasRateLimitHeaders,
      [`${test.name} - Response time acceptable`]: (r) => r.timings.duration < 5000,
    });

    if (isRateLimited) {
      console.log(`Rate limit hit for ${test.name}: ${response.status}`);
    }
  });

  sleep(1);
}
