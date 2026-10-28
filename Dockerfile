FROM php:8.3-cli

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    unixodbc-dev \
    curl \
    gnupg2 \
    apt-transport-https \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Driver ODBC para SQL Server
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
    && rm -rf /var/lib/apt/lists/*

# Extensões PHP
RUN docker-php-ext-install pcntl pdo_mysql pdo_pgsql

# pdo_sqlsrv via PECL
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

WORKDIR /app

COPY . .

CMD [ "php", "index.php" ]

# docker run --rm --network server_default williamyeh/wrk -t4 -c400 -d15s http://server-server-1:8080/
