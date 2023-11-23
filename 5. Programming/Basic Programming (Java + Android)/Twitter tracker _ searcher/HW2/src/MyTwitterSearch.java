import java.util.ArrayList;
import java.util.List;

import twitter4j.GeoLocation;
import twitter4j.Query;
import twitter4j.QueryResult;
import twitter4j.Status;
import twitter4j.Twitter;
import twitter4j.TwitterException;
import twitter4j.TwitterFactory;
import twitter4j.conf.ConfigurationBuilder;
import interfaces.Tweet;
import interfaces.TwitterQuery;
import interfaces.TwitterSearch;

public class MyTwitterSearch implements TwitterSearch {

	@Override
	public List<Tweet> getTweets(TwitterQuery query) {
		ConfigurationBuilder cb = new ConfigurationBuilder();
		cb.setDebugEnabled(true)
				.setOAuthConsumerKey("1m9k3Tguszlm6940Dn5VHJlkD")
				.setOAuthConsumerSecret(
						"ICzLg9MFEIQXcDKuLyQfKOa2qDruhUaL5E714T8CeiqU8XOmbe")
				.setOAuthAccessToken(
						"442176637-8nqLrPzLam6SUdnwVfeD5rdqYywKOFalqL4V3bz8")
				.setOAuthAccessTokenSecret(
						"P7ai4482kC7XGGbivHvu6sp3nG7qvPYYKyPjLpNLvYucq");
		TwitterFactory tf = new TwitterFactory(cb.build());
		Twitter twitter = tf.getInstance();

		List<Tweet> tweets = new ArrayList<Tweet>();

		try {
			// The factory instance is re-useable and thread safe.
			Query query1 = new Query().geoCode(
					new GeoLocation(query.getLatitude(), query.getLongitude()),
					query.getRadius(), Query.Unit.km.toString()).count(
					query.getCount());
			QueryResult result = twitter.search(query1);

			for (Status status : result.getTweets()) {
				Tweet twt = new MyTweet();
				twt.setUser(status.getUser().getScreenName());
				twt.setText(status.getText());
				twt.setTimestamp(status.getCreatedAt());
				tweets.add(twt);

			}
		} catch (TwitterException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		return tweets;
	}

}
