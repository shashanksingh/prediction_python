from queries import *
from data_backend import *
from plot_scatter_graph import *
from plot_3d_scatter_graph import *
from plot_heatmap import *

def run():
	print "executing"
	#data=execute(get_all_pickup_drop_lat_lng_timestamp())
	#data=execute(get_all_pickup_drop_lat_lng_timestamp_moved_origin())
	data=execute(get_all_pickup_lat_lng_timestamp_moved_origin(limit=90000))
	print len(data)
	#save_to_backend(data,"file")	
	x,y,timestamp = convert_to_numpy_x_y_timestamp_singular_data(data)#we are passing pickup data only
	#scatter_plot(x,y,timestamp)
	heatmap(x,y,timestamp)
	#threeD_scatter_plot(data)


run()
