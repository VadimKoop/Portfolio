package gomoku;

import gomoku.Game.GameStatus;
import gomoku.Game.SquareState;

import java.io.IOException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.ThreadFactory;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.concurrent.Task;
import javafx.concurrent.WorkerStateEvent;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.scene.Scene;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.GridPane;
import javafx.scene.text.Font;
import javafx.scene.text.FontWeight;

public class GomokuController {
	
	/**
	 * Used as choice box item.
	 * (Currently not used)
	 * @author Ago
	 *
	 */
	private class ChoiceItem {
		private final String value;
		private final int key;
		public ChoiceItem(int key, String value) {
			this.key = key;
			this.value = value;
		}
		public int getKey() { return key; }
		public String toString() { return value; }
	}

	private final ExecutorService executorService ;
	private Game game;
	private Square[][] squares;
	
	// white player
	@FXML
	private ChoiceBox<String> playerWChoiceBox;
	
	// black player
	@FXML
	private ChoiceBox<String> playerBChoiceBox;
	
	public GomokuController(Game game) {
		this.game = game;
		squares = new Square[game.getHeight()][game.getWidth()];


		executorService = Executors.newSingleThreadExecutor(new ThreadFactory() {
			@Override
			public Thread newThread(Runnable r) {
				Thread thread = new Thread(r);
				thread.setDaemon(true);
				return thread;
			}
		});
	}

	@FXML
	private GridPane board;
	
	@FXML
	private Label statusLabel;
	
	@FXML
	private Label playerLabel;
	
	
	public void initialize() throws IOException {
		setUpSquares();
		initializePlayerChange();
		initializeGameStatusListener();
		
		if (playerWChoiceBox.getItems().size() == 0) {
			playerWChoiceBox.setItems(getPlayerChoices());
			playerWChoiceBox.getSelectionModel().select(0);
		}
		if (playerBChoiceBox.getItems().size() == 0) {
			playerBChoiceBox.setItems(getPlayerChoices());
			playerBChoiceBox.getSelectionModel().select(1);
		}
		
	}
	
	private ObservableList<String> getPlayerChoices() {
		ObservableList<String> choices = FXCollections.observableArrayList();
		choices.add("Human");
		choices.add("StudentStrategy");
		choices.add("OpponentWeak");
		choices.add("OpponentStrong");
		choices.add("OpponentWinner");
		return choices;
	}
	
	private void initializeGameStatusListener() {
		game.gameStatusProperty().addListener((event) -> 
		{
			
			GameStatus status = game.gameStatusProperty().get();
			if (status == GameStatus.BLACK_WON || status == GameStatus.WHITE_WON) {
				statusLabel.fontProperty().set(Font.font(null, FontWeight.BOLD, 12));
			}
			statusLabel.setText(status.toString());
		});
	}
	private void initializePlayerChange() {
		game.currentPlayerProperty().addListener((event) ->
		{
			//System.out.println(event);
			Player player = game.currentPlayerProperty().get();
			playerLabel.setText(player.getSquareState() + " (" + player.getName() + ") to move");
			player.getSquareState();
			if (player instanceof ComputerPlayer) {
				final Task<Location> computerMoveTask = new Task<Location>() {
					@Override
					public Location call() throws Exception {
						game.resetMoveStartTime();
						return ((ComputerPlayer)player).getMove(game.getSimpleBoard(),
								player.getSquareState() == SquareState.WHITE ? 
										SimpleBoard.PLAYER_WHITE :
										SimpleBoard.PLAYER_BLACK);
					}
				};
				computerMoveTask.setOnSucceeded(new EventHandler<WorkerStateEvent>() {
					@Override
					public void handle(WorkerStateEvent event) {
						Location l = computerMoveTask.getValue();
						//System.out.println("handle make move:" + l);
						game.makeMove(player, l);
					}
				});
				executorService.submit(computerMoveTask);
			}
		});
	}
	
	private void setUpSquares() throws IOException {
		int squareSize = Square.SIZE_10;
		if (game.getWidth() == 20) {
			squareSize = Square.SIZE_20;
		}
		if (board.getScene() != null) {
			board.getScene().getWindow().setWidth(game.getWidth() * squareSize + 22);
			board.getScene().getWindow().setHeight(game.getHeight() * squareSize + 115);
		}
		for (int row = 0; row < game.getHeight(); row++) {
			for (int col = 0; col < game.getWidth(); col++) {
				final Square square = new Square(row, col, game, squareSize);
				board.getChildren().add(square);
				squares[row][col] = square;
			}
		}
	}
	public void setBoardGridPane(GridPane boardGridPane) {
		this.board = boardGridPane;
	}
	
	@FXML
	public void newGame20() throws IOException {
		newGame(20);
	}
	@FXML
	public void newGame() throws IOException {
		newGame(10);
	}
	
	private void newGame(int size) throws IOException {
		if (board != null) {
			board.getChildren().clear();
			board.setMinWidth(20 * size);
			board.setMinHeight(20 * size);
		}
		String[] playerNames = new String[2];
		playerNames[0] = (String) this.playerWChoiceBox.getSelectionModel().getSelectedItem();
		playerNames[1] = (String) this.playerBChoiceBox.getSelectionModel().getSelectedItem();
		Player[] players = new Player[2];
		
		for (int i = 0; i < 2; i++) {
			if (playerNames[i].equals("Human")) {
				players[i] = new Player("Human");
			} else if (playerNames[i].equals("StudentStrategy")) {
				players[i] = new ComputerPlayer(new StudentStrategy());
			} else if (playerNames[i].equals("OpponentWeak")) {
				players[i] = new ComputerPlayer(new ComputerStrategyOpponent(ComputerStrategyOpponent.WEAK));
			} else if (playerNames[i].equals("OpponentStrong")) {
				players[i] = new ComputerPlayer(new ComputerStrategyOpponent(ComputerStrategyOpponent.ADVANCED));
			} else if (playerNames[i].equals("OpponentWinner")) {
				players[i] = new ComputerPlayer(new ComputerStrategyOpponent(ComputerStrategyOpponent.WINNER));
			}
		}
		Player playerW = players[0];
		Player playerB = players[1];
		//Player playerO = new Player("human");
		//Player playerO = new ComputerPlayer(new DummyStrategy());
		//Player playerO = new ComputerPlayer(new ComputerStrategyWeak());
		//Player playerX = new ComputerPlayer(new StudentStrategy());
		//Player playerW = new Player("human");
		//Player playerB = new ComputerPlayer(new DummyStrategy());
		//Player playerB = new ComputerPlayer(new ComputerStrategyWeak());
		
		Game game = new Game(playerW, playerB, size, size);
		this.game = game;
		squares = new Square[game.getHeight()][game.getWidth()];
		initialize();

		// change current player to start event handler
		game.start();
	}
}
