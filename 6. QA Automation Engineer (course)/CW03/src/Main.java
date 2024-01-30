//TIP To <b>Run</b> code, press <shortcut actionId="Run"/> or
// click the <icon src="AllIcons.Actions.Execute"/> icon in the gutter.
public class Main {
    public static void main(String[] args) {

        // Create object of class Dog.
        Dog myFirstDog = new Dog();  //Dog = class, myFirstDog = name, new Dog() = class object.
        Dog mySecondDog = new Dog();

        //myFirstDog characteristics.
        myFirstDog.name = "Vadim 1";
        myFirstDog.breed = "Breed: Human dog 1";
        myFirstDog.weight = 87.5;

        //mySecondDog characteristics.
        mySecondDog.name = "Max 1";
        mySecondDog.breed = "Breed: Human dog 1";
        mySecondDog.weight = 105;

        double myVariable = 1;
        String name = "abcbcbc";

        BloodAnalysis bloodAnalysis = new BloodAnalysis();
        new BloodAnalysis(); //We have created new class BloodAnalysis.

        bloodAnalysis.personalCode = "39510357313";
        bloodAnalysis.itemOne = 10.0;
        bloodAnalysis.itemTwo = 5.0;

    }
}