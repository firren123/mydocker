#
# MAINTAINER        licehnjun <zcy@nicescale.com>
# DOCKER-VERSION    1.12.3
#
# Dockerizing php-fpm: Dockerfile for building php-fpm images xdebug
#
#引入镜像名字
From php:5.6-fpm
#制作镜像名称和邮箱
MAINTAINER  firren <firrren@163.com>
#项目目录 变量值
ENV APP_DIR /var/www/html

RUN mkdir -p /var/src
#移动文件到镜像里面 curl -O https://xdebug.org/files/xdebug-2.5.5.tgz
ADD xdebug-2.5.5.tgz /var/src/www

#创建下载目录
RUN cd /var/src/www/xdebug-2.5.5 && /usr/local/bin/phpize && ./configure && make && make install



#创建镜像
#docker build -t firren/php-xdebug:v1  .

#创建容器 -- name 容器名 -v 本地项目地址:docker项目地址 -v 本地php配置地址:docker项目php配置地址
#docker run --name php-xdebug -v ~/www/docker/www:/var/www/html -v ~/www/docker/php:/usr/local/etc/php -p 9000:9000 -d firren/php-xdebug:v1









