import sys

if __name__ == "__main__":
    from textblob import TextBlob

    polarity=TextBlob(sys.argv[1]).sentiment.polarity
    if polarity>=0.5:
       print("Positive")
    elif polarity<= -0.5:
       print("Negative")
    else:
       print("Neutral")