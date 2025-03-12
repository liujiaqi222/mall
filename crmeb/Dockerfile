FROM phpdockerio/php:7.4-fpm AS crmeb_php
WORKDIR "/var/www"

# 扩展依赖
RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php7.4-bcmath \ 
        php7.4-redis \
        php7.4-mysqli \
        php7.4-gd
RUN apt-get clean 
RUN apt-get autoremove   
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
RUN echo "open_basedir = /tmp:/var" > /etc/php/7.4/fpm/conf.d/open_basedir.ini

# 

# 设定工作目录
WORKDIR /app

# 将当前目录下所有文件拷贝到/app （.dockerignore中文件除外）
COPY . /app

# 替换nginx、fpm、php配置
RUN cp /app/conf/nginx.conf /etc/nginx/conf.d/default.conf \
    && cp /app/conf/fpm.conf /etc/php7/php-fpm.d/www.conf \
    && cp /app/conf/php.ini /etc/php7/php.ini \
    && mkdir -p /run/nginx \
    && chmod -R 777 /app/runtime \
    && mv /usr/sbin/php-fpm7 /usr/sbin/php-fpm

# 暴露端口
# 此处端口必须与「服务设置」-「流水线」以及「手动上传代码包」部署时填写的端口一致，否则会部署失败。
EXPOSE 80

CMD ["sh", "phprun.sh"]