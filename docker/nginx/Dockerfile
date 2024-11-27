# Use the official NGINX image
FROM nginx:latest
RUN rm /etc/nginx/conf.d/default.conf

COPY ./nginx/nginx.conf /etc/nginx/conf.d/

COPY ./nginx/ssl/* /etc/ssl/