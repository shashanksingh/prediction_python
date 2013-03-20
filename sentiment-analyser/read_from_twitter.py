import urllib
import simplejson
#import datetime


#tweet_type : {recent,popular,mixed}
#until : Returns tweets generated before the given date. Date should be formatted as YYYY-MM-DD. 2010-03-28
def searchTweets(query,page=1,count=30,tweet_type='mixed',rate_per_page=30,since='2013-01-01',until='2013-12-12'):
	tweets = []
	params = urllib.urlencode({'q': query,'count':count,'type':tweet_type,'rpp':rate_per_page,'until':until,'page':page})
	print params
	search = urllib.urlopen("http://search.twitter.com/search.json?%s"%str(params))
	dict = simplejson.loads(search.read())
	for result in dict["results"]: # result is a list of dictionaries
		tweets.append({'text':result["text"],'timestamp':result["created_at"]})
	return tweets


def collated_twitter_data(result_since='', result_until='2013-12-12',result_page=0,search_terms=[]):
	tweets = []
	for terms in (search_terms):
		tweets.extend(searchTweets(query=terms,page=result_page,since=result_since,until=result_until))
	return tweets


