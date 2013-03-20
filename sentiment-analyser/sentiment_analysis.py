from read_from_twitter import collated_twitter_data
from pattern.en import sentiment,positive


for page in range (1,1):
	tweets = collated_twitter_data(search_terms=["#olacabs","@olacabs","#ola cabs"],result_page=page)
	for tweet in tweets:
		result = sentiment(tweet["text"])
		print tweet["timestamp"],"=>",result
