import numpy as np
import matplotlib.pyplot as plt
from matplotlib.ticker import NullFormatter

x = []
y = []

def convert_to_numpy_x_y(data):
	_x = []
	_y = []
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
		
	
			if (len(lats) > 1):
				_x.append(lats[0])
				_x.append(lats[1])
	
			if (len(lngs) > 1):
				_y.append(lngs[0])
				_y.append(lngs[1])

	x = np.asarray(_x)
	y = np.asarray(_y)
	print "x=>"+str(x)+str(len(x))
	print "\ny=>"+str(y)+str(len(y))

