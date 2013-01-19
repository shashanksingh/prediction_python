from plot import *

# Generate some test data  
#x = np.random.randn(88730)  
#y = np.random.randn(88730)  
def heatmap_one(x,y,timestamp):
	heatmap, xedges, yedges = np.histogram2d(x, y, bins=50)  
	extent = [xedges[0], xedges[-1], yedges[0], yedges[-1]] 
	plt.clf()  
	plt.imshow(heatmap, extent=extent)
	plt.show() 

def heatmap_two(x,y,timestamp):
	"""
	The method shown here is only for very simple, low-performance
	use.  For more demanding applications, look at the animation
	module and the examples that use it.
	"""
	x = np.arange(6)
	y = np.arange(5)
	z = x * y[:,np.newaxis]

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
