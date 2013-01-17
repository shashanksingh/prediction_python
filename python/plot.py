import numpy as np
import matplotlib.pyplot as plt
from matplotlib.ticker import NullFormatter
from mpl_toolkits.mplot3d import Axes3D

x = []
y = []
#x = np.random.randn(2)
#y = np.random.randn(2)
#print "x=>"+str(x)+str(len(x))
#print "\ny=>"+str(y)+str(len(y))


def convert_to_numpy_x_y(data):
	_x = []#internal use for x axis
	_y = []#intrnal use
	_timestamp = []
	for booking in data:
		if (booking[2] != None and booking[3] != None):
			try:
				lats = booking[2].split(",")
			except ValueError, RuntimeError:
				lat = ['0.00','0.00']
			try:
				lngs = booking[3].split(",")
			except ValueError , RuntimeError:
				lngs = ['0.00','0.00']
			try:
				time = booking[4]
			except ValueError , RuntimeError:
				timestamp = 0
		
	
			if (len(lats) > 1):
				_x.append(float(lats[0]))
				_x.append(float(lats[1]))
	
			if (len(lngs) > 1):
				_y.append(float(lngs[0]))
				_y.append(float(lngs[1]))

			if (len(time)>0):
				_timestamp.append(time)

	x = np.array(_x)
	y = np.array(_y)
	timestamp = np.array(_timestamp)
	return x,y,timestamp

