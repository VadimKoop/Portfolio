package gomoku;

import gomoku.Game.SquareState;
import gomoku.Game.GameStatus;
import javafx.beans.binding.Bindings;
import javafx.beans.property.ReadOnlyObjectProperty;
import javafx.fxml.FXML;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.Pane;
import javafx.scene.paint.Color;
import javafx.scene.shape.Circle;

public class SquareController {
	
	
	private final Game game;
	private final Location location;
	private final int squareSize;
	
	public SquareController(Game game, Location location, int size) {
		this.game = game;
		this.location = location;
		this.squareSize = size;
	}
	
	@FXML
	private Pane root;
	@FXML
	private Circle circle;
	
	public void initialize() {
		// circle size
		circle.radiusProperty().bind(Bindings.min(root.widthProperty(), root.heightProperty()).divide(2).subtract(5));
		circle.layoutXProperty().bind(root.widthProperty().divide(2));
		circle.layoutYProperty().bind(root.heightProperty().divide(2));
		
		ReadOnlyObjectProperty<SquareState> squareState = game.squareStateProperty(location);
		
		change(squareState);
		
		// every square state in the board gets listener (if the square state changes...)
		squareState.addListener((event) ->
		{
			change(squareState);
		});
		// disable if ..
		
		root.disableProperty().bind(
				// location is not empty
				game.squareStateProperty(location).isNotEqualTo(SquareState.EMPTY)
				// or game status is not "open" (someone has won or it's a draw)
				.or(game.gameStatusProperty().isNotEqualTo(GameStatus.OPEN))
				// not current player's turn?
				// or in replay mode?
				.or(game.inReplayMode())
		);
		
	}
	
	private void change(ReadOnlyObjectProperty<SquareState> squareState) {
		if (squareState.get() == SquareState.EMPTY) {
			circle.visibleProperty().set(false);
		} else {
			circle.visibleProperty().set(true);
			if (squareState.get() == SquareState.BLACK) {
				circle.setFill(Color.BLACK);
			} else if (squareState.get() == SquareState.WHITE) {
				circle.setFill(Color.WHITE);
			}
		}
	}
	
	@FXML
	public void makeMove() {
		game.makeMove(location);
	}
}
