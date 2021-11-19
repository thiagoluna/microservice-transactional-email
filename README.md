<h2 align="center">
  Transactional Email Microservice
</h2>

<br>  
## 💻 Project

This project is a transactional email microservice. This microservice will use external services to actually sent the emails.

## Organization
This project has 5 resources
- Kafka  (Message Broker with 2 Topics: emails, status)
- StartEmailService (publish emails into the queue from API and CLI command and consume status form the queue)
- SendgridService (consume emails from the queue, send emails and publish status in the queue)
- MailjetService (consume emails from the queue, send emails and publish status in the queue)
- Frontend (send emails and list all emails sent and their status)

## 🚀 Tecnologies

- [PHP](https://php.net)
- [Laravel](https://laravel.com)
- [VueJS](https://vuejs.org/)
- [MySQL](https://mysql.com)
- [Docker](https://docker.com)
- [Kafka](https://kafka.apache.org/)

## 📝 Description
The system starts at the process of sending emails through the StartEmailService service which via API or CLI command 
receives emails, then send them to Kafka's SendGrid queue.  
<br>
The SendGridService, asynchronously, consumes the queue, then sends it via POST to the SendGrid email delivery platform 
that will effectively send the email to the recipient.  
<br>
After having the email sent, SendGridService receives the status and immediately forward it to Kafka's status queue which will consumed by the service that started this process.  
<br>
In the case of either 500 or 503 status, the process is restarted, therefore this time sending the message's payload to 
the Mailjet queue at Kafka.  
<br>
The Mailjet queue is consumed by a service called MailjetService which will repeat the steps sending the message to the 
Mailjet delivery platform, which will receive the status and publish it to the Status queue.  
<br>
The service StartEmailService will be constantly pulling the Status queue and updating the status of sending emails in 
the database.  
There are logs for every email that is sent by the services.  
<br>
This approach was chosen to be able to guarantee greater resilience in the email sending process and to help with 
scalability, which provides us with the possibility to scale the services individually.  
<br>
If perhaps the sending of the message payload to both SendGrid and Mailjet was in a single service and it was necessary 
to escalate the SendGrid service, the MailjetService would also be escalated unnecessarily.  
<br>
Apache Kafka was chosen because of its great reputation, speed, stability, and documentation, which improves the 
overall performance of the API calls, sending asynchronously.  
<br>  
## ⚙️ Setup & Run
Clone this Repository and enter on its folder.
```sh
# git clone https://github.com/thiagoluna/microservice-transactional-email.git  
# cd microservice-transactional-email
``` 
**Create the external network**
```sh
# docker network create takeaway-network
```

## 1º- Apache Kafka
**Go to kafka folder and start the containers.**
```sh
# cd kafka 
# docker-compose up -d
``` 
Verify if `kafka_kafka_1` and  `kafka_zookeeper_1` are running.
```sh
# docker ps
```
**Create the Topics**
```sh
# docker exec -it kafka_kafka_1 sh
# kafka-topics --create --bootstrap-server localhost:9092 --replication-factor 1 --partitions 2 --topic sendgrid
# kafka-topics --create --bootstrap-server localhost:9092 --replication-factor 1 --partitions 2 --topic mailjet
# kafka-topics --create --bootstrap-server localhost:9092 --replication-factor 1 --partitions 2 --topic status
```
**Verify if these 3 topics were created.**
```sh
# kafka-topics --bootstrap-server localhost:9092 --list  
```

## 2º- StartEmailService
**Go to startemail_service folder and start the containers**
```sh
# cd startemail_service
# docker-compose up -d --build
```
Verify if `startemail_service_startemailapp_1"`, `startemail_service_startemailapp-nginx_1`, `startemail_service_startemailapp-mysql_1` and `startemail_service_startemailapp-redis_1` are running.
```sh
# docker ps 
```
**Start the Consumer**
```sh
# docker exec -it startemail_service_startemailapp_1 php artisan kafka:consumer status status-group 
```
## 3º- SendGridService
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

## 4º- MailjetService
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

## 5º- Frontend
Go to frontend folder and start the container
```sh
# cd frontend
# docker-compose up
```
Access the Frontend in the browser http://localhost:8080


## 💻 Microservice Features

Allows to send an e-mail by an (JSON) API and through a CLI command.

**From CLI command**  
PS.: It requires to be in startemail_service folder and all the containers running.
```sh
# docker exec -it startemail_service_startemailapp_1 php artisan send:email
```

**From API RESOURCE**  
Host: http://localhost:8001
- Get all Emails Sent
  -- Resource : GET / listemail
  -- http://localhost:8001/listemail - endpoint to list emails
- Send Email
  --Resource: POST / sendemail
  -- http://localhost:8001/sendemail - endpoint to send email  
  --- Example of content - Allows text/plan and text/html   
  --- All required fields

```
{
    "name": "Name Test",
    "email": "contact@email.com",
    "subject": "Subject Test",
    "content": "text/plain - <h2>text/html<h2>"
}
```
**Frontend**  
Offer a VueJS application which allows to send an email (using this service) and allows to see all the emails with 
their status. http://localhost:8080

## 📄 Logs
Log entry for every email that is sent through this microservice.  
Publisher logs: `/storage/logs/publisher.log`  
Consumer logs: `/storage/logs/consumer.log`

## 📝 Tests
PS.: It requires the be in startemail_service folder  with this container and kafka container running.
```sh
docker exec -it startemail_service_startemailapp_1 vendor/bin/phpunit
```

Developed by Thiago Luna - [Linkedin](https://www.linkedin.com/in/thiago-luna/)

