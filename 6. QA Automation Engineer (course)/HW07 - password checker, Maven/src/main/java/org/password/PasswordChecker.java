package org.password;

import java.util.Scanner;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class PasswordChecker {

    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);

        System.out.println("Welcome! ");
        System.out.println("Password must contain at least one digit, one special character, and have a length of at least 8 characters.");
        System.out.println("Please enter your password below ... ");

        String password = scanner.nextLine();

        if (checkPasswordComplexity(password)) {
            System.out.println("Password is complex.");
        } else {
            System.out.println("Password is not complex enough. Make sure it contains at least one digit, one special character, and has a length of at least 8 characters.");
        }

    }

    /** Non-static method, so this why we don't create an instance. */
    // Static methods are called from the class.
    // Non-static methods are called from methods.
    public static boolean checkPasswordComplexity(String password) {
        if (password.length() < 8) {
            return false;
        }

        Pattern digitPattern = Pattern.compile("\\d");
        Matcher digitMatcher = digitPattern.matcher(password);

        Pattern specialCharPattern = Pattern.compile("[^a-zA-Z0-9]");
        Matcher specialCharMatcher = specialCharPattern.matcher(password);

        return digitMatcher.find() && specialCharMatcher.find();
    }

}


/**
 * Pattern specialCharPattern = Pattern.compile("[^a-zA-Z0-9]");  - тут я так понимаю, что происходит поиск валидных символов
 * латинского алфавита, а также цифр и верхний регистр букв, а если ввести пароль русскими буквами неважно в каком регистре,
 * то статус пароля password is complex получается?
 *
 * -> использование только латинских символов и цифр в регулярном выражении [a-zA-Z0-9] будет означать, что пароль, содержащий
 * символы кириллицы, будет считаться сложным, так как он не соответствует этим требованиям.
 *
 * Это может быть либо намеренным выбором (например, если система требует использование только латинских символов и цифр в паролях),
 * либо недочетом, если разработчик не учел возможность использования других алфавитов в паролях.
 */