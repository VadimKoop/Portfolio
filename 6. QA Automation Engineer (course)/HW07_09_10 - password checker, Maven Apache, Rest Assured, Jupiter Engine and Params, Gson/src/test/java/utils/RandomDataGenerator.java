/** CW11*/
package utils;
import org.apache.commons.lang3.RandomStringUtils;

public class  RandomDataGenerator {

    public static String generateName() {

        int length = 10;
        boolean useLetters = true;
        boolean useNumbers = false;
        String generatedCustomerName = RandomStringUtils.random(length, useLetters, useNumbers);

        return generatedCustomerName;
    }
}