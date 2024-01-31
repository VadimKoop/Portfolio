//TIP To <b>Run</b> code, press <shortcut actionId="Run"/> or
// click the <icon src="AllIcons.Actions.Execute"/> icon in the gutter.
public class Main {
    public static void main(String[] args) {
        System.out.printf("Hello and welcome!");

        // int
        int weight = 200; //created a variable.
        int areYoung = 10;  //created a variable.
        int ageOld = 100;  //created a variable.
        int sumOfAllAges = areYoung + ageOld;

        // int logic maximum value testing -> we get minus value in console.
        int maxIntNumber = 2147483647;
        int moreThenMaxInt = maxIntNumber+1;

        // boolean
        boolean isWeightOk = true; //True or False.
        boolean isAgeOk = false; //True or False.
        boolean isOldAgeBiggerThanYoung = ageOld > areYoung;

        // rewrite
        weight = 50;
        isWeightOk = false;

        // Constant
        final int SCREEN_SIZE = 1024; //Can not be rewrited, because it is final.

        // Even and Odd number testing.
        int evenNumber = 4 % 2; // even number
        int oddNumber = 5 % 3;  //odd number

        // Increment and decrement number.
        int number = 7;
        number++;

        System.out.println("Number: " + number);
        System.out.println("This is variable content: " + sumOfAllAges);
        System.out.println("This is variable content: " + weight);

        }
    }