import java.net.URL;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import interfaces.LocationSearch;
import interfaces.TwitterQuery;

public class MyLocationSearch implements LocationSearch {

	@Override
	public TwitterQuery getQueryFromLocation(String location) {
		TwitterQuery tq = new MyTwitterQuery();
		tq.setLocation(location);

		try {
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(new URL(
					"http://nominatim.openstreetmap.org/search?q=" + location
							+ "&format=xml").openStream());

			NodeList nList = doc.getElementsByTagName("place");

			Node nNode = nList.item(0);

			Element eElement = (Element) nNode;

			tq.setLatitude(Double.parseDouble(eElement.getAttribute("lat")));
			tq.setLongitude(Double.parseDouble(eElement.getAttribute("lon")));
			
			String[] boundingbox = eElement.getAttribute("boundingbox").split(
					",");
			double lat1 = Double.parseDouble(boundingbox[0]);
			double lat2 = Double.parseDouble(boundingbox[1]);
			double lon1 = Double.parseDouble(boundingbox[2]);
			double lon2 = Double.parseDouble(boundingbox[3]);
			
			tq.setRadius(Math.min(Math.abs(lat1 - lat2), Math.abs(lon1 - lon2)));

		} catch (Exception e) {
			e.printStackTrace();
		}

		return tq;
	}
}
