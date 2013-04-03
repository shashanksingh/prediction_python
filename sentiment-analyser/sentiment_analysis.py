from read_from_twitter import collated_twitter_data,get_all_olacabs_tweet
from pattern.en import sentiment,positive
import pprint
pp = pprint.PrettyPrinter(indent=4)
#init

date_array = dict()
date_keys_others = [] #stores all dates data was present for other tweetting about us
date_keys_olacabs = [] #stors all the dates data was present for our tweeting

#for i in range(1,31):
#	date_array[str(i)+"_posititve"] = 0.0
#	date_array[str(i)+"_negative"] = 0.0
#	date_array[str(i)+"_count"] = 0
#	date_array[str(i)+"_count_of_olacabs"] = 0
#	date_array[i]["negative"] = 0.0

#csv priting
#print "Day of This Month ,Sentiment Positive Value, Sentiment Negative Value,Positive(True Or False)"
for page in range (1,5):
	tweets_from_others = collated_twitter_data(search_terms=["#olacabs","@olacabs","#ola cabs"],result_page=page,count_of_tweets=100)
	for tweet in tweets_from_others:
		result = sentiment(tweet["text"])
		date_of_data = tweet["timestamp"]
		#print tweet["timestamp"],",",result[0] , ",", result[1],",", positive(tweet["text"],0.1)
		date_array[str(date_of_data)+"_posititve"] = date_array.get(str(date_of_data)+"_posititve",0) + result[0]
		date_array[str(date_of_data)+"_negative"] = date_array.get(str(date_of_data)+"_negative",0) + result[1]
		date_array[str(date_of_data)+"_count"] = date_array.get(str(date_of_data)+"_count",0)+ 1
		date_keys_others.append(str(date_of_data))

for page in range (1,10):
	all_tweets_from_olacabs = get_all_olacabs_tweet(page,count=100)
	for tweet in all_tweets_from_olacabs:
		date_of_data = tweet["timestamp"]
		date_array[str(date_of_data)+"_count_of_olacabs"] = date_array.get((str(date_of_data)+"_count_of_olacabs"),0) + 1
		date_keys_olacabs.append(str(date_of_data))

#pp.pprint(date_array)
date_keys_others = sorted(list(set(date_keys_others))) #remove duplicate
date_keys_olacabs = sorted(list(set(date_keys_olacabs))) #remove duplicates

#pp.pprint(date_keys_olacabs)
#for i in range(13,21):
for i in date_keys_others:
	if (date_array[str(i)+"_count"] != 0) :
        	date_array[str(i)+"_posititve"] /= date_array[str(i)+"_count"]
        	date_array[str(i)+"_negative"] /= date_array[str(i)+"_count"]
		pass

positive_sentiment = []
negative_sentiment = []
count = []
total_tweets = []

#total_tweet = [20:"19",19:"9" ,18:"1" , 17:"3" , 16:"5" , 15:"9" , 14:"6" , 13:"4"]
#total_tweets = [19,9 ,1 , 3 , 5 , 9 , 6 , 4]
for i in date_keys_others:
	positive_sentiment.append(date_array[str(i)+"_posititve"]) 
	negative_sentiment.append(date_array[str(i)+"_negative"]) 
	count.append(date_array[str(i)+"_count"])
for i in date_keys_olacabs:
	total_tweets.append(date_array[str(i)+"_count_of_olacabs"])

#print total_tweets

#pp.pprint(date_array)

#plotting
from pylab import *

#print positive
#print t
#print negative_sentiment

print date_keys_others
print positive_sentiment
print negative_sentiment
figure(num=None, figsize=(14, 6), dpi=80, facecolor='w', edgecolor='k')
subplot(211)
positive_sentiment_plot,  =  plot(date_keys_others, positive_sentiment, 'b-o',linewidth = 1.0)
negative_sentiment_plot, =  plot(date_keys_others, negative_sentiment, 'r-o',linewidth=1.0)
legend([positive_sentiment_plot,negative_sentiment_plot],["+ive","-ive"])
xlabel('Day of this month[March]')
ylabel('Sentiments #Olacabs')
title('Sentiment Analysis for 7 day')
grid(True)

subplot(212)
count_tweets_plot, = plot(date_keys_others, count,'g-o',linewidth = 1.0)
#legend([count_tweets_plot],["#olacabs"])
#label = "Economist Tweet"
#plt.annotate(label,xy = (14,10), xytext = (40, 40),textcoords = 'offset points', ha = 'right', va = 'bottom',bbox = dict(boxstyle = 'round,pad=0.5', fc = 'yellow', alpha = 0.5),arrowprops = dict(arrowstyle = '->', connectionstyle = 'arc3,rad=0'))
#label = "Ipad Mini Contest"
#plt.annotate(label,xy = (18,10), xytext = (-20, 20),textcoords = 'offset points', ha = 'right', va = 'bottom',bbox = dict(boxstyle = 'round,pad=0.5', fc = 'yellow', alpha = 0.5),arrowprops = dict(arrowstyle = '->', connectionstyle = 'arc3,rad=0'))
xlabel('Day of this month[March]')
ylabel('Count of tweets ')
grid(True)

#legend([total_tweets_plot],["@Olacabs"])
#xlabel('Day of this month[March]')
#ylabel('Count of tweets ')
#grid(True)

savefig("test.png")
show()

		
