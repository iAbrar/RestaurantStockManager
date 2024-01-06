FROM nginx:stable-alpine

ARG NGINX_USER
ARG NGINX_GROUP

ENV NGINX_USER=${USER:-defaultuser}
ENV NGINX_GROUP=${GROUP:-defaultgroup}

# Create the group if it does not exist
RUN addgroup -S ${NGINX_GROUP} || true

# Create the user and add to the group
RUN adduser -g "${NGINX_GROUP}" -s /bin/sh -D ${NGINX_USER}

# Create necessary directory
RUN mkdir -p /var/www/html/public

# Add the default Nginx configuration file
ADD nginx/default.conf /etc/nginx/conf.d/default.conf

# Update nginx.conf to use the new user
RUN sed -i "s/user  nginx/user ${NGINX_USER}/g" /etc/nginx/nginx.conf
