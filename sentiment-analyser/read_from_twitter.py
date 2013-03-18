import urllib
import simplejson


#tweet_type : {recent,popular,mixed}
#until : Returns tweets generated before the given date. Date should be formatted as YYYY-MM-DD. 2010-03-28
def searchTweets(query,page=0,count=10,tweet_type='mixed',rate_per_page=30,since='2013-01-01',until='2013-12-12'):
	tweets = []
	params = urllib.urlencode({'q': query,'count':count,'type':tweet_type,'rpp':rate_per_page,'until':until})
	search = urllib.urlopen("http://search.twitter.com/search.json?%s"%str(params))
	dict = simplejson.loads(search.read())
	for result in dict["results"]: # result is a list of dictionaries
		tweets.append(result["text"])
	return tweets


def collated_twitter_data(since='', until='2013-12-12',search_terms=[]):
	tweets = []
	for terms in (search_terms):
		tweets.extend(searchTweets(terms,since,until))
	return tweets


