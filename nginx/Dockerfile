#
# MAINTAINER        licehnjun <zcy@nicescale.com>
# DOCKER-VERSION    1.12.3
#
# Dockerizing nginx: Dockerfile for building php-fpm images xdebug
#
#引入镜像名字
From nginx:1.13
#制作镜像名称和邮箱
MAINTAINER  firren <firrren@163.com>
#项目目录 变量值
ENV APP_DIR /var/www/html


#创建镜像
#docker build -t firren/nginx:v1  .

#创建容器 -- name 容器名 -v 本地项目地址:docker项目地址 -v 本地php配置地址:docker项目php配置地址
#docker run --name nginx --link php-xdebug:php -v ~/www/docker/www:/var/www/html -v ~/www/docker/nginx/logs:/var/log/nginx -v ~/www/docker/nginx/conf:/etc/nginx/conf.d -p 80:80 -d nginx:1.13









