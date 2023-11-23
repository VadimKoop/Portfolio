package interfaces;

/**
 * General interface for different actions.
 * You should not implement this directly in your code.
 * @author Ago
 *
 */
public interface Action {
	/**
	 * If you prefer you can have every action
	 * to execute itself instead of running
	 * everything from TwitterApplication.
	 * But as this is optional, the body
	 * of this method is empty - you don't
	 * have to implement it.
	 */
	default public void execute() {
	}
}
