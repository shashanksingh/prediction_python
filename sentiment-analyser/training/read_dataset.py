import csv
#debate time, tweet ,author,nickname rating [1 = negative, 2 = positive, 3 = mixed, 4 = other],
#debate-->936736034       9/27/08 3:30b sn#debate08 rt @blogdiva NPR is saying their audience gave the debate to Obama.	Populista	Populista	1	3	 2
#github postive/negative , tweet 
#github-->"positive","the rock is destined to be the 21st century's new "" conan "" and that he's going to make a splash even greater than arnold schwarzenegger , jean-claud van damme or steven segal ."

def debate08_sentiment_tweets():
	print "NOT IMPLEMENTED::debate08_sentiment_tweets"

def github_full_training_dataset():
	print "EXECUTING::github_full_training_dataset"
	dataset = []
	with open('rawdata/github_full_training_dataset.csv', 'rb') as csvfile:
		data_reader = csv.reader(csvfile, delimiter=',', quotechar='"')
		for row in data_reader:
			sentiment = row[0]
			tweet = row[1]
			result = (tweet,sentiment)
			dataset.append(result)
	return dataset


# list of function acting as source for training csv's
data_source_func = [debate08_sentiment_tweets , github_full_training_dataset]

def main():
	result = []
	for data_source in data_source_func:
		result.append(data_source())
	print result[1]

main()
