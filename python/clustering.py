import matplotlib
matplotlib.use('Agg')
from scipy.cluster.vq import *
from plot import *


def kmeans(x,y,timestamp):
	# kmeans for 3 clusters
	#print res
	#print idx
 	xy=np.ndarray(shape=(2,2))
	i=0
	point =[]
	for point_x in x:	
		point_y = y[i]
		point.append([point_x,point_y])
		i += 1
	xy = np.asanyarray(point)
	print xy

	res, idx = kmeans2(xy,2)
	colors = ([([0.4,1,0.4],[1,0.4,0.4],[0.1,0.8,1])[i] for i in idx])
 
	# plot colored points
	plt.scatter(xy[:,0],xy[:,1], c=colors)
 
	# mark centroids as (X)
	plt.scatter(res[:,0],res[:,1], marker='o', s = 500, linewidths=2, c='none')
	plt.scatter(res[:,0],res[:,1], marker='x', s = 500, linewidths=2)
	plt.show()
