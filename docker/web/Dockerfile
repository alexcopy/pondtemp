FROM nginx:1.13-alpine
RUN mkdir -p /var/www
COPY  public /var/www/public
ADD web/vhost.conf /etc/nginx/conf.d/default.conf
