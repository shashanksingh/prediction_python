from cluster import KMeansClustering
import time
import pprint
import csv
pp = pprint.PrettyPrinter(indent=8)

#The Size of clusters and the sample elements
NUMBER_OF_CLUSTERS = 5
#INPUT_SPACE = [(0.6214580034229178, 0.8576297985599433), (0.49796736015811427, 0.6037587064719865), (0.5411333614806241, 2.3547226853805583), (2.742609395476848, 3.3147934321268973), (4.119467621575695, 3.034443676613285), (5.580029939038178, 1.3017120385968812), (3.1145887165422135, 3.1319979742220907), (6.332899705413154, 6.55481182508934), (8.890971223692338, 0.2716458700068186)]

INPUT_SPACE = []
def read_csv_into_array():
	input_file=open("test.csv")
	reader=csv.reader(input_file)
	for row in reader:
		element=(float(row[0]),float(row[1]))
		INPUT_SPACE.append(element);
			


read_csv_into_array()
print INPUT_SPACE
print "Starting Clustering.."
start_time=time.time()
cl = KMeansClustering(INPUT_SPACE)
clusters = cl.getclusters(NUMBER_OF_CLUSTERS)
end_time=time.time()
print "Clustering Done.."
pp.pprint(clusters)
print "total time " + str(end_time-start_time) + " secs for "+ str(len(INPUT_SPACE)) +" element"
