# PHP FPM container.
#
# All web requests are sent from Nginx to this container.
# This container would be scaled up/down in production.
ARG CLI_IMAGE
FROM ${CLI_IMAGE:-cli} as cli

# @see https://hub.docker.com/r/amazeeio/php/tags?page=1&name=fpm
# @see https://github.com/amazeeio/lagoon/tree/master/images/php/fpm
FROM amazeeio/php:7.2-fpm-v1.1.2

COPY --from=cli /app /app
