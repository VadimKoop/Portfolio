package gomoku.strategies;

import gomoku.*;

/**
 * Important!
 * This is an example strategy class.
 * You should not overwrite this.
 * Instead make your own class, for example:
 * public class AgoStrategy implements ComputerStrategy
 * 
 * and add all the logic there. The created strategy
 * should be visible under player selection automatically.
 * 
 * This file here might be overwritten in future versions.
 *
 */
public class StudentStrategyExample implements ComputerStrategy {

	@Override
	public Location getMove(SimpleBoard board, int player) {
		// let's operate on 2-d array
		int[][] b = board.getBoard();
		for (int row = b.length - 1; row >= 0; row--) {
			for (int col = b[0].length - 1; col >= 0; col--) {
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
		return "Tudengi nimi";
	}

}
