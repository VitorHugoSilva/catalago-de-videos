#!/bin/bash

dockerize -template ./.docker/nginx/nginx.tmpl:./.docker/nginx/nginx.conf

rm /etc/nginx/conf.d/default.conf

cp ./nginx.conf /etc/nginx/conf.d
