<h2 align="center">
  Transactional Email Microservice
</h2>

<br>

## 💻 Project

This project is a transactional email microservice. This microservice will use external services to actually sent the emails.

## ⚙️ Setup & Run

## MailjetService
**Go to mailjet_service folder and start the container**
```sh
# cd mailjet_service
# docker-compose up -d
```
Verify if `mailjet_service_mailjetapp` is running.
```sh
# docker ps 
```
**Add in .env file the keys below:**
These env variables require API keys, versions and secrets related to the email Platform.
- MAILJET_KEY=key_registred_in_Mailjet_API
- MAILJET_SECRET=secret_registred_in_Mailjet_API
- MAILJET_FROM_EMAIL=email_registred_in_Mailjet_API
- MAILJET_FROM_NAME=name_registred_in_Mailjet_API

**Start the Consumer**
```sh
# docker exec -it mailjet_service_mailjetapp_1 php artisan kafka:consumer mailjet mailjet-group
```
