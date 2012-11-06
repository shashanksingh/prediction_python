#!/usr/bin/python
# -*- coding: utf-8 -*-

import _mysql
import sys
from queries import *
from phpserialize import *



con = None

try:

    con = _mysql.connect('localhost', 'root', 
        'root', 'blackbox_prod')
        
    con.query(get_all_p2p_metadata())
    result = con.use_result()
    
    a=result.fetch_all()
    print a[0][0]

    loads(a[0][0])	
    
except _mysql.Error, e:
  
    print "Error %d: %s" % (e.args[0], e.args[1])
    sys.exit(1)

finally:
    
    if con:
        con.close()
