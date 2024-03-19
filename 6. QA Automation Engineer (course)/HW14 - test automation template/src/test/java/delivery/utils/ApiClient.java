package delivery.utils;

import com.google.gson.Gson;
import delivery.api.BaseSetupApi;
import delivery.dto.LoginDto;
import io.restassured.http.ContentType;
import io.restassured.response.Response;
import io.restassured.specification.RequestSpecification;

import static io.restassured.RestAssured.given;

public class ApiClient extends BaseSetupApi {
    public static Response getOrders(RequestSpecification spec){

        return given()
                .spec(spec)
                .log()
                .all()
                .get( "orders")
                .then()
                .log()
                .all()
                .extract()
                .response();
    }

    public static String authorizeAndGetToken(String username, String password){

        return given()
                .log()
                .all()
                .contentType(ContentType.JSON)
                .body( new Gson().toJson( new LoginDto(username,password) ) ) // Gson libary, Json libary.
                .post("login/student" )
                .then()
                .log()
                .all()
                .extract()
                .response()
                .asString();

    }

}