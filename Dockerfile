FROM php:8.2-apache

# 复制源码到容器
COPY . /var/www/html/

# 确保数据目录权限正确
RUN mkdir -p /var/www/html/data && chown -R www-data:www-data /var/www/html/data

EXPOSE 80

CMD ["apache2-foreground"]