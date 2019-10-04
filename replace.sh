find . -name '*school*' | while read FILE ; do
    newfile="$(echo ${FILE} |sed -e 's/school/college/g')" ;
    mv "${FILE}" "${newfile}" ;
done
