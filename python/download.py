#!/usr/bin/python
# -*- coding: utf-8 -*-

import _mysql
import sys
from queries import *



con = None

def execute(query):
	try:
    	con = _mysql.connect('localhost', 'root','root', 'blackbox_prod')
    	con.query(query)
    	result = con.use_result()
    	full_result_query=result.fetch_all()
    
	except _mysql.Error, e:
    	return "Error %d: %s" % (e.args[0], e.args[1])

	finally:
    	if con:
        	con.close()

	return full_result_query
