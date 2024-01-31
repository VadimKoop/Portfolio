import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

//TIP To <b>Run</b> code, press <shortcut actionId="Run"/> or
// click the <icon src="AllIcons.Actions.Execute"/> icon in the gutter.
public class Main {
    public static void main(String[] args) throws IOException {

        // Task 1 - triangel.
        int intLength = 2;
        int intWidth = 5;
        int intHeight = 3;
        int sqrt = (intWidth * intHeight) / 2; //Area of triangle

        System.out.println("Task 1 - triangel.");
        System.out.println("Length * width = " + intLength * intWidth);
        System.out.println("Square root is: " + sqrt);
        System.out.println();





        // Task 2 - body mass index.
        double doubleWidth = 4;  // Kilograms
        double doubleHeight = 3; // Meeters
        double bmi = doubleWidth / (doubleHeight * doubleHeight);

        System.out.println("Task 2 - body mass index.");
        System.out.println(doubleWidth + " width kilograms");
        System.out.println(doubleHeight + " height meeters");
        System.out.println("Body mass index = " + bmi);
        System.out.println();





        // Task 3 - calculation of temperature converter.
        double celsiusDoubleTemperature = 100;
        double fahrenheitDoubleTestTemperature1 = 9/5;
        double fahrenheitDoubleTestTemperature2 = (double) 9/5;
        double fahrenheitDoubleTemperature = (double) 9/5 * celsiusDoubleTemperature + 32;

        System.out.println("Task 3 - calculation of temperature converter.");
        System.out.println("fahrenheitDoubleTestTemperature 1: " + fahrenheitDoubleTestTemperature1 + " false");
        System.out.println("fahrenheitDoubleTestTemperature 2: " + fahrenheitDoubleTestTemperature2 + " correct");
        System.out.println("Fahrenheit temperature = " + fahrenheitDoubleTemperature);
        System.out.println();





        // Task 4 - short type variable.
        short shortNumber = 10;
        shortNumber++;

        System.out.println("Task 4 - short type variable.");
        System.out.println("Short varible number with increment: " + shortNumber);
        System.out.println();





        // Task 5 - rounding a number.
        double doubleNumber = 3.3;
        int intNumber = (int) doubleNumber;

        System.out.println("Task 5 - rounding a number.");
        System.out.println("Double value: " + doubleNumber);
        System.out.println("Double value convered to the int format: " + intNumber);
        System.out.println();





        // Task 6 - boolean age control.
        int twentyNine = 29;
        int eighTeen = 18;
        float ageThird = (float) 17.999;
        int ageControlNumber = 18;

        boolean ageIsBiggerThanAgeOne = twentyNine >= ageControlNumber;
        boolean ageIsBiggerThanAgeTwo = eighTeen >= ageControlNumber;
        boolean ageIsBiggerThanAgeThird = ageThird >= ageControlNumber;

        System.out.println("Task 6 - boolean age control.");
        System.out.println("29 is bigger than 18: " + ageIsBiggerThanAgeOne);
        System.out.println("18 is bigger or equal to 18: " + ageIsBiggerThanAgeTwo);
        System.out.println("17.999 is bigger or equal to 18: " + ageIsBiggerThanAgeThird);





        // Task 7 - character encryption.
        char charSymbol = 'A';
        int shiftAmount = 3;
        char shiftedChar = (char) (charSymbol + shiftAmount);

        System.out.println("Task 7 - character encryption.");
        System.out.println("Source symbol: " + charSymbol);
        System.out.println("Shifted symbol: " + shiftedChar);
        System.out.println();





        // Task 8 - data processing".
        System.out.println("Task 8 - data processing.");
        // Enter data using BufferReader
        BufferedReader reader = new BufferedReader(new InputStreamReader(System.in));

        // First number input.
        System.out.println("Enter first number:");
        String input1 = reader.readLine();
        int number1 = Integer.parseInt(input1);

        // Second number input.
        System.out.println("Enter second numbeer");
        String input2 = reader.readLine();
        int number2 = Integer.parseInt(input2);

        // Multiplying numbers.
        int result = number1 * number2;

        System.out.println("Multiplication result: " + result);
    }
    }
