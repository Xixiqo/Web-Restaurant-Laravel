# ==========================
# Stage 1 - Build Frontend
# ==========================
FROM node:20-alpine AS build

WORKDIR /app

# Copy dependency manifest terlebih dahulu agar cache Docker optimal
COPY package.json package-lock.json ./

RUN npm ci

# Copy source code
COPY . .

# Build production
RUN npm run build

# ==========================
# Stage 2 - Runtime
# ==========================
FROM nginxinc/nginx-unprivileged:1.27-alpine AS runtime

# Copy hasil build
COPY --from=build /app/dist /usr/share/nginx/html

# Konfigurasi Nginx
RUN cat <<'EOF' > /etc/nginx/conf.d/default.conf
server {
    listen 8080;
    server_name _;

    root /usr/share/nginx/html;
    index index.html;

    server_tokens off;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|svg|ico|woff2?)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
EOF

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
CMD wget --spider -q http://127.0.0.1:8080 || exit 1

CMD ["nginx", "-g", "daemon off;"]