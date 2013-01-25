from queries import *
from data_backend import *
from clustering import *
from plot_scatter_graph import *
from plot_3d_scatter_graph import *
from plot_heatmap import *
from time_series import *

def run():
	print "Executing..."
	"""INPUT NODE"""
	print "Getting Input data"
	#data=execute(get_all_pickup_drop_lat_lng_timestamp())
	#data=execute(get_all_pickup_drop_lat_lng_timestamp_moved_origin())
	data=execute(get_all_pickup_lat_lng_timestamp(limit=3,service_city="mumbai"))
	#data=read_from_backend("file")

	"""Debug Print"""
	print "Actual Data Being Used=>"+str(len(data))

	"""Saving Node"""
	print "Save"
	#save_to_backend(data,"file")

	"""Convert Data to NUMPY NODE"""
	print "Convert"	
	x,y,timestamp = convert_to_numpy_x_y_timestamp_singular_data(data)#we are passing pickup data only

	"""Clustering"""
	print "Clustering"
	#kmeans(x,y,timestamp)

	"""Generating Time Series"""
	print "Time Series"
	print generate_time_series(x,y,timestamp)
	

	"""PLOT NODE"""
	print "Plot"
	#scatter_plot(x,y,timestamp)
	#heatmap_standard(x,y,timestamp)
	#threeD_scatter_plot(data)


if __name__ == "__main__":
	run()
