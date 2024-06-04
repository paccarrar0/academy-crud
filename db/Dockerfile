FROM mysql:8.4

ENV MYSQL_ROOT_PASSWORD="123456"
ENV MYSQL_DATABASE="academydb"

ENV TZ="America/Sao_Paulo"

COPY ./createDb.sql /docker-entrypoint-initdb.d/createDb.sql

EXPOSE 3306
