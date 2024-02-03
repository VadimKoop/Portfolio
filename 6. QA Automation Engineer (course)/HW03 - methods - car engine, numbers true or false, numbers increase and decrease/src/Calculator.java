public class Calculator {

    // I have created method addition.
    public int addition () {
        int intNum1 = 1;
        int intNum2 = 5;

        System.out.println(intNum1 + intNum2);
        return 0;
    }

    // I have created method isGreather.
    public boolean isGreather () {
        double doubleNum1 = 2;
        double doubleNum2 = 3;

        // doublemNum1 > doublemNum2 check -> true.
        boolean numberIsBiger = doubleNum2 > doubleNum1;

        System.out.println(numberIsBiger);

        // doublemNum1 < doublemNum2 check - false.
        doubleNum1 = 5;
        doubleNum2 = 4;
        numberIsBiger = doubleNum2 > doubleNum1;

        System.out.println(numberIsBiger);

        return numberIsBiger;
    }
}
// Git 2 commit comment
