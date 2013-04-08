import urllib
import simplejson
import time

from twython import Twython

TWITTER_APP_KEY = 'XXXXXX' #supply the appropriate value
TWITTER_APP_KEY_SECRET = 'XXXXXXXXX' 
TWITTER_ACCESS_TOKEN = 'XXXXXXXXXXXXX'
TWITTER_ACCESS_TOKEN_SECRET = 'XXXXXXXXXXXX'

twitter = Twython(app_key=TWITTER_APP_KEY, 
            app_secret=TWITTER_APP_KEY_SECRET, 
            oauth_token=TWITTER_ACCESS_TOKEN, 
            oauth_token_secret=TWITTER_ACCESS_TOKEN_SECRET)


#tweet_type : {recent,popular,mixed}
#until : Returns tweets generated before the given date. Date should be formatted as YYYY-MM-DD. 2010-03-28
def searchTweets(query,page=1,count=30,tweet_type='recent',rate_per_page=100,since='2013-01-01',until='2013-12-12'):
	tweets = []
	params = urllib.urlencode({'q': query,'count':count,'type':tweet_type,'rpp':rate_per_page,'until':until,'page':page})
	search = urllib.urlopen("http://search.twitter.com/search.json?%s"%str(params))
	dict = simplejson.loads(search.read())
	for result in dict["results"]: # result is a list of dictionaries
		timestamp = time.strftime('%y%m%d', time.strptime(result["created_at"],'%a, %d %b %Y %H:%M:%S +0000'))
		tweets.append({'text':result["text"],'timestamp':timestamp})
	return tweets


def collated_twitter_data(result_since='', result_until='2013-12-12',result_page=0,search_terms=[],count_of_tweets=30):
	tweets = []
	for terms in (search_terms):
		tweets.extend(searchTweets(query=terms,page=result_page,since=result_since,until=result_until,count=count_of_tweets))
	return tweets

def get_all_olacabs_tweet(page=1,count=30):
	tweets = []
	user_timeline = twitter.getUserTimeline(screen_name="olacabs",page=5,count=100)
	for tweet in user_timeline:
		timestamp = time.strftime('%y%m%d', time.strptime(tweet["created_at"],'%a %b %d %H:%M:%S +0000 %Y'))
		tweets.append({'text':tweet["text"],'timestamp':timestamp})
	return tweets
