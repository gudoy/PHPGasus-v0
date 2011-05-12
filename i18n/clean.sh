#!/bin/sh

DOMAIN="mynewproject"
APP_PATH="/var/www/${DOMAIN}/"
LOCALES="${APP_PATH}i18n"

echo "> Cleaning"
for i in $LOCALES/*; do
	if [ -d "${i}/LC_MESSAGES" ]
	then
		echo -en "$i ... "
		for ii in $i/LC_MESSAGES/*; do
			if [ ! $ii = "${i}/LC_MESSAGES/${DOMAIN}.mo" ] && [ ! $ii = "${i}/LC_MESSAGES/${DOMAIN}.po" ] 
			then
				rm $ii
			fi
		done
		echo "DONE."
	fi
done
echo "DONE."