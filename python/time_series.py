#This function get_grid right now has simple function but it can be more sophisticated
from plot import *
def test():
	""" A function to test the whole algo"""
	i = 0
	for xc in range(188000,195000):
		for yc in range(727000,732000):
			xcm = xc/10000.00
			ycm =  yc/10000.00
			grid = int(142.857142*(xcm-18.80)+20*(ycm-72.70))
			print "["+str(i)+"]"+"xc=>"+str(xc)+" yc=>"+str(yc)+" grid=>"+str(grid)
			i += 1

def get_grid(xc,yc):
	grid = int(142.857142*(xc-18.80)+20*(yc-72.70))#get all your fancy smazy algos here
	return grid

def generate_time_series(x,y,timestamp):
	count = 0
	time_series = []
	for point_x in x:
		point_y = y[count]
		grid =  get_grid(point_x,point_y)
		print point_x, point_y, grid
		time_series.append([grid,timestamp[count]])
		count += 1
	time_series_np = np.asanyarray(time_series)
	print time_series_np
	return time_series_np
