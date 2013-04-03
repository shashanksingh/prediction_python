#from training.read_dataset import get_data

from training.read_dataset import get_data
from preprocess import preprocess_tweet
from filter_words import get_feature_list

tweets = get_data()
print tweets[:2]
preprocessed_tweets = preprocess_tweet(tweets)
#filtered_tweet = get_feature_list(preprocessed_tweets)

print preprocessed_tweets

