package gomoku;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.stage.Stage;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;


public class Main extends Application {
	@Override
	public void start(Stage primaryStage) {
		try {
			Game game = new Game(10, 10);
			game.start();

			FXMLLoader loader = new FXMLLoader(getClass().getResource("Gomoku.fxml"));
			GomokuController c = new GomokuController(game);
			loader.setController(c);
			Parent root = (Parent)loader.load();

			Scene scene = new Scene(root);
			scene.getStylesheets().add(getClass().getResource("application.css").toExternalForm());
			
			primaryStage.setTitle("GoMoku v0.5 (2014-12-10)");
			primaryStage.setScene(scene);
			primaryStage.show();
		} catch(Exception e) {
			e.printStackTrace();
		}
		
	}
	
	public static void main(String[] args) {
		launch(args);
	}
}
