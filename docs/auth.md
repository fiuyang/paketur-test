# Auth API Spec

## Login

Endpoint : POST /api/auth/login

Request Body :

```json
{
    "email": "john@gmail.com",
    "password": "password",
}
```

Response Body (Success) :

```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MzI2OTA1NzEsImV4cCI6MTczMjY5NDE3MSwibmJmIjoxNzMyNjkwNTcxLCJqdGkiOiJUdmZ3VldJbFNoeUgxaVBpIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.SL8iAp4ZE--RAui59SpNiXULT8BmvWOYpBGJvq5XlSA",
    "token_type": "bearer",
    "expires_in": 3600
}
```

Response Body (Failed) :

```json
{
    "message": "email or password is wrong"
}
```

Response Body (Validation Error) :

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
