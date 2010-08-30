
APP_PATH="/var/www/mynewproject/"
#POOTLE_LOCALES="/var/www/pootle/po/i18n"
LOCALES="${APP_PATH}i18n"
DOMAIN="mynewproject"

TODAY=$(date +%Y%m%d%H%M%S)

echo -n "Please enter the locale code to get from Pootle server and compile (eg fr_FR or ALL): "
read -e CURRENT_LOCALE

STATUS=1

if test ${CURRENT_LOCALE} = 'ALL'
then

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

#	echo "> Get PO files from pootle"
#	cd $POOTLE_LOCALES
#	for i in *
#	do
#		if [ -f "${POOTLE_LOCALES}/${i}/LC_MESSAGES/${DOMAIN}.po" ] 
#		then
#			echo -ne "\t${POOTLE_LOCALES}/${i}/LC_MESSAGES/${DOMAIN}.po ... "
#			cp "${POOTLE_LOCALES}/${i}/LC_MESSAGES/${DOMAIN}.po" "${LOCALES}/${i}/LC_MESSAGES/${DOMAIN}.po"
#			if [ -f "${POOTLE_LOCALES}/${i}/LC_MESSAGES/${DOMAIN}.po" ] 
#			then
#				echo "DONE."
#			else
#				echo "ERROR."
#				STATUS=0
#			fi
#		fi
#	done

	echo "> Compiling current po files"
	for i in $LOCALES/*; do
		if [ -d "${i}/LC_MESSAGES" ]
		then
			if [ -f "${i}/LC_MESSAGES/${DOMAIN}.po" ]
			then
				echo -en "\t$i/LC_MESSAGES/${DOMAIN}.po ... "
				msgfmt ${i}/LC_MESSAGES/${DOMAIN}.po -o ${i}/LC_MESSAGES/${DOMAIN}.mo
				echo "DONE."
			fi
		fi
	done

else
	if [ -f "${POOTLE_LOCALES}/${CURRENT_LOCALE}/LC_MESSAGES/${DOMAIN}.po" ]
	then
		echo -e "Locale $CURRENT_LOCALE will be used ($POOTLE_LOCALES/LC_MESSAGES/$DOMAIN.po)\n\n"
	
		echo -n "> Archiving current po files for locale $CURRENT_LOCALE ... "
		if [ -f "${LOCALES}/${CURRENT_LOCALE}/LC_MESSAGES/${DOMAIN}.po" ] 
		then
			cp $LOCALES/$CURRENT_LOCALE/LC_MESSAGES/$DOMAIN.po $LOCALES/$CURRENT_LOCALE/LC_MESSAGES/$DOMAIN.po--$TODAY
			if [ -f "${LOCALES}/${CURRENT_LOCALE}/LC_MESSAGES/${DOMAIN}.po--${TODAY}" ] 
			then
				echo "DONE."
				echo "File generated: $LOCALES/$CURRENT_LOCALE/LC_MESSAGES/$DOMAIN.po--$TODAY"
			else
				echo "ERROR."
				STATUS=0
			fi
		else
			echo "NOT EXISTING, SKIP."
		fi
	
		if test ${STATUS} -eq "1" 
		then 
			echo -n "> Get PO files from Pootle ... "
			cp "${POOTLE_LOCALES}/${CURRENT_LOCALE}/LC_MESSAGES/${DOMAIN}.po" "${LOCALES}/${CURRENT_LOCALE}/LC_MESSAGES/${DOMAIN}.po"
			if [ -f "${POOTLE_LOCALES}/${CURRENT_LOCALE}/LC_MESSAGES/${DOMAIN}.po" ] 
			then
				echo "DONE."
			else
				echo "ERROR."
				STATUS=0
			fi
		fi
	
		if test ${STATUS} -eq "1" 
		then 
			echo -n "> Compiling current po files ($LOCALES/$CURRENT_LOCALE/LC_MESSAGES/$DOMAIN.po) ... "
			msgfmt $LOCALES/$CURRENT_LOCALE/LC_MESSAGES/$DOMAIN.po -o $LOCALES/$CURRENT_LOCALE/LC_MESSAGES/$DOMAIN.mo
			echo "DONE."
		fi
	
	else
		echo "ERROR, this locale does not exist in Pootle server ($POOTLE_LOCALES)"
	fi
fi