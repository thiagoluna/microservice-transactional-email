<h2 align="center">
  Transactional Email Microservice
</h2>

<br>

## 💻 Project

This project is a transactional email microservice. This microservice will use external services to actually sent the emails.

## ⚙️ Setup & Run  

## SendGridService
**Go to sendgrid_service folder and start the container**
```sh
# cd sendgrid_service
# docker-compose up -d
```
Verify if `sendgrid_service_sendgridapp_1` is running.
```sh
# docker ps 
```
**Add in .env file the keys below**
These env variables require API keys, versions and secrets related to the email Platform.
- SENDGRID_KEY=key_registred_in_SendGrid_API
- SENDGRID_FROM_EMAIL=email_registred_in_SendGrid_API
- SENDGRID_FROM_NAME=name_registred_in_SendGrid_API

**Start the Consumer**
```sh
# docker exec -it sendgrid_service_sendgridapp_1 php artisan kafka:consumer sendgrid sendgrid-group 
```
