#!/bin/bash
k=0
for i in `cat $1 |grep hlName | cut -f2 -d\> | cut -f1 -d\< | sed -e 's/^[ \t]*//' |sed 's/ /¤/g'`; do

clubname[$k]=`echo "$i"`

k=$[k+1]

done

k=0
for i in `cat clubs.htm |grep hlClub | cut -f4 -d\= | cut -f1 -d\"`; do

clubid[$k]=$i
k=$[k+1]


done

i=0

size=`echo ${#clubid[@]}`

rm Clubs.txt

while [[ $i -lt $size ]]; do

echo -n "${clubname[$i]}:"| sed 's/¤/ /g'  >> Clubs.txt
echo ${clubid[$i]} >> Clubs.txt

i=$[i+1]

done

