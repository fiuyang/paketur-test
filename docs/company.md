# Company API Spec

## Get Companies

Endpoint : GET /api/companies

Request Header :
- Authorization : Bearer token

Request Param :
- search (optional)
- sort (optional) desc/asc
- limit (optional)
- page (optional)

Response Body :

```json
{
    "data": [
        {
            "id": 1,
            "name": "BRI",
            "email": "bri@example.org",
            "phone_number": "(530) 664-1431",
            "deleted_at": null,
            "created_at": "2024-11-27T06:21:24.000000Z",
            "updated_at": "2024-11-27T06:21:24.000000Z"
        },
        {
            "id": 2,
            "name": "BCA",
            "email": "bca@example.org",
            "phone_number": "(478) 773-1147",
            "deleted_at": null,
            "created_at": "2024-11-27T06:21:24.000000Z",
            "updated_at": "2024-11-27T06:21:24.000000Z"
        },
        {
            "id": 3,
            "name": "BNI",
            "email": "bni@example.org",
            "phone_number": "(765) 612-7435",
            "deleted_at": null,
            "created_at": "2024-11-27T06:21:24.000000Z",
            "updated_at": "2024-11-27T06:21:24.000000Z"
        }
    ],
    "first_page_url": "http://localhost:8000/api/companies?page=1",
    "from": 1,
    "last_page": 10,
    "last_page_url": "http://localhost:8000/api/companies?page=10",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://localhost:8000/api/companies?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": "http://localhost:8000/api/companies?page=2",
            "label": "2",
            "active": false
        },
        {
            "url": "http://localhost:8000/api/companies?page=3",
            "label": "3",
            "active": false
        },
        {
            "url": "http://localhost:8000/api/companies?page=2",
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": "http://localhost:8000/api/companies?page=2",
    "path": "http://localhost:8000/api/companies",
    "per_page": 2,
    "prev_page_url": null,
    "to": 2,
    "total": 20
}
```

## Create Company

Endpoint : POST /api/companies

Request Header :
- Authorization : Bearer token

Request Body :

```json
{
    "name": "PT BERKAH SELALUS",
    "email": "berkah@gmail.com",
    "phone_number": "08735253243434"
}
```

Response Body (Success) :

```json
{
    "message": "company successfully created"
}
```

Response Body (Failed) :

```json

{
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

## Delete Company

Endpoint : DELETE /api/companies/:id

Request Header :
- Authorization : Bearer token

Response Body (Success) :

```json
{
    "message": "company successfully deleted"
}
```

Response Body (Failed) :

```json
{
    "message": "record not found"
}
```
