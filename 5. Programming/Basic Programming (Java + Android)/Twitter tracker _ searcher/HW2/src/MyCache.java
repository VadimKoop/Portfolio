import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import interfaces.Cache;
import interfaces.TwitterQuery;

public class MyCache implements Cache {

	public String _filename;

	@Override
	public void init() {
		_filename = "kohad.csv";
	}

	@Override
	public TwitterQuery getQueryFromCache(String location) {
		try {
			Boolean thisLine = false;
			File file = new File(_filename);
			FileReader fileReader = new FileReader(file);
			BufferedReader bufferedReader = new BufferedReader(fileReader);
			StringBuffer stringBuffer = new StringBuffer();
			String line;
			while ((line = bufferedReader.readLine()) != null) {

				String[] cachedLoc = line.split(",");
				if (cachedLoc.length < 4) {
					continue;
				}

				for (int i = 0; i < (cachedLoc.length - 1); i++) {
					if (i != 1 && i != 2 && i != 3) {
						if (cachedLoc[i].equalsIgnoreCase(location)) {
							location = cachedLoc[0].toLowerCase();
							thisLine = true;
						}
					}
				}
				if (thisLine) {
					if (cachedLoc[1] != "" && cachedLoc[2] != ""
							&& cachedLoc[3] != "") {
						TwitterQuery tq = new MyTwitterQuery();
						tq.setLatitude(Double.parseDouble(cachedLoc[1]));
						tq.setLongitude(Double.parseDouble(cachedLoc[2]));
						tq.setRadius(Double.parseDouble(cachedLoc[3]));
						return tq;
					}
				}
			}
			bufferedReader.close();
			fileReader.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return null;
	}

	@Override
	public void updateLocation(TwitterQuery query) {
		List<String> lineCache = new ArrayList<String>();
		Boolean lineFound = false;

		try {
			Boolean thisLine = false;

			File file = new File(_filename);
			FileReader fileReader = new FileReader(file);
			BufferedReader bufferedReader = new BufferedReader(fileReader);
			StringBuffer stringBuffer = new StringBuffer();
			String line;
			while ((line = bufferedReader.readLine()) != null) {
				String[] cachedLoc = line.split(",");
				if (cachedLoc.length < 4) {
					lineCache.add(line);
					continue;
				}

				for (int i = 0; i < (cachedLoc.length - 1); i++) {
					if (i != 1 && i != 2 && i != 3) {
						if (cachedLoc[i].equalsIgnoreCase(query.getLocation())) {
							thisLine = true;
							lineFound = true;
						}
					}
				}

				if (thisLine) {
					cachedLoc[1] = Double.toString(query.getLatitude());
					cachedLoc[2] = Double.toString(query.getLongitude());
					cachedLoc[3] = Double.toString(query.getRadius());
					lineCache.add(String.join(",", cachedLoc));
				} else {
					lineCache.add(line);
				}
				thisLine = false;
			}
			bufferedReader.close();
			fileReader.close();
		} catch (IOException e) {
			e.printStackTrace();
		}

		if (!lineFound) {
			lineCache.add(query.getLocation().toLowerCase() + ","
					+ Double.toString(query.getLatitude()) + ","
					+ Double.toString(query.getLongitude()) + ","
					+ Double.toString(query.getRadius()));
		}

		try {
			// Create file
			FileWriter fstream = new FileWriter(_filename, false);
			BufferedWriter out = new BufferedWriter(fstream);
			out.write(String.join("\n", lineCache));
			out.close();
		} catch (Exception e) {
			e.printStackTrace();
		}

	}

	@Override
	public void setCacheFilename(String filename) {
		_filename = filename;
	}

	@Override
	public String getCacheFilename() {
		return _filename;
	}

}
