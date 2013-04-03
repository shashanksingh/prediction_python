import matplotlib
matplotlib.use('Agg')
from scipy.cluster.vq import *
from plot import *
#TODO:cleanup of code to follow the code structure
def kmeans(x,y,timestamp):
	# kmeans for 3 clusters
	number_of_cluster = 30
	number_of_iterations = 15000
	i=0
	point =[]
	for point_x in x:	
		point_y = y[i]
		if point_x < 19.4 and point_y < 73.2 and point_x > 18.8  and point_y > 72.6: #lets remove noise signal
			point.append([point_x,point_y])
		else:
			pass
		i += 1
	xy = np.asanyarray(point)

	res, idx = kmeans2(xy,number_of_cluster,minit="random",iter=5000)
	print "Done Clustering.. Now coloring "
	colors = ([([0.9,1.0,0.4],[1.0,0.4,0.4],[0.9,0.8,1.0],[0.9,1.0,0.1],[0.85,1.0,0.2],[0.99,0.5,0.2],[0.98,0.12,0.2],[0.97,0.13,0.2],[0.96,0.14,0.1],[0.96,0.15,0.2])[i%10]for i in idx])
	
 
	# plot colored points
	#pylab.scatter(x,y,marker="o", c="green", facecolors="white", edgecolors="red")
	plt.scatter(xy[:,0],xy[:,1],edgecolors=colors,facecolors="white")
	print "Lets Scatter Some Colour :) "
	figure = plt.gcf()
	figure.set_size_inches(24, 12) 
 
	# mark centroids as (X)
	plt.scatter(res[:,0],res[:,1], marker='*',facecolors="black", s = 20, linewidths=2, c='none')
	i = 0
	for x, y in zip(res[:, 0], res[:, 1]):
		print "["+str(i)+"] "+str(y)+","+str(x)
		plt.annotate(str(i),xy = (x, y), xytext = (-20, 20),textcoords = 'offset points', ha = 'right', va = 'bottom',bbox = dict(boxstyle = 'round,pad=0.5', fc = 'yellow', alpha = 0.5),arrowprops = dict(arrowstyle = '->', connectionstyle = 'arc3,rad=0'))
		i += 1
	plt.savefig('/tmp/kmeans_banglore_90000.png',dpi=200)
