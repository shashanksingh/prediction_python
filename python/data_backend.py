#!/usr/bin/python
# -*- coding: utf-8 -*-

import MySQLdb as mdb
from file_lib import *
from redis_lib import *

file_lib_obj = file_lib()
redis_lib_obj = redis_lib()

mysql_pass = "wheninfrancespeakfrenchanddrinkchampaign"
mysql_user = "reader"
mysql_host = "103.8.124.41"
def execute(query):
	con = None
	try:
		con = mdb.connect(mysql_host, mysql_user, mysql_pass, 'blackbox_prod')
		cur = con.cursor()
		cur.execute(query)
		full_result_query=cur.fetchall()
    
	except mdb.Error, e:
		import sys
		sys.exit("Error %d: %s" % (e.args[0], e.args[1]))

	finally:
		if con:
			con.close()
	return full_result_query

def save_to_backend(data,backend="file"):
	if (backend == "file"):
		return file_lib_obj.save(data)
	elif (backend == "redis"):#todo
		return redis_lib_obj.save(data)
	else: 
		return "not valid backend"

def read_from_backend(backend="file"):
	if (backend == "file"):
		return file_lib_obj.read()
	elif (backend == "redis"):#todo
		return redis_lib_obj.read()
	else:
		return "not valid backend"
 
