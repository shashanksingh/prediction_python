from queries import *
from download import *


def run():
	print "executing"
	data=execute(get_all_pickup_drop_lat_lng_timestamp())
	print data


run()
