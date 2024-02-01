public class Calculator {

    // I have created method addition.
    public int addition () {
        int intNum1 = 1;
        int intNum2 = 5;

        System.out.println(intNum1 + intNum2);
        return 0;  /** Почему нам нужно обязательно return? Для чего?*/
    }

    // I have created method isGreather.
    public boolean isGreather () {
        double doubleNum1 = 2;
        double doubleNum2 = 3;

        boolean numberIsBiger = doubleNum2 > doubleNum1;
        return numberIsBiger;
    }
}
