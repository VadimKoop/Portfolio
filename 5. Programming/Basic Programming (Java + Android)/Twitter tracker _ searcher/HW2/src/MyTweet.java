
import java.util.Date;

import interfaces.Tweet;

public class MyTweet implements Tweet {

	String _text;
	String _user;
	Date _timestamp;

	public MyTweet() {
		_text = "";
		_user = "";
		_timestamp = new Date();
	}

	@Override
	public String getText() {
		return _text;
	}

	@Override
	public void setText(String text) {
		_text = text;
	}

	@Override
	public String getUser() {
		return _user;
	}

	@Override
	public void setUser(String user) {
		_user = user;
	}

	@Override
	public Date getTimestamp() {
		return _timestamp;
	}

	@Override
	public void setTimestamp(Date timestamp) {
		_timestamp = timestamp;
	}
	
	@Override
	public String toString() {
		return "@"+getUser() +" "+ getTimestamp() +" "+ getText();
	}

}
