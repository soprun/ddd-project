
FROM alpine:latest AS certificate
# RUN apk --no-cache add ca-certificates
# WORKDIR /var/app
# COPY --from=builder /go/src/github.com/alexellis/href-counter/app .


FROM nginx:alpine AS development

# copy nginx configuration
COPY /docker/nginx/app.conf /etc/nginx/conf.d/default.conf
COPY /docker/nginx/nginx.conf /etc/nginx/nginx.conf

# change working directory
WORKDIR /var/app

COPY /public /var/app/public

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]
