public class Figure {

    int height;
    int width;
    int length;


    // Empty constructor.
    public Figure() {

    }


    // Constructor.
    public Figure(int height, int width, int length) {
        this.height = height;
        this.width = width;
        this.length = length;
    }


    // Creating method which calculates volume of the figure.
    public double calculateVolume() {
        System.out.println(this.height + this.width + this.length);
        return this.length * this.width * this.height;
    }

    // A method that calculates the surface area of figure from formula S = 2(ab + ah + bh).
    public void calculateSurfaceArea() {
        double surfaceArea = 2 * (this.length * this.width + this.length * this.height + this.width * this.height);
        System.out.println("Surface area of the figure: " + surfaceArea);
    }


    public static void main(String[] args) {
        // Create an object of the Figure class, giving height, height and length values.
        Figure figureSizes = new Figure (5, 3, 2);
        System.out.println("Figure sizes are: " + figureSizes); /** Почему отобрается Figure@3feba861? Откуда доп 10?*/

        double volume = figureSizes.calculateVolume();
        System.out.println("Vigure volume is: " + volume);

        figureSizes.calculateSurfaceArea();
    }
}
