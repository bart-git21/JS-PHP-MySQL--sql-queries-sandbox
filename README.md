# SQL queries sandbox

# Project Overview:
```
Application for testing the sql queries.
```
# Technologies Used
Backend: PHP, PDO, MySQL.
Frontentd: Javascript, jQuery 3+, Bootstrap 4.3.
Authentication: no.
Data format: JSON.
Deployment: GitHub.

# Application features
- The settings for connecting to the database are stored in the .env file. ./vendor/autoload.php create array $_ENV.
- Header. It imported into each page. :hover styles, the active page is highlighted.
- Header has log-in select
![screen](https://github.com/bart-git21/JS-PHP-MySQL--sql-queries-testing/blob/main/login.jpg)
- The selected user is stored in localStorage. It is displayed when you navigate between pages. 
- Intro page show all user queries.
![screen](https://github.com/bart-git21/JS-PHP-MySQL--sql-queries-testing/blob/main/intro.jpg)
- Queries page show queries for logged user. Selection of another user will display the queries for that user.
![screen](https://github.com/bart-git21/JS-PHP-MySQL--sql-queries-testing/blob/main/query.jpg)
- User can modify the query or create the new one.
![screen](https://github.com/bart-git21/JS-PHP-MySQL--sql-queries-sandbox/blob/main/edit.jpg)

### Base api URL
localhost/api/index.php

# Endpoints

## GET /login
Read all users. Access simple log-in process. It is used for creating list of users in the login page.

## POST /login
Log-in. Create session and store user in localStorage.

## GET /query
Read all queries for specific logged user. Admin read all queries from all users.

## GET /query?id
Read a query with a specific id.

## POST /query
Create new query.
### Client request example
* **Headers**: 'Content-Type': 'application/json'
* **Body**:
```
{
    "name": "New query",
    "query": "SELECT * FROM test_data WHERE id > 3 AND id < 5",
    "userId": "2"
}
```
### API response example
* **Status code**: 200
* **Headers**: 'Content-Type': 'application/json'
* **Body**:
```
{
    "newQueryId": 4
}
```
### Error Handling
- 400 Bad Request: invalid request data or format
- 401 Unauthorized: authentication failed or missing
- 404 Not Found: query not found or does not exist
- 500 Internal Server Error: server-side error or exception

## PUT /query?id
Update a query with a specific id.
### Client request example
* **Headers**: 'Content-Type': 'application/json'
* **Body**:
```
{
    "id": "3",
    "name": "get Earth data",
    "query": "SELECT * FROM test_data WHERE id = 3",
    "userId": "3"
}
```
### API response example
* **Status code**: 200
* **Headers**: 'Content-Type': 'application/json'
* **Body**:
```
{
    "success": "query successfully updated"
}
```
### Error Handling
- 400 Bad Request: invalid request data or format
- 401 Unauthorized: authentication failed or missing
- 404 Not Found: query not found or does not exist
- 500 Internal Server Error: server-side error or exception