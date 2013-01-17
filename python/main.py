from queries import *
from data_backend import *
from plot_scatter_graph import *

def run():
	print "executing"
	#data=execute(get_all_pickup_drop_lat_lng_timestamp())
	data=execute(get_all_pickup_drop_lat_lng_timestamp_moved_origin())
	len(data)
	#save_to_backend(data,"file")	
	scatter_plot(data)


run()
