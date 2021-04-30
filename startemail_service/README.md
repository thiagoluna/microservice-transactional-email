<h2 align="center">
  Transactional Email Microservice
</h2>

<br>

## 💻 Project

This project is a transactional email microservice. This microservice will use external services to actually sent the emails.  

## ⚙️ Setup & Run

## StartEmailService
**Go to startemail_service folder and start the containers**
```sh
# cd startemail_service
# docker-compose up -d
```
Verify if `startemail_service_startemailapp_1"`, `startemail_service_startemailapp-nginx_1`, `startemail_service_startemailapp-mysql_1` and `startemail_service_startemailapp-redis_1` are running.
```sh
# docker ps 
```
**Start the Consumer**
```sh
# docker exec -it startemail_service_startemailapp_1 php artisan kafka:consumer status status-group 
```
