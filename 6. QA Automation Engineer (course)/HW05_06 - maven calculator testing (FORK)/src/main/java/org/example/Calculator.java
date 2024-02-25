package org.example;

public class Calculator {

    /** HW05 PART */
    // Method for adding two integers.
    public int add(int a, int b) {
        return a + b;
    }

    // Method to subtract two integers.
    public int subtract(int a, int b) {
        return a - b;
    }

    // Method for comparing two numbers: returns true if the first number is greater than the second.
    public boolean isGreaterThan(int a, int b) {
        return a > b;
    }

    // Example of using a class
    public static void main(String[] args) {
        // Create an instance of the Calculator class.
        Calculator calc = new Calculator();

        // Examples of using addition and subtraction methods.
        int sum = calc.add(5, 3); // 5 + 3 = 8
        System.out.println("Sum: " + sum);

        int difference = calc.subtract(10, 4); // 10 - 4 = 6
        System.out.println("Difference: " + difference);
    }

    /** HW06 part */

    // Floating point division method.
    public double divide(double x, double y) {
        if (y == 0) {
            throw new IllegalArgumentException("Cannot divide by zero!");
        }
        return x / y;
    }

    // Floating point multiplication method.
    public double multiply(double x, double y) {
        return x * y;
    }
}
