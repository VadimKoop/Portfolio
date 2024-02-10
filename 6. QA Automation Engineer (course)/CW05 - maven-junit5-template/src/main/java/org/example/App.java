package org.example;

/**
 * Hello world!
 *
 */
public class App 
{
    public static void main( String[] args )
    {

        //Create object.
        Calculator calculator = new Calculator();
        //Call method.
        calculator.calculateSumOfTwoIntegerValues(5,0);


        System.out.println( "Run calculator: " + calculator.calculateSumOfTwoIntegerValues(5,0));
    }
}
