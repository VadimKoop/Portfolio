package interfaces;

/**
 * Location search functionality. The implementation
 * of this interface should be able to find query parameters
 * (see TwitterQuery) for the given location.
 * @author Ago
 *
 */
public interface LocationSearch {
	
	/**
	 * Find query parameters for the given location.
	 * @param location Location to be searched for
	 * @return Query parameters
	 */
	public TwitterQuery getQueryFromLocation(String location);
}
