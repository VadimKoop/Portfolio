import java.util.HashMap;
import java.util.Map;
import java.util.Random;

//TIP To <b>Run</b> code, press <shortcut actionId="Run"/> or
// click the <icon src="AllIcons.Actions.Execute"/> icon in the gutter.
public class Main {
    public static void main(String[] args) {

        /** Exercise 1 - average elements of a given array of integers. */
        // array of the numbers.
        int[] numbers = {5, 10, 15, 20, 25};
        int sum = 0;

        // Calculate the sum of array elements.
        for (int number : numbers) {
            sum += number;
        }

        // Finding the average value.
        double average = (double) sum / numbers.length;
        System.out.println("Exercise 1 -> Average: " + average);


        /** Exercise 2 - Largest element in the array integers. */
        int[] array = {10, 5, 8, 20, 15};

        // Variable to store the largest element.
        int max = array[0];

        // We iterate through the array and compare each element with the current maximum.
        for (int i = 1; i < array.length; i++) {
            if (array[i] > max) {
                max = array[i];
            }
        }
        System.out.println("Exercise 2 -> Largest element in array: " + max);


        /** Exercise 3 - Finding duplicate elements. */

        int[] listofnumbers = {1, 2, 3, 2, 4, 5};
        findDuplicates(listofnumbers);
    }


    public static void findDuplicates(int[] arr) {
        Map<Integer, Integer> map = new HashMap<>();
        boolean duplicatesFound = false;

        // We count the quantity of each element.
        for (int num : arr) {
            if (map.containsKey(num)) {
                map.put(num, map.get(num) + 1);
            } else {
                map.put(num, 1);
            }
        }

        // Output elements that are repeated.
        for (Map.Entry<Integer, Integer> entry : map.entrySet()) {
            if (entry.getValue() > 1) {
                System.out.println("Exercise 3 -> Repeating element: " + entry.getKey());
                duplicatesFound = true;
            }
        }

        // If there are no duplicate elements.
        if (!duplicatesFound) {
            System.out.println("There are no duplicate elements!");
        }


        /** Exercise 4 - the sum of all elements in a given array. */
        int[] numbers = {1, 2, 3, 4, 5};
        int sum = 0;

        // Sum all the elements of the array.
        for (int number : numbers) {
            sum += number;
        }
        System.out.println("Exercise 4 -> Sum of all array elements:" + sum);


        /** Exercise 5 - Reverse order from 10 to 1. */
        // Loop starts at 10 and continues to 1 in steps of -1.
        for (int i = 10; i >= 1; i--) {
            System.out.println("Exercise 5 -> Reverse order from 10 to 1: " + i);
        }




        /** Exercise 6a - Count the number of characters in a string, ignoring spaces. */
        String str = "Hello, World!";
        int count = 0;

        // Loop through each character in the string.
        for (int i = 0; i < str.length(); i++) {
            // Check if the current character is a space.
            if (str.charAt(i) != ' ') {
                count++; // Increment the counter if the character is not a space.
            }
        }
        System.out.println("Exercise 6a -> Number of characters (without spaces): " + count);




        /** Exercise 6b - Count the number of spaces in the string “All of The Times”. */
        String str2 = "All of The Times";
        int count2 = 0;

        // We go through each character in the line.
        for (int i = 0; i < str2.length(); i++) {
            // Checking if the current character is a space.
            if (str2.charAt(i) == ' ') {
                count2++; // Increment the counter if the character is a space.
            }
        }

        // Display the result on the screen.
        System.out.println("Exercise 6b -> Number of spaces per line: " + count2);





        /** Exercise 7 - finds the sum of all elements in a given an array of integers using a while loop. */
        int[] numbers7 = {1, 2, 3, 4, 5};
        int sum7 = 0;
        int i = 0;

        // Using a while loop to sum array elements.
        while (i < numbers7.length) {
            sum7 += numbers7[i];
            i++;
        }

        // We display the amount on the screen.
        System.out.println("Exercise 7 -> Sum of all array elements: " + sum);





        /** Exercise 8 - random cisel generator. */
        int ARRAY_LENGTH = 10;

        // Create an instance of the Random class.
        Random rd = new Random();

        // Create an array of integers.
        int[] numbers8 = new int[ARRAY_LENGTH];

        // Filling the array with random integers.
        for (int a = 0; a < numbers8.length; a++) {
            numbers8[a] = rd.nextInt();
        }

        // Displaying the array on the screen.
        System.out.println("Exercise 8 -> Generated array:");
        for (int number : numbers8) {
            System.out.println(number);
        }




        /** Exercise 9 - Use a while loop to print the contents of the array with even numbers on the screen. */
        // Create an array of integers from 1 to 10.
        int[] originalArray = new int[10];
        for (int c = 0; c < originalArray.length; c++) {
            originalArray[c] = c + 1;
        }

        // Create a new array containing only even numbers from originalArray.
        int[] evenArray = new int[originalArray.length];
        int evenIndex = 0;
        for (int num : originalArray) {
            if (num % 2 == 0) {
                evenArray[evenIndex] = num;
                evenIndex++;
            }
        }

        // Printing the contents of an array with even numbers using a while loop.
        System.out.println("Exercise 9 -> Array with even numbers:");
        int index = 0;
        while (index < evenIndex) {
            System.out.println(evenArray[index]);
            index++;
        }
    }
}