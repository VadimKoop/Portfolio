package gomoku;

/**
 * @author Ago
 *
 * Interface for computer strategy.
 */
public interface ComputerStrategy {
	/**
	 * Takes the game state and return the best move
	 * @param board Board state
	 * @param player Player indicator. Which player's
	 * strategy it is. Possible values: SimpleBoard.PLAYER_*.
	 * @return A location where to make computer's move.
	 * 
	 * @see SimpleBoard
	 * @see Location
	 */
	public Location getMove(SimpleBoard board, int player);
	
	/**
	 * Name will be shown during the play.
	 * This method should be overridden to
	 * show student's name.
	 * @return Name of the player
	 */
	public String getName();
}
