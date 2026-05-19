FROM node:18-alpine AS build

WORKDIR /app

COPY package.json .
RUN npm install

COPY public ./public
COPY src ./src
RUN npm run build

FROM node:18-alpine

WORKDIR /app

RUN npm install -g serve

COPY --from=build /app/build ./build

EXPOSE 3000

HEALTHCHECK --interval=15s --timeout=5s --retries=5 \
    CMD node -e "require('http').get('http://localhost:3000', (r) => {if (r.statusCode !== 200) throw new Error(r.statusCode)})" || exit 1

CMD ["serve", "-s", "build", "-l", "3000"]
