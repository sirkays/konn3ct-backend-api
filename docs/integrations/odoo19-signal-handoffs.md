# Odoo 19 Signal Handoff Integration

## Overview

The Konn3ct backend acts as a producer of outbound asynchronous signals to an external Odoo 19 integration. 
The system dispatches webhooks for five specific business events in real-time or via scheduled jobs, with guarantees for delivery, idempotency, and security.

### Configured Signals

1. **API-026**: `USER_REGISTERED`
2. **API-027**: `USAGE_METRICS`
3. **API-028**: `PAYMENT_SUCCESS`
4. **API-028**: `PAYMENT_FAILED`
5. **API-028**: `PAID_EVENT_PURCHASE`

## Delivery Architecture

- **Outbox Pattern**: Signals are created transactionally in the `odoo_outbound_signals` table. 
- **Asynchronous**: The `DeliverOdooSignalJob` is queued immediately after the database transaction commits.
- **Idempotency**: Duplicate prevention is enforced via a unique database constraint on the `idempotency_key`. The queue job carries only an `event_id` UUID, ensuring identical behavior across retries.
- **Security**: The payload is stored encrypted in the database using Laravel's Crypt facade. Passwords, MFA tokens, and raw gateway responses are never included in the payload. Delivery logs are sanitized.
- **HTTPS Enforcement**: Non-local/testing environments enforce delivery over HTTPS.
- **HMAC Signatures**: Configurable HMAC-SHA256 request signing is available.

## Configuration

All configuration is managed in `.env` and exposed via `config/odoo.php`.

### Feature Flags & Scheduling
- `ODOO19_INTEGRATION_ENABLED` (bool): Master kill-switch. Set to `true` to enable outbound signal creation.
- `ODOO19_USAGE_METRICS_ENABLED` (bool): Enables the daily usage metrics batch processor.
- `ODOO19_USAGE_METRICS_TIME` (string): Time to run the daily usage metrics job (e.g., `01:00`).

### Endpoints
- `ODOO19_BASE_URL`: Base URL for the Odoo 19 inbound API (e.g., `https://odoo.newwavesecosystem.com`).
- `ODOO19_USER_REGISTERED_PATH`: Endpoint path for `USER_REGISTERED` (e.g., `/api/v1/user/registered`).
- `ODOO19_USAGE_METRICS_PATH`: Endpoint path for `USAGE_METRICS`.
- `ODOO19_PAYMENT_SUCCESS_PATH`: Endpoint path for `PAYMENT_SUCCESS`.
- `ODOO19_PAYMENT_FAILED_PATH`: Endpoint path for `PAYMENT_FAILED`.
- `ODOO19_PAID_EVENT_PURCHASE_PATH`: Endpoint path for `PAID_EVENT_PURCHASE`.

### Authentication & Queueing
- `ODOO19_API_TOKEN`: Bearer token for the `Authorization` header.
- `ODOO19_SIGNING_SECRET`: Secret used to generate the `X-Konn3ct-Signature` header (HMAC-SHA256).
- `ODOO19_QUEUE_CONNECTION`: Connection name for signal delivery (defaults to `database`).
- `ODOO19_QUEUE_NAME`: Queue name for signal delivery (defaults to `odoo`).

## Known Limitations & Contract Gaps

The following issues were identified against the integration contract and handled defensively:

1. **Missing `country_code` in Users schema**: The `users` table lacks a dedicated `country_code` column, but contains a 3-character `country` column. This is used in place of `country_code` for the `USER_REGISTERED` signal.
2. **Missing Usage Metrics sources**: `watch_duration_seconds` and `ai_notes_used` metrics lack a database source. They are completely omitted from the payload rather than injecting invented null/zero values. `meetings_joined` semantics are unverified and thus also omitted. Currently, only `meetings_hosted` (number of rooms owned) is provided.
3. **Paid Event Identity Gap**: The `prereg_users` table lacks a direct foreign key to the `users` table. The `PAID_EVENT_PURCHASE` signal attempts to resolve identity via email matching. If unresolvable, the signal is recorded with a `waiting_for_identity` status and is not queued.
4. **No Abandoned Cart State**: The system lacks a persistent abandoned cart state. The `abandoned_cart` boolean is strictly `false` for now on `PAYMENT_FAILED`.

## Running Workers

Odoo signal delivery requires a dedicated, supervised queue worker in production:

```bash
php artisan queue:work database --queue=odoo --tries=5 --timeout=60
```
