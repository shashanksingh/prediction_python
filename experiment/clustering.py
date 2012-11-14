from cluster import KMeansClustering
import random
import time
sample_space= [11,21,31,41,51,61,71,81,91,101,201,301,401,501,601,701,801,901,1001,2001,3001,4001,5001,6001,7001,8001,9001,10001]
#print "Input \n"
for key,value in enumerate(sample_space):
	#print "For value " + str(value) + "=>"
	a=[]
	for i in range(1,value):
		a.append((i*random.random(),i*random.random()))
		#print a
		#print "\nOutput \n"
	start_time=time.time()
	cl = KMeansClustering(a)
	clusters = cl.getclusters(3)
	end_time=time.time()
	#print clusters
	print "total time " + str(end_time-start_time) + " secs for "+ str(i) +" element"
