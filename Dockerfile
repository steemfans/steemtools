FROM alpine:latest
WORKDIR /app
ENV API_URL https://steem-mention.com/api/block/watcher
RUN apk add --no-cache \
        python3 \
        git \
        gcc \
        python3-dev \
        musl-dev \
        openssl-dev
RUN pip3 install requests && \
        pip3 install -U git+git://github.com/Netherdrake/steem-python
COPY watcher/bots.py /app/bots.py
CMD ['python3 /app/bots.py']

