#!/bin/bash


table="$(sed -n 's/table_prefix = //p' "./wordpress/wp-config.php")"
table="${table//"'"}"
table="${table//";"}"
table="${table//"$"}"

rm ./wordpress/wp-config.php
cp wp-config.php ./wordpress/wp-config.php

#sed -i 's!/home/users/sprytn01/public_html/fantastycznie.blog/wp-content/uploads!/var/www/html/wp-content/uploads!g' backup.sql

echo $table
