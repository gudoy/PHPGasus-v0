#!/bin/bash
DB_NAME=mynewproject
DB_USER=adm-mynewproject
DB_PASS=F4K3paSSw0rD
mysqldump --user=$DB_USER --password=$DB_PASS $DB_NAME -q --single-transaction -R -t --disable-keys --complete-insert | gzip > $(date +%F-%T)_$DB_NAME.sql.gz