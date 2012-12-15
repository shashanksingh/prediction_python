from cluster import KMeansClustering
import time
import pprint
import csv
from pylab import plot,show

pp = pprint.PrettyPrinter(indent=8)

#The Size of clusters and the sample elements
NUMBER_OF_CLUSTERS = 5
INPUT_SPACE = []
OUTPUT_SPACE = []

def read_csv_into_array(file_name):
	input_file=open(file_name)
	reader=csv.reader(input_file)
	for row in reader:
		element=(float(row[0]),float(row[1]))
		INPUT_SPACE.append(element);
			

def make_cluster():
	print "Starting Clustering.."
	start_time=time.time()
	cl = KMeansClustering(INPUT_SPACE)
	clusters = cl.getclusters(NUMBER_OF_CLUSTERS)
	end_time=time.time()
	print "Clustering Done.."
	pp.pprint(clusters)
	print "total time " + str(end_time-start_time) + " secs for "+ str(len(INPUT_SPACE)) +" element"


def plot_output_space():
	pass

read_csv_into_array("test.csv")
make_cluster()
