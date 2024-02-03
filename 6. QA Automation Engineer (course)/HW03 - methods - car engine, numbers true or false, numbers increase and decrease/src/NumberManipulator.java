public class NumberManipulator {

    // Number increment method.
    public int incrementByOne(int number) { // We immediately insert a number variable into the function.
        number++;
        System.out.println("Number after increment: " + number);

        return number;
    }

    // Number decrement method.
    public int decrementByOne(int number) { // We immediately insert a number variable into the function.
        number--;
        System.out.println("Number after decrement: " + number);

        return number;
    }

    public static void main(String[] args) {
        NumberManipulator manipulator = new NumberManipulator();

        manipulator.incrementByOne(5); /** Optional Java */
        manipulator.decrementByOne(6);
    }
}
