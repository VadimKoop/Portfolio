package gomoku;

import java.io.IOException;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.Pane;
import javafx.scene.shape.Circle;

public class Square extends Pane {
	public static final int SIZE_10 = 50;
	public static final int SIZE_20 = 40;
	
	public Square(int row, int column, Game game, int size) throws IOException {
		FXMLLoader loader = new FXMLLoader(getClass().getResource("Square.fxml"));
		loader.setRoot(this);
		final SquareController squareController = new SquareController(game, new Location(row, column), size);
		loader.setController(squareController);
		loader.load();
		this.setPrefHeight(size);
		this.setPrefWidth(size);
		GridPane.setColumnIndex(this, column);
		GridPane.setRowIndex(this, row);
	}
}
