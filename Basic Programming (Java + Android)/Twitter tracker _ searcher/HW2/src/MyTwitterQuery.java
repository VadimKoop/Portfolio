
import interfaces.TwitterQuery;

public class MyTwitterQuery implements TwitterQuery {

	double _latidude;
	double _longitude;
	double _radius;
	String _location;
	int _count;

	public MyTwitterQuery() {
		_latidude = 0;
		_longitude = 0;
		_radius = 0;
		_location = "";
		_count = 0;
	}

	@Override
	public void setLatitude(double latitude) {
		_latidude = latitude;
	}

	@Override
	public void setLongitude(double longitude) {
		_longitude = longitude;

	}

	@Override
	public void setRadius(double radius) {
		_radius = radius;
	}

	@Override
	public void setLocation(String location) {
		_location = location;
	}

	@Override
	public void setCount(int count) {
		_count = count;
	}

	@Override
	public double getLatitude() {
		return _latidude;
	}

	@Override
	public double getLongitude() {
		return _longitude;
	}

	@Override
	public double getRadius() {
		return _radius;
	}

	@Override
	public String getLocation() {
		return _location;
	}

	@Override
	public int getCount() {
		return _count;
	}

	@Override
	public boolean isGeoSet() {
		return _location != "" || _latidude != 0 || _longitude != 0
				|| _radius != 0;
	}

}
