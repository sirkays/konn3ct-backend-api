import json

with open('postman/Konn3ct-Backend-API.postman_collection.json', 'r') as f:
    col = json.load(f)

base = '{{base_url}}/api/v1/admin'

new_folders = [
    {
        'name': 'User Management',
        'item': [
            {
                'name': 'List Users',
                'request': {
                    'method': 'GET',
                    'header': [{'key': 'Authorization', 'value': 'Bearer {{admin_access_token}}', 'type': 'text'}],
                    'url': {
                        'raw': base + '/users?page=1&limit=25',
                        'host': ['{{base_url}}'],
                        'path': ['api', 'v1', 'admin', 'users'],
                        'query': [
                            {'key': 'page', 'value': '1'},
                            {'key': 'limit', 'value': '25'},
                            {'key': 'search', 'value': '', 'description': 'Name/email/ID search'},
                            {'key': 'role', 'value': '', 'description': 'Filter by type (user, admin)'},
                            {'key': 'status', 'value': '', 'description': 'ACTIVE, SUSPENDED, BANNED'},
                            {'key': 'sortBy', 'value': 'createdAt'},
                            {'key': 'sortOrder', 'value': 'desc'},
                        ]
                    },
                    'description': 'GET /api/v1/admin/users\nPermission: users:read or admin.*\nReturns status (account_status) and subscription_status (users.status) separately.'
                },
                'response': []
            },
            {
                'name': 'Suspend User',
                'request': {
                    'method': 'POST',
                    'header': [
                        {'key': 'Authorization', 'value': 'Bearer {{admin_access_token}}', 'type': 'text'},
                        {'key': 'Content-Type', 'value': 'application/json', 'type': 'text'},
                    ],
                    'body': {
                        'mode': 'raw',
                        'raw': '{\n  "reason": "Repeated violation of platform policies."\n}',
                        'options': {'raw': {'language': 'json'}}
                    },
                    'url': {
                        'raw': base + '/users/:id/suspend',
                        'host': ['{{base_url}}'],
                        'path': ['api', 'v1', 'admin', 'users', ':id', 'suspend'],
                        'variable': [{'key': 'id', 'value': '1'}]
                    },
                    'description': 'POST /api/v1/admin/users/{id}/suspend\nPermission: users:suspend or admin.*\nSets account_status=SUSPENDED. Revokes Sanctum + admin tokens. Idempotent.\nResponse enforcement.complete=false until meeting service contract is confirmed.'
                },
                'response': []
            },
            {
                'name': 'Ban User',
                'request': {
                    'method': 'POST',
                    'header': [
                        {'key': 'Authorization', 'value': 'Bearer {{admin_access_token}}', 'type': 'text'},
                        {'key': 'Content-Type', 'value': 'application/json', 'type': 'text'},
                    ],
                    'body': {
                        'mode': 'raw',
                        'raw': '{\n  "reason": "Confirmed severe abuse of the platform.",\n  "confirmationCode": "CONFIRM BAN"\n}',
                        'options': {'raw': {'language': 'json'}}
                    },
                    'url': {
                        'raw': base + '/users/:id/ban',
                        'host': ['{{base_url}}'],
                        'path': ['api', 'v1', 'admin', 'users', ':id', 'ban'],
                        'variable': [{'key': 'id', 'value': '1'}]
                    },
                    'description': 'POST /api/v1/admin/users/{id}/ban\nPermission: users:ban or admin.*\nconfirmationCode must be exactly "CONFIRM BAN" (case-sensitive).\nSets account_status=BANNED. Revokes all Sanctum + admin tokens. Idempotent.\nAudit priority: HIGH. enforcement.complete=false (meeting service contract pending).'
                },
                'response': []
            },
        ]
    },
    {
        'name': 'Financials',
        'item': [
            {
                'name': 'List Transactions',
                'request': {
                    'method': 'GET',
                    'header': [{'key': 'Authorization', 'value': 'Bearer {{admin_access_token}}', 'type': 'text'}],
                    'url': {
                        'raw': base + '/financials/transactions?page=1&limit=25',
                        'host': ['{{base_url}}'],
                        'path': ['api', 'v1', 'admin', 'financials', 'transactions'],
                        'query': [
                            {'key': 'page', 'value': '1'},
                            {'key': 'limit', 'value': '25'},
                            {'key': 'status', 'value': '', 'description': 'success, failed, pending'},
                            {'key': 'paymentType', 'value': '', 'description': 'Subscription, Donation'},
                            {'key': 'gateway', 'value': '', 'description': 'Paystack, Flutterwave'},
                            {'key': 'startDate', 'value': '', 'description': 'YYYY-MM-DD (inclusive)'},
                            {'key': 'endDate', 'value': '', 'description': 'YYYY-MM-DD (inclusive, >= startDate)'},
                        ]
                    },
                    'description': 'GET /api/v1/admin/financials/transactions\nPermission: financials:read or admin.*\nDefault order: date DESC, id DESC.\nraw_webhook_payload maps payment.gateway_response (decoded JSON or safe fallback).'
                },
                'response': []
            }
        ]
    }
]

for item in col.get('item', []):
    if item.get('name') == 'Admin API':
        item['item'] = [x for x in item.get('item', []) if x.get('name') not in ['User Management', 'Financials']]
        item['item'].extend(new_folders)
        break

with open('postman/Konn3ct-Backend-API.postman_collection.json', 'w') as f:
    json.dump(col, f, indent=2)

print('Postman collection updated successfully')
