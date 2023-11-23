package interfaces;

/**
 * Cache functionality. The implementation
 * of Cache should be able to look information
 * from the cache file and add/update information
 * in the file.
 * @author Ago
 *
 */
public interface Cache {
	
	/**
	 * Initializes object. Within constructor, nothing
	 * special should be done. Instead, use init() method,
	 * which will be called during application run().
	 * For example, if you want to read all contents from the
	 * file, do it here (not in the constructor).
	 */
	public void init();
	
	/**
	 * Finds query parameters from cache file.
	 * If location is not found in cache, the method returns null.
	 * If location is found, but latitude, longitude or radius is not found,
	 * then the given parameters will be set to null.
	 * @param location Location to be searched for
	 * @return Twitter query object where location information is filled
	 * if it is present in the cache. If the location is not found, 
	 * null is returned.
	 */
	public TwitterQuery getQueryFromCache(String location);
	
	/**
	 * Adds a new location row into the cache file.
	 * Default implementation just executes updateLocation
	 * method, which then should understand whether you
	 * have the given location already in the file or not.
	 * If it would be more convenient to have update and add
	 * methods separately, just override this method and
	 * write your own implementation.
	 * @param query Query parameters instances which
	 * has all the necessary values.
	 */
	default public void addLocation(TwitterQuery query) {
		updateLocation(query);
	}
	
	/**
	 * Updates an existing row in the cache file.
	 * Note: in the current set up, this method
	 * should add a new line if the location does not 
	 * exist (e.g. you don't have anything to update).
	 * If you find it more convenient to have add and update
	 * functionalities separately, implement addLocation method
	 * and call them accordingly to the situation.
	 * @param query Query parameters instances which
	 * has all the necessary values.
	 */
	public void updateLocation(TwitterQuery query);
	
	/**
	 * Sets the filename where the cache is stored.
	 * @param filename Filename where the cache is stored
	 */
	public void setCacheFilename(String filename);
	
	/**
	 * Returns the filename where the cache is stored.
	 * @return Filename where the cache is stored
	 */
	public String getCacheFilename();
}
