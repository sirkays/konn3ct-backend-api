# Admin Portal API Documentation

**Version:** 1.0  
**Branch:** `dev` | **Baseline commit:** `41480843`  
**Maintained by:** Konn3ct Engineering

---

## Table of Contents

1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Permissions Reference](#permissions-reference)
4. [Account Status Model](#account-status-model)
5. [Endpoints](#endpoints)
   - [GET /api/v1/admin/users](#get-apiv1adminusers)
   - [POST /api/v1/admin/users/{id}/suspend](#post-apiv1adminuserssuspend)
   - [POST /api/v1/admin/users/{id}/ban](#post-apiv1adminusersban)
   - [GET /api/v1/admin/financials/transactions](#get-apiv1adminfinancialstransactions)
6. [Enforcement Object](#enforcement-object)
7. [Meeting Enforcement Status](#meeting-enforcement-status)
8. [Audit Logging](#audit-logging)
9. [Error Reference](#error-reference)
10. [Security Constraints](#security-constraints)
11. [Performance Notes](#performance-notes)
12. [Task Completion Status](#task-completion-status)

---

## Overview

The Admin Portal API provides authenticated endpoints for administrator-level operations
including user management, account moderation, and financial transaction viewing.

**Base URL:** `/api/v1/admin`

All protected routes require a valid Admin JWT access token (issued by `POST /api/v1/admin/auth/login`).

---

## Authentication

All protected routes require:

```
Authorization: Bearer <admin_access_token>
```

- Token type: HMAC-SHA256 JWT (symmetric)
- Issued by: `POST /api/v1/admin/auth/login`
- Refreshed by: `POST /api/v1/admin/auth/refresh`
- Token is validated by `AdminJwtMiddleware`
- **Never** send the access token in query parameters or cookies

Responses include `Cache-Control: no-store` (may appear as `no-store, private`).

### account_status enforcement on auth

Authentication is blocked if:
- `users.account_status` is `SUSPENDED` or `BANNED`
- `users.status` (legacy field) is in `['suspended', 'disabled', 'blocked', 'inactive', 'deactivated', 'banned']`

Null `account_status` is treated as `ACTIVE` (backward-compatible).

---

## Permissions Reference

| Permission       | Grants                            |
|-----------------|-----------------------------------|
| `users:read`    | List and search users             |
| `users:suspend` | Suspend a user account            |
| `users:ban`     | Ban a user account                |
| `financials:read` | View financial transactions     |
| `admin.*`       | All of the above (super-admin)    |

**Admin-on-admin moderation:**
- `users:suspend` alone **cannot** suspend another administrator
- `users:ban` alone **cannot** ban another administrator
- `admin.*` is required to moderate another administrator
- No actor (including `admin.*`) can moderate themselves

---

## Account Status Model

### `users.account_status` (NEW)

Authoritative moderation status, separate from payment/subscription status.

| Value      | Meaning                              |
|-----------|--------------------------------------|
| `null`    | Treated as `ACTIVE` (legacy default) |
| `ACTIVE`  | Normal account access                |
| `SUSPENDED` | Account restricted, all tokens revoked |
| `BANNED`  | Permanent restriction, all tokens revoked |

> **Important:** `users.status` (the existing field) is used by payment flows and is
> **not** used for access control decisions. Never use `users.status` to gate login.
> The Admin Portal returns both:
> - `status` → `account_status`
> - `subscription_status` → `users.status`

### State machine

```
null / ACTIVE → SUSPENDED
null / ACTIVE → BANNED
SUSPENDED → BANNED
BANNED → (cannot be downgraded to SUSPENDED)
```

---

## Endpoints

### GET /api/v1/admin/users

List and search users with pagination, filtering, and sorting.

**Permission:** `users:read` or `admin.*`

**Query Parameters:**

| Parameter   | Type    | Default    | Description                                             |
|------------|---------|------------|---------------------------------------------------------|
| `page`     | integer | `1`        | Page number (min: 1)                                    |
| `limit`    | integer | `25`       | Results per page (max: 100)                             |
| `search`   | string  | —          | Search term (name, email, or numeric ID)               |
| `role`     | string  | —          | Filter by `users.type` (e.g. `user`, `admin`)          |
| `status`   | string  | —          | Filter by `account_status`: `ACTIVE`, `SUSPENDED`, `BANNED` |
| `sortBy`   | string  | `createdAt` | `id`, `name`, `email`, `role`, `status`, `createdAt`, `lastUsed` |
| `sortOrder` | string | `desc`     | `asc` or `desc`                                        |

**Search strategy (MySQL/MariaDB):**
- Multi-token name/email search: `FULLTEXT MATCH()` in BOOLEAN MODE
- Short terms (< 3 chars): falls back to `LIKE '%term%'`
- Numeric terms: exact `users.id` match first, then FULLTEXT
- Boolean-mode operators escaped before use (`+ - > < ( ) ~ * : " @`)

**Search strategy (SQLite — test env):**
- Case-insensitive `LIKE '%term%'` on firstname, lastname, email
- Numeric terms: exact `users.id` match

**ACTIVE status filter** matches both `account_status = 'ACTIVE'` and `account_status IS NULL`.

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 123,
      "firstname": "Amina",
      "lastname": "Bello",
      "name": "Amina Bello",
      "email": "amina@example.com",
      "role": "user",
      "status": "ACTIVE",
      "subscription_status": null,
      "plan": 1,
      "country": "NGA",
      "created_at": "2026-01-01T00:00:00+00:00",
      "last_used": null
    }
  ],
  "meta": {
    "page": 1,
    "limit": 25,
    "total": 1,
    "total_pages": 1,
    "has_next": false,
    "has_previous": false
  }
}
```

**Sensitive fields never returned:** `password`, `remember_token`, `two_factor_secret`, `two_factor_recovery_codes`.

---

### POST /api/v1/admin/users/{id}/suspend

Suspend a user's account.

**Permission:** `users:suspend` or `admin.*`

**Path Parameter:** `id` (integer, required) — User ID to suspend

**Request Body:**

```json
{
  "reason": "Repeated violation of platform policies during meetings."
}
```

| Field    | Type   | Required | Constraints      |
|---------|--------|----------|------------------|
| `reason` | string | ✅       | 10–1000 chars, trimmed |

**Business rules:**
- Cannot suspend yourself → `409 USER_STATE_CONFLICT`
- Cannot suspend another administrator without `admin.*` → `403 FORBIDDEN`
- Cannot downgrade a `BANNED` user to `SUSPENDED` → `409 USER_STATE_CONFLICT`
- If user is already `SUSPENDED` → `200` with `message: "User is already suspended (idempotent)."`

**Effects (transactional):**
1. Sets `account_status = 'SUSPENDED'`
2. Deletes all Sanctum `personal_access_tokens`
3. Revokes all `admin_refresh_tokens` (if target is admin)
4. Persists audit record (event: `AUDIT_USER_SUSPENDED`, priority: `NORMAL`)

**Response:**

```json
{
  "success": true,
  "message": "User suspended successfully.",
  "data": {
    "id": 123,
    "status": "SUSPENDED",
    "subscription_status": "active"
  },
  "enforcement": {
    "account_access": "ENFORCED",
    "sanctum_tokens": "REVOKED",
    "admin_refresh_tokens": "REVOKED",
    "meeting_join_tokens": "BLOCKED_PENDING_MEETING_SERVICE_CONTRACT",
    "live_disconnect": "BLOCKED_PENDING_MEETING_SERVICE_CONTRACT",
    "complete": false
  }
}
```

**Response Header:** `X-Correlation-Id: <uuid>`

---

### POST /api/v1/admin/users/{id}/ban

Permanently ban a user's account.

**Permission:** `users:ban` or `admin.*`

**Path Parameter:** `id` (integer, required) — User ID to ban

**Request Body:**

```json
{
  "reason": "Confirmed severe abuse of the platform.",
  "confirmationCode": "CONFIRM BAN"
}
```

| Field              | Type   | Required | Constraints                                    |
|-------------------|--------|----------|------------------------------------------------|
| `reason`           | string | ✅       | 1–1000 chars                                   |
| `confirmationCode` | string | ✅       | Must be exactly `"CONFIRM BAN"` (case-sensitive) |

**Business rules:**
- Cannot ban yourself → `409 USER_STATE_CONFLICT`
- Cannot ban another administrator without `admin.*` → `403 FORBIDDEN`
- If user is already `BANNED` → `200` with `message: "User is already banned (idempotent)."` (tokens still re-revoked)
- A `BANNED` user cannot be downgraded to `SUSPENDED` via the suspend endpoint

**Effects (transactional):**
1. Sets `account_status = 'BANNED'`
2. Deletes all Sanctum `personal_access_tokens`
3. Revokes all `admin_refresh_tokens` (if target is admin)
4. Persists audit record (event: `AUDIT_USER_BANNED`, priority: `HIGH`)

**Response:** Same shape as suspend response with `status: "BANNED"` and `enforcement.complete: false`.

**Response Header:** `X-Correlation-Id: <uuid>`

---

### GET /api/v1/admin/financials/transactions

List payment transactions with optional filtering.

**Permission:** `financials:read` or `admin.*`

**Query Parameters:**

| Parameter     | Type    | Default | Description                                 |
|--------------|---------|---------|---------------------------------------------|
| `page`       | integer | `1`     | Page number (min: 1)                        |
| `limit`      | integer | `25`    | Results per page (max: 100)                 |
| `status`     | string  | —       | Payment status (e.g. `success`, `failed`)  |
| `paymentType` | string | —       | Payment type (e.g. `Subscription`)         |
| `gateway`    | string  | —       | Gateway name (e.g. `Paystack`)             |
| `startDate`  | string  | —       | `YYYY-MM-DD` (inclusive, full day)         |
| `endDate`    | string  | —       | `YYYY-MM-DD` (inclusive, >= `startDate`)   |

**Default ordering:** `date DESC`, `id DESC`

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "reference": "REF-abc123",
      "gateway_reference": "GW-xyz789",
      "amount": 5000,
      "currency": "NGN",
      "status": "success",
      "gateway": "Paystack",
      "payment_type": "Subscription",
      "user_id": 42,
      "date": "2026-08-01T10:00:00+00:00",
      "raw_webhook_payload": {
        "id": "txn_123",
        "status": "success"
      }
    }
  ],
  "meta": { "page": 1, "limit": 25, "total": 1, "total_pages": 1, "has_next": false, "has_previous": false }
}
```

**`raw_webhook_payload` decoding:**
- Valid JSON in `payment.gateway_response` → decoded object/array
- Invalid JSON (legacy) → `{ "__type": "raw_non_json_legacy_value", "__safe_len": N }`
- Empty/null → `null`

Raw gateway payloads are **never** written to application logs.

**Response Header:** `X-Correlation-Id: <uuid>`

---

## Enforcement Object

Moderation endpoints return a truthful `enforcement` object in every response:

| Field                  | Possible values                                   |
|-----------------------|---------------------------------------------------|
| `account_access`       | `ENFORCED`                                        |
| `sanctum_tokens`       | `REVOKED`                                         |
| `admin_refresh_tokens` | `REVOKED` (admin targets), `N/A` (regular users) |
| `meeting_join_tokens`  | `BLOCKED_PENDING_MEETING_SERVICE_CONTRACT`        |
| `live_disconnect`      | `BLOCKED_PENDING_MEETING_SERVICE_CONTRACT`        |
| `complete`             | `false` (until meeting service contract confirmed) |

---

## Meeting Enforcement Status

> **BLOCKED_PENDING_MEETING_SERVICE_CONTRACT**

The external Konn3ct meeting service (`KONN3CT_BASE_URL`) does not currently expose
a force-disconnect or token-revocation endpoint. The only confirmed endpoint is:

```
POST /api/external/v1/meetings/join
```

Until a force-disconnect endpoint is confirmed and tested:
- `MeetingEnforcementGateway` is bound to `UnsupportedMeetingEnforcementGateway`
- Meeting enforcement returns `BLOCKED_PENDING_MEETING_SERVICE_CONTRACT`
- **HTTP 200 is returned** after local enforcement (DB + Sanctum + admin tokens) succeeds
- `enforcement.complete` is always `false`

To implement real meeting enforcement:
1. Confirm the force-disconnect endpoint with the meeting service team
2. Implement a new class extending `MeetingEnforcementGateway` interface
3. Bind it in `AppServiceProvider::register()`
4. Add integration tests before deploying

**Meeting join enforcement for suspended/banned accounts:**  
`POST /api/app/kv4/join-room` checks `account_status` for registered users (by email).
Suspended or banned registered users are rejected with `403`.
Genuinely unregistered guest users retain access.

> Note: Anonymous users can still present another identity (different email/name)
> until the external meeting service provides an enforceable identity contract.

---

## Audit Logging

All moderation actions persist append-only records in `admin_audit_logs`.

| Column           | Type    | Description                                  |
|-----------------|---------|----------------------------------------------|
| `event_code`     | string  | e.g. `AUDIT_USER_SUSPENDED`                  |
| `priority`       | string  | `NORMAL` (suspend), `HIGH` (ban)             |
| `actor_admin_id` | integer | Administrator who performed the action       |
| `target_user_id` | integer | User affected                                |
| `correlation_id` | UUID    | Request correlation ID (also in `X-Correlation-Id` header) |
| `ip_address`     | string  | Actor's IP address                           |
| `user_agent`     | string  | Actor's UA (truncated to 500 chars)          |
| `metadata`       | JSON    | Non-sensitive context (previous status, token revocation flags, etc.) |

Records are **immutable** — the `AdminAuditLog` model raises a `LogicException` if
an update is attempted.

**Never logged:** tokens, raw payment payloads, passwords, `reason` field.

---

## Error Reference

| HTTP | `code`                 | Description                                |
|------|------------------------|--------------------------------------------|
| 400  | —                      | (Not used — see 422)                       |
| 401  | `UNAUTHENTICATED`      | Missing/invalid/expired/banned JWT         |
| 403  | `FORBIDDEN`            | Valid token but insufficient permission    |
| 404  | `USER_NOT_FOUND`       | No user found for given ID                 |
| 409  | `USER_STATE_CONFLICT`  | Self-moderation or BANNED→SUSPENDED attempt |
| 422  | `VALIDATION_ERROR`     | Request body/query validation failed       |
| 500  | —                      | Server error (see application logs)        |

---

## Security Constraints

1. **Token handling:** Admin JWT is read from `Authorization: Bearer` header only.
   Never from cookies, query params, or request body.
2. **Log safety:** Raw JWTs, tokens, passwords, payment payloads never appear in logs.
3. **Self-moderation blocked:** No admin can suspend/ban their own account.
4. **Admin-on-admin protection:** `users:suspend` / `users:ban` alone cannot
   moderate another administrator. Requires `admin.*`.
5. **Sanctum token revocation:** All personal access tokens are deleted on suspension/ban.
6. **Admin refresh token revocation:** `admin_refresh_tokens` are revoked when the target
   user is an administrator.
7. **Cache-Control:** All Admin API responses include `no-store` in `Cache-Control`.

---

## Performance Notes

> **Important:** Sub-100ms performance has **not** been benchmarked or proven with
> `EXPLAIN` or p50/p95 evidence on production MySQL/MariaDB. The following indexes
> are provided for optimal query planning but actual performance must be measured
> in a production-equivalent environment.

**Indexes added (migration `2026_08_13_000003`):**

| Table    | Index                               | Type        | Purpose                        |
|---------|-------------------------------------|-------------|--------------------------------|
| `users`  | `users_fulltext_name_email`         | FULLTEXT    | Name/email token search        |
| `users`  | `users_created_at_id_idx`           | B-tree      | Default ordering               |
| `users`  | `users_last_used_id_idx`            | B-tree      | Last-used sort                 |
| `users`  | `users_type_acct_status_idx`        | B-tree      | Role+status filter             |
| `payment` | `payment_date_id_idx`             | B-tree      | Default ordering               |
| `payment` | `payment_status_date_idx`         | B-tree      | Status+date filter             |
| `payment` | `payment_type_idx`                | B-tree      | Payment type filter            |
| `payment` | `payment_gateway_prefix_idx`      | B-tree prefix | Gateway filter (TEXT column) |

**FULLTEXT minimum word length:** MySQL/MariaDB defaults to 3 characters.
Terms shorter than 3 chars fall back to `LIKE '%term%'` automatically.

---

## Task Completion Status

| Task                                      | Status              | Notes                                       |
|------------------------------------------|---------------------|---------------------------------------------|
| Middleware: AdminJwtMiddleware            | ✅ Complete         | account_status + legacy status check        |
| Middleware: AdminPermissionMiddleware     | ✅ Complete         | Exact match + admin.* wildcard              |
| Migration: users.account_status          | ✅ Complete         | Forward-only, null = ACTIVE                 |
| Migration: admin_audit_logs              | ✅ Complete         | Append-only table                           |
| Migration: Performance indexes           | ✅ Complete         | MySQL FULLTEXT + B-tree, SQLite-safe        |
| GET /admin/users                         | ✅ Complete         | FULLTEXT + LIKE fallback, all sort fields   |
| POST /admin/users/{id}/suspend           | ✅ Complete         | Transactional, idempotent, audit            |
| POST /admin/users/{id}/ban               | ✅ Complete         | Transactional, idempotent, HIGH priority audit |
| GET /admin/financials/transactions       | ✅ Complete         | Filters, date range, gateway_response decode |
| MeetingEnforcementGateway interface      | ✅ Complete         | Dependency-injected, unsupported impl.      |
| Meeting enforcement (live disconnect)    | ⏳ **Partial**      | `BLOCKED_PENDING_MEETING_SERVICE_CONTRACT`  |
| Meeting enforcement (join token revoke)  | ⏳ **Partial**      | `BLOCKED_PENDING_MEETING_SERVICE_CONTRACT`  |
| Meeting join rejection (suspended/banned)| ✅ Complete         | Registered users blocked, guests preserved  |
| Admin login account_status check         | ✅ Complete         | Checks new + legacy field                   |
| Admin refresh account_status check       | ✅ Complete         | Checks new + legacy field                   |
| Audit logging (append-only)              | ✅ Complete         | Immutable model, no sensitive data in logs  |
| Correlation ID in response header        | ✅ Complete         | X-Correlation-Id (UUID)                     |
| Self-moderation block                    | ✅ Complete         | Suspend + ban endpoints                     |
| Admin-on-admin permission guard          | ✅ Complete         | Requires admin.* to moderate another admin  |
| Test suite (195 tests, 0 failures)       | ✅ Complete         | All Admin + Odoo + existing tests passing   |
| Postman collection updated               | ✅ Complete         | User Management + Financials folders added  |

> **Tasks 2 (suspend) and 3 (ban) are classified as partially complete** until the
> external Konn3ct meeting service confirms and deploys a force-disconnect or
> token-revocation endpoint, and a real `MeetingEnforcementGateway` implementation
> is tested.
