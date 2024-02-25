package org.example;

import org.junit.jupiter.api.BeforeAll;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

/** HW05 part */
public class CalculatorTest {
    // Test to check the add method.
    @Test
    public void testAdd() {
        Calculator calc = new Calculator();
        int result = calc.add(5, 3);
        assertEquals(8, result);
    }

    // Test to check the subtract method.
    @Test
    public void testSubtract() {
        Calculator calc = new Calculator();
        int result = calc.subtract(10, 4);
        assertEquals(6, result);
    }

    // Additional test to check the subtract method.
    @Test
    public void testSubtractNegativeResult() {
        Calculator calc = new Calculator();
        int result = calc.subtract(4, 10);
        assertEquals(-6, result);
    }

    // * Boolean TRUE/FALSE logic tests of method isGreaterThan */
    // Test to test the isGreaterThan method when the first argument is greater.
    @Test
    public void testIsGreaterThan_FirstArgGreater() {
        Calculator calc = new Calculator();
        assertTrue(calc.isGreaterThan(5, 3));
    }

    // Test to test the isGreaterThan method when the second argument is greater.
    @Test
    public void testIsGreaterThan_SecondArgGreater() {
        Calculator calc = new Calculator();
        assertFalse(calc.isGreaterThan(3, 5));
    }

    // Test to test isGreaterThan method when both arguments are equal.
    @Test
    public void testIsGreaterThan_ArgsEqual() {
        Calculator calc = new Calculator();
        assertFalse(calc.isGreaterThan(5, 5));
    }

    /** HW06 part */
    @BeforeAll
    static void setUpForAllTest(){
        System.out.println("Setting up tests for HW06! ");
    }

    @Test
    public void testMultiply() {
        Calculator calc = new Calculator();
        assertEquals( 15.0, calc.multiply(5.0, 3.0), 0.0001); // Check that 5 * 3 = 15.
        assertEquals(-12.0, calc.multiply(4.0, -3.0), 0.0001); // Check multiplication by a negative number.
        assertEquals(0.0, calc.multiply(0.0, 10.0), 0.0001); // Check multiplication by zero.
    }

    @Test
    public void testDivide() {
        Calculator calc = new Calculator();
        assertEquals(2.0, calc.divide(6.0, 3.0),0.0001); // Check that 6 / 3 = 2.
        assertEquals(-2.0, calc.divide(6.0, -3.0),0.0001); // Check division by negative number.

        // Check division by zero.
        try {
            calc.divide(5.0, 0.0);
            fail("Expected IllegalArgumentException to be thrown");
        } catch (IllegalArgumentException e) {
            assertEquals("Cannot divide by zero!", e.getMessage());
        }
    }
}

