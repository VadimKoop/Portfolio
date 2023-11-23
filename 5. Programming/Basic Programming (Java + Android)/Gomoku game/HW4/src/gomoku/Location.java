package gomoku;

/**
 * @author Ago
 * Location on the board, 0-based.
 */
public class Location {
	/**
	 * Index of the row.
	 */
	private final int row;
	/**
	 * Index of the column.
	 */
	private final int column;
	
	public Location(int row, int column) {
		this.row = row;
		this.column = column;
	}
	
	/**
	 * @return Row index
	 */
	public int getRow() {
		return row;
	}
	
	/**
	 * @return Column index
	 */
	public int getColumn() {
		return column;
	}
	
	@Override
	public String toString() {
		return String.format("(%d, %d)", row, column);
	}
}
