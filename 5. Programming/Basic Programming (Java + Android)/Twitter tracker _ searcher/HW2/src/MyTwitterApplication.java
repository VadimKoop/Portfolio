import interfaces.Action;
import interfaces.Cache;
import interfaces.LocationSearch;
import interfaces.QueryAction;
import interfaces.SearchAction;
import interfaces.SortAction;
import interfaces.Tweet;
import interfaces.TwitterApplication;
import interfaces.TwitterQuery;
import interfaces.TwitterSearch;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import twitter4j.TwitterException;

public class MyTwitterApplication implements TwitterApplication {

	LocationSearch _locationSearch;
	TwitterSearch _twitterSearch;
	Cache _cache;
	List<Tweet> _tweets;

	int printCount = 0;

	public MyTwitterApplication() {
		LocationSearch locSearch = new MyLocationSearch();
		this.setLocationSearch(locSearch);
		Cache cache = new MyCache();
		this.setCache(cache);
		TwitterSearch twitterSearch = new MyTwitterSearch();
		this.setTwitterSearch(twitterSearch);

		cache.init();
	}

	@Override
	public void runWithArgs(String[] args) {
		for (Action action : getActionsFromArguments(args)) {
			executeAction(action);
		}

		for (Tweet tweet : getTweets()) {
			System.out.println(tweet);
		}

	}

	@Override
	public void runInteractive() {
		List<Action> actions = new ArrayList<Action>();
		String input = null;

		while (true) {
			System.out.print("> ");

			BufferedReader br = new BufferedReader(new InputStreamReader(
					System.in));

			try {
				input = br.readLine();
				if (input.toLowerCase() == "exit") {
					break;
				}
				actions = getActionsFromInput(input);
			} catch (IOException ioe) {
			}

			if (!actions.isEmpty()) {
				executeAction(actions.get(0));
			}
		}
	}

	@Override
	public List<Action> getActionsFromInput(String action) {

		List<Action> actions = new ArrayList<Action>();

		try {
			String[] params = action.split(" ");

			switch (params[0]) {
			case "query":
				MyQueryAction queryAction = new MyQueryAction();
				queryAction.setLocation(params[1]);

				if (params.length > 2) {
					queryAction.setCount(Integer.parseInt(params[2]));
				}
				actions.add(queryAction);
				break;
			case "print":
				MyPrintAction printAction = new MyPrintAction();

				if (params.length > 1) {
					printCount = Integer.parseInt(params[1]);
				}
				actions.add(printAction);
				break;
			case "setcount":
				if (params.length > 1) {
					printCount = Integer.parseInt(params[1]);
				}
				break;
			case "sort":
				MySortAction sortAction = new MySortAction();

				switch (params[1]) {
				case "author":
					sortAction.setSortField(SortAction.FIELD_AUTHOR);
					break;
				case "date":
					sortAction.setSortField(SortAction.FIELD_DATE);
					break;
				case "tweet":
					sortAction.setSortField(SortAction.FIELD_TWEET);
					break;
				}

				if (params.length > 2) {
					switch (params[2]) {
					case "asc":
						sortAction.setSortOrder(SortAction.ORDER_ASCENDING);
						break;
					case "desc":
						sortAction.setSortOrder(SortAction.ORDER_DESCENDING);
						break;
					}
				}
				actions.add(sortAction);
				break;
			case "search":
				MySearchAction searchAction = new MySearchAction();
				searchAction.setSearchKeyword(params[1]);
				actions.add(searchAction);
				break;
			default:
				throw new Exception();
			}

		} catch (Exception e) {
			System.out.println("query location [count]");
			System.out.println("print [count]");
			System.out.println("setcount num");
			System.out.println("sort field [asc|desc]");
			System.out.println("search keyword");
			System.out.println("exit");
		}
		return actions;
	}

	@Override
	public List<Action> getActionsFromArguments(String[] args) {
		List<Action> actions = new ArrayList<Action>();
		try {
			int curArg = 0;
			int argCount = args.length;

			String location;
			int count = 15;

			Boolean sort = false;
			int sortField = SortAction.FIELD_DATE;
			int sortOrder = SortAction.ORDER_ASCENDING;

			Boolean search = false;
			String searchKeyword = "";

			if (argCount == 0) {
				throw new Exception();
			}

			location = args[curArg];
			curArg++;

			while (argCount > curArg) {
				switch (args[curArg]) {
				case "-help":
					throw new Exception();
				case "-count":
					curArg++;
					count = Integer.parseInt(args[curArg]);
					curArg++;
					break;
				case "-sort":
					sort = true;
					curArg++;
					switch (args[curArg]) {
					case "author":
						sortField = SortAction.FIELD_AUTHOR;
						break;
					case "date":
						sortField = SortAction.FIELD_DATE;
						break;
					case "tweet":
						sortField = SortAction.FIELD_TWEET;
						break;
					}
					curArg++;

					String nextArg = args[curArg + 1];
					if (!nextArg.startsWith("-")) {
						switch (args[curArg]) {
						case "asc":
							sortOrder = SortAction.ORDER_ASCENDING;
							break;
						case "desc":
							sortOrder = SortAction.ORDER_DESCENDING;
							break;
						}
						curArg++;
					}
					break;
				case "-search":
					search = true;
					curArg++;
					searchKeyword = args[curArg];
					curArg++;
					break;
				default:
					curArg++;
					break;
				}

			}

			MyQueryAction mqa = new MyQueryAction();
			mqa.setLocation(location);
			mqa.setCount(count);
			actions.add(mqa);

			if (sort) {
				MySortAction sortAction = new MySortAction();
				sortAction.setSortField(sortField);
				sortAction.setSortOrder(sortOrder);
				actions.add(sortAction);
			}

			if (search) {
				MySearchAction searchAction = new MySearchAction();
				searchAction.setSearchKeyword(searchKeyword);
				actions.add(searchAction);
			}

		} catch (Exception e) {
			System.out.println("Main <location>");
			System.out.println("-count <num>");
			System.out.println("-sort <field> [asc|desc]");
			System.out.println("-search <keyword>");
			System.out.println("-help");
			System.exit(1);
		}
		return actions;
	}

	@Override
	public void executeAction(Action action) {
		if (action.getClass() == MyQueryAction.class) {
			MyQueryAction mqa = (MyQueryAction) action;

			TwitterQuery tq;
			String location = mqa.getLocation();

			tq = getCache().getQueryFromCache(location);

			if (tq == null) {
				tq = _locationSearch.getQueryFromLocation(location);
				getCache().updateLocation(tq);
			}

			tq.setCount(mqa.getCount());

			setTweets(getTweets(tq));

		} else if (action.getClass() == MySearchAction.class) {
			MySearchAction msa = (MySearchAction) action;

			String keyword = msa.getSearchKeyword();
			List<Tweet> tweets = new ArrayList<Tweet>();

			for (Tweet tweet : getTweets()) {
				if (tweet.getText().contains(keyword)) {
					tweets.add(tweet);
				}
			}

			setTweets(tweets);

		} else if (action.getClass() == MySortAction.class) {
			SortAction msa = (SortAction) action;

			switch (msa.getSortField()) {
			case SortAction.FIELD_AUTHOR:
				Collections.sort(_tweets, new Comparator<Tweet>() {
					@Override
					public int compare(Tweet o1, Tweet o2) {
						return o1.getUser().compareTo(o2.getUser());
					}
				});
				break;
			case SortAction.FIELD_DATE:
				Collections.sort(_tweets, new Comparator<Tweet>() {
					@Override
					public int compare(Tweet o1, Tweet o2) {
						return o1.getTimestamp().compareTo(o2.getTimestamp());
					}
				});
				break;
			case SortAction.FIELD_TWEET:
				Collections.sort(_tweets, new Comparator<Tweet>() {
					@Override
					public int compare(Tweet o1, Tweet o2) {
						return o1.getText().compareTo(o2.getText());
					}
				});
				break;
			default:
				break;
			}

			switch (msa.getSortOrder()) {
			case SortAction.ORDER_ASCENDING:
				break;
			case SortAction.ORDER_DESCENDING:
				Collections.reverse(_tweets);
				break;
			default:
				break;
			}
		} else if (action.getClass() == MyPrintAction.class) {
			if (printCount > 0) {
				for (Tweet tweet : getTweets().subList(0, printCount)) {
					System.out.println(tweet);
				}
			} else {
				for (Tweet tweet : getTweets()) {
					System.out.println(tweet);
				}
			}
		}
	}

	@Override
	public void setLocationSearch(LocationSearch locationSearch) {
		_locationSearch = locationSearch;
	}

	@Override
	public LocationSearch getLocationSearch() {
		return _locationSearch;
	}

	@Override
	public void setTwitterSearch(TwitterSearch twitterSearch) {
		_twitterSearch = twitterSearch;
	}

	@Override
	public TwitterSearch getTwitterSearch() {
		return _twitterSearch;
	}

	@Override
	public void setCache(Cache cache) {
		_cache = cache;
	}

	@Override
	public Cache getCache() {
		return _cache;
	}

	@Override
	public void setTweets(List<Tweet> tweets) {
		_tweets = tweets;
	}

	@Override
	public List<Tweet> getTweets() {
		return _tweets;
	}

}