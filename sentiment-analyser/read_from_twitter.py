import urllib
import simplejson
from config import SEARCH_OLA_TERMS


#tweet_type : {recent,popular,mixed}
#until : Returns tweets generated before the given date. Date should be formatted as YYYY-MM-DD. 2010-03-28
def searchTweets(query,page=0,count=10,tweet_type='mixed',rate_per_page=30):
 params = urllib.urlencode({'q': query,'count':count,'type':tweet_type,'rpp':rate_per_page,'until':'2013-03-28'})
 search = urllib.urlopen("http://search.twitter.com/search.json?%s"%str(params))
 dict = simplejson.loads(search.read())
 for result in dict["results"]: # result is a list of dictionaries
  print "*",result["created_at"],"=>",result["text"],"\n"

for terms in (SEARCH_OLA_TERMS):
	searchTweets(terms)
