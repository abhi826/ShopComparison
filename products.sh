#!/bin/bash
file1=$1
file2=$2
iter=1
if [[ ! -f tagsoup-1.2.1.jar ]]; then
	wget "https://repo1.maven.org/maven2/org/ccil/cowan/tagsoup/tagsoup/1.2.1/tagsoup-1.2.1.jar"
fi

while :
do
	counter=1
	while read -r url
	do
		wget -E -U "Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.27 Safari/" "$url"
		for file in *.html
		do
			
			mv "$file" "$counter.html"
			name="$counter"
			counter=$((counter+1))
			java -jar tagsoup-1.2.1.jar --files "$name.html"
			rm "$name.html"
			python3 parser.py "$name.xhtml" $iter
			rm "$name.xhtml"
			
		done

	done < "$file1"


	while read -r url
	do 
		wget -E -U "Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.27 Safari/" "$url"
		for file in *.html
		do
			mv "$file" "$counter.html"
			name="$counter"
			counter=$((counter+1))
			java -jar tagsoup-1.2.1.jar --files "$name.html"
			rm "$name.html"
			python3 parser.py "$name.xhtml" $iter
			rm "$name.xhtml"

		done

	done < "$file2"
	iter=$((iter+1))
	sleep 6h

done
