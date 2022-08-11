package app;

import app.controller.AppController;
import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

import java.io.IOException;

public class AppLauncher extends Application {

    public static void main(String[] args) {
        launch(args);
    }

    @Override
    public void start(Stage stage) throws Exception {
        AppController.stage = stage;

        stage.setTitle("Prototype");

        stage.setResizable(false);
        stage.setScene(this.getRootScene());
        stage.show();
    }

    private Scene getRootScene() {
        Parent root = null;
        try {
            root = FXMLLoader.load(getClass().getResource("app.fxml"));
        } catch(IOException e) {
            e.printStackTrace();
        }
        return new Scene(root);
    }
}
