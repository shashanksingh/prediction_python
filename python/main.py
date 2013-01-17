from queries import *
from download import *


def run():
	print "executing"
	data=execute(get_all_pickup_drop_lat_lng_timestamp())
	save_to_backend(data,"file")	


run()
