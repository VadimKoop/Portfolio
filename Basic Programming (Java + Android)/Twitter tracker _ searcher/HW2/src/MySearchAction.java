
import interfaces.SearchAction;

public class MySearchAction implements SearchAction {

	String _keyword;

	@Override
	public void setSearchKeyword(String keyword) {
		_keyword = keyword;
	}

	@Override
	public String getSearchKeyword() {
		return _keyword;
	}

}
