FROM alpine:3.19

RUN apk --update add openssh-client && \
    rm -f /var/cache/apk/* && \
    mkdir /root/.ssh

COPY docker/ssh/start.sh app/

RUN chmod +x /app/start.sh

ENTRYPOINT ["/app/start.sh"]
