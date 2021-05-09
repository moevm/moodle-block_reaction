FROM node:latest

RUN apt-get update
RUN apt-get install zip

RUN npm install uglifyjs-folder -g

ADD . /code
WORKDIR /code

CMD sh /code/build.sh