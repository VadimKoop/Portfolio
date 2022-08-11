package gomoku;

public class ComputerPlayer extends Player {
	/**
	 * Strategy object which is responsible
	 * of choosing the best move for the computer.
	 */
	private ComputerStrategy strategy;
	
	public ComputerPlayer(String name, ComputerStrategy strategy) {
		super(name);
		this.strategy = strategy;
	}
	
	public ComputerPlayer(ComputerStrategy strategy) {
		super(strategy.getName());
		this.strategy = strategy;
	}
	
	public Location getMove(SimpleBoard board, int player) {
		return this.strategy.getMove(board, player);
	}
}
