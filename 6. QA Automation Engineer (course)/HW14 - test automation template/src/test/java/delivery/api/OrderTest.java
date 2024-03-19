package delivery.api;

import delivery.dto.OrderDto;
import io.restassured.response.Response;
import org.apache.http.HttpStatus;
import org.junit.jupiter.api.Assertions;
import org.junit.jupiter.api.Test;
import delivery.utils.ApiClient;

import static io.restassured.RestAssured.given;

public class OrderTest extends BaseSetupApi {

    @Test
    void getOrderInformationAndCheckResponse() {

        Response response = ApiClient.getOrders(getAuthenticatedRequestSpecification() );

        Assertions.assertAll("Test description",
                () -> Assertions.assertEquals(HttpStatus.SC_OK, response.getStatusCode(), "Status code is OK")
        );

    }

    @Test
    void createOrderAndCheckResponse() {

        Response response = ApiClient.getOrders(getAuthenticatedRequestSpecification() );

        Assertions.assertAll("Test description",
                () -> Assertions.assertEquals(HttpStatus.SC_OK, response.getStatusCode(), "Status code is OK")
        );

    }

    /** HW14*/
    @Test
    public void createOrder() {

//        OrderDto OrderDtoObject = OrderDto.builder()
//                .status("OPEN")
//                .courierId(5)
//                .customerName("Vadim")
//                .comment("Lombok plugin is on the way!")
//                .id(6)
//                .build();

        given().
                log()
                .all()
                .when()
                .post("http://35.208.34.242:8080/order")
                .then()
                .log()
                .all()
                .statusCode(200); // 200 - OK.
    }
}


}
