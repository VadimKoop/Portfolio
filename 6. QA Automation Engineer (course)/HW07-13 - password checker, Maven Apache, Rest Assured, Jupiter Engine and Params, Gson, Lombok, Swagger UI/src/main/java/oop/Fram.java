package oop;

/** CW13 - class inheritance*/
public class Fram {

    public static void main(String[] args) {

        Animal animal = new Animal();
        Dog dog = new Dog();

        dog.sayHello();
        dog.name = "My name is Dog class!";
        dog.old = 2;
    }
}
