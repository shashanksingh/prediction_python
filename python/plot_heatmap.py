from plot import *

# Generate some test data  
#x = np.random.randn(88730)  
#y = np.random.randn(88730)  
def heatmap_standard(x,y,timestamp):
	heatmap , xedges, yedges = np.histogram2d(x,y,bins=100);
	plt.clf()
	plt.imshow(heatmap)
	plt.show()
	
def heatmap_standard_with_extent(x,y,timestamp):
	heatmap, xedges, yedges = np.histogram2d(x, y, bins=1)  
	extent = [xedges[0], xedges[-1], yedges[0], yedges[-1]] 
	plt.clf()  
	plt.imshow(heatmap, extent=extent)
	plt.show() 

def heatmap_animated_antialised(x,y,timestamp):
	"""
	The method shown here is only for very simple, low-performance
	use.  For more demanding applications, look at the animation
	module and the examples that use it.
	"""
	#x = np.arange(6)
	#y = np.arange(5)
	z = timestamp
	for i in xrange(5):
		if i==0:
			p = plt.imshow(z)
			fig = plt.gcf()
			plt.clim()   # clamp the color limits
			plt.title("Boring slide show")
		else:
			z = z + 2
			p.set_data(z)

		print("step", i)
		plt.pause(0.5)

def heatmap_with_hexagon_cell(x,y,timestamp):
	from matplotlib import cm 
	from matplotlib import mlab as ml
	n = 1e5
	#x = y = NP.linspace(-5, 5, 100)
	X, Y = np.meshgrid(x, y)
	Z1 = ml.bivariate_normal(X, Y, 2, 2, 0, 0)
	Z2 = ml.bivariate_normal(X, Y, 4, 1, 1, 1)
	ZD = Z2 - Z1
	x = X.ravel()
	y = Y.ravel()
	z = ZD.ravel()
	gridsize=300
	plt.subplot(111)

	# if 'bins=None', then color of each hexagon corresponds directly to its count
	# 'C' is optional--it maps values to x-y coordinates; if 'C' is None (default) then 
	# the result is a pure 2D histogram 

	plt.hexbin(x, y, C=z, gridsize=gridsize, cmap=cm.jet, bins=None)
	plt.axis([x.min(), x.max(), y.min(), y.max()])
        plt.text(18.9300,72.8200,"ChurchGate",bbox=dict(facecolor='green', alpha=0.5))
        plt.text(19.1833,72.8333,"Malad",bbox=dict(facecolor='green', alpha=0.5))
        plt.text(19.0587,72.8997,"Chembur",bbox=dict(facecolor='green', alpha=0.5))
        plt.text(19.2045,72.8376,"Kandivili",bbox=dict(facecolor='green', alpha=0.5))

	cb = plt.colorbar()
	cb.set_label('mean value')
	plt.show() 
