FROM node:lts-alpine

WORKDIR /var/www/client
COPY client/package*.json /var/www/client/

RUN npm install -g npm@latest
RUN npm uninstall -g @angular/cli
RUN npm cache clean --force
RUN npm install -g @angular/cli@latest
RUN npm ci

RUN apk add chromium
RUN apk add chromium-chromedriver
ENV CHROME_BIN=/usr/bin/chromium-browser

EXPOSE 4200
CMD ["ng", "serve", "--host", "0.0.0.0"]