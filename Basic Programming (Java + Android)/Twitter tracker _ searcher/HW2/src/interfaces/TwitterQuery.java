package interfaces;

/**
 * Data object for twitter query parameters.
 * It holds all the necessary values to make the
 * twitter search. The location is stored for caching.
 * When the data is read from the cache, the 
 * main location should also be stored here.
 * If the geographical data is missing, the main location
 * (or the getLocation() value) is used to make the
 * query from twitter API.
 * @author Ago
 *
 */
public interface TwitterQuery {
	public void setLatitude(double latitude);
	public void setLongitude(double longitude);
	public void setRadius(double radius);
	public void setLocation(String location);
	/**
	 * The count of tweets to query.
	 * @param count Count of tweets to query
	 */
	public void setCount(int count);
	
	public double getLatitude();
	public double getLongitude();
	public double getRadius();
	public String getLocation();
	public int getCount();
	
	/**
	 * Checks whether the given instance has necessary
	 * parameters set. This is used in case of caching -
	 * if location exist but all the parameters do
	 * not exist, the cache needs to be updated.
	 * @return Whether latitude, longitude and radius are set
	 */
	public boolean isGeoSet();
}
