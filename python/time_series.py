#This function get_grid right now has simple function but it can be more sophisticated
from plot import *

def get_grid(xc,yc):
	grid = int(142.857142*(xc-18.80)+20*(yc-72.70))#get all your fancy smazy algos here
	return grid

def normalize_timestamp(timestamp):
	return timestamp

def generate_time_series(x,y,timestamp):
	count = 0
	time_series = []
	for point_x in x:
		point_y = y[count]
		grid_current =  get_grid(point_x,point_y)
		timestamp_current = normalize_timestamp(timestamp[count])
		time_series.append([grid_current,timestamp_current])
		count += 1
	time_series_np = np.asanyarray(time_series)
	print time_series_np
	return time_series_np
