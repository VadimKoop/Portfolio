package gomoku;

import gomoku.strategies.StudentStrategyExample;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Set;

import javafx.beans.Observable;
import javafx.beans.binding.ObjectBinding;
import javafx.beans.property.ReadOnlyBooleanWrapper;
import javafx.beans.property.ReadOnlyObjectProperty;
import javafx.beans.property.ReadOnlyObjectWrapper;

/**
 * Holds the state of the game.
 * @author Ago
 */
public class Game {
	
	/**
	 * The status of the game. 
	 * Its either open (game goes on),
	 * one of the players has won,
	 * or a draw (board is full).
	 * @author Ago
	 */
	public enum GameStatus {
		WHITE_WON {
			@Override
			public String toString() {
				return "White won";
			}
		},
		BLACK_WON {
			@Override
			public String toString() {
				return "Black won";
			}
		},
		DRAW {
			@Override
			public String toString() {
				return "Game drawn";
			}
		},
		OPEN {
			@Override
			public String toString() {
				return "Game on";
			}
		};
		
		/**
		 * Used to translate player on the simple board
		 * into winning state.
		 * @param value SimpleBoard.board cell value
		 * @return If the given argument indicates a player,
		 * corresponding player's winning state is returned,
		 * OPEN otherwise.
		 */
		public static GameStatus getWinnerFromSimpleBoardValue(int value) {
			if (value == SimpleBoard.PLAYER_WHITE) return WHITE_WON;
			if (value == SimpleBoard.PLAYER_BLACK) return BLACK_WON;
			return OPEN;
		}
	}
	
	public static enum SquareState {
		WHITE, BLACK, EMPTY {
			@Override
			public String toString() {
				return "-";
			}
		}
	}
	
	// Score related stuff	
	/**
	 * Holds information about game results.
	 * @author Ago
	 *
	 */
	static class PlayerScoreValue {
		private int wins = 0;
		private int losses = 0;
		private int draws = 0;
		private static final int WIN_POINTS = 2; 
		private static final int DRAW_POINTS = 1; 
		
		public int getGameCount() {
			return wins + losses + draws;
		}
		
		public int getPoints() {
			return WIN_POINTS * wins + DRAW_POINTS * draws;
		}
		
		public float getPointsPercentage() {
			return ((float) getPoints()) * 100/ (getGameCount() * WIN_POINTS); 
		}
	}
	
	/**
	 * Used as the key in PlayerScore hashmap.
	 * @author Ago
	 *
	 */
	static class PlayerScoreConf {
		/**
		 * Value ("color") of the player
		 * who's score it is.
		 */
		private SquareState playerValue;
		/**
		 * Opponent player.
		 */
		private Player opponent;
		/**
		 * Board size.
		 */
		private int boardSize;
		
		public PlayerScoreConf(SquareState playerValue, int boardSize,
				Player opponent) {
			this.playerValue = playerValue;
			this.boardSize = boardSize;
			this.opponent = opponent;
		}
		
		@Override
		public boolean equals(Object obj) {
			if (this == obj) return true;
			if (!(obj instanceof PlayerScoreConf)) return false;
			PlayerScoreConf c = (PlayerScoreConf)obj;
			return playerValue == c.playerValue &&
					boardSize == c.boardSize &&
					// compare names as we will have different instances
					// between different games, but we want
					// the scores to reflect the players over
					// all the games
					opponent.getName().equals(c.opponent.getName());
		}
		
		@Override
		public int hashCode() {
			//return opponent.getName().hashCode() * playerValue.hashCode() * boardSize;
			return (opponent == null ? 1 : opponent.getName().hashCode()) *
					(playerValue == null ? 1 : playerValue.hashCode()) *
					(boardSize + 1);
		}
	}
	
	/**
	 * Scores for one player
	 * @author Ago
	 *
	 */
	static class PlayerScore {
		private Player player;
		private HashMap<PlayerScoreConf, PlayerScoreValue> scoreValues = new HashMap<PlayerScoreConf, PlayerScoreValue>();
		
		public PlayerScore(Player player) {
			this.player = player;
		}
		
		public PlayerScoreValue getValue(SquareState playerValue, int boardSize, Player opponent) {
			PlayerScoreConf c = new PlayerScoreConf(playerValue, boardSize, opponent);
			PlayerScoreValue v = scoreValues.get(c);
			if (v == null) {
				v = new PlayerScoreValue();
				scoreValues.put(c, v);
			}
			return v;
		}
		
		public void addDraw(SquareState playerValue, int boardSize, Player opponent) {
			getValue(playerValue, boardSize, opponent).draws++;
		}
		public void addWin(SquareState playerValue, int boardSize, Player opponent) {
			getValue(playerValue, boardSize, opponent).wins++;
		}
		public void addLoss(SquareState playerValue, int boardSize, Player opponent) {
			getValue(playerValue, boardSize, opponent).losses++;
		}
	}
	
	/**
	 * A wrapper to keep all the scores.
	 * All the methods are static, technically
	 * all this functionality could be directly
	 * under Game class.
	 * @author Ago
	 *
	 */
	static class PlayerScores {
		/**
		 * Scores for every player.
		 */
		static HashMap<Player, PlayerScore> scores = new HashMap<Player, PlayerScore>();
		
		public static PlayerScore getPlayerScore(Player player) {
			PlayerScore playerScore = scores.get(player);
			if (playerScore == null) {
				playerScore = new PlayerScore(player);
				scores.put(player, playerScore);
			}
			return playerScore;
		}
		
		/**
		 * Adds a draw score.
		 * @param player1 First player
		 * @param player2 Second player
		 * @param boardSize Board size (square size presumed)
		 */
		public static void addDraw(Player player1, Player player2, int boardSize) {
			PlayerScore ps = getPlayerScore(player1);
			ps.addDraw(player1.getSquareState(), boardSize, player2);
			ps = getPlayerScore(player2);
			ps.addDraw(player2.getSquareState(), boardSize, player1);
		}
		
		/**
		 * Adds a winning score.
		 * @param winner Winner player
		 * @param loser Loser player
		 * @param boardSize Board size (square size presumed)
		 */
		public static void addWin(Player winner, Player loser, int boardSize) {
			PlayerScore ps = getPlayerScore(winner);
			ps.addWin(winner.getSquareState(), boardSize, loser);
			ps = getPlayerScore(loser);
			ps.addLoss(loser.getSquareState(), boardSize, winner);
		}
		
		/**
		 * Prints out player score.
		 * The format is as follows:
		 * [player] stat:
		 *   [player color] [opponent name] [board]: ... 
		 *   ... [wins]-[draws]-[losses] / [games], p:[points], p%:[points percentage]
		 *   
		 *   
		 *   
		 * [player] - player name whose score is shown
		 * [player color] - the color of pieces for the player
		 * [opponent name] - the opponent name
		 * [board] - board size (square size presumed)
		 * 
		 * The following information is all for
		 * when the player was playing with [player color] pieces
		 * against [opponent name] on the board with size [board]:
		 *  
		 * [wins] - number of won games
		 * [draws] - number of drawn games
		 * [losses] - number of lost games
		 * [games] - total number of games
		 * [points] - total number of points (win = 2p, draw = 1p)
		 * [points percentage] - the percentage of points out of
		 * theoretical maximum (if all the games were won).
		 * 
		 * @param player Player whose score is to be shown
		 */
		public static void printPlayerScore(Player player) {
			PlayerScore ps = scores.get(player);
			if (ps == null) {
				System.out.println("No information about player:" + player);
				return;
			}
			//System.out.println("[player] stat:");
			//System.out.println("  [player color] [opponent name] [board]: [wins]-[draws]-[losses] / [games], p:[points], p%:[points percentage]");
			System.out.println(player + " stat:");
			for (PlayerScoreConf key : ps.scoreValues.keySet()) {
				PlayerScoreValue psv = ps.scoreValues.get(key);
				System.out.format("  %s %10s b:%d:",
						key.playerValue, key.opponent, key.boardSize);
				System.out.format(" %d-%d-%d / %d, p:%d, p%%:%2.2f\n", 
						psv.wins, psv.draws, psv.losses, psv.getGameCount(), psv.getPoints(), psv.getPointsPercentage());
			}
		}
	}
	
	
	/**
	 * Number of pieces in a "row" for win.
	 */
	public static final int WIN_COUNT = 5;
	
	private Player playerWhite;
	private Player playerBlack;
	
	private ReadOnlyObjectWrapper<Player> currentPlayer;
	private ReadOnlyObjectWrapper<GameStatus> gameStatus;

	/**
	 * Whether we are in replay mode.
	 */

	private ReadOnlyBooleanWrapper inReplayMode = new ReadOnlyBooleanWrapper(false);
	
	
	// Board related stuff
	//private final Board board;
	private final int width;
	private final int height;
	private final List<List<ReadOnlyObjectWrapper<SquareState>>> board;
	
	/**
	 * The time when the last move was done. 
	 * Used to measure move duration.
	 */
	private long lastMoveTime = System.nanoTime();
	
	/**
	 * Whether moves are logged.
	 */
	private boolean gameHistoryLogging = true;
	private String gameHistoryLogFilename = null;
	private GameHistory gameHistory = null;
	
	
	/**
	 * Initialization with the default players:
	 * human vs student strategy
	 * @param height
	 * @param width
	 */
	public Game(int height, int width) {
		// FIXME: should be unified with GomokuController.newGame()
		this(new Player("Human"), new ComputerPlayer(new StudentStrategyExample()), height, width);
	}

	public Game(Player playerWhite, Player playerBlack, int height, int width) {
		this(playerWhite, playerBlack, height, width, null);
	}
	
	public Game(Player playerWhite, Player playerBlack, int height, int width, SimpleBoard initialBoard) {
		this.playerWhite = playerWhite;
		this.playerBlack = playerBlack;
		// set square states for the players
		playerWhite.setSquareState(SquareState.WHITE);
		playerBlack.setSquareState(SquareState.BLACK);
		
		currentPlayer = new ReadOnlyObjectWrapper<Player>(this, "currentPlayer", null);
		
		
		
		// init the board
		this.width = width;
		this.height = height;
		List<Observable> allSquares = new ArrayList<Observable>();
		board = new ArrayList<List<ReadOnlyObjectWrapper<SquareState>>>(height);
		for (int i = 0; i < height; i++) {
			List<ReadOnlyObjectWrapper<SquareState>> row = new ArrayList<ReadOnlyObjectWrapper<SquareState>>(this.width);
			for (int j = 0; j < width; j++) {
				SquareState s = SquareState.EMPTY;
				if (initialBoard != null) {
					try {
						int val = initialBoard.getBoard()[i][j];
						switch (val) {
							case SimpleBoard.PLAYER_WHITE:
								s = SquareState.WHITE;
								break;
							case SimpleBoard.PLAYER_BLACK:
								s = SquareState.BLACK;
								break;
						}
							
					} catch (Exception e) {
						// just fail silently, empty square
					}
				}
				ReadOnlyObjectWrapper<SquareState> square = new ReadOnlyObjectWrapper<SquareState>(s);
				row.add(square);
				allSquares.add(square);
			}
			board.add(row);
		}
		//System.out.println(board.squareStateProperty(new Location(0, 0)));
		gameStatus = new ReadOnlyObjectWrapper<GameStatus>(this, "gameStatus", GameStatus.OPEN);
		
		// binding for game status
		ObjectBinding<GameStatus> gameStatusBinding = new ObjectBinding<GameStatus>() {

			{
				super.bind(allSquares.toArray(new Observable[allSquares.size()]));
			}
			
			@Override
			protected GameStatus computeValue() {
				return getGameStatus();
			}
			
		};
		
		// when the game status changes, we want to keep track of scores
		gameStatus.addListener((event) -> {
			int boardSize = height;
			if (gameStatus.get() == GameStatus.DRAW) {
				// draw
				System.out.println("draw");
				PlayerScores.addDraw(playerWhite, playerBlack, boardSize);
			} else {
				if (gameStatus.get() == GameStatus.WHITE_WON) {
					PlayerScores.addWin(playerWhite, playerBlack, boardSize);
				} else if (gameStatus.get() == GameStatus.BLACK_WON) {
					PlayerScores.addWin(playerBlack, playerWhite, boardSize);
				}
			}
			if (gameStatus.get() != GameStatus.OPEN) {
				// if game ended, let's print out the scores for both players
				PlayerScores.printPlayerScore(playerWhite);
				PlayerScores.printPlayerScore(playerBlack);
			}
			if (gameHistoryLogging) {
				gameHistory.setGameStatus(gameStatus.get());
				if (gameHistoryLogFilename != null &&
						gameStatus.get() != GameStatus.OPEN) {
					writeHistoryLog(gameHistoryLogFilename);
				}
				//gameHistory.save("");
			}
		});
		if (gameHistoryLogging) {
			gameHistory = new GameHistory(this);
		}
		gameStatus.bind(gameStatusBinding);
	}
	
	/**
	 * Used to reset the start time
	 * which is used to measure the duration of move.
	 */
	public void resetMoveStartTime() {
		lastMoveTime = System.nanoTime();
	}
	
	public void start(Player startingPlayer) {
		currentPlayer.set(startingPlayer);
	}
	
	public void start() {
		// player with O starts
		start(playerWhite);
	}
	
	public int getHeight() {
		return height;
	}
	
	public int getWidth() {
		return width;
	}
	
	public SimpleBoard getSimpleBoard() {
		Location lastMove = null;
		int[][] simpleBoard = new int[height][width];
		for (int row = 0; row < height; row++) {
			for (int col = 0; col < width; col++) {
				SquareState state = squareStateProperty(row, col).get();
				simpleBoard[row][col] = state == SquareState.EMPTY ? SimpleBoard.EMPTY :
					(state == SquareState.WHITE ? SimpleBoard.PLAYER_WHITE : SimpleBoard.PLAYER_BLACK);
			}
		}
		SimpleBoard board = new SimpleBoard(simpleBoard);
		return board;
	}
	
	public void makeMove(Location location) {
		if (!(currentPlayer.get() instanceof ComputerPlayer)) {
			// only human player can use this shortcut
			makeMove(currentPlayer.get(), location);
		} else {
			throw new IllegalArgumentException("It's computer's turn, pass the player to makeMove()");
		}
	}
	
	public void makeMove(Player player, int row, int column) {
		makeMove(player, new Location(row, column));
	}
	
	public void makeMove(Player player, Location location) {
		//System.out.println("make move " + player + "@" + location);
		if (player != currentPlayer.get()) {
			// TODO: specific exceptions?
			throw new IllegalArgumentException("It is not " + player + "'s turn!");
		}
		if (location == null) {
			throw new IllegalArgumentException("Location is not set");
		}
		final ReadOnlyObjectWrapper<SquareState> squareState = board.get(location.getRow()).get(location.getColumn());
		
		if (squareState.get() != SquareState.EMPTY) {
			throw new IllegalArgumentException(location + " is already occupied by " + squareState.get());
		}
		if (gameHistoryLogging) {
			// game history logging
			gameHistory.addMove(player, location);
			//gameHistory.save("");
		}
		
		if (player == playerWhite) {
			squareState.set(SquareState.WHITE);
		} else {
			squareState.set(SquareState.BLACK);
		}
		
		if (gameStatus.get() == GameStatus.OPEN) {
			currentPlayer.set(player == playerBlack ? playerWhite : playerBlack);
		}
		long currentTime = System.nanoTime();
		System.out.format("%s move: %s duration %6.4f Player (%s)\n",
				player == playerWhite ? "White" : "Black",
				location, ((currentTime - lastMoveTime) / 1000000000.0),
				player);
		lastMoveTime = currentTime;
	}
	
	
	/**
	 * Used by the replay.
	 * @param location Location on the board to "undo"
	 */
	public void undoMove(Location location) {
		final ReadOnlyObjectWrapper<SquareState> squareState = board.get(location.getRow()).get(location.getColumn());
		if (squareState.get() == SquareState.EMPTY) {
			throw new IllegalArgumentException("Square is already empty");
		}
		Player player = null;
		if (squareState.get() == SquareState.BLACK) {
			player = playerBlack;
		} else {
			player = playerWhite;
		}
		if (player instanceof ComputerPlayer) {
			throw new IllegalArgumentException("Computer player cannot undo its move");
		}
		squareState.set(SquareState.EMPTY);
		currentPlayer.set(player);
	}
	
	SimpleBoard simpleBoard;
	int[][] simpleBoardBoard;
	private GameStatus getGameStatus() {
		simpleBoard = getSimpleBoard();
		simpleBoardBoard = simpleBoard.getBoard();
		int rows = simpleBoard.getHeight();
		int cols = simpleBoard.getWidth();
		int winningPlayer = 0;
		boolean hasEmpty = false;
		for (int row = 0; row < rows; row++) {
			for (int col = 0; col < cols; col++) {
				int player = simpleBoardBoard[row][col];
				if (player != SimpleBoard.EMPTY) {
					if (row <= rows - WIN_COUNT)  {// down
						if (countFive(row, col, 1, 0)) { // just down
							return GameStatus.getWinnerFromSimpleBoardValue(player);
						}
						if (col > 3 && countFive(row, col, 1, -1)) { // diag down and left
							return GameStatus.getWinnerFromSimpleBoardValue(player);
						}
					}
					if (col <= cols - WIN_COUNT) { // right
						if (countFive(row, col, 0, 1)) { // just right
							return GameStatus.getWinnerFromSimpleBoardValue(player);
						}
						if (row <= rows - WIN_COUNT && countFive(row, col, 1, 1)) { // right and down
							return GameStatus.getWinnerFromSimpleBoardValue(player);
						}
					}
				} else {
					hasEmpty = true;
				}
			}
		}
		if (hasEmpty) return GameStatus.OPEN;
		return GameStatus.DRAW;
	}
	
	private boolean countFive(int row, int column, int deltaRow, int deltaColumn) {
		int player = simpleBoardBoard[row][column];
		for (int i = 1; i < WIN_COUNT; i++) {
			if (simpleBoardBoard[row + i * deltaRow][column + i * deltaColumn] != player) return false;
		}
		return true;
	}
	
	
	
	public ReadOnlyObjectProperty<GameStatus> gameStatusProperty() {
		return gameStatus.getReadOnlyProperty();
	}
	
	public ReadOnlyObjectProperty<Player> currentPlayerProperty() {
		return currentPlayer.getReadOnlyProperty();
	}
	
	public ReadOnlyObjectProperty<SquareState> squareStateProperty(int row, int column) {
		return squareStateProperty(new Location(row, column));
	}
	
	public ReadOnlyObjectProperty<SquareState> squareStateProperty(Location location) {
		return board.get(location.getRow()).get(location.getColumn()).getReadOnlyProperty();
	}
	
	public Player getPlayerWhite() {
		return playerWhite;
	}
	public Player getPlayerBlack() {
		return playerBlack;
	}
	
	public void setHistoryLogFilename(String filename) {
		this.gameHistoryLogging = true;
		this.gameHistoryLogFilename = filename;
	}
	
	public void writeHistoryLog(String filename) {
		gameHistory.save(filename);
	}
	
	
	public void setInReplayMode(boolean inReplayMode) {
		this.inReplayMode.set(inReplayMode);
	}
	
	/**
	 * Whether we are in replay mode.
	 * @return True if in replay mode.
	 */
	public ReadOnlyBooleanWrapper inReplayMode() {
		return inReplayMode;
	}
	
}
