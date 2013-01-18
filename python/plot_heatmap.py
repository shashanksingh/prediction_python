from plot import *

# Generate some test data  
#x = np.random.randn(88730)  
#y = np.random.randn(88730)  
def heatmap(x,y,timestamp):
	heatmap, xedges, yedges = np.histogram2d(x, y, bins=50)  
	extent = [xedges[0], xedges[-1], yedges[0], yedges[-1]]  
	plt.clf()  
	plt.imshow(heatmap, extent=extent)  
	plt.show() 
