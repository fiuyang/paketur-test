# Employee API Spec

## Get Employees

Endpoint : GET /api/employees

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
            "name": "Dr. Luisa Nitzsche Sr.",
            "phone_number": "724.257.8271",
            "address": "651 Johns Groves Suite 463\nWest Monicaborough, MS 56768",
            "user_id": 4,
            "deleted_at": null,
            "created_at": "2024-11-27T08:53:05.000000Z",
            "updated_at": "2024-11-27T08:53:05.000000Z"
        },
        {
            "id": 1,
            "name": "Dr. Luisa Nitzsche Sr.",
            "phone_number": "724.257.8271",
            "address": "651 Johns Groves Suite 463\nWest Monicaborough, MS 56768",
            "user_id": 4,
            "deleted_at": null,
            "created_at": "2024-11-27T08:53:05.000000Z",
            "updated_at": "2024-11-27T08:53:05.000000Z"
        },
        {
            "id": 1,
            "name": "Dr. Luisa Nitzsche Sr.",
            "phone_number": "724.257.8271",
            "address": "651 Johns Groves Suite 463\nWest Monicaborough, MS 56768",
            "user_id": 4,
            "deleted_at": null,
            "created_at": "2024-11-27T08:53:05.000000Z",
            "updated_at": "2024-11-27T08:53:05.000000Z"
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

## Get Employee

Endpoint : GET /api/employees/:id

Request Header :
- Authorization : Bearer token

Response Body :

```json
{
    "data": {
        "name": "Muffin",
        "phone_number": "08987654321",
        "address": "Jl. Pengangsaan No. 72"
    }
}
```

Response Body (Failed) :

```json
{
    "message": "record not found"
}
```

## Create Employee

Endpoint : POST /api/employees

Request Header :
- Authorization : Bearer token

Request Body :

```json
{
    "name": "Ahmad Dalan",
    "phone_number": "08987654321",
    "address": "Jl. Ratu 44"
}
```

Response Body (Success) :

```json
{
    "message": "employee successfully created"
}
```

Response Body (Failed) :

```json
{
    "errors": {
        "name": [
            "The email field is required."
        ],
        "phone_number": [
            "The phone_number field is required."
        ]
    }
}
```

## Update Employee

Endpoint : PUT /api/employees/:id

Request Header :
- Authorization : Bearer token

Request Body :

```json
{
    "name": "Ahmad Dalan",
    "phone_number": "08987654321",
    "address": "Jl. Ratu 99"
}
```

Response Body (Success) :

```json
{
    "message": "employee successfully updated"
}
```

Response Body (Failed) :

```json
{
    "errors": {
        "phone_number": [
            "The phone_number field is required."
        ],
        "address": [
            "The address field is required."
        ]
    }
}
```

## Delete Employee

Endpoint : DELETE /api/employees/:id

Request Header :
- Authorization : Bearer token

Response Body (Success) :

```json
{
    "message": "employee successfully deleted"
}
```
