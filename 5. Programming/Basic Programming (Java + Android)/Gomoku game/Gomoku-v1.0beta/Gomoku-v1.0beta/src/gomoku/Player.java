package gomoku;

import gomoku.Game.SquareState;

public class Player {
	private final String name;
	private SquareState squareState;
	
	public Player(String name) {
		this.name = name;
	}

	public Player(String name, SquareState squareState) {
		this.name = name;
		this.squareState = squareState;
	}
	
	public final String getName() {
		return name ;
	}

	public void setSquareState(SquareState squareState) {
		this.squareState = squareState;
	}
	/**
	 * Indicates the square state 
	 * for this player's moves on the board.
	 * @return
	 */
	public final SquareState getSquareState() {
		return squareState;
	}
	
	@Override
	public String toString() {
		return name ;
	}
	
	// simplified case
	@Override
	public boolean equals(Object obj) {
		if (this == obj) return true;
		if (!(obj instanceof Player)) return false;
		return name.equals(((Player)obj).name);
	}
	
	@Override
	public int hashCode() {
		return name.hashCode();
	}
}
