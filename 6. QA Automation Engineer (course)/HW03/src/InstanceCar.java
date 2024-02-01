public class InstanceCar {

    // An instance of the Car class invoking its methods - ЭКЗЕМПЛЯР.
    public static void main(String[] args) {

        // I gave access to class Car data and methods.
        Car instanceCar = new Car();

        // I have launched methods from class Car.
        instanceCar.startEngine();
        instanceCar.stopEngine();

    }
}
