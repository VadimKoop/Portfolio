package delivery.api;

import io.restassured.RestAssured;
import io.restassured.builder.RequestSpecBuilder;
import io.restassured.http.ContentType;
import io.restassured.specification.RequestSpecification;
import org.apache.commons.configuration.ConfigurationException;
import org.apache.commons.configuration.PropertiesConfiguration;
import org.junit.jupiter.api.BeforeAll;
import delivery.utils.ApiClient;

public class BaseSetupApi {
    private final static String PATH_TO_CONFIG = "application.yaml";
    protected static PropertiesConfiguration configuration;
    protected static String bearerToken;



    @BeforeAll
    public static void setUp() throws ConfigurationException {

        // read config file.
        configuration = new PropertiesConfiguration();
        configuration.load(PATH_TO_CONFIG);

        // get data from config.
        RestAssured.baseURI = configuration.getString("base-url");

        // String username = configuration.getString("username");
        // String password = configuration.getString("password");

        /** CW15*/
        String username = System.getProperty("username");
        String password = System.getProperty("password");

        // auth
//        bearerToken = ApiClient.authorizeAndGetToken(u, p);
        /** CW15*/
        bearerToken = ApiClient.authorizeAndGetToken(username, password);
    }

    public RequestSpecification getAuthenticatedRequestSpecification(){
        RequestSpecBuilder builder = new RequestSpecBuilder();
        builder.setContentType(ContentType.JSON);
        builder.addHeader("Authorization", "Bearer " + bearerToken);
        return builder.build();
    }

}
