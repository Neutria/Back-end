FROM mariadb:11.7-rc

RUN ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime && echo "Europe/Paris" > /etc/timezone

COPY ./build/database/conf.cnf /etc/mysql/conf.d/.
