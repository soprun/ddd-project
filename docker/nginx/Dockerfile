FROM nginx:alpine

WORKDIR /var/app

# copy nginx configuration
COPY /docker/nginx/app.conf /etc/nginx/conf.d/default.conf
COPY /docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY /public /var/app/public

CMD ["nginx", "-g", "daemon off;"]

EXPOSE 80 443
