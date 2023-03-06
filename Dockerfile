FROM php:7.3-apache
RUN apt-get update
RUN apt-get install -y wget
RUN wget https://github.com/mpyw/TwistOAuth/raw/master/build/TwistOAuth.phar
