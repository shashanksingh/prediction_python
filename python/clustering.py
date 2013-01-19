import matplotlib
matplotlib.use('Agg')
from scipy.cluster.vq import *
from plot import *
import pylab
pylab.close()

def kmeans(x,y,timestamp):
	# kmeans for 3 clusters
	#print res
	#print idx
 	xy=np.ndarray(shape=(2,2))
	number_of_cluster = 30
	i=0
	point =[]
	for point_x in x:	
		point_y = y[i]
		point.append([point_x,point_y])
		i += 1
	xy = np.asanyarray(point)

	res, idx = kmeans2(xy,number_of_cluster,minit="points",iter=1000)
	colors = ([([0.9,1,0.4],[1,0.4,0.4],[0.9,0.8,1])[i%3]for i in idx])
	#colors =[(1.0/float(i+1.0),1.0/float(i+1.0),0.0) for i in range(number_of_cluster)]
 
	# plot colored points
	#pylab.scatter(x,y,marker="o", c="green", facecolors="white", edgecolors="red")
	pylab.scatter(x,y,edgecolors=colors,facecolors="white")
 
	# mark centroids as (X)
	pylab.scatter(res[:,0],res[:,1], marker='*',facecolors="red", s = 50, linewidths=2, c='none')
	#pylab.scatter(res[:,0],res[:,1], marker='*', s = 50, linewidths=2)
	pylab.savefig('/tmp/kmeans_70000.png')
