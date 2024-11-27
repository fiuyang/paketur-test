# Manager API Spec

## Get Managers

Endpoint : GET /api/managers

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
            "id": 2,
            "name": "manager",
            "email": "manager@gmail.com",
            "email_verified_at": null,
            "role_id": 2,
            "company_id": null,
            "deleted_at": null,
            "created_at": null,
            "updated_at": null,
            "role": {
                "id": 2,
                "name": "manager",
                "created_at": null,
                "updated_at": null
            },
            "company": null
        },
        {
            "id": 2,
            "name": "manager",
            "email": "manager@gmail.com",
            "email_verified_at": null,
            "role_id": 2,
            "company_id": null,
            "deleted_at": null,
            "created_at": null,
            "updated_at": null,
            "role": {
                "id": 2,
                "name": "manager",
                "created_at": null,
                "updated_at": null
            },
            "company": null
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

## Get Manager

Endpoint : GET /api/managers/:id

Request Header :
- Authorization : Bearer token

Response Body :

```json
{
    "data": {
        "id": 2,
        "name": "manager",
        "email": "manager@gmail.com",
        "email_verified_at": null,
        "role_id": 2,
        "company_id": null,
        "deleted_at": null,
        "created_at": null,
        "updated_at": null,
        "role": {
            "id": 2,
            "name": "manager",
            "created_at": null,
            "updated_at": null
        },
        "company": null
    }
}
```

Response Body (Failed) :

```json
{
    "message": "record not found"
}
```
