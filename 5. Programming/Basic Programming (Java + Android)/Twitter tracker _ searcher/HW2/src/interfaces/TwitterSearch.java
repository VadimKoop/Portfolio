package interfaces;

import java.util.List;

/**
 * Twitter search functionality. The implementation
 * of this interface should be able to search tweets
 * from Twitter API.
 * @author Ago
 *
 */
public interface TwitterSearch {
	/**
	 * Given an object with query parameters send a 
	 * query to Twitter API, reads out tweet information
	 * and returns a list of Tweet objects.
	 * @param query Query parameters to be used for search
	 * @return A list of Tweet objects
	 */
	public List<Tweet> getTweets(TwitterQuery query);

}
