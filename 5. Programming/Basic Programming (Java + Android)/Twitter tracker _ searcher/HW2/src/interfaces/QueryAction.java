package interfaces;

/**
 * Data object to hold parameters for query action.
 * The methods are regular getters/setters.
 * @author Ago
 *
 */
public interface QueryAction extends Action {
	public void setLocation(String location);
	public String getLocation();
	
	public void setCount(int count);
	public int getCount();
}
