FROM nginx:1.13-alpine
RUN mkdir -p /var/www 
ADD web/vhost.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/
RUN set -x ; \
  addgroup -g 82 -S www-data ; \
  adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1
 