FROM nginx:alpine

RUN rm /etc/nginx/conf.d/default.conf

COPY .docker/nginx/default.conf /etc/nginx/conf.d

COPY . /app

EXPOSE 80
