public class DogFactory {

    //Entry point declaration that allows dogs to be created in a given class.
    public static void main(String[] args) { //Shortcut psvm.

        Dog myDog = new Dog();
        myDog.weight = 2;
        myDog.name = "Valentin 2";
        myDog.breed = "Breed: Human dog 2";

        // Execute without parameter.
        myDog.sayHello();

        // Should be executed with parameter.
        myDog.repeatWord(" Sit down dog!");
        System.out.println (myDog.sumTwoNumber(1, 2));

        System.out.println (myDog.breed);

    }

}
