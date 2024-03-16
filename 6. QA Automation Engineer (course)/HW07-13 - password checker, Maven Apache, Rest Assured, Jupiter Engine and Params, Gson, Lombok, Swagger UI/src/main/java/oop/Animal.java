package oop;

/** CW13 - class inheritance*/
public class Animal {

    String name;

    int old;

    public void sayHello(){
        System.out.println("aoof");
    }

    private void sayHelloprivate(){ // Can not be inheritance!
        System.out.println("aoof private");
    }

    public void setOld(int old) {
        if (old > 0) {
            this.old = old;
        } else {
            System.out.println("Age can't be negative");
        }
    }
}
