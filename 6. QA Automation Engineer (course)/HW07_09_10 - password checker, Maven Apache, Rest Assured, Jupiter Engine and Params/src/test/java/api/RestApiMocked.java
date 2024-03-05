package api;

import io.restassured.RestAssured;
import org.apache.http.HttpStatus;
import org.junit.jupiter.api.Assertions;
import org.junit.jupiter.api.BeforeAll;
import org.junit.jupiter.api.RepeatedTest;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.params.ParameterizedTest;
import org.junit.jupiter.params.provider.CsvSource;
import org.junit.jupiter.params.provider.ValueSource;

import static io.restassured.RestAssured.*;

public class RestApiMocked {

    @BeforeAll
    public static void setup() {
        RestAssured.baseURI = "http://35.208.34.242";
        RestAssured.port = 8080;
    }

    @Test
    public void getOrderByIDAndCheckResponseCodeIsOK() {  ////Retrieve details of an order by providing a valid order ID from 1..10. Other ID will lead to 404 Request Code.
        given().
                log()
                .all()
                .when()
                .get("http://35.208.34.242:8080/test-orders/9") //
                .then()
                .log()
                .all()
                .statusCode(200); // 200 - OK.
    }

    @Test
    public void getOrderByInvalidIdAndCheckResponseCodeIsBadRequest() {
        //Retrieve details of an order by providing a valid order ID from 1..10. Other ID will lead to 404 Request Code.
        get("http://35.208.34.242:8080/test-orders/11")
                .then()
                .statusCode(400); // HTTP Status 400 - invalid request.
    }

    @Test
    public void getAllOrderAndCheckResponseCodeIsOk() {
        // Retrieve details for all orders.
        get("http://35.208.34.242:8080/test-orders/get_orders")
                .then()
                .statusCode(200); // 200 - OK.
    }

    /**
     * HW09.
     */
    @Test
    public void deleteValidOrderAndResponseIsOk() {
        //Retrieve details of an order by providing a valid order ID from 1..10. Other ID will lead to 404 Request Code.
        given()
                .log()
                .all()
                .when()
                .header("api_key", "1234567890123456") // s - String, o - Object.
                .delete("http://35.208.34.242:8080/test-orders/9")
                .then()
                .log()
                .all()
                .statusCode(204); // HTTP Status 204 (No Content) indicates that the server has successfully fulfilled the request and that there is no content to send in the response payload body
    }

    @Test
    public void deleteWrongKeyOrderAndResponseIsNok() {
        //Retrieve details of an order by providing a valid order ID from 1..10. Other ID will lead to 404 Request Code.
        given()
                .when()
                .get("http://35.208.34.242:8080/test-orders/11")
                .then()
                .log()
                .all()
                .statusCode(400); // HTTP Status Bad Request error (No Content) indicates that the server has successfully fulfilled the request and that there is no content to send in the response payload body
    }

    /**
     * CW10
     */
    //@Test
    @RepeatedTest(11)
    public void deleteOrder() {
        RestAssured
                .given()
                .log()
                .all()
                .when()
                .header("api_key", "1234567890123456")
                .delete("http://35.208.34.242:8080/test-orders/1")
                .then()
                .log()
                .all()
                .statusCode(204);
    }

    @ParameterizedTest
    @ValueSource(ints = {1, 5, 9, 10, 11})
    public void getOrdersByIdAndCheckResponseCodeIsOk(int orderId) {

        int responseOrderId = given().
        given().
                log()
                .all()
                .when()
                .get("/test-orders/" + orderId) //Retrieve details of an order by providing a valid order ID from 1..10. Other ID will lead to 404 RC
//                .get("/test-orders/{orderId}", orderId)  // Alternative method how to send parameter.
//                .get("/test-orders/user/{userid}/{orderId}", orderId)  // Alternative method how to send parameter.
                .then()
                .log()
                .all()
                .statusCode(HttpStatus.SC_OK)
                .and()
                .extract()
                .path("id");

        Assertions.assertEquals(orderId, responseOrderId);
    }

    @ParameterizedTest
    @ValueSource(ints = {11, 12})
    public void getOrdersByIdAndCheckResponseCodeIsNok(int orderId) {
        given().
                log()
                .all()
                .when()
                .get("/test-orders/" + orderId) //Retrieve details of an order by providing a valid order ID from 1..10. Other ID will lead to 404 RC
                .then()
                .log()
                .all()
                .statusCode(HttpStatus.SC_BAD_REQUEST);
    }

    /** HW10*/
    @ParameterizedTest
    @CsvSource({
            "12345, example1",
            "67890, example2",
            "54321, example3"
    })
    void testWithCsvSource(String orderId, String additionalParam) {
        given().
                log()
                .all()
                .queryParam("orderId", orderId)
                .queryParam("additionalParam", additionalParam)
                .when()
                .get("/test-orders")
                .then()
                .log()
                .all()
                .statusCode(HttpStatus.SC_INTERNAL_SERVER_ERROR);
    }
}

//Source: http://35.208.34.242:8080/swagger-ui/index.html#/Mocked%20Order%20Operations/getById