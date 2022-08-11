package interfaces;
/**
 * Used to perform searching action (find tweets with specific keyword/hashtag)
 * @author Ago
 *
 */
public interface SearchAction extends Action {
	/**
	 * Sets search keyword
	 * @param keyword Search keyword
	 */
	public void setSearchKeyword(String keyword);
	public String getSearchKeyword();
}
