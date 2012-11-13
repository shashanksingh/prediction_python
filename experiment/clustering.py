from cluster import KMeansClustering
import random
import time
a=[]
print "Input \n"
for i in range(1,1000):
	a.append((i*random.random(),i*random.random()))
print a
print "\nOutput \n"
start_time=time.time()
cl = KMeansClustering(a)
clusters = cl.getclusters(50)
end_time=time.time()
print clusters
print "total time " + str(end_time-start_time) + " secs for "+ str(i) +" element"
