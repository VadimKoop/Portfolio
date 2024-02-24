package org.password;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertFalse;
import static org.junit.jupiter.api.Assertions.assertTrue;

public class PasswordCheckerTest {

    @Test
    void shouldReturnTrueForComplexPasswordWithDigitsAndSpecialChars() {
        assertTrue( PasswordChecker.checkPasswordComplexity("Password123!") );
    }

    @Test
    void shouldReturnFalsePasswordInputIsEmpty() {
        assertFalse(PasswordChecker.checkPasswordComplexity(""));
    }

    @Test
    void shouldReturnFalseForNotEnoughComplexPasswordWithMissingSpecialChar () {
        assertFalse(PasswordChecker.checkPasswordComplexity("Pas123"));
    }

    @Test
    void shouldReturnFalseForNotEnoughComplexPasswordNotEnoughLong () {
        assertFalse(PasswordChecker.checkPasswordComplexity("P2&"));
    }

    @Test
    void shouldReturnFalseForNotEnoughComplexPasswordWithMissingNumber() {
        assertFalse(PasswordChecker.checkPasswordComplexity("Pass@!#"));
    }

    @Test
    void shouldReturnTrueForComplexPasswordWithDigitsAndSpecialRusChars () {
        assertTrue(PasswordChecker.checkPasswordComplexity("Хацкер1337!"));
    }
}


/** Additional fun testing.
 *     @BeforeEach
 *     public void setUpInput() {
 *         testIn = new ByteArrayInputStream("TestPassword1!".getBytes());
 *         System.setIn(testIn);
 *     }
 *
 *     @AfterEach
 *     public void restoreSystemInputOutput() {
 *         System.setIn(systemIn);
 *     }
 *
 *     @Test
 *     public void testComplexPassword() {
 *         String expectedOutput = "Password is complex.\n";
 *         assertEquals(expectedOutput, getProgramOutput());
 *     }
 *
 *     @Test
 *     public void testPasswordTooShort() {
 *         testIn = new ByteArrayInputStream("short".getBytes());
 *         System.setIn(testIn);
 *         String expectedOutput = "Password is not complex enough. Make sure it contains at least one digit, one special character, and has a length of at least 8 characters.\n";
 *         assertEquals(expectedOutput, getProgramOutput());
 *     }
 *
 *     @Test
 *     public void testPasswordNoDigit() {
 *         testIn = new ByteArrayInputStream("NoDigitPassword!".getBytes());
 *         System.setIn(testIn);
 *         String expectedOutput = "Password is not complex enough. Make sure it contains at least one digit, one special character, and has a length of at least 8 characters.\n";
 *         assertEquals(expectedOutput, getProgramOutput());
 *     }
 *
 *     @Test
 *     public void testPasswordNoSpecialCharacter() {
 *         testIn = new ByteArrayInputStream("NoSpecialChar123".getBytes());
 *         System.setIn(testIn);
 *         String expectedOutput = "Password is not complex enough. Make sure it contains at least one digit, one special character, and has a length of at least 8 characters.\n";
 *         assertEquals(expectedOutput, getProgramOutput());
 *     }
 *
 *     private String getProgramOutput() {
 *         PasswordChecker.main(new String[]{});
 *         return systemOut().toString();
 *     }
 *
 *     private ByteArrayOutputStream testOut = new ByteArrayOutputStream();
 *
 *     private String systemOut() {
 *         return testOut.toString();
 *     }
 *
 *     */