package org.password;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertTrue;

public class PasswordCheckerTest {

    @Test
    void shouldReturnTrueForComplexPasswordWithDigitsAndSpecialChars() {
        assertTrue(PasswordChecker.checkPasswordComplexity("Password123!"));

        @Before
        public void setUp () {
            System.setIn(sysInBackup);
        }

        @After
        public void tearDown () {
            System.setIn(sysInBackup);
        }

        @Test
        public void testComplexPassword () {
            provideInput(complexPassword);
            assertTrue(PasswordChecker.checkPasswordComplexity(complexPassword));
        }

        @Test
        public void testPasswordWithoutDigit () {
            provideInput(passwordWithoutDigit);
            assertFalse(PasswordChecker.checkPasswordComplexity(passwordWithoutDigit));
        }

        @Test
        public void testPasswordWithoutSpecialChar () {
            provideInput(passwordWithoutSpecialChar);
            assertFalse(PasswordChecker.checkPasswordComplexity(passwordWithoutSpecialChar));
        }

        @Test
        public void testShortPassword () {
            provideInput(shortPassword);
            assertFalse(PasswordChecker.checkPasswordComplexity(shortPassword));
        }

        private void provideInput (String data){
            ByteArrayInputStream testIn = new ByteArrayInputStream(data.getBytes());
            System.setIn(testIn);
        }
    }
    }