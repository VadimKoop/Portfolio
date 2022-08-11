package interfaces;

import java.util.Date;

/**
 * Data object for a tweet. Holds the text,
 * the author and the timestamp.
 * The methods are regular getters/setters.
 * @author Ago
 *
 */
public interface Tweet {
	public String getText();
	public void setText(String text);
	
	public String getUser();
	public void setUser(String user);
	
	public Date getTimestamp();
	public void setTimestamp(Date timestamp);
}
