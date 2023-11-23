package interfaces;

/**
 * Data object for sort action, holds action parameters.
 * The methods are regular getters/setters.
 * @author Ago
 *
 */
public interface SortAction extends Action {
	/**
	 * Sort field author.
	 */
	public static int FIELD_AUTHOR = 1;
	/**
	 * Sort field date.
	 */
	public static int FIELD_DATE = 2;
	/**
	 * Sort field tweet (content).
	 */
	public static int FIELD_TWEET = 3;
	
	/**
	 * Sort order ascending.
	 */
	public static int ORDER_ASCENDING = 1;
	
	/**
	 * Sort order descending.
	 */
	public static int ORDER_DESCENDING = 2;
	
	/**
	 * Sets sort field. Use IFilterAction.FIELD_* constants.
	 * @param field Sort field, use IFilterAction.FIELD_* constants.
	 */
	public void setSortField(int field);
	public int getSortField();
	
	/**
	 * Sets sort order. Use IFilterAction.ORDER_* constants.
	 * @param order Sort order, use IFilterAction.ORDER_* constants.
	 */
	public void setSortOrder(int order);
	public int getSortOrder();

}
