/**HW14 */
package delivery.utils;


import org.apache.commons.lang3.RandomStringUtils;
import java.util.Random;

public class RandomDataGenerator {

    public static String  generateName (){
        int length = 12;
        boolean useLetters = true;
        boolean useNumbers = false;
        String generateCustomerName = RandomStringUtils.random(length,useLetters,useNumbers);
        return generateCustomerName;

    }
    public static String generatePhoneNumber(){
        int length = 8;
        boolean useLetters = false;
        boolean useNumbers = true;
        String generateCustomerPhoneNumber = RandomStringUtils.random(length,useLetters,useNumbers);
        return generateCustomerPhoneNumber;
    }
    public static String generateComment(){
        int length = 20;
        boolean useLetters = true;
        boolean useNumbers = true;
        String generateCustomerComment = RandomStringUtils.random(length,useLetters,useNumbers);
        return generateCustomerComment;
    }
    public static int generateCourierId(){
        int length = 3;
        boolean useLetters = false;
        boolean useNumbers = true;
        int generateCourierId = Integer.parseInt(RandomStringUtils.random(length,useLetters,useNumbers));
        return generateCourierId;
    }
    public static long generateOrderId(){
        int length = 3;
        boolean useLetters = false;
        boolean useNumbers = true;
        long generateOrderId = Long.parseLong(RandomStringUtils.random(length,useLetters,useNumbers));
        return generateOrderId;
    }
    public static String generateRandomOrderStatus() {
        String[] orderStatuses = {"OPEN", "ACCEPTED", "INPROGRESS", "DELIVERED"};
        int randomIndex = new Random().nextInt(orderStatuses.length);
        return orderStatuses[randomIndex];
    }

}