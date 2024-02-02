public class NumberManipulator {

    // Number increment method.
    public int incrementByOne (int number) { // We immediately insert a number variable into the function.
            number++;
            System.out.println("Number after increment: " + number);

            return number;
        }

    // Number decrement method.
        public int decrementByOne (int number) { // We immediately insert a number variable into the function.
            number--;
            System.out.println("Number after decrement: " + number);

            return number;
        }
        public static void main(String[] args) {
            NumberManipulator manipulator = new NumberManipulator();  /** Это нужно обязательно прописывать, даже если я хочу вызвать метод внутрки класса?*/

            manipulator.incrementByOne(5); /** Почему Java не может взять автоматом 0 значение? */
            manipulator.decrementByOne(6);
    }
}

/** У мегя съехал код, вроде бы как есть shortcut, который может выравнить код класса? */



