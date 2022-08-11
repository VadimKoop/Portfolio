
import interfaces.QueryAction;

public class MyQueryAction implements QueryAction {

	String _location;
	int _count;

	@Override
	public void setLocation(String location) {
		_location = location;
	}

	@Override
	public String getLocation() {
		return _location;
	}

	@Override
	public void setCount(int count) {
		_count = count;
	}

	@Override
	public int getCount() {
		return _count;
	}

}
