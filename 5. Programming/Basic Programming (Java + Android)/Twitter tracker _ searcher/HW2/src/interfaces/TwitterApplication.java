package interfaces;

import java.util.List;
 
/**
 * The core interface for your application.
 * <p>
 * The setup of this class is the following:
 * <ul>
 * <li>in the constructor (not described here)
 * all the different service objects should be set.
 * E.g. setLocationSearch(), setTwitterSearch(), setCache() etc.
 * <li>the actual start of the application is done with:
 * <code>application.run(args);</code>
 * </ul>
 * <p>
 * The constructor of the class which implements TwitterApplication
 * could be the following:
 * <pre>
 * public AwesomeTwitterApplication() {
 *     LocationSearch locSearch = new AwesomeLocationSearch();
 *     this.setLocationSearch(locSearch);
 *     Cache cache = new AwesomeCache();
 *     this.setCache(cache);
 *     TwitterSearch twitterSearch = new AwesomeTwitterSearch();
 *     this.setTwitterSearch(twitterSearch);
 * }
 * </pre>
 * 
 * Constructor should not do anything else. The actual execution
 * of the program starts with run(args).
 * <p>
 * Setting all your implementations of the services in the constructor
 * gives the test system opportunity to test each one of those.
 * @author Ago
 * @see TwitterApplication#run(String[])
 *
 */
public interface TwitterApplication {	
	/**
	 * The application will be run using arguments
	 * from the command line (should be passed here
	 * directly from the main method).
	 * runWithArgs() method will be called.
	 * <p>
	 * If the arguments are empty, runInteractive() is run.
	 * 
	 * @param args Arguments from command line (the same
	 * which are passed to the main method).
	 * @see TwitterApplication#runWithArgs(String[])
	 * @see TwitterApplication#runInteractive()
	 */
	default public void run(String[] args) {
		if (args.length == 0) {
			runInteractive();
		} else {
			runWithArgs(args);
		}
	}
 
	/**
	 * Tries to run the program with command line
	 * arguments.
	 * <p>
	 * If command line arguments are not correct,
	 * the program can exit with an error code.
	 * @param args Arguments from command line.
	 */
	public void runWithArgs(String[] args);
 
	/**
	 * The application will be run in an interactive mode
	 * where the user can enter different statements
	 * to control the program.
	 */
	public void runInteractive();
 
	/**
	 * Given a command as a String (in interactive mode), 
	 * this method should parse the input and return
	 * a list of Action instances.
	 * Usually one command should create one action, this 
	 * method gives the opportunity to have combined actions
	 * for one command line ("query tallinn 30" - query + count).
	 * If you don't use combined actions, just return a list
	 * with one element in it - the Action instance to be 
	 * executed. 
	 * @param action Command string from interactive mode
	 * @return List of actions to be executed
	 */
	public List<Action> getActionsFromInput(String action);
 
	/**
	 * Given command line arguments this method parses
	 * the arguments and returns a list of Action instances.
	 * As the command line can accept several different actions
	 * (for example query, sort and search), this method
	 * return a list of all the actions.
	 * @param args Command line arguments (from main method)
	 * @return List of actions to be executed
	 */
	public List<Action> getActionsFromArguments(String[] args);
 
	/**
	 * Given an instance of Action, it will be executed.
	 * If you choose to implement Action.execute()
	 * for every action, then the body of this method can just be:
	 * action.execute()
	 * @param action Action to be executed
	 */
	public void executeAction(Action action);
 
	/**
	 * Executes all the actions given as a list.
	 * The default implementation just iterates over
	 * all the actions and calls executeAction.
	 * @param actions A list of actions
	 */
	default public void executeActions(List<Action> actions) {
		for (Action action : actions) {
			executeAction(action);
		}
	}
 
	/**
	 * Executes a location search using location search set with
	 * setLocationSearch().
	 * Returns a query object which holds all the values for Twitter search.
	 * Note that this method has a default implementation which
	 * just executes a method from local location search and 
	 * returns its return value.
	 * Use this default implementation if you don't have caching implemented.
	 * If you need caching, you need to override this method.
	 * @param location The location which is to be searched for
	 * @return Query object which holds all the necessary information
	 * about Twitter query
	 * @see TwitterApplication#setLocationSearch(LocationSearch)
	 */
	default public TwitterQuery getQueryFromLocation(String location) {
		LocationSearch locationSearch = getLocationSearch();
		return locationSearch.getQueryFromLocation(location);
	}
 
	/**
	 * Executes a search of tweets on TwitterSearch object which
	 * is stored via setTwitterSearch(). 
	 * Returns a list of received tweets. 
	 * @param query Query object which holds all the necessary values
	 * @return List of Tweet objects received from Twitter search.
	 * <code>null</code> if nothing received.
	 * @see TwitterApplication#setTwitterSearch(TwitterSearch)
	 */
	default public List<Tweet> getTweets(TwitterQuery query) {
		TwitterSearch twitterSearch = getTwitterSearch();
		return twitterSearch.getTweets(query);
	}
 
	/**
	 * Stores location search object which will
	 * be used to make queries to location search API.
	 * This should be called from the constructor.
	 * @param locationSearch Implementation of LocationSearch, which
	 * can find information about location (city, country etc.).
	 */
	public void setLocationSearch(LocationSearch locationSearch);
 
	/**
	 * Returns currently stored location search object.
	 * @return Implementation of LocationSeach
	 * which will be used for location search.
	 */
	public LocationSearch getLocationSearch();
 
	/**
	 * Stores Twitter search object which will be used
	 * to query tweets from Twitter API.
	 * Should be called from the constructor.
	 * @param twitterSearch Implementation of TwitterSearch
	 */
	public void setTwitterSearch(TwitterSearch twitterSearch);
 
	/**
	 * Returns currently stored Twitter search object.
	 * @return Implementation of TwitterSearch
	 * which will be used for queries.
	 */
	public TwitterSearch getTwitterSearch();
 
	/**
	 * Stores cache object which will be used
	 * to cache locations in the file.
	 * Should be called from the constructor.
	 * @param cache Implementation of Cache
	 */
	public void setCache(Cache cache);
 
	/**
	 * Returns currently stored cache object.
	 * @return Implementation of Cache
	 * which will be used for location caching.
	 */
 
	public Cache getCache();
 
	/**
	 * Stores the latest state of tweets list.
	 * You should store your tweets using this
	 * method after querying, sorting, searching.
	 * @param tweets A list of tweets
	 */
	public void setTweets(List<Tweet> tweets);
 
	/**
	 * Get the latest state of tweets list.
	 * This method should be used for printing
	 * and when applying sorting.
	 * @return A list of tweets
	 */
	public List<Tweet> getTweets();
 
}