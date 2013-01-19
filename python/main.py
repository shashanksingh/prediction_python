from queries import *
from data_backend import *
from plot_scatter_graph import *
from plot_3d_scatter_graph import *
from plot_heatmap import *

def run():
	print "Executing..."
	"""INPUT NODE"""
	print "Getting Input data"
	#data=execute(get_all_pickup_drop_lat_lng_timestamp())
	#data=execute(get_all_pickup_drop_lat_lng_timestamp_moved_origin())
	data=execute(get_all_pickup_lat_lng_timestamp(limit=9))
	#data=read_from_backend("file")

	"""Debug Print"""
	print len(data)

	"""Saving Node"""
	print "Save"
	#save_to_backend(data,"file")

	"""Convert Data to NUMPY NODE"""
	print "Convert"	
	x,y,timestamp = convert_to_numpy_x_y_timestamp_singular_data(data)#we are passing pickup data only

	"""PLOT NODE"""
	print "Plot"
	#scatter_plot(x,y,timestamp)
	heatmap_two(x,y,timestamp)
	#threeD_scatter_plot(data)


if __name__ == "__main__":
	run()
