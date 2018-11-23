FROM nginx:1.13-alpine
RUN mkdir -p /var/www
COPY  public /var/www/public
COPY  .env.example /var/www/.env
ADD docker/web/vhost.conf /etc/nginx/conf.d/default.conf
