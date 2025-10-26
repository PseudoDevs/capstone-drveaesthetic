# 📱 Mobile App API Quick Reference

## 🔗 Base URL
```
https://drveaestheticclinic.online/api/client
```

---

## 🔐 Authentication
All endpoints require Bearer token authentication:
```
Authorization: Bearer {your_sanctum_token}
```

---

## 💰 **Bills API Endpoints**

### 1️⃣ Get All Client Bills
```http
GET /bills/users/{clientId}
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/bills/users/5 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "bill_number": "BILL-2024-0001",
      "client_id": 5,
      "appointment_id": 10,
      "total_amount": 2500.00,
      "paid_amount": 0.00,
      "remaining_balance": 2500.00,
      "status": "pending",
      "due_date": "2024-01-30",
      "is_overdue": false,
      "payment_progress": 0,
      "is_fully_paid": false,
      "is_staggered_payment": false,
      "next_payment_amount": 2500.00,
      "appointment": {
        "id": 10,
        "appointment_date": "2024-01-20",
        "service": {
          "id": 3,
          "service_name": "Facial Treatment",
          "price": 2500.00
        }
      },
      "payments": []
    }
  ]
}
```

---

### 2️⃣ Get Single Bill Details
```http
GET /bills/{billId}
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/bills/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "bill_number": "BILL-2024-0001",
    "total_amount": 2500.00,
    "paid_amount": 0.00,
    "remaining_balance": 2500.00,
    "status": "pending",
    "payment_progress": 0,
    "is_fully_paid": false,
    "client": {
      "id": 5,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "appointment": {
      "id": 10,
      "service": {
        "service_name": "Facial Treatment",
        "price": 2500.00
      }
    },
    "payments": []
  }
}
```

---

### 3️⃣ Get Outstanding Balance
```http
GET /bills/users/{clientId}/outstanding-balance
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/bills/users/5/outstanding-balance \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "total_outstanding": 5000.00,
    "overdue_amount": 1500.00,
    "pending_amount": 3500.00,
    "partial_amount": 0.00,
    "overdue_count": 1,
    "pending_count": 2,
    "partial_count": 0,
    "total_bills": 3
  }
}
```

---

### 4️⃣ Get Bill Payment History
```http
GET /bills/{billId}/payment-history
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/bills/1/payment-history \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "bill": {
      "id": 1,
      "bill_number": "BILL-2024-0001",
      "total_amount": 2500.00,
      "paid_amount": 1000.00,
      "remaining_balance": 1500.00,
      "status": "partial"
    },
    "payments": [
      {
        "id": 1,
        "payment_number": "PAY-2024-0001",
        "amount": 1000.00,
        "payment_method": "cash",
        "status": "completed",
        "payment_date": "2024-01-20"
      }
    ],
    "payment_summary": {
      "total_payments": 1,
      "completed_payments": 1,
      "total_paid": 1000.00,
      "payment_progress": 40.00
    }
  }
}
```

---

### 5️⃣ Download Bill Receipt
```http
GET /bills/{billId}/receipt
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/bills/1/receipt \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "receipt_url": "https://drveaestheticclinic.online/bills/1/print",
    "bill_number": "BILL-2024-0001",
    "download_message": "Receipt generated successfully"
  }
}
```

---

## 💳 **Payments API Endpoints**

### 1️⃣ Get All Client Payments
```http
GET /payments/users/{clientId}
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/payments/users/5 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "payment_number": "PAY-2024-0001",
      "bill_id": 1,
      "amount": 1000.00,
      "payment_method": "cash",
      "status": "completed",
      "payment_date": "2024-01-20",
      "is_completed": true,
      "bill": {
        "id": 1,
        "bill_number": "BILL-2024-0001",
        "total_amount": 2500.00,
        "remaining_balance": 1500.00,
        "status": "partial"
      }
    }
  ]
}
```

---

### 2️⃣ Get Single Payment Details
```http
GET /payments/{paymentId}
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/payments/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "payment_number": "PAY-2024-0001",
    "bill_id": 1,
    "amount": 1000.00,
    "payment_method": "cash",
    "payment_reference": "REF123",
    "status": "completed",
    "payment_date": "2024-01-20",
    "notes": "Partial payment",
    "bill": {
      "id": 1,
      "bill_number": "BILL-2024-0001",
      "total_amount": 2500.00,
      "paid_amount": 1000.00,
      "remaining_balance": 1500.00,
      "status": "partial"
    },
    "client": {
      "id": 5,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

---

### 3️⃣ Process New Payment
```http
POST /payments
```

**Example Request:**
```bash
curl -X POST https://drveaestheticclinic.online/api/client/payments \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "bill_id": 1,
    "amount": 1000.00,
    "payment_method": "cash",
    "payment_reference": "REF123",
    "notes": "Partial payment"
  }'
```

**Request Body:**
```json
{
  "bill_id": 1,
  "amount": 1000.00,
  "payment_method": "cash",
  "payment_reference": "REF123",
  "notes": "Partial payment"
}
```

**Available Payment Methods:**
- `cash`
- `credit_card`
- `debit_card`
- `bank_transfer`
- `check`
- `gcash`
- `maya`
- `paymaya`

**Example Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "payment_number": "PAY-2024-0002",
    "bill_id": 1,
    "amount": 1000.00,
    "payment_method": "cash",
    "payment_reference": "REF123",
    "status": "completed",
    "payment_date": "2024-01-25",
    "bill": {
      "id": 1,
      "bill_number": "BILL-2024-0001",
      "total_amount": 2500.00,
      "paid_amount": 2000.00,
      "remaining_balance": 500.00,
      "status": "partial",
      "is_fully_paid": false
    }
  },
  "message": "Payment processed successfully"
}
```

---

### 4️⃣ Get Payment Summary
```http
GET /payments/users/{clientId}/summary
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/payments/users/5/summary \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "total_paid": 5000.00,
    "total_payments": 5,
    "completed_payments": 5,
    "pending_payments": 0,
    "failed_payments": 0,
    "average_payment": 1000.00,
    "payment_methods": [
      {
        "method": "cash",
        "count": 3,
        "total_amount": 3000.00
      },
      {
        "method": "gcash",
        "count": 2,
        "total_amount": 2000.00
      }
    ],
    "recent_payments": [
      {
        "id": 5,
        "payment_number": "PAY-2024-0005",
        "amount": 1000.00,
        "payment_method": "cash",
        "payment_date": "2024-01-25",
        "bill_number": "BILL-2024-0003"
      }
    ]
  }
}
```

---

### 5️⃣ Download Payment Receipt
```http
GET /payments/{paymentId}/receipt
```

**Example Request:**
```bash
curl -X GET https://drveaestheticclinic.online/api/client/payments/1/receipt \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "receipt_url": "https://drveaestheticclinic.online/payments/1/print",
    "payment_number": "PAY-2024-0001",
    "download_message": "Payment receipt generated successfully"
  }
}
```

---

## 🚨 **Error Responses**

### Unauthorized Access (403)
```json
{
  "success": false,
  "message": "Unauthorized access to client bills"
}
```

### Invalid Payment Amount (400)
```json
{
  "success": false,
  "message": "Payment amount cannot exceed remaining balance"
}
```

### Bill Already Paid (400)
```json
{
  "success": false,
  "message": "This bill is already fully paid"
}
```

### Validation Error (422)
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "bill_id": ["The bill id field is required."],
    "amount": ["The amount must be at least 0.01."]
  }
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Failed to process payment: Database connection error"
}
```

---

## 🔑 **Authorization Rules**

### For Clients:
- ✅ Can access their own bills and payments
- ❌ Cannot access other clients' data
- ✅ Can make payments for their own bills

### For Staff/Admin:
- ✅ Can access any client's bills and payments
- ✅ Can make payments for any bill
- ✅ Full access to all endpoints

---

## 📊 **Bill Status Types**

| Status | Description |
|--------|-------------|
| `pending` | Bill is unpaid and not overdue |
| `partial` | Bill is partially paid |
| `paid` | Bill is fully paid |
| `overdue` | Bill is unpaid and past due date |

---

## 💳 **Payment Status Types**

| Status | Description |
|--------|-------------|
| `completed` | Payment successfully processed |
| `pending` | Payment is being processed |
| `failed` | Payment failed |

---

## 🧪 **Testing the API**

### Get Your Auth Token First:
```bash
# Login
curl -X POST https://drveaestheticclinic.online/api/client/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "your@email.com",
    "password": "yourpassword"
  }'
```

### Use Token in Requests:
```bash
# Replace YOUR_TOKEN with the token from login response
curl -X GET https://drveaestheticclinic.online/api/client/bills/users/YOUR_CLIENT_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## 📱 **Mobile App Integration Tips**

### 1. **Store the Auth Token Securely**
```typescript
// Use secure storage
import AsyncStorage from '@react-native-async-storage/async-storage';

await AsyncStorage.setItem('auth_token', token);
```

### 2. **Create an API Client**
```typescript
const apiClient = axios.create({
  baseURL: 'https://drveaestheticclinic.online/api/client',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
});

// Add auth token to requests
apiClient.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

### 3. **Handle Errors Gracefully**
```typescript
try {
  const response = await apiClient.get(`/bills/users/${userId}`);
  return response.data;
} catch (error) {
  if (error.response?.status === 403) {
    // Handle unauthorized
    Alert.alert('Error', 'You do not have access to this data');
  } else if (error.response?.status === 422) {
    // Handle validation errors
    Alert.alert('Validation Error', error.response.data.message);
  } else {
    // Handle other errors
    Alert.alert('Error', 'Something went wrong');
  }
}
```

---

## ✅ **All Endpoints Summary**

### Bills API (5 endpoints)
1. ✅ `GET /bills/users/{clientId}` - List all bills
2. ✅ `GET /bills/{billId}` - Get bill details
3. ✅ `GET /bills/users/{clientId}/outstanding-balance` - Get balance
4. ✅ `GET /bills/{billId}/payment-history` - Get payment history
5. ✅ `GET /bills/{billId}/receipt` - Download receipt

### Payments API (5 endpoints)
1. ✅ `GET /payments/users/{clientId}` - List all payments
2. ✅ `GET /payments/{paymentId}` - Get payment details
3. ✅ `POST /payments` - Process new payment
4. ✅ `GET /payments/users/{clientId}/summary` - Get payment summary
5. ✅ `GET /payments/{paymentId}/receipt` - Download receipt

---

**🎉 All endpoints are live and ready for mobile app integration!**

**Production URL:** https://drveaestheticclinic.online  
**Last Updated:** January 2025
