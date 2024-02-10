package org.example;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

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

    // * Boolean TRUE/FALSE logic tests */
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
}
