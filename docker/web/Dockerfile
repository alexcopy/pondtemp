FROM nginx:1.13-alpine
RUN mkdir -p /var/www
COPY   . /var/www
ADD web/vhost.conf /etc/nginx/conf.d/default.conf
