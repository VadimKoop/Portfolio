/**CW16 */
public class Main {
    public static void main(String[] args) {

        //array of int
        final int ARRAY_LENGTH = 5;
        int[] array = new int [ARRAY_LENGTH];
        double[] arrayDouble = new double[ARRAY_LENGTH];
        array[1] = 55;
        array[0] = 99;
        arrayDouble[3] = 33;

        int[] arrayWithValues = {2, 4, 5, 6, 7, 99, 22}; // [0], [1], [2], [3], [5], [6] = in total 7.
        char[] characters = {'a', 'b', 'c', 121};

        String[] arrayOfString = new String[2];
        arrayOfString[1] = "first String";

        System.out.println(arrayOfString);
        System.out.println("arrayWithValues = " + arrayWithValues);
        System.out.println("arrayWithValues = " + arrayWithValues[0]);

        System.out.println("characters = " + characters);
        System.out.println("characters = " + characters[0] + characters[1]);

        System.out.println("arrayOfString[1] = " + arrayOfString[1]);
        //System.out.println("arrayOfString[1] = " + arrayOfString[3]); // error because of the wrong array length.

        System.out.println("arrayWithValues.length: " + arrayWithValues.length);

        System.out.println("debug");

        String[] seasons = new String[4];

        seasons[0] = "Spring";
        seasons[1] = "Winter";
        seasons[2] = "Autumn";
        seasons[3] = "Summer";

        //Loop = цыкл.
        for (int number = 0; number <=7; number++){
            System.out.println("Current value of number is: " + number);
        }

        String[] seasonsArray = new String[4];

        seasonsArray[0] = "Spring";
        seasonsArray[1] = "Winter";
        seasonsArray[2] = "Autumn";
        seasonsArray[3] = "Summer";

        //Loop.
        for (int seasonId = 0; seasonId < seasonsArray.length; seasonId++)
            System.out.println("Current value of number is: " + seasonsArray[seasonId]);

        //Loop.
        int counter = 5;
        while (counter > 0){
            System.out.println("Counter -> " + counter);
            counter--;
        }
    }
}
