#!/usr/bin/python
# -*- coding: utf-8 -*-

import MySQLdb as mdb
import sys


def execute(query):
	con = None
	try:
		con = mdb.connect('olacabs-dev.in', 'blackbox','blackbox', 'blackbox_prod')
		cur = con.cursor()
		cur.execute(query)
		full_result_query=cur.fetchone()
    
	except mdb.Error, e:
		return "Error %d: %s" % (e.args[0], e.args[1])

	finally:
		if con:
			con.close()
	return full_result_query
