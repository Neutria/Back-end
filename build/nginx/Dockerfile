# Dev image
FROM nginx:stable-perl

# Copy nginx config
COPY ./build/nginx/api.conf /etc/nginx/conf.d/default.conf

RUN mkdir -p /app/api
RUN ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime && echo "Europe/Paris" > /etc/timezone

EXPOSE 80