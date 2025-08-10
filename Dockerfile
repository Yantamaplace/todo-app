FROM php:8.2-apache

# Apache を 8080 に変更
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf && \
    sed -i 's/:80>/:8080>/g' /etc/apache2/sites-enabled/000-default.conf

# PHP拡張（mysqli）をインストール
RUN docker-php-ext-install mysqli

# index.php を配置
COPY index.php /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Apache起動
EXPOSE 8080
CMD ["apache2-foreground"]
