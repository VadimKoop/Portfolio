package gomoku;

import java.awt.*;
import java.util.ArrayList;
import java.util.Random;

public class StudentStrategy implements ComputerStrategy {

	int turn = 0;
	private static final byte zero = 0;
	private static final byte one = 1;
	private static final byte minusOne = -1;
	final LightLocation right = new LightLocation(zero, one);
	final LightLocation bottom = new LightLocation(one, zero);
	final LightLocation bottomRight = new LightLocation(one, one);
	final LightLocation bottomLeft = new LightLocation(one, minusOne);
	final LightLocation left = new LightLocation(zero, minusOne);
	final LightLocation topLeft = new LightLocation(minusOne, minusOne);
	final LightLocation top = new LightLocation(minusOne, zero);
	final LightLocation topRight = new LightLocation(minusOne, one);
	@Override
	public Location getMove(SimpleBoard board, int player) {
		// let's operate on 2-d array
		int[][] b = board.getBoard();
		turn++;
		if(turn == 1){
			LightLocation firstMove = getBestFirstMove(b, player);
			int moveRow = firstMove.getRow();
			int moveCol = firstMove.getColumn();
			return  new Location(moveRow, moveCol);
		}
		else {
			LightLocation bestMove = getBestMove(b, player);
			int bestRow = bestMove.getRow();
			int bestColumn = bestMove.getColumn();
			return new Location(bestRow, bestColumn);
		}
	}

	@Override
	public String getName() {
		return "Aleksandr Lapuškin & Ilja Kudrjavtsev";
	}

	public LightLocation getBestFirstMove(int[][] board, int player){
		if(player == SimpleBoard.PLAYER_WHITE){
			//We go first
			int length = board.length/2;
			byte byteLength = (byte) length;
			return new LightLocation(byteLength, byteLength);
		}
		else {
			//We go second
			for(int row = 0; row < board.length; row++){
				for(int col = 0; col < board[row].length; col++){
					if(board[row][col] == SimpleBoard.PLAYER_WHITE){
//						byte zero = 0;
//						byte one = 1;
//						byte minusOne = -1;
//						LightLocation right = new LightLocation(zero, one);
//						LightLocation bottom = new LightLocation(one, zero);
//						LightLocation bottomRight = new LightLocation(one, one);
//						LightLocation bottomLeft = new LightLocation(one, minusOne);
//						LightLocation left = new LightLocation(zero, minusOne);
//						LightLocation topLeft = new LightLocation(minusOne, minusOne);
//						LightLocation top = new LightLocation(minusOne, zero);
//						LightLocation topRight = new LightLocation(minusOne, one);
						LightLocation[] vectors = new LightLocation[]{right, bottomRight, bottom, bottomLeft, left, topLeft, top, topRight};
						//Need to make a move near the other player's cell. Chosen at random
						byte neighbourRow;
						byte neighbourCol;

						do {
							int random = new Random().nextInt(8);
							neighbourRow = (byte) row;
							neighbourCol = (byte) col;
							LightLocation vector = vectors[random];
							neighbourRow += vector.getRow();
							neighbourCol += vector.getColumn();
						} while (!(neighbourRow>=0 && neighbourRow<board.length && neighbourCol>=0 && neighbourCol<board.length));
						return new LightLocation(neighbourRow, neighbourCol);
					}
				}
			}
		}
		return null;
	}

	public LightLocation getBestMove(int[][] board, int player){
		int maxFirstPlyValue = -90000;
		LightLocation bestFirstPlyMove = new LightLocation((byte) 0, (byte) 0);
		//Iterate over base board to generate first ply
		//Implement a basic alpha-beta to choose only cells that have a value next to them
		for (int row = 0; row < board.length; row++) {
			for (int col = 0; col < board[row].length; col++) {
				if(board[row][col] == SimpleBoard.EMPTY){
//					boolean areCellsNear = false;
//					int[] neighbourValues = getNeighbourValues(row, col, board);
//					for(int neighbourValue : neighbourValues){
//						if(neighbourValue == player){
//							areCellsNear = true;
//							break;
//						}
//					}
					//Create empty first ply board and fill it with the base board's values
					LightLocation currentFirstPlyMove = new LightLocation((byte) row, (byte) col);
					int[][] firstPlyBoard = new int[board.length][board.length];
					for(int i = 0; i < board.length; i++){
						for(int j = 0; j < board[i].length; j++){
							firstPlyBoard[i][j] = board[i][j];
						}
					}
					firstPlyBoard[row][col] = player;
					int minSecondPlyValue = 90000;
					LightLocation worstSecondPlyMove = new LightLocation((byte) 0, (byte) 0);
					//Iterate over first ply board to generate second ply for each first level ply
					for (int secondRow = 0; secondRow < firstPlyBoard.length; secondRow++) {
						for (int secondCol = 0; secondCol < firstPlyBoard[secondRow].length; secondCol++) {
							if(firstPlyBoard[secondRow][secondCol] == SimpleBoard.EMPTY){
								LightLocation currentSecondPlyMove = new LightLocation((byte) secondRow, (byte) secondCol);

								int[][] secondPlyBoard = new int[firstPlyBoard.length][firstPlyBoard.length];
								for(int i = 0; i < firstPlyBoard.length; i++){
									for(int j = 0; j < firstPlyBoard[i].length; j++){
										secondPlyBoard[i][j] = firstPlyBoard[i][j];
									}
								}
								secondPlyBoard[secondRow][secondCol] = -player;
								//Insert more plies here
								int currentSecondPlyScore = -evaluateBoard(-player, secondPlyBoard);
								if(currentSecondPlyScore < minSecondPlyValue){
									minSecondPlyValue = currentSecondPlyScore;
									worstSecondPlyMove = currentSecondPlyMove;
								}
							}
						}
					}
					int currentFirstPlyScore = minSecondPlyValue;
					if(currentFirstPlyScore > maxFirstPlyValue){
						maxFirstPlyValue = currentFirstPlyScore;
						bestFirstPlyMove = currentFirstPlyMove;
					}
				}
			}
		}
		return bestFirstPlyMove;

	}

	public int evaluateBoard(int player, int[][] board){
		int currentScore = 0;
		//Iterate over given board
		for (int row = 0; row < board.length; row++){
			for (int col = 0; col < board[row].length; col++){
				//If cell is filled by the player being maximized
				if (board[row][col] == player){
					//Get current location and derive sunrays in all directions from it
					LightLocation location = new LightLocation((byte) row, (byte) col);
//					byte zero = 0;
//					byte one = 1;
//					byte minusOne = -1;
//					LightLocation right = new LightLocation(zero, one);
//					LightLocation bottom = new LightLocation(one, zero);
//					LightLocation bottomRight = new LightLocation(one, one);
//					LightLocation bottomLeft = new LightLocation(one, minusOne);
//					LightLocation left = new LightLocation(zero, minusOne);
//					LightLocation topLeft = new LightLocation(minusOne, minusOne);
//					LightLocation top = new LightLocation(minusOne, zero);
//					LightLocation topRight = new LightLocation(minusOne, one);
					LightLocation[] vectors = new LightLocation[]{right, bottomRight, bottom, bottomLeft};
					for (LightLocation currentVector : vectors){
						int[] nextFourValues = getNextFourValues(board, location, currentVector);
						int[] previousFourValues = getNextFourValues(board, location, new LightLocation((byte) -currentVector.getRow(),(byte) -currentVector.getColumn()));
						if(nextFourValues[0] == player && nextFourValues[1] == player && nextFourValues[2] == SimpleBoard.EMPTY && nextFourValues[2] != -player && (nextFourValues[3] == SimpleBoard.EMPTY || nextFourValues[3] == player)){
							currentScore += 100;
							break;
						}
						if((nextFourValues[0] == player && nextFourValues[1] == player && ((nextFourValues[2] == player && nextFourValues[3] == SimpleBoard.EMPTY) || (nextFourValues[2] == SimpleBoard.EMPTY && nextFourValues[3] == player))) ||
						(previousFourValues[0] == player && previousFourValues[1] == player && ((previousFourValues[2] == player && previousFourValues[3] == SimpleBoard.EMPTY) || (previousFourValues[2] == SimpleBoard.EMPTY && previousFourValues[3] == player)))){
							currentScore += 1000;
							break;
						}
						else if((nextFourValues[0] == player && nextFourValues[1] == player && nextFourValues[2] == player && nextFourValues[3] == player) || (previousFourValues[0] == player && previousFourValues[1] == player && previousFourValues[2] == player && previousFourValues[3] == player)
								|| (nextFourValues[0] == player && nextFourValues[1] == player && previousFourValues[0] == player && previousFourValues[1] == player) || (nextFourValues[0] == player && nextFourValues[1] == player && nextFourValues[2] == player && previousFourValues[0] == player)){
							//System.out.println("Found a finished 5 line, starts at " + location + " along vector " + currentVector);
							currentScore += 10000;
							break;
						}
						else {
							currentScore += 0;
						}
					}

//							Second version logic, uses 2-point logic
//							if((nextThreeValues[0] == player && nextThreeValues[1] == SimpleBoard.EMPTY) || (previousThreeValues[0] == player && previousThreeValues[1] == SimpleBoard.EMPTY)){
//								currentScore = 1000;
//							}
//							else if((nextThreeValues[0] == player && nextThreeValues[1] == player && nextThreeValues[2] == SimpleBoard.EMPTY) || (previousThreeValues[0] == player &&  previousThreeValues[1] == player && previousThreeValues[2] == SimpleBoard.EMPTY)){
//								currentScore = 1200;
//							}
//							else if(nextThreeValues[0] == player && nextThreeValues[1] == player && nextThreeValues[2] == player){
//								currentScore = 1500;
//							}
// First logic version, uses 2-point logic
//							if((nextThreeValues[0] == player && nextThreeValues[1] == player && nextThreeValues[2] == player) || (previousThreeValues[0] == player && previousThreeValues[1] == player && previousThreeValues[2] == player) || (nextThreeValues[0] == player && nextThreeValues[1] == player && previousThreeValues[0] == player) || (previousThreeValues[0] == player && previousThreeValues[1] == player && nextThreeValues[0] == player)){
//								//Will be a five-line, block it
//								currentScore = 1500;
//							}
//							else if((nextThreeValues[0] == player && nextThreeValues[1] == player && previousThreeValues[0] == SimpleBoard.EMPTY) || (previousThreeValues[0] == player && previousThreeValues[1] == player && nextThreeValues[0] == SimpleBoard.EMPTY) || (nextThreeValues[0] == player && nextThreeValues[1] == SimpleBoard.EMPTY && previousThreeValues[0] == player) || (nextThreeValues[0] == player && previousThreeValues[0] == player && previousThreeValues[1] == SimpleBoard.EMPTY)
//									|| (nextThreeValues[0] == player && nextThreeValues[1] == player && nextThreeValues[1] == SimpleBoard.EMPTY) || (previousThreeValues[0] == player && previousThreeValues[1] == player && previousThreeValues[2] == SimpleBoard.EMPTY) || (nextThreeValues[0] == player && nextThreeValues[1] == SimpleBoard.EMPTY && previousThreeValues[0] == player && previousThreeValues[1] == SimpleBoard.EMPTY)){
//								//Will be a four line
//								currentScore = 1000;
//							}
//							else if((nextThreeValues[0] == player && nextThreeValues[1] == SimpleBoard.EMPTY && nextThreeValues[2] != 2) || (previousThreeValues[0] == player && previousThreeValues[1] == SimpleBoard.EMPTY && nextThreeValues[2] != 2)){
//								//We have a three-length line with an open end
//								currentScore = 800;
//							}
//							else if(nextThreeValues[0] == SimpleBoard.EMPTY && nextThreeValues[1] == player && previousThreeValues[0] == -player){
//								currentScore = 600;
//							}
//							else if((nextThreeValues[0] == player && nextThreeValues[1] == player && nextThreeValues[2] == -player) || (previousThreeValues[0] == player && previousThreeValues[1] == player && previousThreeValues[2] == -player)) {
//								//Blocking a possible 5-line
//								currentScore = -1400;
//							}
//							else if((nextThreeValues[0] == player && nextThreeValues[1] == -player && (nextThreeValues[2] != 2 || nextThreeValues[2] == SimpleBoard.EMPTY)) || (previousThreeValues[0] == player && previousThreeValues[1] == -player && (previousThreeValues[2] != 2 || previousThreeValues[2] == SimpleBoard.EMPTY))) {
//								//Blocking a possible 4-line
//								currentScore = -900;
//							}
//							else if((nextThreeValues[0] == player && nextThreeValues[1] == -player && nextThreeValues[2] == SimpleBoard.EMPTY) || (previousThreeValues[0] == player && previousThreeValues[1] == -player && previousThreeValues[2] == SimpleBoard.EMPTY)){
//								//Blocking a possible 3-line
//								currentScore = -700;
//							}
//							//Own moves
//							else if((nextThreeValues[0] == -player && nextThreeValues[1] == -player && nextThreeValues[2] == -player) || (previousThreeValues[0] == -player && previousThreeValues[1] == -player && previousThreeValues[2] == -player) || (nextThreeValues[0] == -player && nextThreeValues[1] == -player && previousThreeValues[0] == -player) || (previousThreeValues[0] == -player && previousThreeValues[1] == -player && nextThreeValues[0] == -player)){
//								currentScore = -1500;
//							}
//							else if((nextThreeValues[0] == -player && nextThreeValues[1] == -player && previousThreeValues[0] == SimpleBoard.EMPTY) || (previousThreeValues[0] == -player && previousThreeValues[1] == -player && nextThreeValues[0] == SimpleBoard.EMPTY) || (nextThreeValues[0] == -player && nextThreeValues[1] == SimpleBoard.EMPTY && previousThreeValues[0] == -player) || (nextThreeValues[0] == -player && previousThreeValues[0] == -player && previousThreeValues[1] == SimpleBoard.EMPTY)
//									|| (nextThreeValues[0] == -player && nextThreeValues[1] == -player && nextThreeValues[1] == SimpleBoard.EMPTY) || (previousThreeValues[0] == -player && previousThreeValues[1] == -player && previousThreeValues[2] == SimpleBoard.EMPTY)){
//								//Will be a four line
//								currentScore = -1000;
//							}
//							else if((nextThreeValues[0] == -player && nextThreeValues[1] == SimpleBoard.EMPTY && nextThreeValues[2] != 2) || (previousThreeValues[0] == -player && previousThreeValues[1] == SimpleBoard.EMPTY && nextThreeValues[2] != 2)){
//								//We have a three-length line with an open end
//								currentScore = -900;
//							}
				}
				else if (board[row][col] == -player){
					//Get current location and derive sunrays in all directions from it
					LightLocation location = new LightLocation((byte) row, (byte) col);
//					byte zero = 0;
//					byte one = 1;
//					byte minusOne = -1;
//					LightLocation right = new LightLocation(zero, one);
//					LightLocation bottom = new LightLocation(one, zero);
//					LightLocation bottomRight = new LightLocation(one, one);
//					LightLocation bottomLeft = new LightLocation(one, minusOne);
//					LightLocation left = new LightLocation(zero, minusOne);
//					LightLocation topLeft = new LightLocation(minusOne, minusOne);
//					LightLocation top = new LightLocation(minusOne, zero);
//					LightLocation topRight = new LightLocation(minusOne, one);
					LightLocation[] vectors = new LightLocation[]{right, bottomRight, bottom, bottomLeft};
					for (LightLocation currentVector : vectors){
						int[] nextFourValues = getNextFourValues(board, location, currentVector);
						if(nextFourValues[0] == -player && nextFourValues[1] == SimpleBoard.EMPTY && nextFourValues[2] == SimpleBoard.EMPTY && nextFourValues[3] == SimpleBoard.EMPTY){
							currentScore -= 10;
							break;
						}
						else if(nextFourValues[0] == -player && nextFourValues[1] == -player && nextFourValues[2] == SimpleBoard.EMPTY && nextFourValues[2] != player && (nextFourValues[3] == SimpleBoard.EMPTY || nextFourValues[3] == -player)){
							currentScore -= 100;
							break;
						}
						if(nextFourValues[0] == -player && nextFourValues[1] == -player && ((nextFourValues[2] == -player && nextFourValues[3] == SimpleBoard.EMPTY) || (nextFourValues[2] == SimpleBoard.EMPTY && nextFourValues[3] == -player))){
							currentScore -= 1000;
							break;
						}
						else if(nextFourValues[0] == -player && nextFourValues[1] == -player && nextFourValues[2] == -player && nextFourValues[3] == -player){
							//System.out.println("Found a finished 5 line, starts at " + location + " along vector " + currentVector);
							currentScore -= 10000;
							break;
						}
						else {
							currentScore += 0;
						}
					}
				}
			}
		}
		return currentScore;
	}

	public int[] getNextFourValues(int[][] board, LightLocation start, LightLocation vector){
		byte maxLength = (byte) board.length;
		byte firstRow = start.getRow();
		firstRow += vector.getRow();
		byte secondRow = firstRow;
		secondRow += vector.getRow();
		byte thirdRow = secondRow;
		thirdRow += vector.getRow();
		byte fourthRow = thirdRow;
		fourthRow += vector.getRow();
		byte firstCol = start.getColumn();
		firstCol += vector.getColumn();
		byte secondCol = firstCol;
		secondCol += vector.getColumn();
		byte thirdCol = secondCol;
		thirdCol += vector.getColumn();
		byte fourthCol = thirdCol;
		fourthCol += vector.getColumn();
		int first = 2;
		int second = 2;
		int third = 2;
		int fourth = 2;
		if(firstRow>=0 && firstRow<maxLength && firstCol>=0 && firstCol<maxLength){
			first = board[firstRow][firstCol];
		}
		if(secondRow>=0 && secondRow<maxLength && secondCol>=0 && secondCol<maxLength){
			second = board[secondRow][secondCol];
		}
		if(thirdRow>=0 && thirdRow<maxLength && thirdCol>=0 && thirdCol<maxLength){
			third = board[thirdRow][thirdCol];
		}
		if(fourthRow>=0 && fourthRow<maxLength && fourthCol>=0 && fourthCol<maxLength){
			fourth = board[fourthRow][fourthCol];
		}
		return new int[]{first, second, third, fourth};
	}

	public class LightLocation {

		private final byte row;

		private final byte column;

		public LightLocation(byte row, byte column) {
			this.row = row;
			this.column = column;
		}

		public byte getRow() {
			return row;
		}

		public byte getColumn() {
			return column;
		}

		@Override
		public String toString() {
			return String.format("(%d, %d)", row, column);
		}
	}

}
