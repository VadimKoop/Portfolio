package gomoku;

/**
 * Simple 2-dimensional presentation
 * of the game board.
 * Every cell in the board is represented
 * by one integer, the Values are either
 * PLAYER_BLACK, PLAYER_WHITE or EMPTY.
 * This object also knows the size of the board
 * and the last move (Location object).
 * 
 * @author Ago
 * @see Location
 */
public class SimpleBoard {
	
	/**
	 * Cell value for black player's piece
	 */
	public static final int PLAYER_BLACK = 1;
	
	/**
	 * Cell value for white player's piece 
	 */
	public static final int PLAYER_WHITE = -1;
	
	/**
	 * Empty cell value
	 */
	public static final int EMPTY = 0;
	
	/**
	 * The height of the board.
	 * Indicates the number of rows.
	 */
	private int height = -1;
	/**
	 * The width of the board.
	 * Indicates the number of columns.
	 */
	private int width = -1;
	
	private int[][] board;
	
	/**
	 * Returns the height (number of rows)
	 * of the board.
	 * @return Number of rows
	 */
	public int getHeight() {
		return height;
	}
	
	/**
	 * Returns the width (number of columns)
	 * of the board.
	 * @return Number of columns
	 */
	public int getWidth() {
		return width;
	}
	
	/**
	 * Returns 2-dimensional
	 * array of integers with values
	 * PLAYER_WHITE, PLAYER_BLACK or EMPTY.
	 * The values correspond to
	 * White player's piece,
	 * Black player's piece,
	 * or an empty cell accordingly.
	 * @return
	 */
	public int[][] getBoard() {
		return board;
	}
	
	/**
	 * Constructor to instantiate the board.
	 * @param simpleBoard 2-dimensional
	 * array for the board.
	 */
	public SimpleBoard(int[][] simpleBoard) {
		height = simpleBoard.length;
		if (height > 0) width = simpleBoard[0].length;
		board = simpleBoard;
	}
}
