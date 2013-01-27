#This function get_grid right now has simple function but it can be more sophisticated
from plot import *

def get_grid(xc,yc):
	grid = int(142.857142*(xc-18.80)+20*(yc-72.70))#get all your fancy smazy algos here
	return grid

def normalize_timestamp(timestamp):
	#print timestamp, type(timestamp)	
	#if type(timestamp) == int:
	#if True:
	timestamp = timestamp.partition(',')[0]
	timestamp = (int(timestamp) - int(1343579331))
	return timestamp
	#else:
	#	return False

def generate_time_series(x,y,timestamp):
	count = 0
	time_series = []
	grid = []
	for point_x in x:
		point_y = y[count]
		timestamp_current = normalize_timestamp(timestamp[count])
		#if timestamp_current != False:
		grid_current =  get_grid(point_x,point_y)
		time_series.append(timestamp_current)
		grid.append(grid_current)
		count += 1
	#time_series_np = np.asanyarray(time_series)
	return grid, time_series
