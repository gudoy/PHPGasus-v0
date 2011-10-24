#!/bin/sh

DOMAIN="mynewproject"
APP_PATH="/var/www/${DOMAIN}/"
LOCALES="${APP_PATH}i18n"
SOURCE="${APP_PATH}templates"
SOURCE_PHP="${APP_PATH}"
#SOURCE_PHP2="${APP_PATH}config"
#SOURCE_JS="${APP_PATH}public/javascripts/config/config.js.php"

TODAY=$(date +%Y%m%d%H%M%S)
STATUS=1

# Parse the smarty .tpl files looking for the gettext {t}label to translate{/t}
./tsmarty2c.php $SOURCE > $SOURCE/gettexts.html

echo "> Archiving current po files"
for i in $LOCALES/*; do
	if [ -d "${i}" ]
	then 
		echo -en "\t$i/LC_MESSAGES/${DOMAIN}.po ... "
		if [ -f "${i}/LC_MESSAGES/${DOMAIN}.po" ] 
		then
			cp $i/LC_MESSAGES/$DOMAIN.po $i/LC_MESSAGES/$DOMAIN.po--$TODAY
			if [ -f "${i}/LC_MESSAGES/${DOMAIN}.po" ] 
			then
				echo "DONE."
			else
				echo "ERROR."
				STATUS=0
			fi
		else
			echo "NOT EXISTING."
		fi
	fi
done

echo "> Updating po files"
for i in $LOCALES/*; do
	if [ -d "${i}/LC_MESSAGES" ]
	then
		echo -en "\t$i/LC_MESSAGES/${DOMAIN}.po ... "
		if [ -f "${i}/LC_MESSAGES/${DOMAIN}.po" ]
		then
			find $SOURCE_PHP -iname \*.php -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s -j --from-code=UTF-8 {} \;
			#find $SOURCE_PHP2/ -iname \*.php -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s -j --from-code=UTF-8 {} \;
			#xgettext  $SOURCE_JS -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s -j --from-code=UTF-8
			#find $SOURCE/ -iname \*.tpl -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s -j --from-code=UTF-8 {} \;
			find $SOURCE/ -iname \*.html -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s -j --from-code=UTF-8 {} \;
			
			find $SOURCE -iregex '.*\.\(php\|tpl\|html\)' -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s -j --from-code=UTF-8 {} \;
			
		else
			find $SOURCE_PHP -iname \*.php -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s --from-code=UTF-8 {} \;
			#find $SOURCE_PHP2/ -iname \*.php -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s --from-code=UTF-8 {} \;
			#xgettext  $SOURCE_JS -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s --from-code=UTF-8
			#find $SOURCE/ -iname \*.tpl -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s --from-code=UTF-8 {} \; 
			find $SOURCE/ -iname \*.html -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s --from-code=UTF-8 {} \;
			
			find $SOURCE -iregex '.*\.\(php\|tpl\|html\)' -exec xgettext -p $i/LC_MESSAGES -L PHP -d ${DOMAIN} --force-po -s --from-code=UTF-8 {} \;
		fi
		echo "DONE."
	fi
done
