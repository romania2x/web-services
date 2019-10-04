FROM alpine:3.8

RUN apk add --update --no-cache nginx

COPY nginx.conf /etc/nginx/
COPY api.conf /etc/nginx/conf.d/default.conf

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

EXPOSE 80
