package org.example;

import org.junit.jupiter.api.*;

import static org.junit.jupiter.api.Assertions.*;

/**
 * Unit test for simple App.
 */
public class AppTest {

    // Declaration of making object of classs Calculator.
    private Calculator calculator;

    @BeforeAll
    static void setUpForAllTest(){
        System.out.println("Start. Setting up ALL!");
    }

    @AfterAll
    static void tearDownForAllTest() {
        System.out.println("End. Closing up after ALL tests!");
    }

    @BeforeEach
    void setUp() {
        calculator = new Calculator(); // We have already initialized private Calculator calculator. The new calculator will be generated automatically.
        System.out.println("Setting up calculator object for new test!");
    }

    @AfterEach
    void tearDown() {
        System.out.println("This is execution of post condition after each test!");
    }

    @Test
    public void checkTwoValuesEquals(){
        assertEquals( 3 , 3);
    }

    @Test
    public void checkTwoValuesNotEquals(){
        assertNotEquals( 3 , 4);
    }

    @Test
    public void shouldAnswerWithTrue()
    {
        assertTrue( 3 == 3 );
    }

    @Test
    public void shouldAnswerWithFalse()
    {
        assertFalse( 4 < 2 );
    }

}
