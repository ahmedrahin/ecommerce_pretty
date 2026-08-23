
# API Documentation

Welcome to the API documentation for Padmin. This document provides details on how to interact with the API endpoints, authentication, and example usage.

## Base URL

The base URL for all API endpoints is:
http://localhost:8000/api/register
http://localhost:8000/api/login

Replace `http://localhost:8000/api/` with your actual API domain.

## Authentication

All endpoints require Bearer Token Authentication. Obtain a token using the provided `/register` or `/login` endpoints.

## Endpoints

### 1. Register a Subscriber

- **Endpoint:** `POST /register`
- **Description:** Registers a new subscriber and returns an authentication token if successful.
- **Request Body:**
  ```json
  {
    "first_name": "string",
    "last_name": "string",
    "phone_number": "string",
    "email": "string",
    "password": "string",
    "password_confirmation": "string",
    "terms_accepted": true
  }

- **Response:**

  `201 Created`

    ```json
    {
        "token": "Bearer <authentication_token>"
    }

- **-**

  `422 Unprocessable Entity` on validation errors:
  
  ```json
        {
              "errors": {
                "field_name": [
                  "Error message"
                ]
              }
          }

`500 Internal Server Error` on server issues.
  
### 2. Login a Subscriber
    
    - **Endpoint:** `POST /login`
    - **Description:** login a subscriber and returns an authentication token if successful.
    
- **Request Body:**

    ```json
          {
          "email_or_phone": "string",
          "password": "string"
         }

- **Response:**

    `200 OK`
  
     ```json
        {
          "token": "Bearer <authentication_token>"
        } 
