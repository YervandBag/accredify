# Certificate Verification API

This Laravel application provides an API endpoint for verifying certificates. The verification process checks the validity of the recipient, issuer, and certificate signature using a `VerificationService`. The application also includes a `JsonContentValidator` to validate JSON input data.

## Table of Contents

-   [Installation](#installation)
-   [API Endpoint](#api-endpoint)
    -   [Verify Certificate](#verify-certificate)
    -   [Verification History](#verification-history)
    -   [Swagger Docs](#swagger-docs)
-   [Using the `/api/verify` Endpoint](#using-the-apiverify-endpoint)
    -   [Request Example with JSON File](#request-example-with-json-file)
-   [Testing](#testing)
-   [License](#license)

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/username/certificate-verification-api.git
    cd certificate-verification-api
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Create a copy of the `.env` file:

    ```bash
    cp .env.example .env
    ```

4. Generate an application key:

    ```bash
    php artisan key:generate
    ```

5. Configure your database and other environment variables in the `.env` file f needs.

6. Run the database migrations:

    ```bash
    php artisan migrate
    ```

7. Start the development server:

    ```bash
    php artisan serve
    ```

    The application should now be running on `http://localhost:8000`.

## API Endpoints

### Verify Certificate

#### URL

`POST /api/verify`

#### Description

This endpoint accepts a JSON file request containing certificate data and validates it. The certificate's recipient, issuer, and signature are verified.

#### Request Body

-   `json_file`

## Using the `/api/verify` Endpoint

### Request Example with JSON File

To use the `/api/verify` endpoint with a JSON file, follow these steps:

- In the root folder we have certificate.json file

1.  **Use `curl` to send the request** with the JSON file:

    ```bash
    curl -X POST http://localhost:8000/api/verify \
         -H "Content-Type: application/json" \
         -d @certificate.json
    ```

2.  **Response Example**:

```json
{
    "data": {
        "issuer": "Accredify",
        "result": "invalid_issuer"
    }
}
```


### Verification History

#### URL

`GET /api/history`

### Swagger Docs

#### URL

`GET /api/documentation`


## Testing

To run the tests, use the following command:

```bash
php artisan test
```
