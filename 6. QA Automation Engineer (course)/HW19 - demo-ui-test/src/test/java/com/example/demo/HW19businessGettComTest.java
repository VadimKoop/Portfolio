import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;

import java.util.UUID;

public class HW19businessGettComTest {

    private WebDriver driver;

    @BeforeEach
    public void setUp() {
        // Set the path to the webriver
        System.setProperty("webdriver.chrome.driver", "C:\\Users\\talli\\Downloads\\chromedriver_win32\\chromedriver");

        // Initialize driver of Chrome browser
        driver = new ChromeDriver();

        // Открываем веб-страницу
        driver.get("https://business.gett.com/auth");
    }

    @Test
    public void loginTest() {
        // Looks for emailLoginForm
        WebElement emailInput = driver.findElement(By.cssSelector("input[data-name='emailLoginForm']"));

        // Generation of the random email
        String randomEmail = "random" + UUID.randomUUID().toString() + "@example.com";

        // Entering random email
        emailInput.sendKeys(randomEmail);
    }

    @Test
    public void clickNextButton() {
        // Find the button with data-name="loginNextButton"
        WebElement nextButton = driver.findElement(By.cssSelector("button[data-name='loginNextButton']"));

        // Click on the button
        nextButton.click();
    }

    @Test
    public void enterRandomPassword() {
        // Generate a random password
        String randomPassword = UUID.randomUUID().toString().substring(0, 8);

        // Find the input field with data-name="passwordLoginForm"
        WebElement passwordInput = driver.findElement(By.cssSelector("input[data-name='passwordLoginForm']"));

        // Enter the random password into the input field
        passwordInput.sendKeys(randomPassword);
    }
}
