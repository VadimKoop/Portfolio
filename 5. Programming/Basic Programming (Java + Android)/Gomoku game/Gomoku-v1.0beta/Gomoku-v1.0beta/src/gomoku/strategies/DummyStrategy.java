package gomoku.strategies;

import gomoku.*;

public class DummyStrategy implements ComputerStrategy {

	@Override
	public Location getMove(SimpleBoard board, int player) {
		// let's operate on 2-d array
		int[][] b = board.getBoard();
		for (int row = 0; row < b.length; row++) {
			for (int col = 0; col < b[0].length; col++) {
				if (b[row][col] == SimpleBoard.EMPTY) {
					// first empty location
					return new Location(row, col);
				}
			}
		}
		return null;
	}

	@Override
	public String getName() {
		return "Dummy strategy";
	}
	
}
