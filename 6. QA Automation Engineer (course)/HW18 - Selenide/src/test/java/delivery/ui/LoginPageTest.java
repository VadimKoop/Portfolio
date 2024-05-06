/* CW 18 Selenide - wrong login and password.

 1. Open brauser and go to URL.
 2. Necessary login and password.
 3. Enter incorrect data in login and password.
 4. Click on the button Sign In.
 5. Check error message.
 */
package delivery.ui;

import com.codeborne.selenide.Condition;
import com.codeborne.selenide.Selenide;
import com.codeborne.selenide.SelenideElement;
import delivery.utils.RandomDataGenerator;
import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;


import static com.codeborne.selenide.Selenide.$;
import static com.codeborne.selenide.Selenide.$x;

public class LoginPageTest {
    @Test
    public void insertIncorrectLoginAndPasswordAndCheckErrorMessage() {

        // Step 0
        Selenide.open("http://35.208.34.242:3000/signin");


        // Step 1
        SelenideElement inputUsername = $(By.xpath("//*[@data-name=\"username-input\"]"));
        inputUsername.sendKeys("dummyusername");

        // Short version of step 1
        $x("//*[@data-name=\"username-input\"]").sendKeys("dummyusername");


        // Step 2
        SelenideElement InputPassword = $(By.xpath("//*[@data-name=\"password-input\"]"));
        InputPassword.sendKeys("dummypasswword");


        // Short version of step 2
        $x("//*[@data-name=\"password-input\"]").sendKeys("dummypasswword");

        // Selenide syntaxis
        // Step 3
        //SelenideElement signIn = $x(By.xpath("//*[@data-name=\"signIn-button\"]");
        //signIn.click();

        // Short version of step 3
        $x("//*[@data-name=\"signIn-button\"]").click();


        // Step 4
        // SelenideElement errorInvalidCredentials = $x("//span[@data-name = 'username-input-error']");
        //SelenideElement errorInvalidCredentials = $x("//*[@data-name = 'autorizationError-popup-close-button']");
        //errorInvalidCredentials.shouldBe(Condition.visible);

        // Short version of step 4.
        $x("//*[@data-name = 'autorizationError-popup-close-button']").shouldBe(Condition.visible);

        // int a = 1; - point of stop

        /* Xpath*/
        //*[data-name="username-input"]
        //*[data-name="password-input*]


    }


    /* HW 18. */

    @Test
    public void LoginWithIncorrectDataAndImplementErrorChecking() {

        $x("//*[@data-name=\"username-input\"]").sendKeys("WrongName");
        $x("//*[@data-name=\"password-input\"]").sendKeys("WrongPassword");
        $x("//*[@data-name=\"signIn-button\"]").click();
        $x("//*[@data-name=\"authorizationError-popup-close-button\"]").shouldBe(Condition.visible);
    }

    @Test
    public void LoginWithTheCorrectDataAndCheckOrderPage() {

        $x("//*[@data-name=\"username-input\"]").sendKeys("vadimkp");
        $x("//*[@data-name=\"password-input\"]").sendKeys("pd2V7wYbfT2n");
        $x("//*[@data-name=\"signIn-button\"]").click();
        $x("//*[@data-name=\"authorizationError-popup-close-button\"]").shouldBe(Condition.visible);
        $x("//*[@data-name=\"createOrder-button\"]").shouldBe(Condition.visible);
        $x("//*[@data-name=\"logout-button\"]").click();
    }


    // Error checking using a minimum number of characters (2 scenario username + password) - try to find tricky selector
    @Test
    public void TwoScenarioUsernameAndPassword() {

        // Selector for username input field
        $x("//input[contains(@data-name,'username') and contains(@data-name,'input') and not(contains(@data-name,'password'))]").
                sendKeys("vadimkp");

        // Selector for password input field
        $x("//input[contains(@data-name,'password') and contains(@data-name,'input') and not(contains(@data-name,'username'))]").
                sendKeys("pd2V7wYbfT2n");

        // Selector for sign-in button
        $x("//button[contains(@data-name,'signIn')]").click();

        // Selector for authorization error popup close button
        $x("//button[contains(@data-name,'authorizationError') and contains(@data-name,'popup-close-button')]").
                shouldBe(Condition.visible);
    }

}
