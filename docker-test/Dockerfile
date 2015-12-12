FROM ubuntu:14.04

# Ensure UTF-8
RUN locale-gen en_US.UTF-8
ENV LANG       en_US.UTF-8
ENV LC_ALL     en_US.UTF-8


ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update
RUN apt-get install -y --force-yes \
	php5-cli php5-fpm php5-mysql php5-pgsql php5-sqlite php5-curl \
	php5-gd php5-mcrypt php5-intl php5-imap php5-tidy php5-memcache \
	nginx \
	memcached \
	supervisor

RUN sed -i "s/;date.timezone =.*/date.timezone = UTC/" /etc/php5/fpm/php.ini
RUN sed -i "s/;date.timezone =.*/date.timezone = UTC/" /etc/php5/cli/php.ini

RUN mkdir -p /var/log/supervisor
RUN mkdir -p /var/www

ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN echo "daemon off;" >> /etc/nginx/nginx.conf
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php5/fpm/php-fpm.conf
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php5/fpm/php.ini
 
ADD nginx-site   /etc/nginx/sites-available/default


EXPOSE 80

CMD ["/usr/bin/supervisord"]

