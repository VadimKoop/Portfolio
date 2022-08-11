package gomoku;

import gomoku.Game.GameStatus;
import gomoku.Game.SquareState;
import gomoku.GameHistory.GameHistoryEntry;
import gomoku.strategies.StudentStrategyExample;

import java.io.File;
import java.io.IOException;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.ThreadFactory;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.concurrent.Task;
import javafx.concurrent.WorkerStateEvent;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.HBox;
import javafx.scene.text.Font;
import javafx.scene.text.FontWeight;
import javafx.stage.FileChooser;

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
	
	// for tournament
	// TODO: a better solution for that
	private int numberOfGames = 1;
	private boolean switchPlayers = false;
	private boolean bothSizes = false;
	private Player player1 = null;
	private Player player2 = null;
	
	public GomokuController(Game game, int numberOfGames, boolean switchSides, boolean bothSizes) {
		this(game);
		this.player1 = game.getPlayerWhite();
		this.player2 = game.getPlayerBlack();
		this.numberOfGames = numberOfGames;
		this.switchPlayers = switchSides;
		this.bothSizes = bothSizes;
	}
	
	public GomokuController(Game game) {
		setGame(game);


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
	
	@FXML
	private HBox replayHBox;
	
	@FXML
	private Button saveReplay;
	
	/**
	 * Sets up the game (initializes squares array).
	 * @param game
	 */
	private void setGame(Game game) {
		this.game = game;
		squares = new Square[game.getHeight()][game.getWidth()];
	}
	
	public void initialize() throws IOException {
		System.out.println("init");
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
		List<String> strategies = Main.getStrategies();
		System.out.println(strategies);
		if (strategies != null) {
			for (String s : strategies) {
				choices.add(s);
			}
		}
		
		choices.add("OpponentWeak");
		choices.add("OpponentStrong");
		choices.add("OpponentWinner2013");
		choices.add("OpponentWinner2014");
		return choices;
	}
	
	private void initializeGameStatusListener() {
		game.gameStatusProperty().addListener((event) -> 
		{
			
			GameStatus status = game.gameStatusProperty().get();
			saveReplay.visibleProperty().set(false);
			if (status == GameStatus.BLACK_WON || status == GameStatus.WHITE_WON) {
				statusLabel.fontProperty().set(Font.font(null, FontWeight.BOLD, 12));
				if (!game.inReplayMode().get()) {
					// let's show "save replay" only if not in replay mode already
					saveReplay.visibleProperty().set(true);
				}
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
				computerMoveTask.setOnSucceeded(event1 -> {
                    Location l = computerMoveTask.getValue();
                    //System.out.println("handle make move:" + l);
                    game.makeMove(player, l);
                });
				executorService.submit(computerMoveTask);
			}
		});
		// handler is defined, let's start the game
		game.start();
	}
	
	private void setUpSquares() throws IOException {
		int squareSize = Square.SIZE_10;
		if (game.getWidth() == 20) {
			squareSize = Square.SIZE_20;
		}
		if (board != null && board.getScene() != null) {
			board.getScene().getWindow().setWidth(game.getWidth() * squareSize + 22);
			board.getScene().getWindow().setHeight(game.getHeight() * squareSize + 115);
		}
		board.getChildren().clear();
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
			} else if (playerNames[i].equals("OpponentWeak")) {
				players[i] = new ComputerPlayer(new ComputerStrategyOpponent(ComputerStrategyOpponent.WEAK));
			} else if (playerNames[i].equals("OpponentStrong")) {
				players[i] = new ComputerPlayer(new ComputerStrategyOpponent(ComputerStrategyOpponent.ADVANCED));
			} else if (playerNames[i].equals("OpponentWinner2013")) {
				players[i] = new ComputerPlayer(new ComputerStrategyOpponent(ComputerStrategyOpponent.WINNER));
			} else if (playerNames[i].equals("OpponentWinner2014")) {
				players[i] = new ComputerPlayer(new ComputerStrategyOpponentNetsajev());
			} else {
				// tournament 2014/2015
				// let's load from gomoku.players
				ClassLoader classLoader = GomokuController.class.getClassLoader();
				try {
					Class<?> studentClass = classLoader.loadClass("gomoku.strategies." + playerNames[i]);
					Object s = studentClass.newInstance();
					System.out.println(s);
					ComputerStrategy cs = (ComputerStrategy)s;
					Player p = new ComputerPlayer(cs);
					players[i] = p;
				} catch (ClassNotFoundException e) {
					System.out.println("Class " + playerNames[i] + " not found.");
					System.exit(1);
				} catch (InstantiationException e) {
					e.printStackTrace();
				} catch (IllegalAccessException e) {
					e.printStackTrace();
				}
			}
		}
		Player playerW = players[0];
		Player playerB = players[1];
		
		Game game = new Game(playerW, playerB, size, size);
		setGame(game);
		initialize();

		// change current player to start event handler
		// this is done when handler is set up
		//game.start();
	}

	private GameHistory gameHistory = null;
	/**
	 * Next move will be with the given index,
	 * previous will be -1
	 */
	private int gameHistoryIndex = 0;
	
	public void loadReplay() {
		FileChooser fileChooser = new FileChooser();
		fileChooser.setTitle("Open replay file");
		File file = fileChooser.showOpenDialog(board.getScene().getWindow());
		if (file != null) {
			try {
				gameHistory = GameHistory.load(file);
				System.out.println(gameHistory);
				/*replayStrategyWhite = new ReplayStrategy(gh, SquareState.WHITE);
				Player playerWhite = new ComputerPlayer(replayStrategyWhite);
				replayStrategyBlack = new ReplayStrategy(gh, SquareState.BLACK);
				Player playerBlack = new ComputerPlayer(replayStrategyBlack);
				Game game = new Game(playerWhite, playerBlack, gh.getBoardSize(), gh.getBoardSize());
				setGame(game);
				initialize();
				 */
				Game game = new Game(new Player(gameHistory.getPlayerWhiteName() + " (Replay white)"), 
						new Player(gameHistory.getPlayerBlackName() + " (Replay black)"), 
						gameHistory.getBoardSize(), gameHistory.getBoardSize());
				game.setInReplayMode(true);
				setGame(game);
				initialize();
				replayHBox.visibleProperty().set(true);
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
	}
	
	@FXML
	public void nextMove() {
		try {
			GameHistoryEntry e = gameHistory.getEntries().get(gameHistoryIndex);
			game.makeMove(e.getMove());
			gameHistoryIndex++;
		} catch (IndexOutOfBoundsException e) {
			System.out.println("there is no next move");
		}
	}
	
	public void firstMove() {
		// let's take back all the moves
		while (gameHistoryIndex > 0) previousMove();
	}
	
	public void previousMove() {
		try {
			if (gameHistoryIndex <= 0) {
				throw new IndexOutOfBoundsException();
			}
			gameHistoryIndex--;
			GameHistoryEntry e = gameHistory.getEntries().get(gameHistoryIndex);
			game.undoMove(e.getMove());
		} catch (IndexOutOfBoundsException e) {
			System.out.println("there is no previous move");
		}
		
	}
	public void play() {
		System.out.println("todo: implement");
	}
	public void lastMove() {
		while (gameHistoryIndex < gameHistory.getEntries().size()) nextMove();
	}
	
	public void saveReplay() {
		FileChooser fileChooser = new FileChooser();
		fileChooser.setTitle("Save replay file");
		File file = fileChooser.showSaveDialog(board.getScene().getWindow());
		if (file != null) {
			game.writeHistoryLog(file.getPath());
		}
	}
}
