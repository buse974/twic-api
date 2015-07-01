#!/bin/bash

X=""

while getopts "p::u::a::hx:" option
do
        case $option in
                p)
                        P=$OPTARG
                ;;
                u)
                        U=$OPTARG
                ;;
                a)
                        URL_API=$OPTARG
                ;;
		h)
			H=true
		;;
		x)
			X="-x " $OPTARG
		;;
		
        esac
done

if [ -z "$P" ] || [ -z "$U" ] || [ -z "$URL_API" ] || [ ! -z "$H" ] ; then
 	echo -e "\n\n-----------------------------------------------------------------------------------";
	echo -e "| uploadZopentok.sh -a <url_api> -u <url_destination> -p <path_upload_file> -h    |";
        echo -e "| -a <url_api> : Url api                                                          |";
        echo -e "| -u <url_destination> : Url destination for download file                        |";
        echo -e "| -p <path_upload_file> : Path write file                                         |";
        echo -e "| -x <proxyhost[:port]> : Proxy                                                   |";
        echo -e "| -h : help                                                                       |";
        echo -e "*---------------------------------------------------------------------------------*\n\n";
exit 1
fi


RES=`curl -s --data-binary '{"jsonrpc": "2.0", "id":1, "method": "videoconf.getListVideoUpload", "params": [] }' $URL_API`
URL=$(echo $RES | jq -c -r '.result|.[]')


for LINE in $URL
do
if [ ! $LINE = "[]" ]; then
        NAME=`uuidgen -t`".mp4"
        PATH_DEST=$P$NAME
        OUT=`curl $X -w '%{http_code}' $(echo $LINE | jq -r '.url') -o $PATH_DEST`
        if [[ $OUT -eq 200 ]]
        then
                RES=`curl -s --data-binary  "{\"jsonrpc\": \"2.0\", \"id\":1, \"method\": \"videoconf.validTransfertVideo\", \"params\": {\"m_videoconf_id\": $(echo $LINE | jq -r '.id'),\"url\":\""$U$NAME"\"  } }" $URL_API`
                echo "Download ok : " $(echo $RES | jq -c -r '.result') $U$NAME
        fi
fi
done

exit 0
