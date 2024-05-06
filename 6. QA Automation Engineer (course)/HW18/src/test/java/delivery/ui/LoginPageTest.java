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
import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;

import static com.codeborne.selenide.Selenide.$;
import static com.codeborne.selenide.Selenide.$x;

public class LoginPageTest {
    @Test
    public void insertIncorrectLoginAndPasswordAndCheckErrorMessage(){

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

}
