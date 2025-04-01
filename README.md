### CST8265 - Web Security Basics :lock: :computer:

### dump database

docker exec db /usr/bin/mysqldump -u root -p cst8256proposal > backup_20250329.sql

### Final proposal project

## Introduction chapter

## Chapter 1:
1. SQL Injection and its Prevention.
2. Cross Side Scripting (XSS) Attack and its Prevention.

## Chapter 2:
3. Database Security and Threats: (Lab 4 and Lab 5)

## Chapter 3:
4. Cryptography and its application (Lab 10)

## Conclusion chapter

### Instructions for InsecureLogin

## SQL Injection

In order to bypass authentication: 

1. Add the following query as Student Id and Password
```sql
' OR '1'='1
```

## XSS Atttack

In order to make a stored (stored in db) XSS attack: 

1. Create a new user with the following data and this script as a name: 
- Student id: testXss
- Name: 
```js
 <script>alert(document.cookie);</script>
 ```
- Password: Test@2025

2. Login as the infected user (testXss)

- A pop up window will show to confirm the attack