from queries import *
from download import *
from plot_scatter_graph import *

def run():
	print "executing"
	data=execute(get_all_pickup_drop_lat_lng_timestamp())
	save_to_backend(data,"file")	
	scatter_plot()


run()
