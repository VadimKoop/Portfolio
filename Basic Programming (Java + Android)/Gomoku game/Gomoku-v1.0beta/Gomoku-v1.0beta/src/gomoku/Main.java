package gomoku;


import gomoku.strategies.StudentStrategyExample;

import java.io.File;
import java.net.URISyntaxException;
import java.net.URL;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

public class Main extends Application {
	@Override
	public void start(Stage primaryStage) {
		try {
			Parameters params = getParameters();
			List<String> args = params.getRaw();
			Game game = null;
			if (args.size() > 0) {
				if (args.size() < 2) {
					System.out.println("usage: program player1 player2 [logfile] [boardsize]");
					System.out.println("where player1 and player2 are the class names (without .java extension).");
					System.out.println("into optional logfile will be written game moves' log.");
					System.out.println("boardsize 10 or 20, 10 is default.");
					System.exit(0);
				}
				Player[] players = new Player[2];
				for (int i = 0; i < 2; i++) {
					ClassLoader classLoader = Main.class.getClassLoader();
					try {
						Class<?> studentClass = classLoader.loadClass("gomoku.players." + args.get(i));
						Object s = studentClass.newInstance();
						System.out.println(s);
						ComputerStrategy cs = (ComputerStrategy)s;
						Player p = new ComputerPlayer(cs);
						players[i] = p;
					} catch (ClassNotFoundException e) {
						System.out.println("Class " + args.get(i) + " not found.");
						System.exit(1);
					}
				}
				int size = 10;
				if (args.size() > 3) {
					size = Integer.parseInt(args.get(3));
				}
				game = new Game(players[0], players[1], size, size);
				System.out.println("game for two");
				if (args.size() > 2) {
					System.out.println("with moves log");
					game.setHistoryLogFilename(args.get(2));
				}
			} else {
				game = new Game(10, 10);
			}

			FXMLLoader loader = new FXMLLoader(getClass().getResource("Gomoku.fxml"));
			GomokuController c = new GomokuController(game);
			loader.setController(c);
			Parent root = (Parent)loader.load();

			Scene scene = new Scene(root);
			scene.getStylesheets().add(getClass().getResource("application.css").toExternalForm());
			
			primaryStage.setTitle("GoMoku v1.0beta (2017-04-11)");
			primaryStage.setScene(scene);
			primaryStage.show();
		} catch(Exception e) {
			e.printStackTrace();
		}
		
	}
	
	public static void main(String[] args) {
		launch(args);
	}
	
	// kind of singleton
	private static List<String> strategies = null;
	public static List<String> getStrategies() {
		if (strategies != null) {
			return strategies;
		}
		
		strategies = getClassesFromPackage(StudentStrategyExample.class.getPackage());
	
		
		return strategies;
	}
	
	private static List<String> getClassesFromPackage(Package pkg) {
	    String pkgname = pkg.getName();

	    List<String> classes = new ArrayList<>();

	    // Get a File object for the package
	    File directory = null;
	    String fullPath;
	    String relPath = pkgname.replace('.', '/');

	    //System.out.println("ClassDiscovery: Package: " + pkgname + " becomes Path:" + relPath);

	    URL resource = ClassLoader.getSystemClassLoader().getResource(relPath);

	    //System.out.println("ClassDiscovery: Resource = " + resource);
	    if (resource == null) {
	        throw new RuntimeException("No resource for " + relPath);
	    }
	    fullPath = resource.getFile();
	    //System.out.println("ClassDiscovery: FullPath = " + resource);

	    try {
	        directory = new File(resource.toURI());
	    } catch (URISyntaxException e) {
	        throw new RuntimeException(pkgname + " (" + resource + ") does not appear to be a valid URL / URI.  Strange, since we got it from the system...", e);
	    } catch (IllegalArgumentException e) {
	        directory = null;
	    }
	    //System.out.println("ClassDiscovery: Directory = " + directory);

	    if (directory != null && directory.exists()) {
	        // Get the list of the files contained in the package
	        String[] files = directory.list();
	        for (int i = 0; i < files.length; i++) {

	            // we are only interested in .class files
	            if (files[i].endsWith(".class")) {

	                // removes the .class extension
	                String className = pkgname + '.' + files[i].substring(0, files[i].length() - 6);

	                //System.out.println("ClassDiscovery: className = " + className);

	                try {
	                	Class clazz = Class.forName(className);
	                	if(Arrays.asList(clazz.getInterfaces()).contains(Class.forName("gomoku.ComputerStrategy"))) {
	                		classes.add(Class.forName(className).getSimpleName());
	                	}
	                } catch (ClassNotFoundException e) {
	                    throw new RuntimeException("ClassNotFoundException loading " + className);
	                } 
	            }
	        }
	    }
	    return classes;
	}
	
}
