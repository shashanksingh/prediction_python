#This function get_grid right now has simple function but it can be more sophisticated
from plot import *

def normalize_x_y(point_x,point_y):
	if point_x < 19.4 and point_y < 73.2 and point_x > 18.8  and point_y > 72.6:
		return point_x,point_y
	else:
		return 18.8,72.6	

def get_grid(xc,yc):
	grid = int(142.857142*(xc-18.80)+20*(yc-72.70))#get all your fancy smazy algos here
	if grid<0:
		grid = 0
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
		point_x,point_y=normalize_x_y(point_x,point_y)
		timestamp_current = normalize_timestamp(timestamp[count])
		#if timestamp_current != False:
		grid_current =  get_grid(point_x,point_y)
		time_series.append(timestamp_current)
		grid.append(grid_current)
		print timestamp_current, point_y+30.00 , point_x+51.50, "\"Event A\""
		#print timestamp_current , grid_current 
		count += 1
	#time_series_np = np.asanyarray(time_series)
	return grid, time_series
