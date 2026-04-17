FROM mysql:9.6.0 AS base


# create.sql will run before load.sql since it is alphabetically before load.sql
COPY create.sql load.sql /docker-entrypoint-initdb.d/
