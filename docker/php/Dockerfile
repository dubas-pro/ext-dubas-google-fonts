FROM devilbox/php-fpm:8.0-work

USER devilbox
RUN ["/bin/bash", "-c", "/usr/local/bin/composer global require dubas/espo-console"]

USER root
RUN set -ex; \
    \
    ln --symbolic /home/devilbox/.composer/vendor/bin/espo /usr/local/bin/espo;

COPY --chmod=0755 common/docker-*.sh  /usr/local/bin/
