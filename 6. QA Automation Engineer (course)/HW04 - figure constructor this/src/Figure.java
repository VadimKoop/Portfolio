public class Figure {
    int height;
    int width;
    int length;

    // Empty constructor.
    public Figure() {}

    // Constructor.
    public Figure(int height, int width, int length) {
        this.height = height;
        this.width = width;
        this.length = length;
    }

    // Creating method which calculates volume of the figure.
    public double calculateVolume() {
        System.out.println("Class calculateVolume, the sum of figure sizes is: " + this.height + this.width + this.length);
        System.out.println(this.height + this.width + this.length); // -> 10.
        return this.length * this.width * this.height;
    }

    // A method that calculates the surface area of figure from formula S = 2(ab + ah + bh).
    public void calculateSurfaceArea() {
        double surfaceArea = 2 * (this.length * this.width + this.length * this.height + this.width * this.height);
        System.out.println("Surface area of the figure: " + surfaceArea); // -> 62.0
    }


    public static void main(String[] args) {
        // Create an object of the Figure class, giving height, height and length values.
        Figure figureSizes = new Figure (5, 3, 2);
        System.out.println("Figure sizes are: " + figureSizes); // -> Figure@3feba861
        System.out.println("Figure sizes are: " + figureSizes.height + figureSizes.width + figureSizes.length); // -> 532

        // Creating double volume variable and calling 2 methods.
        double volume = figureSizes.calculateVolume();
        System.out.println("Figure volume is: " + volume); // -> 30.0

        figureSizes.calculateSurfaceArea();
    }
}
