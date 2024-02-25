package api;

import org.junit.jupiter.api.Test;
import static io.restassured.RestAssured.*;

public class RestApiMocked {

    @Test
    public void getOrderByIDAndCheckResponseCodeIsOK() {
        //Retrieve details of an order by providing a valid order ID from 1..10. Other ID will lead to 404 Request Code.
        get("http://35.208.34.242:8080/test-orders/9") //
                .then()
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

    /** Hw09.*/

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
                .log()
                .all()

                .when()
                .header("wrong_key", "101")
                .delete("http://35.208.34.242:8080/test-orders/9")


                .then()
                .log()
                .all()
                .statusCode(204); // HTTP Status 204 (No Content) indicates that the server has successfully fulfilled the request and that there is no content to send in the response payload body
    }
}

//Source: http://35.208.34.242:8080/swagger-ui/index.html#/Mocked%20Order%20Operations/getById

