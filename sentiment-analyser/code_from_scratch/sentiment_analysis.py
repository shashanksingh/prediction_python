import re, math, collections, itertools
import nltk, nltk.classify.util, nltk.metrics
from nltk.classify import NaiveBayesClassifier
from nltk.metrics import BigramAssocMeasures
from nltk.probability import FreqDist, ConditionalFreqDist

from read_from_twitter import collated_twitter_data
from config import SEARCH_OLA_TERMS
from preprocess import preprocess_tweet
from filter_words import get_feature_list


tweet = collated_twitter_data(since='',until='',search_terms=SEARCH_OLA_TERMS)
preprocessed_tweets = preprocess_tweet(tweet)
filtered_tweet = get_feature_list(preprocessed_tweets)

print filtered_tweet

