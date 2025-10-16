# Payment Gateway Integration Module

## Overview

A Laravel payment processing module that supports multiple payment gateways Stripe and Cash on Delivery The system processes payments asynchronously using Laravel Queues while providing immediate API responses and on cash on delivery process synchronously

---

## Workflow

### Payment Initiation Flow

```
1. User sends payment request
   ↓
2. PaymentController receives request
   ↓
3. CreatePaymentRequest validates input (amount, gateway)
   ↓
4. IntializePaymentAction is executed
   ↓
5. CreateTransactionAction creates transaction in database
   ↓
6. PaymentGatewayFactory determines gateway type
   ↓
7. Two paths based on gateway type:

   Path A (COD - Synchronous):
   - CodGateway processes payment immediately
   - Transaction marked as SUCCESS
   - User receives success response

   Path B (Stripe - Asynchronous):
   - GatewayPaymentProcess job queued
   - Transaction marked as PENDING
   - User receives pending response
```

### Background Processing Flow

```
1. Queue worker picks up GatewayPaymentProcess job (after 5s delay)
   ↓
2. Gateway processes payment (simulated)
   ↓
3. UpdateTransactionAction updates transaction status
   ↓
4. Transaction marked as SUCCESS or FAILED
   ↓
5. User can check status via check-transaction endpoint
```

### Transaction Status Check Flow

```
1. User sends check-transaction request with reference_id
   ↓
2. CheckTransactionRequest validates reference_id exists
   ↓
3. TransactionQueryBuilder retrieves transaction from database
   ↓
4. TransactionShowViewModel formats response
   ↓
5. User receives current transaction status
```
