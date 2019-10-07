# clickhouse-server-statisitc
ClickHouse administrative, statistic viewer and RESTful API

## Installation & Run

1. `git clone https://github.com/zikwall/clickhouse-server-statistic`
2. `cd clickhouse-server-statistic`
3. `composer install`

### Nginx

```bash
server {
    listen 80;
    server_name clickhouse-server-statistic.local;

    access_log /var/log/nginx/access_clickhouse-server-statistic.log;
    root /path/to/clickhouse-server-statistic/web;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php7.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 600;
    }
}
```