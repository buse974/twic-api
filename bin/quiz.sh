#!/bin/bash

while getopts "p::u::a::hx:" option
do
        case $option in
                a)
                        URL_API=$OPTARG
                ;;
		h)
			H=true
		;;
        esac
done

if [ -z "$URL_API" ] || [ ! -z "$H" ] ; then
 	echo -e "\n\n-----------------------------------------------------------------------------------";
	echo -e "| quiz.sh -a -h                                                         |";
        echo -e "| -a <url_api> : Url api                                                          |";
        echo -e "| -h : help                                                                       |";
        echo -e "*---------------------------------------------------------------------------------*\n\n";
exit 1
fi

curl --data-binary '{"jsonrpc": "2.0", "id":1, "method": "subquiz.checkGrade", "params": [] }' $URL_API
