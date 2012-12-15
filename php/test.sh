#!/bin/bash


for i in `seq 1 10000`
do
	statuscode=`curl http://alerts.olacabs-dev.in/link/crn_to_shortlink/${i} |grep 'HTTP' | awk '{print $2}' `
	echo "curl http://alerts.olacabs-dev.in/link/crn_to_shortlink/${i}"
	echo statuscode
done
