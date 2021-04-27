FROM node:14.6.0
WORKDIR /app
COPY package.json ./
RUN  npm install
EXPOSE 8080
CMD ["npm", "run", "serve"]