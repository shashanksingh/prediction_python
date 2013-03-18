import re
#initialize stopWords
stopWords = []
 
def replace_two_or_more(s):
    #look for 2 or more repetitions of character and replace with the character itself hmmmm -> hm
    pattern = re.compile(r"(.)\1{1,}", re.DOTALL)
    return pattern.sub(r"\1\1", s)

def get_stop_word_list(stopWordListFileName):
    #read the stopwords file and build a list
    stopWords = []
    stopWords.append('AT_USER')
    stopWords.append('URL')
 
    fp = open(stopWordListFileName, 'r')
    line = fp.readline()
    while line:
        word = line.strip()
        stopWords.append(word)
        line = fp.readline()
    fp.close()
    return stopWords

def get_feature_vector(tweet):
    feature_vector = []
    #split tweet into words
    words = tweet.split()
    for w in words:
        #replace two or more with two occurrences
        w = replace_two_or_more(w)
        #strip punctuation
        w = w.strip('\'"?,.')
        #check if the word stats with an alphabet
        val = re.search(r"^[a-zA-Z][a-zA-Z0-9]*$", w)
        #ignore if it is a stop word
        if(w in stopWords or val is None):
            continue
        else:
            feature_vector.append(w.lower())
    return feature_vector


def get_feature_list(tweets):
	tweets_feature = []
	for tweet in tweets:
		tweets_feature.append(get_feature_vector(tweet))
	return tweets_feature
