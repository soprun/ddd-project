FROM nginx:alpine

WORKDIR /var/app

# copy nginx configuration
COPY /docker/nginx/app.conf /etc/nginx/conf.d/default.conf
COPY /docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY /public /var/app/public

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]
