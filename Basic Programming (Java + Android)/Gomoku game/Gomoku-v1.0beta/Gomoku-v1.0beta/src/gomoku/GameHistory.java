package gomoku;

import gomoku.Game.GameStatus;
import gomoku.Game.SquareState;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.time.Duration;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;
import java.util.Scanner;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * Handles game history
 * @author Ago
 *
 */
public class GameHistory {
	static class GameHistoryEntry {
		/**
		 * Format to be used in log file.
		 */
		public static final String FORMAT = "%s %2d,%2d %s %2.4f\n";
		public static final int DURATION_NULL = -1;
		//private Player player;
		private SquareState squareState;
		private LocalDateTime start;
		/**
		 * Duration of the move. -1 ==> not set.
		 */
		private long duration = DURATION_NULL;
		private Location move;
		public GameHistoryEntry(SquareState squareState, Location move, LocalDateTime start, long duration) {
			//this.player = player;
			this.squareState = squareState;
			this.move = move;
			this.start = start;
			this.duration = duration;
		}
		
		public void setDuration(double duration) {
			this.duration = (long) (duration * 1_000_000_000);
		}
		
		public void end() {
			if (duration == DURATION_NULL) {
				// let's set duration based on current time and start time
				duration = Duration.between(start, LocalDateTime.now()).toNanos();
			}
		}
		public long getDuration() {
			return duration;
		}

		public Location getMove() {
			return move;
		}
	}
	
	private List<GameHistoryEntry> entries = new ArrayList<GameHistoryEntry>();
	
	private Player playerWhite;
	private String playerWhiteName;
	private Player playerBlack;
	private String playerBlackName;
	private LocalDateTime gameStart;
	private int boardSize;
	
	/**
	 * The result of the game.
	 */
	private GameStatus gameStatus = GameStatus.OPEN;
	
	/**
	 * Loads game history from File object.
	 * TODO: should do some input check.
	 * @param file File object
	 * @return Game history instance
	 * @throws IOException In case there is a problem reading file
	 */
	public static GameHistory load(File file) throws IOException {
		// let's change the locale for numbers
		Locale.setDefault(Locale.US);
		GameHistory gh = new GameHistory();
		List<String> lines = Files.readAllLines(file.toPath());
		// start: xxx
		Pattern startPattern = Pattern.compile("start:\\s(.*)");
		Matcher startMatcher = startPattern.matcher(lines.get(0));
		if (startMatcher.find()) {
			String startTime = startMatcher.group(1);
			gh.gameStart = LocalDateTime.parse(startTime, DateTimeFormatter.ISO_LOCAL_DATE_TIME);
		}
		// board: 10
		String[] tokens = lines.get(1).split(" ");
		if (tokens.length == 2) {
			gh.boardSize = Integer.parseInt(tokens[1]);
		}
		// player white name
		Pattern playerPattern = Pattern.compile(".\\s(.*)");
		Matcher playerMatcher = playerPattern.matcher(lines.get(2));
		if (playerMatcher.find()) {
			gh.playerWhiteName = playerMatcher.group(1);
		}
		// player black name
		playerMatcher = playerPattern.matcher(lines.get(3));
		if (playerMatcher.find()) {
			gh.playerWhiteName = playerMatcher.group(1);
		}
		// moves
		// W  0, 0 2014-12-28T23:45:36.303 0.0530
		Pattern movePattern = Pattern.compile("(.)\\s+(\\d+),\\s*(\\d+)\\s+([^ ]+)\\s+([\\d.]*)");
		for (int i = 3; i < lines.size() - 1; i++) {
			String line = lines.get(i);
			Matcher moveMatcher = movePattern.matcher(line);
			if (moveMatcher.find()) {
				SquareState squareState = moveMatcher.group(1).equals("B") ? SquareState.BLACK : SquareState.WHITE;
				int row = Integer.parseInt(moveMatcher.group(2));
				int col = Integer.parseInt(moveMatcher.group(3));
				String dtStr = moveMatcher.group(4);
				double duration = Double.parseDouble(moveMatcher.group(5));
				LocalDateTime startTime = LocalDateTime.parse(dtStr, DateTimeFormatter.ISO_LOCAL_DATE_TIME);
				Location move = new Location(row, col);
				GameHistoryEntry e = new GameHistoryEntry(squareState, move, startTime, -1);
				e.setDuration(duration);
				gh.entries.add(e);
			}
			/*
			Scanner scanner = new Scanner(line);
			SquareState squareState = scanner.next().equals("B") ? SquareState.BLACK : SquareState.WHITE;
			int row = scanner.nextInt();
			int col = scanner.nextInt();
			Location move = new Location(row, col);
			String dtStr = scanner.next();
			LocalDateTime startTime = LocalDateTime.parse(dtStr, DateTimeFormatter.ISO_LOCAL_DATE_TIME);
			double duration = scanner.nextDouble();
			GameHistoryEntry e = new GameHistoryEntry(squareState, move, startTime, -1);
			e.setDuration(duration);
			scanner.close();
			
			gh.entries.add(e);
			*/
		}
		return gh;
	}
	
	private GameHistory() {
	}
	
	public GameHistory(Game game) {
		gameStart = LocalDateTime.now();
		playerWhite = game.getPlayerWhite();
		playerWhiteName = playerWhite.getName();
		playerBlack = game.getPlayerBlack();
		playerBlackName = playerBlack.getName();
		System.out.println(game.getHeight());
		boardSize = game.getHeight();
	}
	
	private void endLastMove() {
		// let's set last move duration if not set
		if (entries.size() > 0) {
			GameHistoryEntry e = entries.get(entries.size() - 1);
			// if not set, let's set end time for the last move
			e.end();
		}
	}
	public void addMove(Player player, Location move) {
		LocalDateTime prev = null;
		if (entries.size() > 0) {
			// time from the last move
			GameHistoryEntry eLast = entries.get(entries.size() - 1);
			prev = eLast.start;
		} else {
			prev = gameStart;
		}
		LocalDateTime start = LocalDateTime.now();
		long duration = Duration.between(prev, start).toNanos();
		addMove(player, move, start, duration);
	}
	
	public void setGameStatus(GameStatus gameStatus) {
		this.gameStatus = gameStatus;
	}
	
	public void addMove(Player player, Location move, LocalDateTime start, long duration) {
		SquareState s = null;
		if (player == playerWhite) {
			s = SquareState.WHITE;
		} else if (player == playerBlack) {
			s = SquareState.BLACK;
		} else {
			throw new RuntimeException("player does not exist:" + player);
		}
		addMove(s, move, start, duration);
	}
	
	public void addMove(SquareState squareState, Location move, LocalDateTime start, long duration) {
		GameHistoryEntry e = new GameHistoryEntry(squareState, move, start, duration);
		entries.add(e);
	}
	
	
	public void save(String filename) {
		// let's change the locale for numbers
		Locale.setDefault(Locale.US);
		StringBuilder out = new StringBuilder();
		out.append("start: " + gameStart.format(DateTimeFormatter.ISO_LOCAL_DATE_TIME) + "\n");
		out.append("board: " + boardSize + "\n");
		out.append("W " + playerWhiteName + "\n");
		out.append("B " + playerBlackName + "\n");
		for (GameHistoryEntry e : entries) {
			out.append(String.format(GameHistoryEntry.FORMAT,
					(e.squareState == SquareState.BLACK ? "B" : "W"),
					e.move.getRow(), e.move.getColumn(),
					e.start.format(DateTimeFormatter.ISO_LOCAL_DATE_TIME),
					e.duration == -1 ? 0 : (float)e.duration / 1_000_000_000));
			/*out.append((e.squareState == SquareState.BLACK ? "B" : "W") + " "
					+ e.move.getRow() + "," + e.move.getColumn()
					+ " " + e.start.format(DateTimeFormatter.ISO_LOCAL_DATE_TIME)
					+ " " + e.duration + "\n");
			*/
		}
		out.append("Result: " + gameStatus);
		
		try {
			Files.write(Paths.get(filename), out.toString().getBytes());
		} catch (IOException e1) {
			e1.printStackTrace();
		}
		
		//System.out.println(out);
	}
	
	public List<GameHistoryEntry> getEntries() {
		return entries;
	}

	public String getPlayerWhiteName() {
		return playerWhiteName;
	}

	public String getPlayerBlackName() {
		return playerBlackName;
	}

	public GameStatus getGameStatus() {
		return gameStatus;
	}
	
	public int getBoardSize() {
		return boardSize;
	}


}