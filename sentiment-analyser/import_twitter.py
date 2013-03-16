import urllib
import simplejson
from config import SEARCH_OLA_TERMS


def searchTweets(query,page=0):
 params = urllib.urlencode({'q': query,'count':10,'type':'mixed','rpp':30})
 search = urllib.urlopen("http://search.twitter.com/search.json?%s"%str(params))
 dict = simplejson.loads(search.read())
 for result in dict["results"]: # result is a list of dictionaries
  print "*",result["created_at"],"=>",result["text"],"\n"

for index in range(len(SEARCH_OLA_TERMS)):
	searchTweets(SEARCH_OLA_TERMS[index])
