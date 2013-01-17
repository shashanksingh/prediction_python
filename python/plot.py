import numpy as np
import matplotlib.pyplot as plt
from matplotlib.ticker import NullFormatter

_x = []
_y = []
def convert_to_numpy_x_y(data):
	for booking in data:
		if (booking[2] != None and booking[3] != None):
			try:
				lats = booking[2].split(",")
			except ValueError, RuntimeError:
				continue
			try:
				lngs = booking[3].split(",")
			except ValueError , RuntimeError:
				continue
		
			_x.append(lats[0])
	
			if (len(lats) > 1):
				_x.append(lats[1])

			_y.append(lngs[0])
	
			if (len(lngs) > 1):
				_y.append(lngs[1])

	x = np.asarray(_x)
	y = np.asarray(_y)
	print "x=>"+str(x)
	print "\ny=>"+str(y)

		
		
		 
	
